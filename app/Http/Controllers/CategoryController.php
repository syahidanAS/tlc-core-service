<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Store category
    public function store(Request $request){
        if(!$request->category){
            return response()->json([
                'message' => 'category required',
            ], 403);
        }
        $result = CategoryModel::create(['category'=>$request->category]);
        if($result){
            return response()->json([
                'message' => 'category saved',
            ], 201);
        }
        return response()->json([
            'message' => 'failed saving category',
        ], 500);
    }
    //Get Categories
    public function get(){
        $payload = CategoryModel::orderBy('id', 'DESC')->get();
        $counted = CategoryModel::count();
        if($payload){
            return response()->json([
                'message' => $counted.' data successfully retrieved',
                'data' => $payload
            ], 200);
        }else{
            return response()->json([
                'message' => $counted.' data successfully retrieved'
            ], 200);
        }
        return response()->json([
            'message' => 'internal server error',
        ], 500);
    }

        //Get Category by Id
        public function getById($id){
            $payload = CategoryModel::where('id', $id)->get();
            $counted = CategoryModel::where('id', $id)->count();
            if($payload){
                return response()->json([
                    'message' => $counted.' data successfully retrieved',
                    'data' => $payload
                ], 200);
            }else{
                return response()->json([
                    'message' => $counted.' data successfully retrieved'
                ], 200);
            }
            return response()->json([
                'message' => 'internal server error',
            ], 500);
        }

        //Update Category
        public function update(Request $request){
            if(!$request->id){
                return response()->json([
                    'message' => 'id required',
                ], 400);
            }else if(!$request->category){
                return response()->json([
                    'message' => 'category required',
                ], 400);
            }
            $result = CategoryModel::where('id', $request->id)->update(['category'=>$request->category]);
            if($result){
                return response()->json([
                    'message' => 'data successfully updated',
                ], 200);
            }
            return response()->json([
                'message' => 'internal server error',
            ], 400);
        }

        //Delete Category
        public function destroy($id){
            $result = CategoryModel::destroy($id);
            if($result){
                return response()->json([
                    'message' => 'data successfully deleted',
                ], 200);
            }
            return response()->json([
                'message' => 'data not found',
            ], 404);
        }
}
