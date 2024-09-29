<div>
    <h3 class="text-lg font-medium text-gray-900">
        Manage Your Cities
    </h3>

    <div class="mt-4">
        <form wire:submit.prevent="addCity">
            <div class="flex items-center">
                <x-input id="newCity" type="text" class="mt-1 block w-full" placeholder="Enter city name" wire:model.defer="newCity" />
                <x-button class="ml-2">
                    Add City
                </x-button>
            </div>
            <x-input-error for="newCity" class="mt-2" />
        </form>
    </div>

    <ul class="mt-4">
        @foreach($cities as $cityId => $cityName)
            <li class="flex items-center justify-between">
                <span>{{ $cityName }}</span>
                <x-button wire:click="removeCity({{ $cityId }})" class="ml-2">
                    Remove
                </x-button>
            </li>
        @endforeach
    </ul>
</div>
