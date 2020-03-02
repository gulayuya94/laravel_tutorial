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
            'title' => 'sampleTodo2',
            'content' => 'sampleContent2',
            'status' => 1,
            'due_date' => new DateTime(),
        ]);
    }
}
