<div class="aspect-w-4 aspect-h-3 w-full min-h-96">
    <canvas id="canvas-{{ $schematic->string_id }}" wire:ignore class="w-full h-full"></canvas>

</div>

<div class="flex justify-center p-4">
    <button id="take-screenshot-{{ $schematic->string_id }}" class="text-blue-700 dark:text-white hover:underline">
        Take Screenshot
    </button>
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

        document.getElementById('take-screenshot-{{ $schematic->string_id }}').addEventListener('click', () => {
            const resolutionX = 3840;
            const resolutionY = 2160;
            renderer_{{ $schematic->string_id }}.takeScreenshot(resolutionX, resolutionY).then(screenshot => {
                const a = document.createElement('a');
                a.href = screenshot;
                a.download = '{{ $schematic->name }}.png';
                a.click();
            });
        });
    </script>
@endpush
