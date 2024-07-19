<?php

namespace Database\Factories;

use App\Models\Directory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DirectoryFactory extends Factory
{

    protected $model = Directory::class;

    public function definition()
    {
        return [
            'name'       => $this->faker->name(),
            'parent_id'  => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
