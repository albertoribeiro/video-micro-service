<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTableIfExists();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTableIfExists();
        parent::tearDown();
    }

    public function testIndex()
    {
        /** @var CategoyStub $category  */
        $category  = CategoryStub::create(['name'=>'test_name','description'=>'test_rescription']);
        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()],$result);

    }

    public function testInvlidationDataInStore(){
        $this->expectException(ValidationException::class);
        //Mockery php
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name'=>'']);
        $this->controller->store($request);
    }

    public function testStore(){
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name'=>'test_name','description'=>'test_description']);
        $obj = $this->controller->store($request);
        $this->assertEquals(
            CategoryStub::find(1)->toArray(),
            $obj->toArray()
        );
    }

    public function testIfFindOrFailFetchModel(){
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $result);
    }

    public function testIfFindOrFailThrowExceptionWhenIdInvalid(){

        //$this->expectException(ModelNotFoundException::class);
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [0]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $result);
    }

    public function testShow()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);

        $result = $this->controller->show($category->id);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $result);
    }

    public function testUpdate()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
                ->once()
                ->andReturn(['name' => 'another_name', 'description' => 'another_description']);
        $result = $this->controller->update($request, $category->id);
        $this->assertEquals([$category->refresh()->toArray()],$result->get()->toArray());
    }

    public function testDestroy()
    {
        //$this->expectException(ModelNotFoundException::class);
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_description']);
        $response = $this->controller->destroy($category->id);

        $this
            ->createTestResponse($response)
            ->assertStatus(204);
        $this->assertCount(0, CategoryStub::all());

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invokeArgs($this->controller, [$category->id]);
    }
}
