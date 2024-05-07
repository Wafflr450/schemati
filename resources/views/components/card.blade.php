@props(['header' => '', 'body' => '', 'footer' => ''])

<div {{ $attributes->merge(['class' => 'bg-white shadow-lg rounded-lg overflow-hidden']) }}>
    @if ($header)
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r {{ \App\Utils\UiUtils::getGradientClasses('base') }}">
            {{ $header }}
        </div>
    @endif

    @if ($body)
        <div class="px-4 py-5 sm:p-6">
            {{ $body }}
        </div>
    @endif

    @if ($footer)
        <div class="px-4 py-4 sm:px-6 bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>
