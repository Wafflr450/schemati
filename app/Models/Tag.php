<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Tag extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'description'];

    protected $casts = [
        'id' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($tag) {
            $tag->id = (string) Str::uuid();
        });
    }

    public function admins()
    {
        return $this->belongsToMany(Player::class, 'tag_admins');
    }

    public function users()
    {
        return $this->belongsToMany(Player::class, 'tag_users');
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
        return $this->admins->contains($player) || $this->parent->canAdmin($player);
    }

    public function canUse($player)
    {
        $canChildrenUse = $this->children
            ->map(function ($child) use ($player) {
                return $child->canUse($player);
            })
            ->contains(true);
        return $this->users->contains($player) || $this->canAdmin($player) || $canChildrenUse;
    }

    public function canSee($player)
    {
        $canChildrenSee = $this->children
            ->map(function ($child) use ($player) {
                return $child->canSee($player);
            })
            ->contains(true);
        return $this->viewers->contains($player) || $this->canUse($player) || $canChildrenSee;
    }

    public static function getRoot()
    {
        return Tag::whereNull('parent_id')->first();
    }

    public static function getTree()
    {
        return Tag::getRoot()->getNodeWithChildren();
    }
}
