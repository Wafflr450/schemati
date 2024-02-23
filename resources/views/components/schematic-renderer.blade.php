<div class="aspect-w-4 aspect-h-3 w-full min-h-96">
    <canvas id="canvas-{{ $schematic->string_id }}" wire:ignore class="w-full h-full"></canvas>
</div>


@once
    @push('scripts')
        <script>
            function setCanvasDimensions(canvasId) {
                const canvas = document.getElementById(canvasId);
                const container = canvas.parentElement;
                canvas.width = container.offsetWidth;
                canvas.height = container.offsetHeight;
            }
        </script>
    @endpush
@endonce


@push('scripts')
    <script type="module">
        setCanvasDimensions('canvas-{{ $schematic->string_id }}');
        window.addEventListener('resize', () => setCanvasDimensions('canvas-{{ $schematic->string_id }}'));
        const schematic_{{ $schematic->string_id }} = @json($schematic->base64);
        const canvas_{{ $schematic->string_id }} = document.getElementById('canvas-{{ $schematic->string_id }}');
        const renderer_{{ $schematic->string_id }} = new SchematicRenderer.SchematicRenderer(
            canvas_{{ $schematic->string_id }},
            schematic_{{ $schematic->string_id }},
            defaultSchematicOptions);
    </script>
@endpush
