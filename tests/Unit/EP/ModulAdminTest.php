<?php

namespace Tests\Unit;

class EPModulAdminTest extends EquivalencePartitioningTest
{
    protected function generateInputs(): array
    {
        return [
            'password' => $this->ep->epRangeInput('text', 8, 100),
            'name' => $this->ep->epRangeInput('text', 1, 100),
            'email' => $this->ep->epSpecificInput('email', 100),
            'role' => $this->ep->epSetInput(['admin', 'sekertaris', 'bendahara']),
            'status_admin' => $this->ep->epBooleanInput(),
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
