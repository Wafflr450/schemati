@props(['header' => '', 'body' => '', 'footer' => ''])

<div {{ $attributes->merge(['class' => 'bg-neutral-950 bg-radial-gradient shadow-lg rounded-lg']) }}>
    @if ($header)
        <div class="px-4 py-5 sm:px-6">
            {{ $header }}
        </div>
    @endif

    @if ($body)
        <div class="px-4 py-5 sm:p-6">
            {{ $body }}
        </div>
    @endif

    @if ($footer)
        <div class="px-4 py-4 sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
