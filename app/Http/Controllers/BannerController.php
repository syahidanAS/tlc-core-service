<?php

namespace App\Http\Controllers;

use App\Models\BannerModel;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){
        $result = BannerModel::get();
        if($result){
            return response()->json([
                'message' => count($result) . ' Data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' =>'Something went wrong'
        ], 500);
    }
}
