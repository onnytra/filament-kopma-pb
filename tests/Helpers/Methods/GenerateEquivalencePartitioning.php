<?php

namespace Tests\Helpers;

use Faker\Factory as Faker;

class GenerateEquivalencePartitioning
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function ep_input(bool $required, $input_type)
    {
        switch ($input_type) {
            case 'text':
                return [
                    // Valid
                    [$this->faker->lexify(str_repeat('?', 0)), $required, true],
                    // Invalid
                    [$this->faker->lexify(str_repeat('?', 0)), $required, false],
                    [$this->faker->lexify(str_repeat('?', 101)), $required, false],
                ];
                
        }
    }
}
