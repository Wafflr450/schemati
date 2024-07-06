<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;
use function Laravel\Folio\name;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use App\Models\Player;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use App\Models\Tag;
use App\Forms\Components\SchematicPreviewRenderer;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Schematic\SchematicUploadRequest;
use App\Http\Controllers\Schematic\SchematicUpload;
use Illuminate\Support\Facades\Route;
use App\Utils\CommonUtils;

name('schematics.submit');

new class extends Component implements HasForms {
    use InteractsWithForms;
    use WithFileUploads;

    public $schematicBase64;
    public $author;
    public ?array $data = [];
    public $schematicFile;
    public $uploadLink;

    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to upload a new schematic.');
            $this->redirect('/login');
            return;
        }
        $this->author = Auth::user()->player->id;
    }


    public function form(Form $form): Form
    {
        $steps = [
            Wizard\Step::make('Upload')
                ->schema([
                    FileUpload::make('schematicFile')
                        ->label('Upload Schematic')
                        ->maxSize(10240) // 10MB
                        ->required()
                        ->helperText('Upload a .schem file (gzip compressed)')
                        ->afterStateUpdated(function ($state) {
                            if ($state) {
                                $this->updateSchematicBase64($state);
                            }
                        }),
                ])
                ->icon('heroicon-o-arrow-up-tray'),
        ];

        return $form
            ->schema([
                Wizard::make($steps)
                    ->nextAction(
                        fn(Action $action) => $action->label('Next step')->extraAttributes([
                            'class' => 'bg-primary-600 hover:bg-primary-700',
                        ]),
                    )
                    ->submitAction(view('components.submit-button')),
            ])
            ->statePath('data');
    }

    public function updateSchematicBase64($state)
    {
        $filePath = $state->getRealPath();
        $this->schematicBase64 = base64_encode(file_get_contents($filePath));
        $this->dispatch('updateSchematicBase64', $this->schematicBase64);
    }

    public function create()
    {

        if(count($this->data['schematicFile']) === 0) {
            $this->addError('schematicFile', 'Failed to upload schematic. Please try again.');
            return;
        }
        //get the real path of the first element in the array
        $firstKey = array_key_first($this->data['schematicFile']);
        $realPath = $this->data['schematicFile'][$firstKey]->getRealPath();
        try {
            $link = SchematicUpload::cacheSchematic(
            $realPath ,
                $this->author
            );
            session()->flash('success', 'Schematic uploaded successfully !');
            return redirect($link);
        } catch (\Exception $e) {
            dd($e);
            $this->addError('schematicFile', 'Failed to upload schematic. Please try again.');
        }
    }
};
?>

<x-app-layout>
    @volt
    <div class="pt-4 mx-auto max-w-7xl">
        <form wire:submit.prevent="create">
            <div class="p-4 shadow-xl bg-neutral sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="p-4">
                        <h1 class="p-0 m-0 text-xl font-semibold leading-tight text-white">
                            Upload Schematic
                        </h1>
                    </div>
                </div>
                {{ $this->form }}
            </div>
        </form>

    </div>
    @endvolt
</x-app-layout>
