<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{

    function index()
    {
        return "you are arrive controller";
    }

    function addProductToCart(Request $request)
    {

        // fetch data and than return view
        return "add production to cart " . $request . " with type " . $request->type;
    }

    function removeProductFromCart($id)
    {
        return "remove product from cart " . $id;
    }
}
