<?php

namespace Tests\Helpers\Methods;

use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GenerateEquivalencePartitioning
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
            'text' => fn($length) => $this->faker->lexify(str_repeat('?', (int) $length)),
            'number' => fn($length) => $this->faker->numerify(str_repeat('#', (int) $length)),
            'image' => fn($size) => $this->generateImage($size),
            'pdf' => fn($size) => $this->generateFile($size, 'pdf'),
            'word' => fn($size) => $this->generateFile($size, 'docx'),
            'email' => fn($length) => $this->generateEmail((int) $length)
        ];
    }

    protected function getOppositeInputType($inputType)
    {
        $oppositeTypes = [
            'text' => 'number',
            'number' => 'text',
            'image' => 'file',
            'file' => 'image',
            'email' => 'text',
            'pdf' => 'word',
            'word' => 'pdf'
        ];
        return $oppositeTypes[$inputType] ?? $inputType;
    }

    protected function generateImage($sizeInKB)
    {
        $extension = $this->faker->randomElement($this->allowedImageExtensions);
        return UploadedFile::fake()->image(
            "test_image_{$sizeInKB}kb.{$extension}",
            100,
            100,
            $extension
        )->size($sizeInKB);
    }

    protected function generateFile($sizeInKB, $type = 'pdf')
    {
        return UploadedFile::fake()->create(
            "test_file_{$sizeInKB}kb.{$type}",
            $sizeInKB,
            "application/{$type}"
        );
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

    public function epRangeInput($inputType, $min, $max)
    {
        $middleValue = (int)(($min + $max) / 2);
        $values = [$middleValue, $min - 1, $max + 1];
        $validities = [true, false, false];
        return $this->generateTestCases($inputType, $values, $validities);
    }

    public function epSpecificInput($inputType, $n)
    {
        $oppositeType = $this->getOppositeInputType($inputType);
        $validCase = $this->generateTestCases($inputType, [$n], [true])[0];
        $invalidCases = $this->generateTestCases($oppositeType, [$n, $n], [false, false]);

        return array_merge([$validCase], $invalidCases);
    }

    public function epSetInput(array $validSet)
    {
        $valid = $this->faker->randomElement($validSet);
        $invalid = $this->faker->text(10);
        $testCases = [
            [
                'value' => $valid,
                'isValid' => true
            ],
            [
                'value' => $invalid,
                'isValid' => false
            ]
        ];
        return $testCases;
    }

    public function epBooleanInput()
    {
        $valid = [1, 0];
        $invalid = $this->faker->randomNumber(2);
        $testCases = [
            [
                'value' => $this->faker->randomElement($valid),
                'isValid' => true
            ],
            [
                'value' => $invalid,
                'isValid' => false
            ]
        ];
        return $testCases;
    }

    protected function generateInvalidValue($validSet)
    {
        $invalidValue = null;
        if (count($validSet) > 0 && is_numeric(max($validSet))) {
            $invalidValue = max($validSet) + 1;
            while (in_array($invalidValue, $validSet)) {
                $invalidValue++;
            }
        } else {
            $invalidValue = $this->generators[$this->getOppositeInputType(key($this->generators))](1);
        }
        return $invalidValue;
    }
}