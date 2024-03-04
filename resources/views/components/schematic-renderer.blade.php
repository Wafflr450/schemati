<div class="aspect-w-4 aspect-h-3 w-full min-h-96">
    <canvas id="canvas-{{ $schematicId }}" wire:ignore class="w-full h-full"></canvas>

</div>

<div class="flex justify-center p-4">
    <button id="take-screenshot-{{ $schematicId }}" class="text-blue-700 dark:text-white hover:underline">
        Take Screenshot
    </button>
    <button id="download-gif-{{ $schematicId }}" class="ml-4 text-blue-700 dark:text-white hover:underline">
        Download GIF
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
        setCanvasDimensions('canvas-{{ $schematicId }}');
        window.addEventListener('resize', () => setCanvasDimensions('canvas-{{ $schematicId }}'));
        const schematic_{{ $schematicId }} = @json($schematicBase64);
        const canvas_{{ $schematicId }} = document.getElementById('canvas-{{ $schematicId }}');
        const renderer_{{ $schematicId }} = new SchematicRenderer.SchematicRenderer(
            canvas_{{ $schematicId }},
            schematic_{{ $schematicId }},
            defaultSchematicOptions);

        document.getElementById('take-screenshot-{{ $schematicId }}').addEventListener('click', () => {
            const resolutionX = 3840;
            const resolutionY = 2160;
            renderer_{{ $schematicId }}.takeScreenshot(resolutionX, resolutionY).then(screenshot => {
                const a = document.createElement('a');
                a.href = screenshot;
                a.download = '{{ $schematicName }}.png';
                a.click();
            });
        });

        document.getElementById('download-gif-{{ $schematicId }}').addEventListener('click', () => {
            const resolutionX = 720;
            const resolutionY = 720;
            const frameRate = 24;
            const duration = 5;
            const rotation = 360;
            console.log('Downloading gif');
            renderer_{{ $schematicId }}.takeRotationGif(resolutionX, resolutionY, frameRate, duration, rotation)
                .then(
                    gif => {
                        const a = document.createElement('a');
                        a.href = gif;
                        a.download = '{{ $schematicName }}.gif';
                        a.click();
                    });

        });
    </script>
@endpush
