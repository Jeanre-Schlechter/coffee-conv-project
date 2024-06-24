<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\User;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart;

class TestController extends BaseController
{
    public function test() 
    {
        logger()->debug(__METHOD__, [
            "HI"
        ]);

        $products = Product::paginate(10);

        $categories = Category::all();

        foreach ($products as $product) {
            // Assuming 'main_image' column stores the blob data
            $imageData = $product->main_image;
    
            // Convert blob data to base64-encoded string
            $base64Image = base64_encode($imageData);
    
            // Assign the base64 string to a new attribute or replace the existing 'main_image' attribute
            // Here we'll add a new 'base64_image' attribute to each product object
            $product->main_image = $base64Image;
        }

        return compact('products', 'categories');
    }

    public function getFilteredProducts(Request $request) 
    {
        logger()->debug(__METHOD__, [
            "HI" => $request->all()
        ]);

        $categoryData = $request['category'];

        $category = Category::where('name', $categoryData)->first();


        $products = $category->products()->paginate(10);

        foreach ($products as $product) {
            // Assuming 'main_image' column stores the blob data
            $imageData = $product->main_image;
    
            // Convert blob data to base64-encoded string
            $base64Image = base64_encode($imageData);
    
            // Assign the base64 string to a new attribute or replace the existing 'main_image' attribute
            // Here we'll add a new 'base64_image' attribute to each product object
            $product->main_image = $base64Image;
        }

        return compact('products');
    }

    public function testLogin() 
    {
        logger()->debug(__METHOD__, [
            "HI"
        ]);


        return view('login');
    }

    public function register(Request $request)
    {
        // logger()->debug(__METHOD__, [
        //     $request
        // ]);
        // Validate request data
        $validatedData = $request->validate([
            'name'     => 'required',
            'email'    => 'required',
            'username' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
        ]);

        // Hash the password
        $hashedPassword = Hash::make($validatedData['password']);

        // Create new user
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => $hashedPassword,
        ]);

        // You can also generate and return a token here if using JWT or Passport for authentication

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt(['username' => $validatedData['username'], 'password' => $validatedData['password']])) {
            // Authentication successful
            $products = Product::all();

            return redirect('/home')->with('user', Auth::user())
                                        ->with('products', $products);
            // return response()->json(['message' => 'Login successful', 'user' => Auth::user()]);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid username or password'], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // If using API, return a JSON response indicating successful logout
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        // If using web routes, redirect the user to a specific page after logout
        return redirect('/home')->with('status', 'Logged out successfully');
    }

    public function admin(Request $request)
    {
        // $userAuthed = Auth::user()->isAdminUser();
        
        $products = Product::all();
        $categories = Category::all();
        $users = User::all();
        // if ($userAuthed) {

        foreach ($products as $product) {
            // Assuming 'main_image' column stores the blob data
            $imageData = $product->main_image;
    
            // Convert blob data to base64-encoded string
            $base64Image = base64_encode($imageData);
    
            // Assign the base64 string to a new attribute or replace the existing 'main_image' attribute
            // Here we'll add a new 'base64_image' attribute to each product object
            $product->main_image = $base64Image;
        }
        
        return view('admin', compact('products', 'categories', 'users'));
    }

    public function getUserCart(Request $request)
    {
        $userAuthed = Auth::user();
        
        if (!$userAuthed) {
            return response()->json(['error' => 'User not authenticated'], 404);
        }

        // Fetch the cart with products, including main_image blob data
        $wishlist = Cart::with('products')->where('user_id', $userAuthed->id)->first();

        if (!$wishlist) {
            $wishlist = new Cart();
            $wishlist->user_id = $userAuthed->id;
        }

        if($wishlist->products){
             // Convert blob data to base64 for each product's main_image
            foreach ($wishlist->products as $product) {
                $product->main_image = base64_encode($product->main_image);
            }
        }
       
        return response()->json($wishlist);
    }

    public function addProductToUserCart(Request $request, int $productId)
    {

        logger()->debug(__METHOD__, [
            $request->all(),
            $productId
        ]);

        $userAuthed = Auth::user();

        if (!$userAuthed) {
            return response()->json(['error' => 'User not authenticated'], 404);
        }

        $userCart = $userAuthed->cart;

        if(!$userCart) {
            $userCart = new Cart();
            $userCart->user_id = $userAuthed->id;
            $userCart->total_amount = 0;
            $userCart->save();
        }

        $qty = $request->input('qty');
        $productCart = ProductCart::where('cart_id', $userCart->id)
                                    ->where('product_id', $productId)
                                    ->first();


        logger()->debug(__METHOD__, [
            $productCart
        ]);

        if ($productCart) {
            $productCart->product_quantity = $qty;
            $productCart->save();
        } else {
            $productCart = new ProductCart();

            $productCart->cart_id = $userCart->id;
            $productCart->product_id = $productId;
            $productCart->product_quantity = $qty;

            $productCart->save();
        }
    
        $cartTotal = $this->recalculateCartTotal($userCart);

        $userCart->total_amount = $cartTotal;
        $userCart->save();

        return response()->json($productCart);
    }

    public function removeProductFromUserCart(Request $request, int $productId)
    {
        $userAuthed = Auth::user();
        
        if (!$userAuthed) {
            return response()->json(['error' => 'User not authenticated'], 404);
        }

        $userCart = $userAuthed->cart;


        $cart = ProductCart::where('cart_id', $userCart->id)
                                ->where('product_id', $productId)
                                ->first();

        if (!$cart) {
            return response()->json(['error' => 'Item Not Found'], 404);
        }

        $cart->delete();
       
        $cartTotal = $this->recalculateCartTotal($userCart);

        $userCart->total_amount = $cartTotal;
        $userCart->save();

        logger()->debug(__METHOD__, [
            $userCart
        ]);
        return response()->json("Success");
    }

    public function payUserCart(Request $request)
    {
        $userAuthed = Auth::user();
        
        if (!$userAuthed) {
            return response()->json(['error' => 'User not authenticated'], 404);
        }

        $userCart = $userAuthed->cart;
        $cart = $request->cart;
        $shippingAddress = $request->shipping['address'];
        $shippingInfo = $request->shipping['info'];

        $purchase = new Purchase();

        $purchase->user_id = $userAuthed->id;
        $purchase->cart_id = $cart['id'];
        $purchase->total_amount = $cart['total_amount'];
        $purchase->is_paid = true;
        $purchase->shipping_address = $shippingAddress;
        $purchase->shipping_information = $shippingInfo;
        $purchase->shipping_status = "Packing";

        $purchase->save();

        logger()->debug(__METHOD__, [
            $purchase
        ]);
        return response()->json("Success");
    }

    private function recalculateCartTotal(Cart $cart)
    {
        $products = $cart->products;

        
        $total = 0;
       foreach($products as $product) {
            $price = $product->price;
            $quantity = $product->pivot->product_quantity;

            $productTotal = $price * $quantity;

            $total += $productTotal;
       }
       logger()->debug(__METHOD__, [
            $total
        ]);
        return $total;
    }
}

