<?php

namespace Tests\Unit;

use App\Models\Jabatan;
use Database\Factories\JabatanFactory;

class EPModulUserTest extends EquivalencePartitioningTest
{
    protected function generateInputs(): array
    {
        $alljabatan = Jabatan::all()->pluck('id')->toArray();
        return [
            'nia' => $this->ep->epSpecificInput('number', 5),
            'name' => $this->ep->epRangeInput('text', 1, 100),
            'email' => $this->ep->epSpecificInput('email', 100),
            'phone_number' => $this->ep->epRangeInput('number', 10, 15),
            'password' => $this->ep->epRangeInput('text', 8, 100),
            'jabatan' => $this->ep->epSetInput($alljabatan),
            'status_user' => $this->ep->epBooleanInput(),
        ];
    }

    protected function TestPreparation(string $field, array $testCase, string $function, string $scenario, string $scenarioMethod): array
    {
        $inputValue = [$field => $testCase['value']];
        $expectedValidity = $testCase['isValid'];
        $actualValidity = $this->executeTest($scenario, $scenarioMethod, $inputValue);

        return [
            'function' => $function,
            'field' => $field,
            'input' => $inputValue[$field],
            'reason' => $actualValidity['reason'],
            'expected' => $expectedValidity,
            'actual' => $actualValidity['actual'],
        ];
    }

    public function test_UserCreate(): array
    {
        $results = [];
        $scenario = 'Tests\Helpers\Scenario\UserScenario';
        $scenarioMethod = 'runCreateUser';
        $testCases = $this->generateInputs();

        foreach ($testCases as $field => $cases) {
            foreach ($cases as $testCase) {
                $results[] = $this->TestPreparation($field, $testCase, 'User Create', $scenario, $scenarioMethod);
            }
        }
        $this->Results($results);
        return $results;
    }

    public function test_UserUpdate(): array
    {
        $results = [];
        $scenario = 'Tests\Helpers\Scenario\UserScenario';
        $scenarioMethod = 'runUpdateUser';
        $testCases = $this->generateInputs();

        foreach ($testCases as $field => $cases) {
            foreach ($cases as $testCase) {
                $results[] = $this->TestPreparation($field, $testCase, 'User Update', $scenario, $scenarioMethod);
            }
        }
        $this->Results($results);
        return $results;
    }
}
