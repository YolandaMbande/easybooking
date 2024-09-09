<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Music', 'description' => 'Music events like concerts, festivals, and more.'],
            ['name' => 'Sports', 'description' => 'Sports events like matches, tournaments, etc.'],
            ['name' => 'Technology', 'description' => 'Tech-related events like conferences, workshops, and more.'],
            ['name' => 'Art', 'description' => 'Art exhibitions, galleries, and other art-related events.'],
            ['name' => 'Education', 'description' => 'Educational events including workshops, lectures, and seminars.'],
            ['name' => 'Business', 'description' => 'Business conferences, networking events, and startup showcases.'],
            ['name' => 'Health & Wellness', 'description' => 'Events focused on health, fitness, wellness, and nutrition.'],
            ['name' => 'Food & Drink', 'description' => 'Events revolving around food, drink festivals, and culinary experiences.'],
            ['name' => 'Film & Media', 'description' => 'Film screenings, media launches, and creative industry events.'],
            ['name' => 'Charity', 'description' => 'Charitable events, fundraisers, and community support initiatives.'],
            ['name' => 'Travel', 'description' => 'Travel-related events including tours, expeditions, and travel fairs.'],
            ['name' => 'Gaming', 'description' => 'Events related to video games, esports tournaments, and gaming conventions.'],
            ['name' => 'Science', 'description' => 'Science-related events including research presentations, science fairs, and workshops.'],
            ['name' => 'Automotive', 'description' => 'Automotive events such as car shows, races, and expos.'],
            ['name' => 'Lifestyle', 'description' => 'Events focused on lifestyle topics such as fashion shows, home and garden expos, and personal development.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

}
