<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

//create table categories (
//    id varchar(100) not null primary key,
//    name varchar(100) not null,
//    description text,
//    created_at timestamp
//) engine InnoDB;

class RawQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE from categories");
    }

    public function test_example()
    {
        DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (?, ?, ?, ?)", [
            'GADGET',
            'GADGET',
            'GADGET Category',
            now()
        ]);

        $result = DB::select("SELECT * FROM categories where id = ?", ['GADGET']);

        $this->assertEquals(1, count($result));
        $this->assertEquals('GADGET', $result[0]->id);
        $this->assertEquals('GADGET', $result[0]->name);
        $this->assertEquals('GADGET Category', $result[0]->description);
    }

    public function testNamedBinding()
    {
        DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => now()
        ]);

        $result = DB::select("SELECT * FROM categories where id = :id", ['id' => 'GADGET']);

        $this->assertEquals(1, count($result));
        $this->assertEquals('GADGET', $result[0]->id);
        $this->assertEquals('GADGET', $result[0]->name);
        $this->assertEquals('GADGET Category', $result[0]->description);
    }

    public function testTransaction()
    {
        DB::transaction(function (){
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'GADGET',
                'name' => 'GADGET',
                'description' => 'GADGET Category',
                'created_at' => now()
            ]);

            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'FOOD',
                'name' => 'FOOD',
                'description' => 'FOOD Category',
                'created_at' => now()
            ]);
        });

        $result = DB::select("SELECT * FROM categories");
        $this->assertEquals(2, count($result));
    }

    public function testTransactionFailed()
    {
        try {
            DB::transaction(function (){
                DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                    'id' => 'GADGET',
                    'name' => 'GADGET',
                    'description' => 'GADGET Category',
                    'created_at' => now()
                ]);

                DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                    'id' => 'GADGET',
                    'name' => 'GADGET',
                    'description' => 'GADGET Category',
                    'created_at' => now()
                ]);
            });
        } catch (QueryException $e) {
        }

        $result = DB::select("SELECT * FROM categories");
        $this->assertEquals(0, count($result));
    }

    public function testManualTransaction()
    {
        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'GADGET',
                'name' => 'GADGET',
                'description' => 'GADGET Category',
                'created_at' => now()
            ]);

            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'FOOD',
                'name' => 'FOOD',
                'description' => 'FOOD Category',
                'created_at' => now()
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
        }

        DB::commit();

        $result = DB::select("SELECT * FROM categories");
        $this->assertEquals(2, count($result));
    }

    public function testManualTransactionFailed()
    {
        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'GADGET',
                'name' => 'GADGET',
                'description' => 'GADGET Category',
                'created_at' => now()
            ]);

            DB::insert("INSERT INTO categories (id, name, description, created_at) VALUES (:id, :name, :description, :created_at)", [
                'id' => 'GADGET',
                'name' => 'GADGET',
                'description' => 'GADGET Category',
                'created_at' => now()
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
        }

        DB::commit();

        $result = DB::select("SELECT * FROM categories");
        $this->assertEquals(0, count($result));
    }
}
