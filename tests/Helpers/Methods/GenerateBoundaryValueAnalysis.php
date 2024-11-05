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
            'image' => fn($size) => $this->generateImage($size),
            'file' => fn($size) => $this->generateFile($size),
            'email' => fn($length) => $this->generateEmail($length)
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

    protected function generateFile($sizeInKB)
    {
        $filePath = storage_path("test_file_{$sizeInKB}kb.txt");
        $handle = fopen($filePath, 'w');
        ftruncate($handle, $sizeInKB * 1024);
        fclose($handle);
        return $filePath;
    }

    protected function generateEmail($length)
    {
        $username = $this->faker->lexify(str_repeat('?', $length));
        return $username . '@example.com';
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
        $values = [$min, $min + 1, $max, $max - 1, $min - 1, $max + 1];
        $validities = [true, true, true, true, false, false];
        return $this->generateTestCases($inputType, $values, $validities);
    }

    public function bvaInputN($inputType, $n, $nAsMinOrMax)
    {
        if ($nAsMinOrMax == 'min') {
            $values = [$n, $n + 1, $n - 1];
            $validities = [true, true, false];
        } elseif ($nAsMinOrMax == 'max') {
            $values = [$n, $n - 1, $n + 1];
            $validities = [true, true, false];
        } else {
            return ['error' => 'Invalid parameter. Expected "min" or "max".'];
        }
        return $this->generateTestCases($inputType, $values, $validities);
    }
}
