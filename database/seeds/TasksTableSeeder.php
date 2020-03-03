<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            'user_id' => 1,
            'title' => 'sampleTodo10',
            'content' => 'sampleContent10 sampleContent10 sampleContent10 sampleContent10 sampleContent10',
            'status' => 1,
            'due_date' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
    }
}
