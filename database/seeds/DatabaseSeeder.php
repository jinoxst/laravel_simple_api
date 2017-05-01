<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Eloquent::unguard();
        DB::statement("SET foreign_key_checks = 0");

        DB::table('reservations')->truncate();

        $this->call(BookUsersSeeder::class);
        $this->call(BooksSeeder::class);

        DB::statement("SET foreign_key_checks = 1");
    }
}
