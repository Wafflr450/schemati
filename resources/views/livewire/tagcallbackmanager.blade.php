<?php

use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

use App\Models\Tag;
use App\Models\TagCallback;

new class extends Component {
    public $tag;

    #[Reactive]
    public $tagId;

    public $callbacks = [];
    public $showAddModal = false;

    public $newCallback = [
        'event_type' => '',
        'callback_url' => '',
        'callback_format' => 'json',
        'is_active' => true,
    ];

    public function mount()
    {
        if (!$this->tagId) {
            return;
        }
        $this->tag = Tag::find($this->tagId);
        $this->loadCallbacks();
    }

    public function loadCallbacks()
    {
        $this->callbacks = $this->tag->callbacks()->get();
    }

    public function showAddCallbackModal()
    {
        $this->showAddModal = true;
    }

    public function addCallback()
    {
        $this->validate([
            'newCallback.event_type' => 'required|string',
            'newCallback.callback_url' => 'required|url',
            'newCallback.callback_format' => 'required|in:json,form-data,xml',
        ]);

        $this->tag->callbacks()->create([
            'event_type' => $this->newCallback['event_type'],
            'callback_url' => $this->newCallback['callback_url'],
            'callback_format' => $this->newCallback['callback_format'],
            'is_active' => $this->newCallback['is_active'],
            'created_by_user_id' => auth()->id(),
        ]);

        $this->loadCallbacks();
        $this->showAddModal = false;
        $this->newCallback = [
            'event_type' => '',
            'callback_url' => '',
            'callback_format' => 'json',
            'is_active' => true,
        ];
    }

    public function toggleCallbackStatus($callbackId)
    {
        $callback = TagCallback::find($callbackId);
        $callback->update(['is_active' => !$callback->is_active]);
        $this->loadCallbacks();
    }

    public function deleteCallback($callbackId)
    {
        TagCallback::destroy($callbackId);
        $this->loadCallbacks();
    }

    #[On('node-selected')]
    public function handleSelectedNode($nodeId)
    {
        $this->tagId = $nodeId;
        $this->mount();
    }

    #[On('node-updated')]
    public function handleNodeUpdated($nodeId)
    {
        $this->tagId = $nodeId;
        $this->mount();
    }
};
?>

<div class="pt-4">
    <h2 class="text-2xl font-semibold text-gray-200">Tag Callbacks for {{ $tag->name }}</h2>
    <div class="mt-4 space-y-4">
        <ul class="bg-neutral-800 rounded-lg space-y-2">
            @foreach ($callbacks as $callback)
                <li class="flex items-center justify-between p-2 border-b border-gray-700 last:border-none">
                    <div>
                        <p class="text-gray-200 font-semibold">{{ $callback->event_type }}</p>
                        <p class="text-gray-400 text-sm">{{ $callback->callback_url }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-action-button wire:click="toggleCallbackStatus('{{ $callback->id }}')" :icon="$callback->is_active ? 'check-circle' : 'times-circle'"
                            :textColor="$callback->is_active ? 'text-success' : 'text-error'">
                            {{ $callback->is_active ? 'Active' : 'Inactive' }}
                        </x-action-button>
                        <x-action-button wire:click="deleteCallback('{{ $callback->id }}')" icon="trash"
                            textColor="text-error">
                            Delete
                        </x-action-button>
                    </div>
                </li>
            @endforeach
            <li class="text-center py-2">
                <x-action-button color="primary" wire:click="showAddCallbackModal" icon="plus">
                    Add Callback
                </x-action-button>
            </li>
        </ul>
    </div>

    <!-- Add Callback Modal -->
    <div x-data="{ open: @entangle('showAddModal') }" x-cloak x-show="open" class="fixed z-20 inset-0 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open"
                class="inline-block align-bottom bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-200" id="modal-title">Add New Callback</h3>
                    <div class="mt-2 space-y-4">
                        <div>
                            <label for="event_type" class="block text-sm font-medium text-gray-400">Event Type</label>
                            <input type="text" id="event_type" wire:model="newCallback.event_type"
                                class="mt-1 block w-full bg-neutral-700 border-gray-600 rounded-md shadow-sm text-gray-200">
                        </div>
                        <div>
                            <label for="callback_url" class="block text-sm font-medium text-gray-400">Callback
                                URL</label>
                            <input type="url" id="callback_url" wire:model="newCallback.callback_url"
                                class="mt-1 block w-full bg-neutral-700 border-gray-600 rounded-md shadow-sm text-gray-200">
                        </div>
                        <div>
                            <label for="callback_format" class="block text-sm font-medium text-gray-400">Callback
                                Format</label>
                            <select id="callback_format" wire:model="newCallback.callback_format"
                                class="mt-1 block w-full bg-neutral-700 border-gray-600 rounded-md shadow-sm text-gray-200">
                                <option value="json">JSON</option>
                                <option value="form-data">Form Data</option>
                                <option value="xml">XML</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" wire:model="newCallback.is_active"
                                class="bg-neutral-700 border-gray-600 rounded text-blue-600">
                            <label for="is_active" class="ml-2 block text-sm text-gray-400">Active</label>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-action-button wire:click="addCallback" color="primary" icon="plus"
                        class="w-full sm:ml-3 sm:w-auto">
                        Add Callback
                    </x-action-button>
                    <x-action-button @click="open = false" color="secondary" icon="times"
                        class="mt-3 w-full sm:mt-0 sm:w-auto">
                        Cancel
                    </x-action-button>
                </div>
            </div>
        </div>
    </div>
</div>
