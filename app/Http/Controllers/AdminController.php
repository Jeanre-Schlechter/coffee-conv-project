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
    }
}
