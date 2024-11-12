<?php

namespace Tests\Helpers\Scenario;

use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Models\Jabatan;
use App\Models\User;
use Livewire\Livewire;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;

class UserScenario
{
    public function __construct()
    {
        Storage::fake('public');
    }
    public function createUser($data)
    {
        return Livewire::test(CreateUser::class)
            ->set('data.nia', $data['nia'])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.phone_number', $data['phone_number'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password_confirmation'])
            ->set('data.photo', $data['photo'])
            ->set('data.status_user', $data['status_user'])
            ->set('data.jabatan_id', $data['jabatan_id'])
            ->call('create');
    }
    public function runCreateUser($testCase)
    {
        return $this->createUser([
            'nia' => $testCase['nia'] ?? Faker::create()->unique()->numerify('#######'),
            'name' => $testCase['name'] ?? 'User',
            'email' => $testCase['email'] ?? Faker::create()->unique()->safeEmail(),
            'phone_number' => $testCase['phone_number'] ?? Faker::create()->unique()->numerify('##########'),
            'password' => $testCase['password'] ?? "password123",
            'password_confirmation' => $testCase['password'] ?? "password123",
            'photo' => $testCase['photo'] ?? null,
            'status_user' => $testCase['status_user'] ?? true,
            'jabatan_id' => $testCase['jabatan_id'] ?? Jabatan::first()->id
        ]);
    }

    public function updateUser($user, $data)
    {
        return Livewire::test(EditUser::class, ['record' => $user->id])
            ->set('data.nia', $data['nia'])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.phone_number', $data['phone_number'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password_confirmation'])
            ->set('data.photo', $data['photo'])
            ->set('data.status_user', $data['status_user'])
            ->set('data.jabatan_id', $data['jabatan_id'])
            ->call('save');
    }

    public function runUpdateUser($testCase)
    {
        $user = User::first();
        return $this->updateUser($user, [
            'nia' => $testCase['nia'] ?? $user->nia,
            'name' => $testCase['name'] ?? $user->name,
            'email' => $testCase['email'] ?? $user->email,
            'phone_number' => $testCase['phone_number'] ?? $user->phone_number,
            'password' => $testCase['password'] ?? "password123",
            'password_confirmation' => $testCase['password'] ?? "password123",
            'photo' => $testCase['photo'] ?? null,
            'status_user' => $testCase['status_user'] ?? $user->status_user,
            'jabatan_id' => $testCase['jabatan_id'] ?? $user->jabatan_id
        ]);
    }
}
