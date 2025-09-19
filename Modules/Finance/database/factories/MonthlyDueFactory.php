<?php

namespace Modules\Finance\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Site;
use App\Models\Apartment;
use App\Models\User;

class MonthlyDueFactory extends Factory
{
    protected $model = \Modules\Finance\app\Models\MonthlyDue::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'apartment_id' => Apartment::factory(),
            'resident_user_id' => User::factory(),
            'period' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'overdue']),
        ];
    }
}
