<?php

namespace Tests\Feature\Models;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Validation\Rule;



class GenreTest extends TestCase
{
    use DatabaseMigrations;
    public function testList()
    {

        factory(Genre::class,1)->create();
        $genres = Genre::all();
        $this->assertCount(1,$genres);
        $categoryKey = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'created_at',
            'updated_at',
            'deleted_at',
            'is_active'
        ],
        $categoryKey);
    }

    public function testCreate(){
        $genre = Genre::create([ 'name' => 'test1' ]);
        $genre->refresh();
        $this->assertEquals('test1',$genre->name);
        $this->assertTrue((bool)$genre->is_active);

        $genre = Genre::create([ 'name' => 'test1', 'is_active'=> false ]);
        $this->assertFalse($genre->is_active);
        $genre = Genre::create([ 'name' => 'test1', 'is_active'=> true ]);
        $this->assertTrue($genre->is_active);

        $v = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',$genre->id);
        $this->assertTrue((bool)$v);
    }

    public function testUpdate(){

        $genre = factory(Genre::class)->create([
            'is_active'=> false
        ])->first();

        $data = [
            'name'=> 'test name updatede',
            'is_active'=> true
        ];
        $genre->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value,$genre->{$key});
        }
    }

    public function testDelete(){
        $genre = Genre::create([ 'name' => 'test1' ]);

        $genre->delete();
        $this->assertSoftDeleted($genre);
    }
}
