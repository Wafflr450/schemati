<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <input type="text" id="schematicPngPreview" x-model="state" />
        <input type="text" id="schematicWebMPreview" x-model="state.webm" />
    </div>
</x-dynamic-component>
