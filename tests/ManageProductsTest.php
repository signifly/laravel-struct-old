<?php

namespace Signifly\Struct\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Signifly\Struct\Factory;
use Signifly\Struct\REST\Cursor;
use Signifly\Struct\REST\Resources\ProductResource;
use Signifly\Struct\Struct;

class ManageProductsTest extends TestCase
{
    private Struct $struct;

    public function setUp(): void
    {
        parent::setUp();

        $this->struct = Factory::fromConfig();
    }

    /** @test **/
    public function it_gets_products()
    {
        Http::fake([
            '*' => Http::response($this->fixture('products.all')),
        ]);

        $resources = $this->struct->getProducts();

        Http::assertSent(function (Request $request) {
            $this->assertEquals($this->struct->getBaseUrl().'/products.json', $request->url());
            $this->assertEquals('GET', $request->method());

            return true;
        });
        $this->assertInstanceOf(Collection::class, $resources);
        $this->assertInstanceOf(ProductResource::class, $resources->first());
        $this->assertCount(3, $resources);
    }

    /** @test **/
    public function it_creates_a_product()
    {
        Http::fake([
            '*' => Http::response([1, 2]),
        ]);

        $productIds = $this->struct->createProducts($payload = [
            [
                'ProductStructureUid' => 'df5733d0-af60-4299-9449-1bed2f4f9ba1',
            ],
            [
                'ProductStructureUid' => 'df5733d0-af60-4299-9449-1bed2f4f9ba1',
            ],
        ]);

        Http::assertSent(function (Request $request) use ($payload) {
            $this->assertEquals($this->struct->getBaseUrl().'/products', $request->url());
            $this->assertEquals($payload, $request->data());
            $this->assertEquals('POST', $request->method());

            return true;
        });
    }

    /** @test **/
    public function it_finds_a_product()
    {
        Http::fake([
            '*' => Http::response($this->fixture('products.show')),
        ]);

        $resource = $this->struct->getProduct($id = 1234);

        Http::assertSent(function (Request $request) use ($id) {
            $this->assertEquals($this->struct->getBaseUrl().'/products/'.$id, $request->url());
            $this->assertEquals('GET', $request->method());

            return true;
        });
        $this->assertInstanceOf(ProductResource::class, $resource);
    }

    /** @test **/
    public function it_updates_a_product()
    {
        $fixture = $this->fixture('products.update-multiple');

        Http::fake([
            '*' => Http::response($this->fixture('products.update-multiple')),
        ]);

        $id = 1234;

        $resource = $this->struct->updateProducts($id, $fixture);

        Http::assertSent(function (Request $request) use ($id) {
            $this->assertEquals($this->struct->getBaseUrl().'/products/', $request->url());
            $this->assertEquals('PATCH', $request->method());

            return true;
        });
        $this->assertInstanceOf(ProductResource::class, $resource);
    }

    /** @test **/
    public function it_deletes_a_product()
    {
        Http::fake([
            '*' => Http::response(),
        ]);

        $id = 1234;

        $this->struct->deleteProduct($id);

        Http::assertSent(function (Request $request) use ($id) {
            $this->assertEquals($this->struct->getBaseUrl().'/products/'.$id.'.json', $request->url());
            $this->assertEquals('DELETE', $request->method());

            return true;
        });
    }

}
