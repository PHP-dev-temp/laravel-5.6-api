<?php

use App\Category;
use App\Inventory;
use App\Author;
use App\Rental;
use App\User;
use App\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Book::truncate();
        Author::truncate();
        Inventory::truncate();
        Rental::truncate();
        DB::table('book_category')->truncate();

        $usersQuantity = 50;
        $categoriesQuantity = 12;
        $booksQuantity = 1000;
        $rentalsQuantity = 500;

        factory(User::class, $usersQuantity)->create();
        factory(Category::class, $categoriesQuantity)->create();
        factory(Author::class, $usersQuantity)->create();
        factory(Inventory::class, $booksQuantity)->create();
        factory(Book::class, $booksQuantity)->create()->each(
            function($book){
                $rand_int = (int)rand(1,3);
                $categories = Category::all()->random($rand_int)->pluck('id');
                $book->categories()->attach($categories);
            });
        factory(Rental::class, $rentalsQuantity)->create();
    }
}
