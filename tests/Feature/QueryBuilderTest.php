<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

//create table counters (
//    id varchar(100) not null primary key,
//    value int not null default 0
//) engine InnoDB;

//CREATE TABLE products (
//    id VARCHAR(100) NOT NULL PRIMARY KEY,
//    name VARCHAR(100) NOT NULL,
//    description TEXT,
//    price INT NOT NULL,
//    category_id VARCHAR(100) NOT NULL,
//    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//    CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id)
//) ENGINE=InnoDB;

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE from products");
        DB::delete("DELETE from categories");
        DB::delete("DELETE from counters");
    }

    public function testInsert()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => now()
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => now()
        ]);

        $result = DB::select("SELECT * FROM categories");
        $this->assertEquals(2, count($result));
    }

    public function testSelect()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => now()
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => now()
        ]);

        $collection = DB::table("categories")->select('id', 'name')->get();
        $this->assertNotNull($collection);

        $collection->each(function ($record) {
            Log::info(json_encode($record));
        });
    }

    public function testWhere()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => now()
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => now()
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => now()
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => now()
        ]);

        $collection = DB::table("categories")->orWhere(function(Builder $builder){
            $builder->where('id', '=', 'GADGET');
            $builder->orWhere('id', '=', 'FOOD');
        })->get();

        $this->assertNotNull($collection);
        for ($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereBetween()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")->whereBetween('created_at', [
            "2021-02-01 00:00:00",
            "2021-04-01 00:00:00"
        ])->get();
        $this->assertNotNull($collection);
        for ($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereIn()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")->whereIn('id', ['GADGET', 'FOOD'])->get();
        $this->assertNotNull($collection);
        for ($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testWhereNull()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")->whereNull('description')->get();
        $this->assertNotNull($collection);
        for ($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testUpdate()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("categories")->where('id', '=', 'GADGET')->update([
            'name' => 'Handphone'
        ]);

        $colection = DB::table("categories")->where('id', '=', 'GADGET')->get();
        $this->assertCount(1, $colection);

        for($i = 0; $i < count($colection); $i++) {
            Log::info(json_encode($colection[$i]));
        }
    }

    public function testUpdateOrInsert()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("categories")->updateOrInsert(['id' => 'GADGET'], [
            'name' => 'Handphone'
        ]);

        DB::table("categories")->updateOrInsert(['id' => 'Voucher'], [
            'name' => 'Voucher',
            'description' => 'Voucher Category',
            'created_at' => "2021-05-01 00:00:00"
        ]);

        $collection = DB::table("categories")->select()->get();
        $this->assertCount(5, $collection);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testIncrement()
    {
        DB::table("counters")->insert([
            'id' => 'sample',
            'value' => 0
        ]);

        DB::table("counters")->where('id', '=', 'sample')->increment('value');
        $collection = DB::table("counters")->where('id', '=', 'sample')->get();
        $this->assertCount(1, $collection);
        $this->assertEquals(1, $collection[0]->value);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testDecrement()
    {
        DB::table("counters")->insert([
            'id' => 'sample',
            'value' => 10
        ]);

        DB::table("counters")->where('id', '=', 'sample')->decrement('value');
        $collection = DB::table("counters")->where('id', '=', 'sample')->get();
        $this->assertCount(1, $collection);
        $this->assertEquals(9, $collection[0]->value);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testDelete()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("categories")->where('id', '=', 'GADGET')->delete();

        $collection = DB::table("categories")->select()->get();
        $this->assertCount(3, $collection);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testJoin()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'products.price', 'categories.name as category_name')
            ->get();

        $this->assertCount(4, $collection);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testOrder()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")->orderBy('created_at', 'desc')->get();
        $this->assertCount(4, $collection);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testTakeSkip()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")->skip(1)->take(2)->get();
        $this->assertCount(2, $collection);

        for($i = 0; $i < count($collection); $i++) {
            Log::info(json_encode($collection[$i]));
        }
    }

    public function testChunkResults()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("categories")
            ->orderBy('created_at')
            ->chunk(2, function($collection){
                $this->assertCount(2, $collection);
                    for($i = 0; $i < count($collection); $i++) {
                        Log::info(json_encode($collection[$i]));
                    }
            });
    }

    public function testLazy()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")
            ->orderBy('created_at')
            ->lazy(1)
            ->take(2);

        $this->assertNotNull($collection);

        $collection->each(function($record){
            Log::info(json_encode($record));
        });
    }

    public function testCursor()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);
        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("categories")
            ->orderBy('created_at')
            ->cursor();

        $this->assertNotNull($collection);

        foreach($collection as $record){
            Log::info(json_encode($record));
        }
    }

    public function testAggregate()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->count('id');

        $this->assertEquals(4, $collection);

        $collection = DB::table("products")
            ->max('price');

        $this->assertEquals(5000000, $collection);

        $collection = DB::table("products")
            ->min('price');

        $this->assertEquals(50000, $collection);

        $collection = DB::table("products")
            ->avg('price');

        $this->assertEquals(1537500.0000, $collection);

        $collection = DB::table("products")
            ->sum('price');

        $this->assertEquals(6150000, $collection);
    }

    public function testRawAggregate()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->select(
                DB::raw('count(id) as total_product'),
                DB::raw('max(price) as max_price'),
                DB::raw('min(price) as min_price'),
                DB::raw('avg(price) as avg_price'),
                DB::raw('sum(price) as total_price')
            )->get();

        $this->assertEquals(4, $collection[0]->total_product);
        $this->assertEquals(5000000, $collection[0]->max_price);
        $this->assertEquals(50000, $collection[0]->min_price);
        $this->assertEquals(1537500.0000, $collection[0]->avg_price);
        $this->assertEquals(6150000, $collection[0]->total_price);

    }

    public function testGrouping()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->select('category_id', DB::raw('count(id) as total_product'))
            ->groupBy('category_id')
            ->get();

        $this->assertCount(3, $collection);

        $collection->each(function($record){
            Log::info(json_encode($record));
        });
    }

    public function testHaving()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->select('category_id', DB::raw('count(id) as total_product'))
            ->groupBy('category_id')
            ->having('total_product', '>', 1)
            ->get();

        $this->assertCount(1, $collection);

        $collection->each(function($record){
            Log::info(json_encode($record));
        });
    }

    public function testLocking()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Handphone',
            'name' => 'Handphone',
            'description' => 'Handphone',
            'price' => 1000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Laptop',
            'name' => 'Laptop',
            'description' => 'Laptop',
            'price' => 5000000,
            'category_id' => 'GADGET',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Rice',
            'name' => 'Rice',
            'description' => 'Rice',
            'price' => 50000,
            'category_id' => 'FOOD',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("products")->insert([
            'id' => 'Shirt',
            'name' => 'Shirt',
            'description' => 'Shirt',
            'price' => 100000,
            'category_id' => 'CLOTH',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $collection = DB::table("products")
            ->where('category_id', '=', 'GADGET')
            ->lockForUpdate()
            ->get();

        $this->assertCount(2, $collection);

        $collection->each(function($record){
            Log::info(json_encode($record));
        });
    }

    public function testPagination()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $paginate = DB::table("categories")
            ->orderBy('created_at')
            ->paginate(2);

        $this->assertEquals(1, $paginate->currentPage());
        $this->assertEquals(2, $paginate->perPage());
        $this->assertEquals(2, $paginate->lastPage());
        $this->assertEquals(4, $paginate->total());

        $collection = $paginate->items();
        $this->assertCount(2, $collection);

        foreach($collection as $item) {
            Log::info(json_encode($item));
        }
    }

    public function testIterateAllPaginate()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $page = 1;

        while(true)
        {
            $paginate = DB::table("categories")
                ->orderBy('created_at')
                ->paginate(perPage:2, page: $page);

            if ($paginate->isEmpty()) {
                break;
            } else {
                $page++;

                $collection = $paginate->items();
                $this->assertCount(2, $collection);

                foreach($collection as $item) {
                    Log::info(json_encode($item));
                }

            }

        }

    }

    public function testCursorPagination()
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);

        $cursor = "id";

        while(true)
        {
            $paginate = DB::table("categories")
                ->orderBy('created_at')
                ->cursorPaginate(perPage:2, cursor: $cursor);

            foreach ($paginate as $item) {
                $this->assertNotNull($item);
                Log::info(json_encode($item));
            }

            $cursor = $paginate->nextCursor();
            if (is_null($cursor)) {
                break;
            }
        }
    }
}
