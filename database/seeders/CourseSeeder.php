<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('courses')->insert([
            'c_name' => 'Curso Primero',
            'c_description' => 'desc Primero',
            'c_period' => '2022',
            'c_numberStudent' => 50,
            'c_date_initial' => Carbon::now()->format('Y-m-d'),
            'c_note_approved' => 12.5,
        ]);

        DB::table('courses')->insert([
            'c_name' => 'Curso segundo',
            'c_description' => 'desc segundo',
            'c_period' => '2023',
            'c_numberStudent' => 20,
            'c_date_initial' => Carbon::now()->format('Y-m-d'),
            'c_note_approved' => 15.6,
        ]);

        DB::table('courses')->insert([
            'c_name' => 'Curso tercero',
            'c_description' => 'desc tercero',
            'c_period' => '2023',
            'c_numberStudent' => 60,
            'c_date_initial' => Carbon::now()->format('Y-m-d'),
            'c_note_approved' => 14.6
        ]);
    }
}
