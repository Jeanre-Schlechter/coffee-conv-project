<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Wishlist;

class TestController extends BaseController
{
    public function test() 
    {
        logger()->debug(__METHOD__, [
            "HI"
        ]);

        $products = Product::paginate(10);

        $categories = Category::all();

        $categories = Category::all();

        return [
            'products' => $products,
            'categories' => $categories
        ];
    }

    public function getFilteredProducts(Request $request) 
    {
        logger()->debug(__METHOD__, [
            "HI" => $request->all()
        ]);

        $categoryData = $request['category'];

        $category = Category::where('name', $categoryData)->first();


        $products = $category->products()->paginate(10);

        logger()->debug(__METHOD__, [
            "cate" => $products
        ]);

        return $products;
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

            return view('admin')->with('products', $products)
                                ->with('categories', $categories)
                                ->with('users', $users);
    }

    public function getUserWishlist(Request $request)
    {
        $userAuthed = Auth::user();
        
        if(!$userAuthed) {
            return response(404, "User not authed");
        }
        $wishlist = Wishlist::where('user_id', $userAuthed->id)
                            ->with('products')
                            ->first();

        return $wishlist;
    }
}
