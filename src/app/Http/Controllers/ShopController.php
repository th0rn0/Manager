<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Settings;
use Helpers;

use App\ShopItem;
use App\ShopItemCategory;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    /**
     * Show Shop Index Page
     * @return View
     */
    public function index()
    {
        $featuredItems = ShopItem::where('featured', true)->get();
        if ($featuredItems->count() < 16) {
            $count = 16 - $featuredItems->count();
            $featuredItems = $featuredItems->merge(ShopItem::inRandomOrder()->where('featured', false)->paginate($count));
        }
        return view('shop.index')
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'))
            ->withFeaturedItems($featuredItems);
    }

    /**
     * Show Basket Page
     * @return View
     */
    public function showBasket()
    {
        if (Session::has(Settings::getOrgName() . '-basket')) {
            $basket = Helpers::formatBasket(Session::get(Settings::getOrgName() . '-basket'));
        } else {
            $basket = 'Empty';
        }
        return view('shop.basket')
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'))
            ->withBasket($basket);
    }

    /**
     * Update Basket
     * @return View
     */
    public function updateBasket(Request $request)
    {
        if (!isset($request->action)) {
            $request->action = 'add';
        }
        $request->action = strtolower($request->action);
        $rules = [
            'shop_item_id'      => 'required|exists:shop_items,id',
            'quantity'          => 'integer',
            'action'            => 'in:add,remove',
        ];
        $messages = [
            'shop_item_id.required'     => 'Item is Required.',
            'shop_item_id.exists'       => 'Item does not exist.',
            'quantity.integer'          => 'Quantity must be a number.',
            'action.in'                 => 'Action must be Add or Remove.',
        ];
        $this->validate($request, $rules, $messages);
        if (!ShopItem::hasStockByItemId($request->shop_item_id) && $request->action == 'add') {
            Session::flash('alert-danger', 'Not enough in Stock. Please try again later.');
            return Redirect::back();
        }
        switch ($request->action) {
            case 'add':
                if (
                    Session::has(Settings::getOrgName() . '-basket') && 
                    !array_key_exists('tickets', Session::get(Settings::getOrgName() . '-basket'))
                ) {
                    $params = Session::get(Settings::getOrgName() . '-basket');
                    if (array_key_exists($request->shop_item_id, $params['shop'])) {
                        $params['shop'][$request->shop_item_id] += $request->quantity;
                    } else {
                        $params['shop'][$request->shop_item_id] = $request->quantity;
                    }
                } else {
                    $params = [
                        'shop' => [
                            $request->shop_item_id => $request->quantity,
                        ],
                    ];
                }
                if (!Helpers::formatBasket($params)->allow_credit && !Helpers::formatBasket($params)->allow_payment) {
                    Session::flash('alert-danger', 'You cannot add a Credit Only Item & a Payment Only Item to the cart at the same time!');
                    return Redirect::back();
                }
                break;
            case 'remove':
                $params = [];
                if (Session::has(Settings::getOrgName() . '-basket')) {
                    $params = Session::get(Settings::getOrgName() . '-basket');
                    if (!array_key_exists('tickets', $params)) {
                        if (array_key_exists($request->shop_item_id, $params['shop'])) {
                            unset($params['shop'][$request->shop_item_id]);
                        }
                    }
                }
                break;
        }
        Session::forget(Settings::getOrgName() . '-basket');
        if (!empty($params['shop'] || !empty($params['tickets']))) {
            Session::put(Settings::getOrgName() . '-basket', $params);
            Session::save();
        }
        Session::flash('alert-success', 'Basket Updated!');
        return Redirect::to('/shop/basket');
    }

    /**
     * Show Orders Page
     * @return View
     */
    public function showOrders()
    {
        return view('shop.orders')
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'));
    }

    /**
     * Show Checkout Page
     * @return View
     */
    public function showCheckout()
    {
        return view('shop.checkout')
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'));
    }

    /**
     * Show Shop Category Page
     * @param ShopItemCategory $category
     * @return View
     */
    public function showCategory(ShopItemCategory $category)
    {
        return view('shop.category')
            ->withCategory($category)
            ->withCategoryItems($category->items()->paginate(20))
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'));
    }

    /**
     * Show Shop Item Page
     * @param ShopItem $item
     * @param ShopItemCategory $category
     * @return View
     */
    public function showItem(ShopItemCategory $category, ShopItem $item)
    {
        return view('shop.item')
            ->withCategory($category)
            ->withItem($item)
            ->withAllCategories(ShopItemCategory::all()->sortBy('order'));
    }

}
