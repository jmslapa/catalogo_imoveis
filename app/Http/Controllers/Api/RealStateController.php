<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RealStateController extends Controller
{

    /**
     * Model injetado
     *
     * @var App\RealState
     */
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = auth('api')->user()->realStates()->paginate(10);
        return response()->json($realState, 200);
    }

    public function show($id)
    {
        try {
            $realState = auth('api')->user()->realStates()->with('photos')->findOrFail($id);
            return response()->json($realState, 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->user()->id;
        $images = $request->file('images');        
        $uploadedImages = [];
        try {
            if($images) {
                foreach($images as $img) {
                    if($img->isValid()) {
                        $path = $img->store('images', 'temp');
                        $uploadedImages[] = ['photo' => $path, 'is_thumb' => false];    
                    }
                }
            }

            DB::transaction(function() use($data, $uploadedImages) {
                $realState = $this->realState->create($data);                           
                if(isset($data['categories']) && count($data['categories']) > 0) {
                    $realState->categories()->sync($data['categories']);
                }
                if(count($uploadedImages)) {
                    $realState->photos()->createMany($uploadedImages);
                }
            });

            if(count($uploadedImages)) {
                foreach($uploadedImages as $img) {
                    $path = $img['photo'];
                    $tempFile = Storage::disk('temp')->get($path);
                    Storage::disk('public')->put($path, $tempFile);
                }
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                ]
            ], 201);
        }catch(\Exception $e) {
            
            if(count($uploadedImages)) {
                foreach($uploadedImages as $img) {
                    $path = $img['photo'];
                    $exists = Storage::disk('public')->exists($path);
                    if($exists) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->user()->id;
        $images = $request->file('images');        
        $uploadedImages = [];

        try {

            if($images) {
                foreach($images as $img) {
                    if($img->isValid()) {
                        $path = $img->store('images', 'temp');
                        $uploadedImages[] = ['photo' => $path, 'is_thumb' => false];    
                    }
                }
            }

            DB::transaction(function() use($id, $data, $uploadedImages) {
                $realState = auth('api')->user()->realStates()->findOrFail($id);
                if($realState->photos->count() + count($uploadedImages) > 10) {
                    throw new \Exception('Um imóvel não pode ter mais que 10 imagens');
                }
                $realState->update($data);                          
                if(isset($data['categories']) && count($data['categories']) > 0) {
                    $realState->categories()->sync($data['categories']);
                }                
                if(count($uploadedImages)) {
                    $realState->photos()->createMany($uploadedImages);
                }
            });

            if(count($uploadedImages)) {
                foreach($uploadedImages as $img) {
                    $path = $img['photo'];
                    $tempFile = Storage::disk('temp')->get($path);
                    Storage::disk('public')->put($path, $tempFile);
                }
            }
            
            return response()->json([
                'data' => [
                    'msg' => 'Imóvel atualizado com sucesso!'
                ]
            ], 200);
        }catch(\Exception $e) {

            if(count($uploadedImages)) {
                foreach($uploadedImages as $img) {
                    $path = $img['photo'];
                    $exists = Storage::disk('public')->exists($path);
                    if($exists) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    public function destroy($id)
    {
        try {

            $realState = auth('api')->user()->realStates()->findOrFail($id);
            $realState->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel removido com sucesso!'
                ]
            ], 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }
}
