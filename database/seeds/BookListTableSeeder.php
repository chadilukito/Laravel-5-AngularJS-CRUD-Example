<?php

use Illuminate\Database\Seeder;

class BookListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\BookList::class, 50)->create()->each(function($booklist) {
            $booklist->make();
        });
    }
}
