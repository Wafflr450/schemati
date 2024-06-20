<?php

namespace App\Utils;

class UiUtils
{
    const gradients = [
        'none' => 'bg-none',
        'primary' => 'bg-gradient-to-r from-primary-600 to-secondary-500',
        'secondary' => 'bg-gradient-to-r from-secondary-600 to-secondary-400',
        'accent' => 'bg-gradient-to-r from-accent-600 to-accent-400',
        'neutral' => 'bg-gradient-to-r from-neutral-950 to-neutral-900',
        'base' => 'bg-gradient-to-r from-base-950 to-base-800',
        'info' => 'bg-gradient-to-r from-info-700 to-info-600',
        'success' => 'bg-gradient-to-r from-success-600 to-success-400',
        'warning' => 'bg-gradient-to-r from-warning-600 to-warning-400',
        'error' => 'bg-gradient-to-r from-error-700 to-error-500',
    ];

    public static function getGradientClasses($color)
    {
        return self::gradients[$color] ?? '';
    }
}
