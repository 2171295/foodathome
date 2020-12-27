<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function indexWithTrash(Request $request)
    {
        return UserResource::collection(User::withTrashed()->get());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json($user, 204);
    }

    public function emailAvailable(Request $request)
    {
        //dd($request->email);
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = User::where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = User::where('email', '=', $request->email)->withTrashed()->count();
        }
        //$totalEmail += User::where('email', '=', $request->email)->whereNotNull('deleted_at')->count();
        return response()->json($totalEmail == 0);
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function usersLogged(){
        $users = User::where('logged_at','!=',null)->get();
        return response()->json($users);
        //return UserResource::collection($users);
    }

    public function availableCookers(){
        return User::where('available_at','!=',null)->where('type','=','EC')->get();
    }

    public function availableDeliveryman(){
        return User::where('available_at','!=',null)->where('type','=','ED')->get();
    }

    public function availableManagers(){
        return User::where('available_at','!=',null)->where('type','=','EM')->get();
    }

    public function loggedAt(User $user){
        $mytime = Carbon::now();
        $user->logged_at = $mytime->toDateTimeString();
        $user->available_at = $mytime->toDateTimeString();
        $user->save();
    }

    public function logout(User $user){
        $user->logged_at = null;
        $user->available_at = null;
        $user->save();
    }

    public function block(User $user){
        $user->blocked = 1;
        $user->save();
        return new UserResource($user);
    }

    public function unblock(User $user){
        $user->blocked = 0;
        $user->save();
        return new UserResource($user);
    }
}
