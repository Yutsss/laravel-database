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

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
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
}
