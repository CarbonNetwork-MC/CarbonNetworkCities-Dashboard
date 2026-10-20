<?php

namespace Database\Seeders;

use App\Models\OnboardingState;
use Illuminate\Database\Seeder;

class OnboardingStatesSeeder extends Seeder
{
    public const array STATES = [
        [
            'state' => 'SELECTING_LANGUAGE',
        ],
        [
            'state' => 'WAITING_FOR_GUIDE',
        ],
        [
            'state' => 'SELECTING_COUNTRY',
        ],
        [
            'state' => 'CONFIRMING_SELECTIONS',
        ],
        [
            'state' => 'TUTORIAL_PLOTS',
        ],
        [
            'state' => 'TUTORIAL_ELEVATORS',
        ],
        [
            'state' => 'TUTORIAL_ATM',
        ],
        [
            'state' => 'TUTORIAL_PIN_CONSOLE',
        ],
        [
            'state' => 'TUTORIAL_FRIDGE',
        ],
        [
            'state' => 'COMPLETING',
        ],
        [
            'state' => 'COMPLETION_FAILED',
        ],
        [
            'state' => 'COMPLETED',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STATES as $state) {
            OnboardingState::query()->updateOrCreate(
                [
                    'state' => $state['state'],
                ],
                [
                    'state' => $state['state'],
                ]
            );
        }
    }
}
