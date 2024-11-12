<?php

namespace Tests\Unit;
use Illuminate\Support\Facades\Storage;

class BVAModulUserTest extends BoundaryValueAnalysisTest
{
    protected function generateInputs(): array
    {
        return [
            'nia' => $this->bva->bvaInputN('number', 5, 'min'),
            'name' => $this->bva->bvaInputN('text', 100, 'max'),
            'phone_number' => $this->bva->bvaInputMinMax('number', 10, 15),
            'password' => $this->bva->bvaInputMinMax('text', 8, 100),
            'photo' => $this->bva->bvaInputN('image', 1024, 'max'),
        ];
    }

    protected function TestPreparation(string $field, array $testCase, string $function, string $scenario, string $scenarioMethod): array
    {
        $inputValue = [$field => $testCase['value']];
        $displayValue = isset($testCase['display_value']) ? $testCase['display_value'] : $testCase['value'];
        $expectedValidity = $testCase['isValid'];
        $actualValidity = $this->executeTest($scenario, $scenarioMethod, $inputValue);

        return [
            'function' => $function,
            'field' => $field,
            'input' => $displayValue, // Using display_value for documentation
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
