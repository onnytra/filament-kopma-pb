<?php

namespace Tests\Helpers\Methods;

use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GenerateBoundaryValueAnalysis
{
    protected $faker;
    protected $generators;
    protected $allowedImageExtensions;
    protected $allowedFileExtensions;

    public function __construct()
    {
        $this->faker = Faker::create();
        $this->allowedImageExtensions = ['jpg', 'jpeg', 'png'];
        $this->allowedFileExtensions = ['pdf', 'doc', 'docx', 'txt'];
        $this->initGenerators();
    }

    protected function initGenerators()
    {
        $this->generators = [
            'text' => fn($length) => $this->faker->lexify(str_repeat('?', $length)),
            'number' => fn($length) => $this->faker->numerify(str_repeat('#', $length)),
            'image' => fn($size) => $this->generateImage($size),
            'file' => fn($size) => $this->generateFile($size),
            'email' => fn($length) => $this->generateEmail($length)
        ];
    }

    protected function generateImage($sizeInKB)
    {
        $extension = $this->faker->randomElement($this->allowedImageExtensions);
        $filename = "test_image_{$sizeInKB}kb.{$extension}";
        
        return [
            'file' => UploadedFile::fake()->image(
                $filename,
                100,
                100, 
                $extension
            )->size($sizeInKB),
            'filename' => $filename
        ];
    }

    protected function generateFile($sizeInKB)
    {
        $extension = $this->faker->randomElement($this->allowedFileExtensions);
        $filename = "test_file_{$sizeInKB}kb.{$extension}";
        
        return [
            'file' => UploadedFile::fake()->create(
                $filename,
                $sizeInKB,
                "application/{$extension}"
            ),
            'filename' => $filename
        ];
    }

    public function generateTestCases($inputType, $values, $validities)
    {
        $generator = $this->generators[$inputType] ?? null;
        if (!$generator) {
            throw new \InvalidArgumentException("Unsupported input type: {$inputType}");
        }

        $testCases = [];
        foreach ($values as $index => $value) {
            $generatedValue = $generator($value);
            
            // Check if the generated value is an array (for files/images)
            $testValue = is_array($generatedValue) ? $generatedValue['file'] : $generatedValue;
            $displayValue = is_array($generatedValue) ? $generatedValue['filename'] : $generatedValue;
            
            $testCases[] = [
                'value' => $testValue,
                'display_value' => $displayValue,
                'isValid' => $validities[$index]
            ];
        }
        return $testCases;
    }

    protected function generateEmail($length)
    {
        // Generate email with specific username length
        $username = $this->faker->lexify(str_repeat('?', $length));
        return $username . '@example.com';
    }

    // public function generateTestCases($inputType, $values, $validities)
    // {
    //     $generator = $this->generators[$inputType] ?? null;
    //     if (!$generator) {
    //         throw new \InvalidArgumentException("Unsupported input type: {$inputType}");
    //     }

    //     $testCases = [];
    //     foreach ($values as $index => $value) {
    //         $testCases[] = [
    //             'value' => $generator($value),
    //             'isValid' => $validities[$index]
    //         ];
    //     }
    //     return $testCases;
    // }

    public function bvaInputMinMax($inputType, $min, $max)
    {
        $values = [
            $min,       // Minimum boundary
            $min + 1,   // Just above minimum
            $max - 1,   // Just below maximum
            $max,       // Maximum boundary
            $min - 1,   // Just below minimum (invalid)
            $max + 1    // Just above maximum (invalid)
        ];
        
        $validities = [
            true,  // Minimum boundary
            true,  // Just above minimum
            true,  // Just below maximum
            true,  // Maximum boundary
            false, // Below minimum (invalid)
            false  // Above maximum (invalid)
        ];

        return $this->generateTestCases($inputType, $values, $validities);
    }

    public function bvaInputN($inputType, $n, $nAsMinOrMax)
    {
        if ($nAsMinOrMax == 'min') {
            $values = [
                $n,     // Exact minimum
                $n + 1, // Just above minimum
                $n - 1  // Just below minimum (invalid)
            ];
            $validities = [true, true, false];
        } elseif ($nAsMinOrMax == 'max') {
            $values = [
                $n,     // Exact maximum
                $n - 1, // Just below maximum
                $n + 1  // Just above maximum (invalid)
            ];
            $validities = [true, true, false];
        } else {
            throw new \InvalidArgumentException('Invalid parameter. Expected "min" or "max".');
        }

        return $this->generateTestCases($inputType, $values, $validities);
    }
}