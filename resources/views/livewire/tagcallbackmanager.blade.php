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
        'event_type' => 'schematic_created',
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
            'newCallback.callback_format' => 'required|in:json,discord,form-data,xml',
        ]);
        $this->tag = Tag::find($this->tagId);
        $this->tag->callbacks()->create([
            'event_type' => $this->newCallback['event_type'],
            'callback_url' => $this->newCallback['callback_url'],
            'callback_format' => $this->newCallback['callback_format'],
            'is_active' => $this->newCallback['is_active'],
            'created_by_user_id' => auth()->id(),
        ]);

        $this->loadCallbacks();
        $this->showAddModal = false;
        $this->resetNewCallback();
    }

    public function resetNewCallback()
    {
        $this->newCallback = [
            'event_type' => 'schematic_created',
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
 <h2
     class="text-2xl font-semibold text-gray-200">
  Webhooks for
  {{ $tag->name }}</h2>
 <div class="mt-4 space-y-4">
  <ul
      class="space-y-2 rounded-lg bg-neutral-800">
   @foreach ($callbacks as $callback)
    <li
        class="flex items-center justify-between border-b border-gray-700 p-2 last:border-none">
     <div>
      <p
         class="font-semibold text-gray-200">
       {{ $callback->event_type }}
      </p>
      <p
         class="text-sm text-gray-400">
       {{ $callback->callback_url }}
      </p>
      <p
         class="text-xs text-gray-400">
       Format:
       {{ $callback->callback_format }}
      </p>
     </div>
     <div
          class="flex items-center space-x-2">
      <x-action-button wire:click="toggleCallbackStatus('{{ $callback->id }}')"
                       :icon="$callback->is_active
                           ? 'check-circle'
                           : 'times-circle'"
                       :textColor="$callback->is_active
                           ? 'text-success'
                           : 'text-error'">
       {{ $callback->is_active ? 'Active' : 'Inactive' }}
      </x-action-button>
      <x-action-button wire:click="deleteCallback('{{ $callback->id }}')"
                       icon="trash"
                       textColor="text-error">
       Delete
      </x-action-button>
     </div>
    </li>
   @endforeach
   <li
       class="py-2 text-center">
    <x-action-button color="primary"
                     wire:click="showAddCallbackModal"
                     icon="plus">
     Add Webhook
    </x-action-button>
   </li>
  </ul>
 </div>

 <!-- Add Webhook Modal -->
 <div x-data="{ open: @entangle('showAddModal') }"
      x-cloak x-show="open"
      class="fixed inset-0 z-20 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true">
  <div
       class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
   <div x-show="open"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        aria-hidden="true">
   </div>

   <span class="hidden sm:inline-block sm:h-screen sm:align-middle"
         aria-hidden="true">&#8203;</span>

   <div x-show="open"
        class="inline-block transform overflow-hidden rounded-lg bg-neutral-800 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
    <div
         class="bg-neutral-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
     <h3 class="text-lg font-medium leading-6 text-gray-200"
         id="modal-title">Add
      Webhook</h3>
     <div
          class="mt-2 space-y-4">
      <div>
       <label for="event_type"
              class="block text-sm font-medium text-gray-400">Event
        Type</label>
       <input type="text"
              id="event_type"
              wire:model="newCallback.event_type"
              placeholder="e.g., schematic_created, schematic_updated"
              class="mt-1 block w-full rounded-md border-gray-600 bg-neutral-700 text-gray-200 shadow-sm">
      </div>
      <div>
       <label for="callback_url"
              class="block text-sm font-medium text-gray-400">Webhook
        URL</label>
       <input type="url"
              id="callback_url"
              wire:model="newCallback.callback_url"
              placeholder="https://"
              class="mt-1 block w-full rounded-md border-gray-600 bg-neutral-700 text-gray-200 shadow-sm">
      </div>
      <div>
       <label for="callback_format"
              class="block text-sm font-medium text-gray-400">Callback
        Format</label>
       <select id="callback_format"
               wire:model="newCallback.callback_format"
               class="mt-1 block w-full rounded-md border-gray-600 bg-neutral-700 text-gray-200 shadow-sm">
        <option
                value="json">
         JSON</option>
        <option
                value="discord">
         Discord</option>
        <option
                value="form-data">
         Form Data</option>
        <option
                value="xml">
         XML</option>
       </select>
      </div>
      <div
           class="flex items-center">
       <input type="checkbox"
              id="is_active"
              wire:model="newCallback.is_active"
              class="rounded border-gray-600 bg-neutral-700 text-blue-600">
       <label for="is_active"
              class="ml-2 block text-sm text-gray-400">Active</label>
      </div>
     </div>
    </div>
    <div
         class="bg-neutral-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
     <x-action-button wire:click="addCallback"
                      color="primary"
                      icon="plus"
                      class="w-full sm:ml-3 sm:w-auto">
      Add Webhook
     </x-action-button>
     <x-action-button @click="open = false"
                      color="secondary"
                      icon="times"
                      class="mt-3 w-full sm:mt-0 sm:w-auto">
      Cancel
     </x-action-button>
    </div>
   </div>
  </div>
 </div>
</div>
