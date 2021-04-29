<?php

namespace Database\Factories\AD;

use App\Models\AD\AD_Computer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AD_Computer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'object_guid' => $this->faker->id(),
            'common_name' => $this->faker->name(),
            'surname' => $this->faker->name(),
            'given_name' => $this->faker->name(),
            'sam_account_name' => $this->faker->name(),
            'physical_delivery_office_name' => $this->faker->name(),
            'telephone_number',
            'email_addresses' =>$this->faker->unique()->safeEmail(),
            'department',
            'obj_dist_name' => $this->faker->name(),
            'last_logon',
            'logon_count' => 1,
            'user_principal_name',
            'is_enabled' => true,
        ];
    }
}
