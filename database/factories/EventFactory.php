<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $locations = [
            'Johannesburg', 'Pretoria', 'Durban', 'Cape Town', 'Soweto',
            'Benoni', 'Boksburg', 'Midrand', 'Ekurhuleni', 'Randburg'
        ];

        $statuses = ['Upcoming', 'Ongoing', 'Completed'];
        $visibilityOptions = ['Public', 'Private'];

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->randomElement($locations),
            'latitude' => $this->faker->latitude(-26.0, -25.0),
            'longitude' => $this->faker->longitude(28.0, 29.0),
            'date_time' => Carbon::now()->addDays(rand(1, 30)),
            'category_id' => Category::factory(), // Assuming you have categories created
            'max_attendees' => $this->faker->numberBetween(10, 500),
            'ticket_price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement($statuses),
            'visibility' => $this->faker->randomElement($visibilityOptions),
            'image' => $this->faker->imageUrl(640, 480, 'events'), 
        ];
    }
}

