<?php

namespace App\Http\Controllers\Api;

use App\RealState;
use App\Api\ApiMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\RealStateRepository;

class RealStateSearchController extends Controller
{

    private $realState;
    private $repository;
    
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
        
        $this->repository = new RealStateRepository(RealState::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // location
        $location = $request->only('state', 'city');
        if(count($location)) {
            $this->repository->setLocation($location);
        }
        
        // filtros
        if($request->has('filters') && !empty($request->filters)) {
            $products = $this->repository->filterBy($request->filters);
        }
        // especificando campos
        if($request->has('fields') && !empty($request->fields)) {
            $products = $this->repository->selectFields($request->fields);
        }

        return response()->json($this->repository->get(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $realState = $this->realState->with('address', 'photos')
                                         ->findOrFail($id)
                                         ->makeHidden('_thumb');
            return response()->json($realState, 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }
}
