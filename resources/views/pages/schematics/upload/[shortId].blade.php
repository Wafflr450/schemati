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
use Livewire\Volt\Component;
name('schematics.upload');

new class extends Component implements HasForms {
    use InteractsWithForms;

    public $shortId;
    public $schematicBase64;
    public ?array $data = [];

    public function mount($shortId)
    {
        $cacheKey = Cache::get("schematic-temporary-short-links:{$shortId}");
        if (!$cacheKey) {
            $this->redirect('/schematics');
        }
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
                // ...
            ])
            ->statePath('data');
    }
    public function create(): void
    {
        dd($this->form->getState());
    }
};
?>


<x-app-layout>
    @volt
        <div class="max-w-7xl mx-auto">
            {{ $shortId }}
            <x-schematic-renderer :base64="$schematicBase64" wire:key="schematic-renderer-{{ $shortId }}" />
            <form wire:submit="create">
                {{ $this->form }}

                <button type="submit">
                    Submit
                </button>
            </form>
        </div>
    @endvolt
</x-app-layout>
