<div class="flex justify-center mx-auto">
    <div class="max-w-3xl bg-gray-50 py-6 px-12 rounded-md shadow-sm mt-20">
        {{-- Steps --}}
        <ol class="flex items-center w-full text-sm font-medium text-center text-body sm:text-base">
            @for ($i = 0; $i < $numOfSteps; $i++)
                @php
                    $step = $i + 1;
                    $isActive = $user->onboarding_step > $step;
                    $isCurrent = $user->onboarding_step == $step;
                @endphp

                <li class="flex items-center {{ $step > 1 ? 'ml-2' : '' }}">
                    <div class="flex items-center gap-x-2 {{ $isActive || $isCurrent ? 'text-blue-500' : '' }}">
                        <i class="fi fi-rr-circle-{{ $step }}"></i>
                        <span>{{ __('onboarding.stepper_step' . $step) }}</span>

                        @if ($step < $numOfSteps)
                            <hr class="w-32 h-1 border-0 rounded-md hidden sm:block
                                {{ $isActive ? 'bg-blue-500' : 'bg-gray-300' }}">
                        @endif
                    </div>
                </li>
            @endfor
        </ol>

        {{-- Content --}}
        <div class="mt-6">
            @if ($user->onboarding_step == 1)
                <div class="">
                    <h1 class="text-lg font-rw-bold">
                        {{ __('onboarding.step1_title') }}
                    </x-containers.title>
                    <p class="text-gray-700 font-rw-regular">
                        {{ __('onboarding.step1') }}
                    </p>
                    <ol class="mt-4 list-decimal list-inside font-rw-regular text-gray-700">
                        <li class="">{{ __('onboarding.steps_1') }}</li>
                        <li class="">{{ __('onboarding.steps_2') }}</li>
                        <li class="">{!! __('onboarding.steps_3') !!}</li>
                        <li class="">{{ __('onboarding.steps_4') }}</li>
                    </ol>
                </div>
            @elseif ($user->onboarding_step == 2)
                <div>
                    <p class="text-gray-700 font-rw-regular">
                        {{ __('onboarding.step2') }}
                    </p>
                    <div class="flex justify-center gap-x-4">
                        <input wire:model="code" type="text" class="mt-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('onboarding.step2_placeholder') }}">
                    </div>

                    @if ($errorMessage)
                        <p class="text-red-500 mt-2 text-center">{{ __($errorMessage) }}</p>
                    @endif
                </div>
            @elseif ($user->onboarding_step == 3)
                <div>
                    <p class="text-gray-700 font-rw-regular">
                        {{ __('onboarding.step3') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-x-4 mt-6">
            @if ($user->onboarding_step > 1 && $user->onboarding_step < $numOfSteps)
                <button wire:click="previousStep"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ __('onboarding.previous') }}
                </button>
            @endif

            @if ($user->onboarding_step == 1)
                <button wire:click="submitStep1"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    {{ __('onboarding.next') }}
                </button>
            @endif

            @if ($user->onboarding_step == 2)
                <button wire:click="submitStep2"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    {{ __('onboarding.next') }}
                </button>
            @endif

            @if ($user->onboarding_step == 3 && $user->onboarding_status)
                <button wire:click="submitStep3"
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    {{ __('onboarding.complete_onboarding') }}
                </button>
            @endif
        </div>
    </div>
</div>