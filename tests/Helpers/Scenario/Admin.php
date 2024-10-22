<?php
namespace Tests\Helpers\Scenario;

use App\Filament\Admin\Resources\AdminResource\Pages\CreateAdmin;
use Livewire\Livewire;
use Faker\Factory as Faker;

class AdminScenario
{
    public function createAdmin($data)
    {
        return Livewire::test(CreateAdmin::class)
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password_confirmation'])
            ->set('data.role', $data['role'])
            ->set('data.status_admin', $data['status_admin'])
            ->call('create');
    }

    public function runTestCase($testCase)
    {
        return $this->createAdmin([
            'name' => $testCase['name']['value'] ?? "John Doe",
            'email' => $testCase['email']['value'] ?? Faker::create()->unique()->safeEmail(),
            'password' => $testCase['password']['value'] ?? "password123",
            'password_confirmation' => $testCase['password']['value'] ?? "password123",
            'role' => 'admin',
            'status_admin' => true
        ]);
    }
}
