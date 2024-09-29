<?php

namespace App\Http\Livewire\Profile;

use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as BaseUpdateProfileInformationForm;
class UpdateProfileInformationForm extends BaseUpdateProfileInformationForm
{
    public function mount(): void
    {
        $this->state = [
            'name'                      => $this->user->name,
            'email'                     => $this->user->email,
            'precipitation_threshold'   => $this->user->precipitation_threshold,
            'uv_index_threshold'        => $this->user->uv_index_threshold,
        ];
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater): void
    {
        $this->resetErrorBag();

        $this->validate([
            'state.name'                    => 'required|string|max:255',
            'state.email'                   => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'state.precipitation_threshold' => 'required|numeric|min:0',
            'state.uv_index_threshold'      => 'required|numeric|min:0',
        ]);

        $this->user->forceFill([
            'name'                      => $this->state['name'],
            'email'                     => $this->state['email'],
            'precipitation_threshold'   => $this->state['precipitation_threshold'],
            'uv_index_threshold'        => $this->state['uv_index_threshold'],
        ])->save();

        $this->dispatch('saved');

        $this->dispatch('refresh-navigation-menu');

        if ($this->state['email'] !== $this->user->email && Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification())) {
            $this->user->sendEmailVerificationNotification();
        }
    }
}
