<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

class AjaxSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 15; $i++) {

            $data = [
                'img'    => 'default.jpg',
                'nama' => $faker->name,
                'job' => $faker->jobTitle,
                'address' => $faker->address
            ];
            $this->db->table('ajax')->insert($data);
        }

        // Simple Queries
        // $this->db->query(
        //     "INSERT INTO orang (nama, alamat, created_at, updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)",
        //     $data
        // );

        // Using Query Builder
    }
}
