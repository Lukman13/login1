<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

class TimelineSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 100; $i++) {

            $data = [
                'day'    => $faker->dayOfWeek,
                'date' => Time::createFromTimestamp($faker->unixTime),
                'title' => $faker->name,
                'text'    => $faker->Text,
                'img' => $faker->imageUrl($width = 480, $height = 480)
            ];
            $this->db->table('timline')->insert($data);
        }

        // Simple Queries
        // $this->db->query(
        //     "INSERT INTO orang (nama, alamat, created_at, updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)",
        //     $data
        // );

        // Using Query Builder
    }
}
