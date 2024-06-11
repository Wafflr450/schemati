<div class="aspect-w-4 aspect-h-3 w-full min-h-96 relative justify-center items-center flex">
    <canvas id="canvas-{{ $schematicId }}" wire:ignore class="w-full h-[50vh]">
        Your browser does not support the HTML5 canvas tag.
    </canvas>
    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none"
        style="display: none" id="progress-{{ $schematicId }}">
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] relative">
            <div class="bg-primary text-xs font-medium text-white-100 text-center p-0.5 leading-none rounded-full progress-value min-h-6"
                style="width: 0%"></div>
            <div class="absolute inset-0 flex items-center justify-center progress-message">
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300"></div>
            </div>

        </div>
    </div>
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
        <script>
            function showProgress(progressId) {
                const progress = document.getElementById(progressId);
                progress.style = '';
            }

            function hideProgress(progressId) {
                const progress = document.getElementById(progressId);
                progress.style = 'display: none';
            }

            function toggleProgress(progressId) {
                const progress = document.getElementById(progressId);
                if (progress.style.display === 'none') {
                    showProgress(progressId);
                } else {
                    hideProgress(progressId);
                }
            }

            function setProgress(progressId, progress) {
                const progressElement = document.querySelector(`#${progressId} > div > .progress-value`);
                progressElement.style.width = `${progress}%`;
            }

            function setProgressMessage(progressId, text) {
                const progressElement = document.querySelector(`#${progressId} > div > .progress-message > div`);
                progressElement.textContent = text;
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

        getAllResourcePackBlobs().then((resourcePackBlobs) => {
            const renderer = new SchematicRenderer(canvas_{{ $schematicId }}, schematic_{{ $schematicId }}, {
                resourcePackBlobs,
                debugGUI: true,
            });
        });


        function generatePreview() {
            const webmInput = document.getElementById('schematicWebMPreview');
            if (!webmInput) {
                console.error('No webm input found');
                return;
            }
            const pngInput = document.getElementById('schematicPngPreview');
            if (!pngInput) {
                console.error('No png input found');
                return;
            }
            const resolutionX = 720
            const resolutionY = 480;
            const frameRate = 24;
            const duration = 5;
            const rotation = 360;
            console.log('Generating preview');
            //store the webm and png in the inputs
            renderer_{{ $schematicId }}.getRotationWebM(resolutionX, resolutionY, frameRate, duration, rotation)
                .then(
                    webmBlob => {
                        const webm = URL.createObjectURL(webmBlob);
                        webmInput.value = webm;
                        return renderer_{{ $schematicId }}.getScreenshot(resolutionX, resolutionY)
                            .then(
                                png => {
                                    pngInput.value = png;
                                }
                            )
                    });
        }
    </script>
@endpush
