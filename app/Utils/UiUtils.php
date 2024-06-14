<?php

namespace App\Utils;

class UiUtils
{
    const gradients = [
        'primary' => 'from-primary-600 to-secondary-500',
        'secondary' => 'from-secondary-600 to-secondary-400',
        'accent' => 'from-accent-600 to-accent-400',
        'neutral' => 'from-neutral-950 to-neutral-900',
        'base' => 'from-base-950 to-base-800',
        'info' => 'from-info-700 to-info-600',
        'success' => 'from-success-600 to-success-400',
        'warning' => 'from-warning-600 to-warning-400',
        'error' => 'from-error-700 to-error-500',
    ];

    public static function getGradientClasses($color)
    {
        return self::gradients[$color] ?? '';
    }
}
