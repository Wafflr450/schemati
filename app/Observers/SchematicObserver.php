<?php

namespace App\Observers;

use App\Models\Schematic;

class SchematicObserver
{
    public function created(Schematic $schematic)
    {
        $this->triggerWebhooks($schematic, 'schematic_created');
    }

    public function updated(Schematic $schematic)
    {
        $this->triggerWebhooks($schematic, 'schematic_updated');
    }

    protected function triggerWebhooks(Schematic $schematic, string $eventType)
    {
        $tags = $schematic->tags;
        foreach ($tags as $tag) {
            $callbacks = $tag->callbacks()->where('event_type', $eventType)->where('is_active', true)->get();
            foreach ($callbacks as $callback) {
                $callback->trigger($schematic);
            }
        }
    }

    /**
     * Handle the Schematic "deleted" event.
     */
    public function deleted(Schematic $schematic): void
    {
        // You can add webhook triggers for deletion if needed
    }

    /**
     * Handle the Schematic "restored" event.
     */
    public function restored(Schematic $schematic): void
    {
        // You can add webhook triggers for restoration if needed
    }

    /**
     * Handle the Schematic "force deleted" event.
     */
    public function forceDeleted(Schematic $schematic): void
    {
        // You can add webhook triggers for force deletion if needed
    }
}
