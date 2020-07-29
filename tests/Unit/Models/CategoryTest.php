<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testFillable()
    {
        $fillAble = ['name','description','is_active'];
        $category = new Category();

        $this->assertEquals($fillAble,$category->getFillable() );
    }

    public function testsCasts(){
        $casts = ['id' => 'string','is_active' => 'boolean' ];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }
}
