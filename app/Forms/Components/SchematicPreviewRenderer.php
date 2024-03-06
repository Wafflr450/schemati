<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Contracts\View\View;

class SchematicPreviewRenderer extends Field
{
    protected string $view = 'forms.components.schematic-preview-renderer';


    public function getPreviewRender()
    {
        return [
            "png" => $
        ]
    }
}
