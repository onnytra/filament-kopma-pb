<?php

namespace Tests\Helpers\Methods;

use Faker\Factory as Faker;

class GenerateEquivalencePartitioning
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
            'text' => fn($length) => $this->faker->lexify(str_repeat('?', (int) $length)),
            'number' => fn($length) => $this->faker->numerify(str_repeat('?', (int) $length)),
            'image' => fn($size) => $this->generateImage($size),
            'pdf' => fn($size) => $this->generatePDF($size),
            'word' => fn($size) => $this->generateWord($size),
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
        $image = imagecreatetruecolor(100, 100);
        $filePath = storage_path("test_image_{$sizeInKB}kb.jpg");
        imagejpeg($image, $filePath);
        $handle = fopen($filePath, 'w');
        ftruncate($handle, $sizeInKB * 1024);
        fclose($handle);
        return $filePath;
    }

    protected function generatePDF($sizeInKB)
    {
        $filePath = storage_path("test_document_{$sizeInKB}kb.pdf");
        $handle = fopen($filePath, 'w');
        ftruncate($handle, $sizeInKB * 1024);
        fclose($handle);
        return $filePath;
    }

    protected function generateWord($sizeInKB)
    {
        $filePath = storage_path("test_document_{$sizeInKB}kb.docx");
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
        if (empty($validSet)) {
            throw new \InvalidArgumentException("Valid set cannot be empty");
        }

        $valid  = $this->faker->randomElement($validSet);
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
