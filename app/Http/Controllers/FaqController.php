<?php

namespace App\Http\Controllers;

use App\Models\FaqModel;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function store(Request $request){
       if(!$request->question){
        return response()->json([
            'message' => 'question field cannot be null',
        ], 400);
       }else if(!$request->answer){
        return response()->json([
            'message' => 'answer field cannot be null',
        ], 400);
       }
       $result = FaqModel::create(["question"=>$request->question,"answer"=>$request->answer]);

       if($result){
        return response()->json([
            'message' => 'data successfully created',
        ], 201);
       }
       return response()->json([
        'message' => 'data successfully created',
    ], 200);
    }
    public function get(){
        $result = FaqModel::orderBy('id', 'DESC')->get();
        $count = FaqModel::count();
        
        if($result){
            return response()->json([
                'message' => $count . ' data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }

    public function search(Request $request){
        $result = FaqModel::where('question', 'LIKE', "%{$request->questionOrAnswer}%")
                        ->orWhere('answer', 'LIKE', "%{$request->questionOrAnswer}%")
                        ->get();

        if($result){
            return response()->json([
                'message' => count($result). ' data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 400);
    }


    public function destroy($id){
        $result = FaqModel::destroy($id);
        
        if($result){
            return response()->json([
                'message' => ' data successfully deleted'
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }

    public function update(Request $request){

        if(!$request->question){
            return response()->json([
                'message' => 'question must be filled'
            ], 400);
        }else if(!$request->answer){
            return response()->json([
                'message' => 'answer must be filled'
            ], 400);
        }
        $payload = [
            "question" => $request->question,
            'answer' => $request->answer
        ];

        $result = FaqModel::where('id', $request->id)->update($payload);

        if($result){
            return response()->json([
                'message' => 'data successfully updated'
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong'
        ], 500);
    }
}
