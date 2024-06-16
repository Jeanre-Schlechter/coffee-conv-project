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
    }

    public function addNewProduct(Request $request) 
    {
        logger()->debug(__METHOD__, [
            $request->all()
        ]);


        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'category' => 'required|exists:categories,id',
            'mainImage' => 'nullable|file|max:2048' // Adjust max file size as needed
        ]);

        // Process mainImage upload if it exists
        if ($request->hasFile('mainImage')) {
            $file = $request->file('mainImage');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'public/images/' . $fileName;
            
            // Store file in storage/app/public/images
            $storedPath = Storage::put($filePath, file_get_contents($file));

            // Generate URL for the stored file
            $url = Storage::url($filePath);
        } else {
            // If no mainImage is uploaded, handle accordingly
            $validatedData['mainImage'] = null; // Or any default behavior you need
        }

        $productObject = new Product();

        $productObject->name            = $validatedData['name'];
        $productObject->description     = $validatedData['description'];
        $productObject->price           = $validatedData['price'];
        $productObject->qty             = $validatedData['qty'];
        $productObject->main_image      = $url;
        $productObject->category_id     = $validatedData['category'];

        $productObject->save();

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
