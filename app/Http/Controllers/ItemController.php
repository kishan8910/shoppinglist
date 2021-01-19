<?php

namespace App\Http\Controllers;

use App\Http\Resources\Item as ItemResource;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItemResource::collection(Item::all(), Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        //creates a new object if method is post or select the item from database
        $item = new Item;
        $item->name = $request->input('name');
        $item->price = $request->input('price');
        $item->sort = $request->input('sort');
        $item->bought = $request->input('bought');

        if ($item->save()) {
            return new ItemResource($item, Response::HTTP_CREATED);
        }
    }


    /**
     * Sort the items in shoppinglist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        // get all the items from the database
        $items = Item::all();

        // loop through existing items in database
        foreach ($items as $item) {
            $id = $item->id;
            // loop through incoming items and if the same item found the item sort field would be updated
            foreach ($request->input('items') as $updatedItem) {
                if ($updatedItem['id'] == $id) {
                    $item->sort = $updatedItem['sort'];
                    $item->save();
                }
            }
        }

        return Response::HTTP_OK;
    }

    /**
     * Update the bought status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bought(Request $request)
    {
        $item_id = $request->input('item_id');
        $item = Item::findOrFail($item_id);
        $item->bought = $request->input('bought');
        $item->save();
        return new ItemResource($item,Response::HTTP_OK);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if ($item->delete()) {
            return new ItemResource($item,Response::HTTP_OK);
        }
    }
}
