<div class="aspect-w-4 aspect-h-3 w-full min-h-96 relative justify-center items-center flex">
    <canvas id="canvas-{{ $schematicId }}" wire:ignore class="w-full h-[50vh]">
        Your browser does not support the HTML5 canvas tag.
    </canvas>
    <div class="absolute bottom-0 right-0 p-4">
        <button type="button" x-data x-on:click="event.preventDefault(); generatePreview()"
            class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary active:bg-secondary tranform hover:scale-105 transition duration-300 ease-in-out active:scale-95">
            Generate Preview <i class="fas fa-camera"></i>
        </button>

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

</div>
<div class="w-48 h-48 bg-gray-200 rounded-t-lg " x-data="{ state: $wire.$entangle('name') }">
    <div x-show="state.constructor === Object && state.png" <img class="w-full h-48 object-cover rounded-t-lg"
        x-bind:src="state.png" alt="Preview">
    </div>
    <div x-show="state.constructor === Object && state.webm" <video class="w-full h-48 object-cover rounded-t-lg"
        x-bind:src="state.webm" loop muted></video>
    </div>
</div>
@pushOnce('scripts')
    <script type="module">
        function setCanvasDimensions(canvasId) {
            const canvas = document.getElementById(canvasId);
            const container = canvas.parentElement;
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
        }

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

        setCanvasDimensions('canvas-{{ $schematicId }}');
        window.addEventListener('resize', () => setCanvasDimensions('canvas-{{ $schematicId }}'));
        const schematic_{{ $schematicId }} = @json($schematicBase64);
        const canvas_{{ $schematicId }} = document.getElementById('canvas-{{ $schematicId }}');
        const options = {
            ...defaultSchematicOptions,
            progressController: {
                showProgress: async () => showProgress('progress-{{ $schematicId }}'),
                hideProgress: async () => hideProgress('progress-{{ $schematicId }}'),
                setProgress: async (progress) => setProgress('progress-{{ $schematicId }}', progress),
                setProgressMessage: async (text) => setProgressMessage('progress-{{ $schematicId }}', text)
            }
        }
        const renderer_{{ $schematicId }} = new SchematicRenderer.SchematicRenderer(
            canvas_{{ $schematicId }},
            schematic_{{ $schematicId }},
            options
        );

        window.generatePreview = function() {

            const resolutionX = 720;
            const resolutionY = 480;
            const frameRate = 24;
            const duration = 5;
            const rotation = 360;


            renderer_{{ $schematicId }}.takeRotationWebM(resolutionX, resolutionY, frameRate, duration, rotation)
                .then(webm => {
                    return renderer_{{ $schematicId }}.takeScreenshot(resolutionX, resolutionY)
                        .then(png => {
                            console.log("Preview generated");
                            @this.set('{{ $getStatePath() }}', {
                                webm: webm,
                                png: png
                            });
                        });
                });
        }
    </script>
@endpushOnce
