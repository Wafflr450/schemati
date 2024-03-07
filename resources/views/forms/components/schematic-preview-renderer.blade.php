<div class="static justify-center  flex justify-around content-around" x-data="{ state: $wire.$entangle('name') }">
    <div class="w-2/3 mx-auto relative">
        <canvas id="canvas-{{ $schematicId }}" wire:ignore
            class="w-full h-[50vh] shadow-[inset_0_4px_4px_rgba(1,0,0,0.6)] bg-base-200 rounded-lg">
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
        <div class="absolute bottom-0 right-0 p-4">
            <button type="button" x-data x-on:click="event.preventDefault(); generatePreview()"
                class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary active:bg-secondary tranform hover:scale-105 transition duration-300 ease-in-out active:scale-95">
                Generate Preview <i class="fas fa-camera"></i>
            </button>
        </div>
    </div>
    <div class="w-1/3 mx-auto">

        <div class="h-full">
            <div class="h-1/2 flex items-center justify-center">
                @if ($getState() && array_key_exists('png', $getState()))
                    <img src="{{ $getState()['png'] }}" class="object-cover rounded-t-lg w-full h-full"
                        alt="Preview" />
                @else
                    <div role="status" x-show="!state || !'png' in state"
                        class="flex items-center justify-center h-56 max-w-sm bg-gray-300 rounded-lg animate-pulse dark:bg-gray-700 w-full h-full">
                        <i class="fas fa-camera text-4xl text-gray-500 dark:text-gray-400"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                @endif
            </div>
            <div class="h-1/2 flex items-center justify-center">
                @if ($getState() && array_key_exists('webm', $getState()))
                    <video class="w-full h-full object-cover rounded-b-lg" src="{{ $getState()['webm'] }}" loop muted
                        autoplay></video>
                @else
                    <div role="status" x-show="!state || !'webm' in state"
                        class="flex items-center justify-center h-56 max-w-sm bg-gray-300 rounded-lg animate-pulse dark:bg-gray-700 w-full h-full">
                        <i class="fas fa-video text-4xl text-gray-500 dark:text-gray-400"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                @endif
            </div>
        </div>
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
