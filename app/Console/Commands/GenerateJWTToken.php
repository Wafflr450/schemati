<?php

namespace App\Console\Commands;

use App\Helpers\JWT;
use Carbon\Carbon;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\form;

class GenerateJWTToken extends Command
{
    protected $signature = 'jwt:generate';
    protected $description = 'Generate a JWT token with custom permissions';

    public function handle()
    {
        $responses = form()
            ->text('What is the token type?', default: 'system', required: true, name: 'type')
            ->multiselect(
                'Select permissions for this token:',
                [
                    'canManagePassword' => 'Can Manage Password',
                    'canViewUsers' => 'Can View Users',
                    'canEditUsers' => 'Can Edit Users',
                ],
                name: 'permissions'
            )
            ->confirm('Do you want to set an expiration?', name: 'has_expiration')
            ->text(
                'Enter expiration time in hours (leave empty for no expiration):',
                validate: function ($value) {
                    if ($value !== '' && !is_numeric($value)) {
                        return 'Please enter a valid number of hours.';
                    }
                },
                name: 'expiration_hours'
            )
            ->submit();

        $payload = [
            'type' => $responses['type'],
            'permissions' => $responses['permissions'],
        ];

        if ($responses['has_expiration'] && $responses['expiration_hours'] !== '') {
            $payload['exp'] = Carbon::now()->addHours((int)$responses['expiration_hours'])->timestamp;
        }

        $token = JWT::getToken($payload);

        $this->info('Generated JWT Token:');
        $this->line($token);

        if (confirm('Do you want to see the decoded payload?')) {
            $this->info('Decoded Payload:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT));
        }
    }
}
