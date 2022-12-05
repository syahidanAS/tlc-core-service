<?php

namespace App\Http\Controllers;

use App\Models\BannerModel;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){
        $result = BannerModel::orderBy('id', 'DESC')->get();
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
    
    public function store(Request $request){
        if(!$request->title){
            return response()->json([
                'message' => 'title must be filled'
            ], 400); 
        }else if(!$request->file('image_uri')){
            return response()->json([
                'message' => 'please upload image'
            ], 400); 
        }

        $file = $request->file('image_uri');
        $filename = date('YmdHi.') . $file->extension();

        $payload = [
            "title" => $request->title,
            "desc" => $request->desc,
            "image_uri" => $filename,
            "image_url" => url('/banners/images/' . $filename)
        ];

        $result = BannerModel::create($payload);

        if($result){
            $file->move(public_path('banners/images'), $filename);
            return response()->json([
                'message' => 'data successfully saved'
            ], 201); 
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500); 
    }
}
