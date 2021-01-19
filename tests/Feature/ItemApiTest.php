<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Item;

class ItemApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * testing store items.
     *
     * @return void
     */
    public function testStoreItem()
    {
        $data = [
            "name" => "beer",
            "price" => 3,
            "bought" => 0
        ];
        
        $this->withoutExceptionHandling();
        $response = $this->post('api/items',$data);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'price', 'sort', 'bought', 'created_at', 'updated_at'
            ]
        ]);
       
    }

    /**
     * testing update item.
     *
     * @return void
     */
    public function testUpdateItem()
    {
        $item = factory(Item::class)->create();

        $data = [
            "item_id" => $item->id,
            "name" => "bread",
            "price" => 3,
            "bought" => 0
        ];
        
        $this->withoutExceptionHandling();
        $response = $this->put('api/items',$data);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'price', 'sort', 'bought', 'created_at', 'updated_at'
            ]
        ]);
    }

    /**
     * testing delete item.
     *
     * @return void
     */
    public function testDeleteItem()
    {
        $item = factory(Item::class)->create();
        
        $this->withoutExceptionHandling();
        $response = $this->delete('api/items/'.$item->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'price', 'sort', 'bought', 'created_at', 'updated_at'
            ]
        ]);
    }

    /**
     * testing list items.
     *
     * @return void
     */
    public function testListItems()
    {   
        $this->withoutExceptionHandling();
        $response = $this->get('api/items/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id','name', 'price', 'sort', 'bought', 'created_at', 'updated_at']
            ]
        ]);
    }

    /**
     * testing bought item.
     *
     * @return void
     */
    public function testBoughtItem()
    {   
        
        $item = factory(Item::class)->create();
        $data = [
            "item_id" => $item->id,
            "bought" => rand(0,1)
        ];
        $this->withoutExceptionHandling();
        $response = $this->put('api/items/bought',$data);
        $response->assertStatus(200);
    }
}
