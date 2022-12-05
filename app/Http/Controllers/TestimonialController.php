<?php

namespace App\Http\Controllers;

use App\Models\TestimonialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index(){
        $result = TestimonialModel::get();

        if($result){
            return response()->json([
                'message' => count($result). ' data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }

    public function store(Request $request){
        //Filter file upload
        if ($request->file('image_uri')) {
            $file = $request->file('image_uri');
            $filename = date('YmdHi.') . $file->extension();
            
        } else {
            return response()->json([
                'message' => 'image uri is required',
            ], 400);
        }

        if(!$request->name){
            return response()->json([
                'message' => 'name must be filled',
            ], 400);
        }else if(!$request->testimonial){
            return response()->json([
                'message' => 'testimonial must be filled',
            ], 400);
        }

        //Variables
        $testimonialPayload = [
            "name" => $request->name,
            "testimonial" => $request->testimonial,
            "image_uri" => $filename,
            "image_url" => url('/testimonials/images/' . $filename)
        ];

        //Action
        $result = TestimonialModel::create($testimonialPayload);
        if($result){
            $file->move(public_path('testimonials/images'), $filename);
            return response()->json([
                'message' => ' data successfully saved'
            ], 201);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }

    public function destroy($id){
        $findUri = TestimonialModel::where('id', $id)->first();
        $imagePath = public_path('testimonials/images/' . $findUri->image_uri);
        $result = TestimonialModel::destroy($id);
        if($result){
            File::delete($imagePath);
            return response()->json([
                'message' => 'testimonial successfully deleted'
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }
}
