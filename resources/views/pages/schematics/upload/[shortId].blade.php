<?php
use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;
use function Laravel\Folio\name;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;

use App\Forms\Components\SchematicPreviewRenderer;
use Livewire\Volt\Component;
name('schematics.upload');

new class extends Component implements HasForms {
    use InteractsWithForms;

    public $shortId;
    public $schematicBase64;
    public $author;
    public ?array $data = [];

    public function mount($shortId)
    {
        $cacheKey = Cache::get("schematic-temporary-short-links:{$shortId}");
        if (!$cacheKey || strlen($cacheKey) === 0) {
            $this->redirect('/schematics');
        }
        $author = explode(':', $cacheKey)[1];
        $schematicFile = Cache::get($cacheKey);
        $this->schematicBase64 = base64_encode($schematicFile);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                RichEditor::make('content'),
                SchematicPreviewRenderer::make('schematicPreview')
                    ->viewData([
                        'schematicBase64' => $this->schematicBase64,
                        'schematicId' => $this->shortId,
                    ])
                    ->required(),
                // ...
            ])
            ->statePath('data');
    }
    public function create(): void
    {
        $schematicUUID = Str::uuid();
        $authors = explode(',', $this->author);
        $schematic = new Schematic([
            'id' => $schematicUUID,
            'name' => $this->data['title'],
            'description' => $this->data['content'],
        ]);
        $schematic->save();
        $schematic = Schematic::find($schematicUUID);
        $schematic
            ->addMediaFromBase64($schematicBase64)
            ->usingFileName($schematicUUID . '.schem')
            ->toMediaCollection('schematic');
        $schematic
            ->addMediaFromBase64($this->data['schematicPreview']['webm'])
            ->usingFileName($schematicUUID . '.webm')
            ->toMediaCollection('preview_video');
        $schematic
            ->addMediaFromBase64($this->data['schematicPreview']['png'])
            ->usingFileName($schematicUUID . '.png')
            ->toMediaCollection('preview_image');
    }
};
?>


<x-app-layout>
    @volt
        <div class="max-w-7xl mx-auto">
            {{ $shortId }}
            <form wire:submit="create">
                {{ $this->form }}

                <button type="submit">
                    Submit
                </button>
            </form>
        </div>
    @endvolt
</x-app-layout>
