<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Schematic;

use Illuminate\Support\Str;

class SchematicRenderer extends Component
{
    public string $schematicId;
    public string $schematicBase64;
    public string $schematicName = 'untitled';
    /**
     * Create a new component instance.
     */
    public function __construct(Schematic $schematic = null, string $base64 = null)
    {
        if ($schematic !== null && $base64 === null) {
            $this->schematicId = $schematic->string_id;
            $this->schematicBase64 = $schematic->base64;
            $this->schematicName = $schematic->name;
            return;
        }
        $this->schematicBase64 = $base64;
        $this->schematicId = str_replace('-', '', Str::uuid());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.schematic-renderer');
    }
}
