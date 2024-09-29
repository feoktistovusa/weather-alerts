<?php

namespace App\Livewire\Profile;

use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageCities extends Component
{
    public $cities;
    public $newCity;

    public function mount()
    {
        $this->cities = Auth::user()->cities()
            ->select('cities.id', 'cities.name')
            ->pluck('cities.name', 'cities.id')
            ->toArray();
    }

    public function addCity()
    {
        $this->validate([
            'newCity' => 'required|string',
        ]);

        $cityName = $this->newCity;
        $city = City::firstOrCreate(['name' => $cityName]);

        Auth::user()->cities()->syncWithoutDetaching($city->id);

        $this->newCity = '';
        $this->mount();
    }

    public function removeCity($cityId)
    {
        Auth::user()->cities()->detach($cityId);
        $this->mount();
    }

    public function render()
    {
        return view('livewire.profile.manage-cities');
    }
}
