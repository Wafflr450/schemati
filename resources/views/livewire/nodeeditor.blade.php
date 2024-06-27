<?php
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $player;
    public $currentlySelectedNode;
    public $nodeId;

    public $showDeleteModal = false;
    public $showAddChildModal = false;

    #[Rule('nullable|max:255|min:3|unique:tags,name')]
    public $newNodeName;

    #[Rule('required|max:255|min:3')]
    public $name;

    #[Rule('nullable|regex:/^#[0-9a-fA-F]{6}$/')]
    public $color;

    #[Rule('required')]
    public $description;

    public $scope; // 1MB Max

    #[Rule('nullable|image|max:4096')]
    public $icon;

    public function mount()
    {
        $this->player = auth()->user()->player;
        if (!$this->currentlySelectedNode) {
            return;
        }
        $this->name = $this->currentlySelectedNode->name;
        $this->color = $this->currentlySelectedNode->color;
        $this->description = $this->currentlySelectedNode->description;
        $this->scope = $this->currentlySelectedNode->scope;
        $this->nodeId = $this->currentlySelectedNode->id;
    }

    #[On('node-selected')]
    public function handleSelectedNode($nodeId)
    {
        $node = Tag::find($nodeId);
        $this->currentlySelectedNode = $node;
        $this->name = $node->name;
        $this->color = $node->color;
        $this->description = $node->description;
        $this->scope = $node->scope;
        $this->publicViewing = $node->public_viewing == 1 || $node->public_use == 1;
        $this->nodeId = $node->id;
        $this->icon = null; // Reset icon when selecting a new node
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|min:3',
            'color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'description' => 'required',
            'scope' => 'required',
            'icon' => 'nullable|image|max:4096',
        ]);

        $node = Tag::find($this->nodeId);
        $node->update([
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
            'scope' => $this->scope,
        ]);

        if ($this->icon) {
            $node->clearMediaCollection('icon');
            $node->addMedia($this->icon->getRealPath())->toMediaCollection('icon', 's3');
        }

        $this->dispatch('node-updated', nodeId: $node->id);
        $this->js(
            <<<JS
            Toast.success('Node updated successfully.');
            JS
            ,
        );
    }

    public function getIconUrl()
    {
        if ($this->currentlySelectedNode) {
            return $this->currentlySelectedNode->icon_url;
        }
        return null;
    }

    public function addChild()
    {
        $this->validate([
            'newNodeName' => 'required|max:255|min:3|unique:tags,name',
        ]);
        $node = Tag::create([
            'name' => $this->newNodeName,
            'description' => 'New Node',
            'parent_id' => $this->nodeId,
        ]);
        $this->newNodeName = '';
        $this->nodeId = $node->id;

        $this->dispatch('node-updated', nodeId: $this->nodeId);
        $this->dispatch('node-selected', nodeId: $this->nodeId);

        $this->showAddChildModal = false;
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
    }

    public function updated($name, $value)
    {
    }

    public function delete()
    {
        $parentId = Tag::find($this->nodeId)->parent_id;
        $node = Tag::find($this->nodeId);
        $node->delete();
        $this->dispatch('node-updated', nodeId: $parentId);
        $this->dispatch('node-selected', nodeId: $parentId);

        $this->showDeleteModal = false;
    }

    public function cancelAddChild()
    {
        $this->showAddChildModal = false;
    }
}; ?>

<div class="bg-gradient-to-br {{ \App\Utils\UiUtils::getGradientClasses('neutral') }} rounded-xl shadow-2xl p-4">
    <div class="pb-4 flex justify-between items-center">
        <x-action-button wire:click="$set('showAddChildModal', true)" textColor="text-info" icon="plus">
        </x-action-button>
        <x-action-button wire:click="confirmDelete" textColor="text-error" icon="trash">
        </x-action-button>
    </div>
    <form wire:submit.prevent="update" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div x-data="{
                previewUrl: '{{ $this->getIconUrl() }}',
                hovering: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            }" class="flex flex-col items-center cursor-pointer">
                <label for="icon" class="block text-sm font-semibold text-gray-200 mb-2">Icon</label>
                <div class="relative w-32 h-32 rounded-full overflow-hidden mb-2" @mouseenter="hovering = true"
                    @mouseleave="hovering = false" @click="$refs.fileInput.click()">
                    <div class="absolute inset-0 bg-gray-700 flex items-center justify-center text-gray-400"
                        x-show="!previewUrl">
                        No Image
                    </div>
                    <img x-bind:src="previewUrl" alt="Icon Preview" class="w-full h-full object-cover"
                        x-show="previewUrl">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300"
                        x-show="hovering || !previewUrl" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
                <input type="file" id="icon" wire:model="icon" accept="image/*" class="hidden" x-ref="fileInput"
                    @change="handleFileSelect($event)">
                @error('icon')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-200 mb-1">Name</label>
                        <input type="text" name="name" id="name" wire:model.defer="name"
                            class="block w-full bg-neutral-900 border border-gray-700 rounded-lg text-gray-200 shadow focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-semibold text-gray-200 mb-1">Color</label>
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <input type="color" name="color" id="color" wire:model.live="color"
                                    class="w-8 h-8 appearance-none border-2 border-gray-700 rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <div class="absolute inset-0 rounded-full border border-gray-700 pointer-events-none"
                                    style="background-color: {{ $color }}"></div>
                            </div>
                            <span class="text-gray-200 text-sm font-medium"
                                id="color-display">{{ $color }}</span>
                        </div>
                        @error('color')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="scope" class="block text-sm font-semibold text-gray-200 mb-1">Scope</label>
                    <select id="scope" name="scope" wire:model.defer="scope"
                        class="block w-full bg-neutral-900 border border-gray-700 rounded-lg text-gray-200 shadow focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="public_use">Public Use</option>
                        <option value="public_viewing">Public Viewing</option>
                        <option value="private">Private</option>
                    </select>
                    @error('scope')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>



            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-semibold text-gray-200">Description</label>
                <textarea id="description" name="description" wire:model.defer="description" rows="3"
                    class="mt-1 block w-full bg-neutral-900 border border-gray-700 rounded-lg text-gray-200 shadow focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <x-action-button type="submit" textColor="text-success" icon="save" class="custom-class">
                Update
            </x-action-button>
        </div>
    </form>


    @if ($nodeId)
        <livewire:playertagpermissionmanager :tagId="$nodeId" />
        <livewire:tagcallbackmanager :tagId="$nodeId" />
    @endif

    @if ($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-200" id="modal-title">
                                    Delete Tag
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">
                                        Are you sure you want to delete this tag? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="cancelDelete"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-md px-4 py-2 bg-neutral-800 text-base font-medium text-gray-200 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showAddChildModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-plus text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-200" id="modal-title">
                                    Add Child Tag
                                </h3>
                                <div class="mt-2">
                                    <form wire:submit.prevent="addChild" class="space-y-4">
                                        <div>
                                            <label for="newNodeName"
                                                class="block text-sm font-semibold text-gray-200">New
                                                Node
                                                Name</label>
                                            <div class="mt-1">
                                                <input type="text" name="newNodeName" id="newNodeName"
                                                    wire:model.defer="newNodeName"
                                                    class="block w-full bg-neutral-800 border border-gray-600 rounded-lg text-gray-200 shadow focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                            @error('newNodeName')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="addChild"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Add Child
                        </button>
                        <button wire:click="cancelAddChild"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-md px-4 py-2 bg-neutral-800 text-base font-medium text-gray-200 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush
