<?php
use function Livewire\Volt\{mount, state, computed};
use function Laravel\Folio\name;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Schematic\SchematicUploadRequest;
use App\Http\Controllers\Schematic\SchematicUpload;

name('schematics.upload');

new class extends Component implements HasForms {
    use InteractsWithForms;

    public $schematicFile;

    public function mount()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->form->fill();
    }

    public function rules(): array
    {
        return [
            'schematicFile' => ['required', 'file', 'mimetypes:application/gzip'],
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([FileUpload::make('schematicFile')->required()->disk('local')]);
    }

    public function submit()
    {
        $this->validate();
        dd($this->schematicFile[0]->getRealPath());
        $author = Auth::user()->id; // Retrieve the authenticated user's UUID

        $request = new SchematicUploadRequest([
            'schematic' => $this->schematicFile,
            'author' => $author,
        ]);

        $controller = new SchematicUpload();
        $response = $controller->__invoke($request);

        if ($response->getStatusCode() === 200) {
            $link = $response->getData()->link;
            return redirect($link);
        } else {
            // Handle error case
            return back()->withErrors(['error' => 'Failed to upload schematic.']);
        }
    }
};
?>

<x-app-layout>
    @volt
        <div class="max-w-7xl mx-auto pt-4">
            <form wire:submit.prevent="submit">
                <div class="bg-base-300 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="p-4">
                            <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight p-0 m-0">
                                Upload Schematic
                            </h1>
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary active:bg-secondary tranform hover:scale-105 transition duration-300 ease-in-out active:scale-95">
                                Next
                            </button>
                        </div>
                    </div>
                    {{ $this->form }}
                </div>
            </form>
        </div>
    @endvolt
</x-app-layout>
