@props(['class' => '', 'duration' => '1s', 'delay' => '0s', 'threshold' => '0.9'])

<div {{ $attributes->merge(['class' => 'scroll-animation ' . $class]) }}
    style="transition-duration: {{ $duration }}; transition-delay: {{ $delay }};"
    data-threshold="{{ $threshold }}">
    {{ $slot }}
</div>
