<?php

namespace Tests\Helpers\Scenario;

use App\Filament\Admin\Resources\AdminResource\Pages\CreateAdmin;
use App\Filament\Admin\Resources\AdminResource\Pages\EditAdmin;
use App\Models\Admin;
use Livewire\Livewire;
use Faker\Factory as Faker;

class AdminScenario
{
    public function createAdmin($data)
    {
        return Livewire::test(CreateAdmin::class)
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.role', $data['role'])
            ->set('data.status_admin', $data['status_admin'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password_confirmation'])
            ->call('create');
    }
    public function runCreateAdmin($testCase)
    {
        return $this->createAdmin([
            'name' => $testCase['name'] ?? "John Doe",
            'email' => $testCase['email'] ?? Faker::create()->unique()->safeEmail(),
            'role' => $testCase['role'] ?? 'admin',
            'password' => $testCase['password'] ?? "password123",
            'password_confirmation' => $testCase['password'] ?? "password123",
            'status_admin' => $testCase['status_admin'] ?? true,
        ]);
    }

    public function updateAdmin($admin, $data)
    {
        return Livewire::test(EditAdmin::class, ['record' => $admin->id])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.role', $data['role'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password_confirmation'])
            ->set('data.status_admin', $data['status_admin'])
            ->call('save');
    }

    public function runUpdateAdmin($testCase)
    {
        $admin = Admin::first();
        return $this->updateAdmin($admin, [
            'name' => $testCase['name'] ?? $admin->name,
            'email' => $testCase['email'] ?? $admin->email,
            'role' => $testCase['role'] ?? $admin->role,
            'status_admin' => $testCase['status_admin'] ?? $admin->status_admin,
            'password' => $testCase['password'] ?? "password123",
            'password_confirmation' => $testCase['password'] ?? "password123",
        ]);
    }
}
