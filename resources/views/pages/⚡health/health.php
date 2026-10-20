<?php

use App\Models\ChatColor;
use App\Models\Country;
use App\Models\Language;
use App\Models\OnboardingState;
use App\Models\User;
use Database\Seeders\ChatColorsSeeder;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\LanguagesSeeder;
use Database\Seeders\OnboardingStatesSeeder;
use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return view('pages.⚡health.health', [
            'checks' => [
                'Chat colors' => $this->containsRequiredRows(
                    ChatColor::query()->pluck('name')->all(),
                    array_column(ChatColorsSeeder::COLORS, 'name'),
                ),
                'Countries' => $this->containsRequiredRows(
                    Country::query()->pluck('iso')->all(),
                    array_column(CountriesSeeder::COUNTRIES, 'iso'),
                ),
                'Languages' => $this->containsRequiredRows(
                    Language::query()->pluck('iso')->all(),
                    array_column(LanguagesSeeder::LANGUAGES, 'iso'),
                ),
                'Users' => User::query()->exists(),
                'Onboarding states' => $this->containsRequiredRows(
                    OnboardingState::query()->pluck('state')->all(),
                    array_column(OnboardingStatesSeeder::STATES, 'state'),
                ),
            ],
        ]);
    }

    /**
     * @param  array<int, string>  $existing
     * @param  array<int, string>  $required
     */
    private function containsRequiredRows(array $existing, array $required): bool
    {
        return array_diff($required, $existing) === [];
    }
};
