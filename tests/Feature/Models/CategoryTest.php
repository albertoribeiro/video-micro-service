<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;
    public function testList()
    {
        // $category = Category::create([
        //                 'name' => 'test1'
        //             ]);
        factory(Category::class,1)->create();
        $categories = Category::all();
        $this->assertCount(1,$categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
            'deleted_at',
            'is_active'
        ],
        $categoryKey);
    }

    public function testCreate(){
        $category = Category::create([ 'name' => 'test1' ]);
        $category->refresh();
        $this->assertEquals('test1',$category->name);
        $this->assertNull($category->description);
        $this->assertTrue((bool)$category->is_active);

        $category = Category::create([ 'name' => 'test1', 'description'=> null ]);
        $this->assertNull($category->description);
        $category = Category::create([ 'name' => 'test1', 'description'=> 'test description' ]);
        $this->assertEquals('test description',$category->description);

        $category = Category::create([ 'name' => 'test1', 'is_active'=> false ]);
        $this->assertFalse($category->is_active);
        $category = Category::create([ 'name' => 'test1', 'is_active'=> true ]);
        $this->assertTrue($category->is_active);

        $v = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',$category->id);
        $this->assertTrue((bool)$v);
    }

    public function testUpdate(){

        $category = factory(Category::class)->create([
            'description'=> 'test description',
            'is_active'=> false
        ])->first();

        $data = [
            'name'=> 'test name updatede',
            'description'=> 'test description updatede',
            'is_active'=> true
        ];
        $category->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value,$category->{$key});
        }
    }

    public function testDelete(){
        $category = Category::create([ 'name' => 'test1' ]);

        $category->delete();
        $this->assertSoftDeleted($category);
    }
}
