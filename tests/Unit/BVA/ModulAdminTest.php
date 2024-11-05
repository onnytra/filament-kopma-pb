<?php

namespace Tests\Unit;

class BVAModulAdminTest extends BoundaryValueAnalysisTest
{
    protected function generateInputs(): array
    {
        return [
            'password' => $this->bva->bvaInputMinMax('text', 8, 100),
            'name' => $this->bva->bvaInputN('text', 100, 'max'),
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

    public function test_AdminCreate(): array
    {
        $results = [];
        $scenario = 'Tests\Helpers\Scenario\AdminScenario';
        $scenarioMethod = 'runCreateAdmin';
        $testCases = $this->generateInputs();

        foreach ($testCases as $field => $cases) {
            foreach ($cases as $testCase) {
                $results[] = $this->TestPreparation($field, $testCase, 'Admin Create', $scenario, $scenarioMethod);
            }
        }
        $this->Results($results);
        return $results;
    }

    public function test_AdminUpdate(): array
    {
        $results = [];
        $scenario = 'Tests\Helpers\Scenario\AdminScenario';
        $scenarioMethod = 'runUpdateAdmin';
        $testCases = $this->generateInputs();

        foreach ($testCases as $field => $cases) {
            foreach ($cases as $testCase) {
                $results[] = $this->TestPreparation($field, $testCase, 'Admin Update', $scenario, $scenarioMethod);
            }
        }
        $this->Results($results);
        return $results;
    }
}
