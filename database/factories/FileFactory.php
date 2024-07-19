<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{

    protected $model = File::class;

    public function definition()
    {
        return [
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'name'        => $this->faker->name(),
            'size'        => $this->faker->randomNumber(),
            'uploaded_at' => Carbon::now(),
            'path'        => $this->faker->filePath(),
            'is_public'   => $this->faker->boolean(),
            'unique_link' => $this->faker->word(),
        ];
    }
}
