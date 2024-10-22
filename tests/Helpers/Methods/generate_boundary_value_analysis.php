<?php
namespace Tests\Helpers\Methods;

use Faker\Factory as Faker;

class GenerateBoundaryValueAnalysis
{
    protected $faker;
    protected $generators;

    public function __construct()
    {
        $this->faker = Faker::create();
        $this->initGenerators();
    }

    protected function initGenerators()
    {
        $this->generators = [
            'text' => fn($length) => $this->faker->lexify(str_repeat('?', $length)),
            'number' => fn($length) => $this->faker->numerify(str_repeat('?', $length)),
            'image' => fn($size) => $this->generateImage($size)
        ];
    }

    protected function generateImage($sizeInKB)
    {
        $image = imagecreatetruecolor(100, 100);
        $filePath = storage_path("test_image_{$sizeInKB}kb.jpg");

        imagejpeg($image, $filePath);
        $handle = fopen($filePath, 'w');
        ftruncate($handle, $sizeInKB * 1024);
        fclose($handle);

        return $filePath;
    }

    public function generateTestCases($inputType, $values, $validities)
    {
        $generator = $this->generators[$inputType] ?? null;
        if (!$generator) {
            throw new \InvalidArgumentException("Unsupported input type: {$inputType}");
        }

        $testCases = [];
        foreach ($values as $index => $value) {
            $testCases[] = [
                'value' => $generator($value),
                'isValid' => $validities[$index]
            ];
        }
        return $testCases;
    }

    public function bvaInputMinMax($inputType, $min, $max)
    {
        $values = [
            $min,     // Valid min
            $min + 1, // Valid min + 1
            $max,     // Valid max
            $max - 1, // Valid max - 1
            $min - 1, // Invalid below min
            $max + 1  // Invalid above max
        ];

        $validities = [true, true, true, true, false, false];
        return $this->generateTestCases($inputType, $values, $validities);
    }

    public function bvaInputN($inputType, $n, $nAsMinOrMax)
    {
        if ($nAsMinOrMax === 'min') {
            $values = [$n, $n + 1, $n - 1];
            $validities = [true, true, false];
        } else {
            $values = [$n, $n - 1, $n + 1];
            $validities = [true, true, false];
        }

        return $this->generateTestCases($inputType, $values, $validities);
    }
}
