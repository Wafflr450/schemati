<div>
    <canvas id="canvas-{{ $schematic->string_id }}" width="400" height="400" wire:ignore>

    </canvas>
</div>

@push('scripts')
    <script type="module">
        const schematic_{{ $schematic->string_id }} = @json($schematic->base64);
        const canvas_{{ $schematic->string_id }} = document.getElementById('canvas-{{ $schematic->string_id }}');
        const renderer_{{ $schematic->string_id }} = new SchematicRenderer.SchematicRenderer(
            canvas_{{ $schematic->string_id }},
            schematic_{{ $schematic->string_id }},
            defaultSchematicOptions);
    </script>
@endpush
