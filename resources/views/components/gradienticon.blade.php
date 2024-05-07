@props(['icon' => 'fas fa-star', 'color' => 'primary'])


<div
    {{ $attributes->merge(['class' => 'flex items-center justify-center w-12 h-12 bg-gradient-to-r ' . UiUtils::getGradientClasses($color) . ' rounded-full']) }}>
    <i class="{{ $icon }} text-white"></i>
</div>
