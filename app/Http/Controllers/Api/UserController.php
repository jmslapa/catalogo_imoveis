<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->user->paginate(10);
        return response()->json($user, 200);
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
            $user = $this->user->with('profile')->findOrFail($id);
            return response()->json($user, 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password')) {
            $message = new ApiMessages('É necessário informar uma senha');
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
            'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try {

            $data['password'] = bcrypt($data['password']);

            DB::transaction(function () use($data) {
                $user = $this->user->create($data);
                $user->profile()->create([
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]);                
            });

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ], 201);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->all();

        if($request->has('password') && $request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        Validator::make($data, [
            'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try {

            $profile = [
                'phone' => $data['phone'],
                'mobile_phone' => $data['mobile_phone'],                
                'about' => isset($data['about']) && $data['about'] ? $data['about'] : null,
                'social_networks' => isset($data['social_networks']) && count($data['social_networks']) > 0 ? 
                                    serialize($data['social_networks']) : null
            ];
            
            DB::transaction(function () use($id, $data, $profile) {
                $user = $this->user->findOrFail($id);
                $user->update($data);
                $user->profile->update($profile);            
            });

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizado com sucesso!'
                ]
            ], 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Usuário removido com sucesso!'
                ]
            ], 200);
        }catch(\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 404);
        }
    }
}
