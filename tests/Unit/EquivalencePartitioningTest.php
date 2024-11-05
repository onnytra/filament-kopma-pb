<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Test;
use Tests\Helpers\Methods\GenerateEquivalencePartitioning;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

class EquivalencePartitioningTest extends TestCase
{
    protected GenerateEquivalencePartitioning $ep;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ep = new GenerateEquivalencePartitioning();
        // Start a database transaction
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        // Ensure any remaining transaction is rolled back
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        parent::tearDown();
    }

    protected function executeTest(string $scenario, string $scenarioMethod, array $inputValue)
    {
        try {
            $scenarioInstance = new $scenario();
            $test = call_user_func_array([$scenarioInstance, $scenarioMethod], [$inputValue]);

            try {
                $test->assertHasNoErrors();
                return [
                    'actual' => true,
                    'reason' => 'Test passed',
                ];
            } catch (Throwable $e) {
                return [
                    'actual' => false,
                    'reason' => $e->getMessage(),
                ];
            }
        } catch (Throwable $e) {
            return [
                'actual' => true,
                'reason' => $e->getMessage(),
            ];
        }
    }

    protected function Results(array $results): void
    {
        // Roll back the test transaction
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        // Start a new transaction for results
        DB::beginTransaction();
        
        try {
            foreach ($results as $result) {
                Test::create([
                    'method' => 'Equivalence Partitioning',
                    'function' => $result['function'],
                    'variable' => $result['field'],
                    'value' => $result['input'],
                    'expected_validity' => $result['expected'],
                    'actual_validity' => $result['actual'],
                    'status' => $result['expected'] === $result['actual'] ? 1 : 0,
                    'reason' => $result['reason'],
                ]);
            }
            
            // If everything went well, commit the results
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \RuntimeException('Database error while saving results: ' . $e->getMessage());
        } catch (Throwable $e) {
            DB::rollBack();
            throw new \RuntimeException('Error while saving results: ' . $e->getMessage());
        }
    }
}