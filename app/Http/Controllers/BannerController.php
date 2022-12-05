<?php

namespace App\Http\Controllers;

use App\Models\BannerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function destroy($id){
        $findUri = BannerModel::where('id', $id)->first();
        $imagePath = public_path('banners/images/' . $findUri->image_uri);

        $result = BannerModel::destroy($id);
        if($result){
            File::delete($imagePath);
            return response()->json([
                'message' => 'banner successfully deleted'
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }

    public function update(Request $request){
        if (!$request->id) {
            return response()->json([
                'message' => 'id must be filled',
            ], 400);
        }

        if(!$request->file('image_uri')){
            $payload = [
                "title" => $request->title,
                "desc" => $request->desc
            ];
            $result = BannerModel::where('id', $request->id)->update($payload);
            if($result){
                return response()->json([
                    'message' => 'testimonial successfully updated',
                ], 200);
            }
        }else{
            $file = $request->file('image_uri');
            $filename = date('YmdHi.') . $file->extension();
            
            $findUri = BannerModel::where('id', $request->id)->first();
            $imagePath = public_path('banners/images/' . $findUri->image_uri);


            $payload = [
                "title" => $request->title,
                "desc" => $request->desc,
                "image_uri" => $filename,
                "image_url" => url('/banners/images/' . $filename),
            ];
            
            $result = BannerModel::where('id', $request->id)->update($payload);
            if($result){
                File::delete($imagePath);
                $file->move(public_path('banners/images'), $filename);
                return response()->json([
                    'message' => 'banner successfully updated',
                ], 200);
            }
        }
        return response()->json([
            'message' => 'something went wrong',
        ], 500);
    }
}
