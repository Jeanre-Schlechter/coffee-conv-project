<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class AdminController extends Controller
{
    public function addNewUser(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);
    }
    
    public function addNewCategory(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);

        $validatedData = $request->validate([
            'name'          => 'required|string',
            'description'   => 'required|string',
            'logo'          => 'required|string'
        ]);

        $categoryObject = new Category();

        $categoryObject->name            = $validatedData['name'];
        $categoryObject->description     = $validatedData['description'];
        $categoryObject->logo           = $validatedData['logo'];

        $categoryObject->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $categoryObject]);
    }

    public function addNewProduct(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'mainImage' => 'nullable|file|max:2048' 
        ]);

        if ($request->hasFile('mainImage')) {
            $image = $request->file('mainImage');

            $fileContent = file_get_contents($image->getRealPath());


        } else {

            $validatedData['mainImage'] = null;
        }

        $productObject = new Product();

        $productObject->name            = $validatedData['name'];
        $productObject->description     = $validatedData['description'];
        $productObject->price           = $validatedData['price'];
        $productObject->qty             = $validatedData['qty'];
        $productObject->main_image      = $fileContent;
        $productObject->category_id     = $validatedData['category'];

        $productObject->save();

        $productObject->main_image = base64_encode($productObject->main_image);

        return response()->json(['message' => 'Product created successfully', 'product' => $productObject]);
    }

    public function editUser(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);
    }
    
    public function editCategory(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);
    }

    public function editProduct(Request $request, string $productId) 
    {
        logger()->debug(__METHOD__, [
            $request->all(),
            $productId
        ]);
    }
}
