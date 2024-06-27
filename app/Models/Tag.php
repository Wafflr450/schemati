<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Livewire\Wireable;

class Tag extends Model implements HasMedia, Wireable
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'description', 'scope', 'color'];

    protected $casts = [
        'id' => 'string',
    ];

    protected $appends = ['icon_url'];

    protected $keyType = 'string';
    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($tag) {
            $tag->id = (string) Str::uuid();
        });

        static::deleting(function ($tag) {
            $tag->children->each->delete();
        });
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'scope' => $this->scope,
            'color' => $this->color,
            'parent_id' => $this->parent_id,
            'icon_url' => $this->icon_url,
        ];
    }

    public static function fromLivewire($value)
    {
        $id = $value['id'];
        $name = $value['name'];
        $description = $value['description'];
        $scope = $value['scope'];
        $color = $value['color'];
        $parent_id = $value['parent_id'];
        return new static(compact('id', 'name', 'description', 'scope', 'parent_id', 'color'));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
    }

    public function getIconUrlAttribute()
    {
        $media = $this->getFirstMedia('icon');
        $url = $media ? $media->getFullUrl() : null;
        return str_replace('minio', 'localhost', $url);
    }

    public function schematics()
    {
        return $this->belongsToMany(Schematic::class);
    }

    public function admins()
    {
        return $this->belongsToMany(Player::class, 'tag_admins');
    }

    public function globalUsers()
    {
        if ($this->scope === 'public_use') {
            return Player::all();
        }
        return $this->users;
    }

    public function users()
    {
        return $this->belongsToMany(Player::class, 'tag_users');
    }

    public function globalViewers()
    {
        if ($this->scope === 'public_viewing' || $this->scope === 'public_use') {
            return Player::all();
        }
        return $this->viewers;
    }

    public function viewers()
    {
        return $this->belongsToMany(Player::class, 'tag_viewers');
    }

    public function parent()
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    public function prettyPrintChildren($level = 0)
    {
        $indent = str_repeat('  ', $level);
        echo $indent . $this->name . "\n";

        foreach ($this->children as $child) {
            $child->prettyPrintChildren($level + 1);
        }
    }

    public function getNodeWithChildren()
    {
        $node = $this->toArray();
        $node['children'] = $this->children->map(function ($child) {
            return $child->getNodeWithChildren();
        });
        return $node;
    }

    public function canAdmin($player)
    {
        return $this->admins->contains($player) || $this->parent?->canAdmin($player);
    }

    public function canSee($player)
    {
        if ($this->scope === 'public_viewing' || $this->scope === 'public_use') {
            return true;
        }
        $canChildrenSee = $this->children
            ->map(function ($child) use ($player) {
                return $child->canSee($player);
            })
            ->contains(true);
        return $this->viewers->contains($player) || $this->canUse($player) || $canChildrenSee;
    }

    public function isChildOf($tag)
    {
        if ($this->parent === null) {
            return false;
        }
        return $this->parent->is($tag) || $this->parent->isChildOf($tag);
    }

    public function isDirectChildOf($tag)
    {
        if ($this->parent === null) {
            return false;
        }
        return $this->parent->is($tag);
    }

    public function isParentOf($tag)
    {
        return $tag->isChildOf($this);
    }

    public function isDirectParentOf($tag)
    {
        return $tag->parent?->is($this);
    }

    public static function getRoot()
    {
        return Tag::whereNull('parent_id')->first();
    }

    public static function getUsable($player)
    {
        return Tag::all()->filter(function ($tag) use ($player) {
            return $tag->canUse($player);
        });
    }

    public static function getTree()
    {
        return Tag::getRoot()->getNodeWithChildren();
    }

    public static function getTreePrettyPrint()
    {
        return Tag::getRoot()->prettyPrintChildren();
    }

    public static function getTagsWithPredicate($player, $predicate)
    {
        $tags = Tag::all();
        $topMostAdminedTags = $tags->filter(function ($tag) use ($player, $predicate) {
            return $predicate($tag, $player);
        });

        return $topMostAdminedTags;
    }

    public static function getHighestParentWithPredicate($tag, $player, $predicate)
    {
        while ($predicate($tag, $player)) {
            $parent = $tag->parent;
            if ($parent === null) {
                return $tag;
            }
            if (!$predicate($parent, $player)) {
                return $tag;
            }
            $tag = $parent;
        }
        return null;
    }

    function array_any(array $array, callable $callback)
    {
        foreach ($array as $element) {
            if ($callback($element)) {
                return true;
            }
        }
        return false;
    }

    public static function getHighestUniqueParentsWithPredicate($player, $predicate)
    {
        $higestParents = [];
        $toSearch = [Tag::getRoot()];
        while (count($toSearch) > 0) {
            $tag = array_pop($toSearch);
            if ($predicate($tag, $player)) {
                $higestParents[] = $tag;
            } else {
                $toSearch = array_merge($toSearch, $tag->children->all());
            }
        }
        return $higestParents;
    }

    public static function getTopMostAdminedTags($player)
    {
        return Tag::getHighestUniqueParentsWithPredicate($player, function ($tag, $player) {
            return $tag->admins->contains($player);
        });
    }

    public static function getTopMostUsableTags($player)
    {
        return Tag::getHighestUniqueParentsWithPredicate($player, function ($tag, $player) {
            return $tag->canUse($player);
        });
    }

    public static function getTopMostVisibleTags($player)
    {
        return Tag::getHighestUniqueParentsWithPredicate($player, function ($tag, $player) {
            return $tag->canSee($player);
        });
    }

    public static function getAdminedTags($player)
    {
        $topMostAdminedTags = Tag::getTopMostAdminedTags($player);
        $allAdminedTags = [];
        addAllChildren($topMostAdminedTags);

        function addAllChildren($tags)
        {
            foreach ($tags as $tag) {
                $allAdminedTags[] = $tag;
                addAllChildren($tag->children);
            }
        }

        return $allAdminedTags;
    }

    public static function getUsableTags($player)
    {
        return Tag::getTagsWithPredicate($player, function ($tag, $player) {
            return $tag->canUse($player);
        });
    }

    public static function getVisibleTags($player)
    {
        return Tag::getTagsWithPredicate($player, function ($tag, $player) {
            return $tag->canSee($player);
        });
    }

    public function setAdmin($player)
    {
        $this->admins()->attach($player);
    }

    public function getAncestors()
    {
        $ancestors = [];
        $tag = $this;
        while ($tag->parent !== null) {
            $tag = $tag->parent;
            $ancestors[] = $tag;
        }
        return $ancestors;
    }

    public function getDescendants()
    {
        $descendants = [];
        $toSearch = $this->children->all();
        while (count($toSearch) > 0) {
            $tag = array_pop($toSearch);
            $descendants[] = $tag;
            $toSearch = array_merge($toSearch, $tag->children->all());
        }
        return $descendants;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'parent_id');
    }

    public function canUse($player)
    {
        if ($this->scope === 'public_use') {
            return true;
        }
        $canChildrenUse = $this->children
            ->map(function ($child) use ($player) {
                return $child->canUse($player);
            })
            ->contains(true);
        return $this->users->contains($player) || $this->canAdmin($player) || $canChildrenUse;
    }

    public function usableTags()
    {
        if (!auth()->user()) {
            return $this->tags()->where('scope', 'public_use');
        }
        $user = auth()->user();
        $player = $user->player;
        return $this->tags()->where(function ($query) use ($player) {
            $query
                ->where('scope', 'public_use')
                ->orWhereHas('users', function ($query) use ($player) {
                    $query->where('player_id', $player->id);
                })
                ->orWhereHas('admins', function ($query) use ($player) {
                    $query->where('player_id', $player->id);
                });
        });
    }

    public function callbacks()
    {
        return $this->hasMany(TagCallback::class);
    }
}
