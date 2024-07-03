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

name('schematics.submit');

new class extends Component implements HasForms {
    use InteractsWithForms;
    use WithFileUploads;

    public $shortId;
    public $schematicBase64;
    public $author;
    public ?array $data = [];
    public $schematicFile;

    public function mount($shortId)
    {
        if ($shortId === 'new') {
            if (!Auth::check()) {
                session()->flash('error', 'You must be logged in to upload a new schematic.');
                $this->redirect('/login');
                return;
            }
            $this->author = Auth::user()->player->id;
        } else {
            $cacheKey = Cache::get("schematic-temporary-short-links:{$shortId}");
            if ($cacheKey == null || strlen($cacheKey) === 0) {
                session()->flash('error', 'The schematic you are trying to upload is not valid.');
                $this->redirect('/schematics');
                return;
            }

            $author = explode(':', $cacheKey)[1];
            if ($author) {
                $this->author = $author;
            } else {
                session()->flash('error', 'The schematic you are trying to upload is not valid.');
                $this->redirect('/schematics');
                return;
            }
            $schematicFile = Cache::get($cacheKey);
            $this->schematicBase64 = base64_encode($schematicFile);
        }

        $this->shortId = $shortId;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $steps = [
            Wizard\Step::make('Preview')
                ->schema([
                    SchematicPreviewRenderer::make('schematicPreview')
                        ->viewData([
                            'schematicBase64' => $this->schematicBase64,
                            'schematicId' => $this->shortId,
                        ])
                        ->required()
                        ->rules(['required', 'array', 'size:2'])
                        ->label('Schematic Preview')
                        ->hint('Upload a preview image and video for your schematic'),
                ])
                ->icon('heroicon-o-camera'),
            Wizard\Step::make('Details')
                ->schema([TextInput::make('title')->required()->label('Schematic Title')->placeholder('Enter a title for your schematic'), TinyEditor::make('description')->profile('default|simple|full|minimal|none|custom')->columnSpan('full')->required()->fileAttachmentsDisk('s3')->required()->label('Description')->placeholder('Provide a detailed description of your schematic'), Toggle::make('isPublic')->label('Public')->default(false)->hint('Make your schematic public so others can view and download it')])
                ->icon('heroicon-o-pencil'),
            Wizard\Step::make('Tags')
                ->schema([
                    SelectTree::make('tags')
                        ->model(Tag::class)
                        ->label('Select Tags')
                        ->enableBranchNode()
                        ->direction('bottom')
                        ->searchable()
                        ->relationship(relationship: 'usableTags', titleAttribute: 'name', parentAttribute: 'parent_id')
                        ->placeholder('Choose tags for your schematic'),
                ])
                ->icon('heroicon-o-tag'),
        ];

        if ($this->shortId === 'new') {
            array_unshift(
                $steps,
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
            );
        }

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
        $this->validate();
        $schematicUUID = Str::uuid();

        try {
            $schematic = new Schematic([
                'id' => $schematicUUID,
                'name' => $this->data['title'],
                'description' => $this->data['description'],
            ]);
            $schematic->save();

            $player = Player::findOrFail($this->author);
            $schematic->authors()->attach($player);

            if (isset($this->data['tags']) && is_array($this->data['tags'])) {
                $schematic->tags()->attach($this->data['tags']);
            }

            if ($this->shortId === 'new') {
                $schematicContent = base64_decode($this->schematicBase64);
            } else {
                $schematicContent = base64_decode($this->schematicBase64);
            }

            $schematic
                ->addMediaFromString($schematicContent)
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
        } catch (\Exception $e) {
            $schematic = Schematic::find($schematicUUID);
            if ($schematic) {
                $schematic->authors()->detach();
                $schematic->tags()->detach();
                $schematic->delete();
            }
            $schematic->clearMediaCollection('schematic');
            $schematic->clearMediaCollection('preview_video');
            $schematic->clearMediaCollection('preview_image');
            throw $e;
        }

        if ($this->shortId !== 'new') {
            $cacheKey = Cache::get("schematic-temporary-short-links:{$this->shortId}");
            Cache::forget("schematic-temporary-short-links:{$this->shortId}");
            Cache::forget($cacheKey);
        }

        session()->flash('success', 'Schematic uploaded successfully.');
        $this->redirect('/schematics');
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
                            <span class="text-sm text-gray-200">
                                {{ $shortId }}
                            </span>
                        </div>
                    </div>
                    {{ $this->form }}
                </div>
            </form>
        </div>
    @endvolt
</x-app-layout>
