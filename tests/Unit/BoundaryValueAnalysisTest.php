<?php
namespace Tests\Unit;

use App\Models\Admin;
use Tests\TestCase;
use Tests\Helpers\Methods\GenerateBoundaryValueAnalysis;
use Tests\Helpers\Scenario\AdminScenario;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class BoundaryValueAnalysisTest extends TestCase
{
    protected $bva;
    protected $adminScenario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::where('email', 'admin@gmail.com')->first());
        $this->bva = new GenerateBoundaryValueAnalysis();
        $this->adminScenario = new AdminScenario();
    }

    public function test_AdminCreationWithBVA()
    {
        $results = []; 
        $nameTests = $this->bva->bvaInputMinMax('text', 3, 100);
        $passwordTests = $this->bva->bvaInputMinMax('text', 8, 100);

        foreach ($nameTests as $nameTest) {
            $inputValue['name'] = $nameTest['value'];
            $expectedValidity = $nameTest['isValid'];
            try {
                $this->adminScenario->runTestCase($inputValue);
                try {
                    $this->assertDatabaseHas('admins', ['name' => $inputValue]);
                    $actualValidity = true;
                } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
                    $actualValidity = false;
                }
            } catch (ValidationException $e) {
                $actualValidity = false;

            } catch (QueryException $e) {
                $actualValidity = false;
            }
            $results[] = [
                'input' => $inputValue['name'],
                'expected' => $expectedValidity,
                'actual' => $actualValidity,
            ];
        }
        foreach ($results as $result) {
            echo "Input: {$result['input']}, Expected: " . ($result['expected'] ? 'true' : 'false') . ", Actual: " . ($result['actual'] ? 'true' : 'false') . PHP_EOL;
        }

        return $results; 
    }
}
