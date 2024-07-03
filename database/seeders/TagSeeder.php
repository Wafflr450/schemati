<?php namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Tag::where('name', 'root')->exists()) {
            dump('Tags already seeded');
            return;
        }
        $root = Tag::create([
            'name' => 'root',
            'description' => 'Root tag',
        ]);

        $structure = json_decode(file_get_contents(resource_path('tags.json')), true);

        $this->createTagTree($root, $structure);
        $player = Player::where('last_seen_name', 'Nano_')->first();
        $root->admins()->attach($player);
    }

    private function createTagTree(Tag $parent, array $structure): void
    {
        foreach ($structure as $tag) {
            $child = Tag::create([
                'name' => $tag['name'],
                'description' => $tag['description'],
                'parent_id' => $parent->id,
            ]);

            if (isset($tag['children'])) {
                $this->createTagTree($child, $tag['children']);
            }
        }
    }
}
