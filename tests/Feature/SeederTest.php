<?php


use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;

class SeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE from products");
        DB::delete("DELETE from categories");
        DB::delete("DELETE from counters");
    }

    public function testSeeding()
    {
        $this->seed(CategorySeeder::class);

        $collection = DB::table("categories")->get();
        $this->assertCount(4, $collection);

        foreach ($collection as $category) {
            $this->assertNotNull($category);
            Log::info(json_encode($category));
        }
    }
}
