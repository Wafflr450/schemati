<a href="/schematics/{{ $schematic->short_id }}" wire:navigate id="schematic-card_{{ $schematic->short_id }}"
    class="block transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] hover:shadow-xl">
    <div class="w-full bg-neutral-800 rounded-lg shadow-md overflow-hidden">
        <div class="relative aspect-video">
            @if ($schematic->preview_video)
                <video class="w-full h-full object-cover bg-neutral-700" src="{{ $schematic->preview_video }}" loop
                    id="preview-video_{{ $schematic->id }}" muted></video>
            @elseif ($schematic->preview_image)
                <img class="w-full h-full object-cover bg-neutral-700" src="{{ $schematic->preview_image }}"
                    alt="{{ $schematic->name }}">
            @else
                <div class="w-full h-full bg-neutral-700 flex items-center justify-center">
                    <svg class="w-12 h-12 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            @endif
        </div>

        <div class="p-5">
            <h5 class="text-xl font-semibold tracking-tight text-white mb-3">
                {{ $schematic->name }}
            </h5>
            <div class="flex flex-wrap items-center mb-3 gap-2">
                @foreach ($schematic->authors as $player)
                    <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}" class="w-8 h-8 rounded-full"
                        title="{{ $player->lastSeenName }}">
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($schematic->tags as $tag)
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded"
                        style="background-color: {{ $tag->color ?? '#374151' }}; color: {{ $tag->text_color ?? '#ffffff' }}">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</a>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video_{{ $schematic->string_id }} = document.getElementById(
                'preview-video_{{ $schematic->id }}');
            const card_{{ $schematic->string_id }} = document.getElementById(
                'schematic-card_{{ $schematic->id }}');

            if (video_{{ $schematic->string_id }} && card_{{ $schematic->string_id }}) {
                card_{{ $schematic->string_id }}.addEventListener('mouseenter', () => {
                    video_{{ $schematic->string_id }}.play();
                });
                card_{{ $schematic->string_id }}.addEventListener('mouseleave', () => {
                    video_{{ $schematic->string_id }}.pause();
                    video_{{ $schematic->string_id }}.currentTime = 0;
                });
            }
        });
    </script>
@endpush
