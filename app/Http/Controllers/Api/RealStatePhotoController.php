<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{

    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    /**
     * Set a photo as thumb.
     *
     * @return \Illuminate\Http\Response
     */
    public function setThumb($id)
    {
        try{
            $photo = $this->realStatePhoto->findOrFail($id);
            $current = $photo->realState->photos->where('is_thumb', true)->first();
            DB::transaction(function () use($photo, $current) {
                if($current) {
                    $current->is_thumb = false;
                    $current->update();
                }
                $photo->is_thumb = true;
                $photo->update();                
            });
            return response()->json([
                'data' => [
                    'msg' => 'Thumbnail atualizada com sucesso!'
                ]
            ], 200);
            
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    /**
     * Remove a photo from database and storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{            
            $photo = $this->realStatePhoto->findOrFail($id);
            if($photo->is_thumb) {
                throw new \Exception('Não é possível remover thumbnail. Selecione outra thumbnail '.
                                    'antes de tentar novamente.');
            }
            $photo->delete();
            if(Storage::disk('public')->exists($photo->photo)) {
                Storage::disk('public')->delete($photo->photo);
            }
            return response()->json([
                'data' => [
                    'msg' => 'Foto removida com sucesso!'
                ]
            ], 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }
}
