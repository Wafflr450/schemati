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

use Filament\Forms\Components\Select;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use App\Models\Tag;

use App\Forms\Components\SchematicPreviewRenderer;
use Livewire\Volt\Component;
name('schematics.submit');

new class extends Component implements HasForms {
    use InteractsWithForms;

    public $shortId;
    public $schematicBase64;
    public $author;
    public ?array $data = [];

    public function mount($shortId)
    {
        $cacheKey = Cache::get("schematic-temporary-short-links:{$shortId}");
        if ($cacheKey == null || strlen($cacheKey) === 0) {
            session()->flash('error', 'The schematic you are trying to upload is not valid.');
            $this->redirect('/schematics');
            return;
        }

        $author = explode(':', $cacheKey)[1];
        if ($author) {
            $this->author = $author;
            info('Author is set to: ' . $author);
        } else {
            session()->flash('error', 'The schematic you are trying to upload is not valid.');
            $this->redirect('/schematics');
            return;
        }
        $schematicFile = Cache::get($cacheKey);
        $this->schematicBase64 = base64_encode($schematicFile);
        $this->form->fill();
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index') . '/schematics/';
    }

    public function messages(): array
    {
        return [
            'data.schematicPreview.webm.required' => 'The schematic preview must include a webm file.',
            'data.schematicPreview.png.required' => 'The schematic preview must include a png file.',
        ];
    }

    public function rules(): array
    {
        return [
            'data.title' => ['required'],
            'data.description' => ['required'],
            'data.schematicPreview' => ['required', 'array', 'size:2'],
            'data.schematicPreview.webm' => ['required', 'string'],
            'data.schematicPreview.png' => ['required', 'string'],
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
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
                ])

                    ->nextAction(
                        fn(Action $action) => $action->label('Next step')->extraAttributes([
                            'class' => 'bg-primary-600 hover:bg-primary-700',
                        ]),
                    )
                    ->submitAction(view('components.submit-button')),
            ])
            ->statePath('data');
    }

    public function create()
    {
        $this->validate();
        $schematicUUID = Str::uuid();
        $authors = explode(',', $this->author);

        try {
            $schematic = new Schematic([
                'id' => $schematicUUID,
                'name' => $this->data['title'],
                'description' => $this->data['description'],
            ]);
            $schematic->save();

            foreach ($authors as $author) {
                $player = Player::firstOrCreate(['id' => $author]);
                if ($player) {
                    $schematic->authors()->attach($player);
                }
            }

            // Attach tags
            if (isset($this->data['tags']) && is_array($this->data['tags'])) {
                $schematic->tags()->attach($this->data['tags']);
            }

            $schematic = Schematic::find($schematicUUID);
            $schematic
                ->addMediaFromBase64($this->schematicBase64)
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
        $cacheKey = Cache::get("schematic-temporary-short-links:{$this->shortId}");
        Cache::forget("schematic-temporary-short-links:{$this->shortId}");
        Cache::forget($cacheKey);
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
