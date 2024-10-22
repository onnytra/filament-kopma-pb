<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\Methods\GenerateBoundaryValueAnalysis;
use Tests\Helpers\Scenario\AdminScenario;

class BoundaryValueAnalysisTest extends TestCase
{
    protected $bva;
    protected $adminScenario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bva = new GenerateBoundaryValueAnalysis();
        $this->adminScenario = new AdminScenario();
    }

    public function testAdminCreationWithBVA()
    {
        // Test cases for name (text input)
        $nameTests = $this->bva->bvaInputMinMax('text', 3, 100);
        
        // Test cases for password (text input)
        $passwordTests = $this->bva->bvaInputMinMax('text', 8, 100);

        // Combine test cases
        foreach ($nameTests as $nameTest) {
            $testCase = [
                'name' => $nameTest
            ];
            $response = $this->adminScenario->runTestCase($testCase);
            if ($nameTest['isValid']) {
                $response->assertHasNoErrors();
            } else {
                $response->assertHasErrors(['data.name']);
            }
        }

        // Similar tests can be added for email and password
    }
}