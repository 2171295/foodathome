<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $user->password = Hash::make($user->password);

        if($request->photo_url != null) {
            $name = $user->id . '_' . time() . '.' . $request->photo_url->getClientOriginalExtension();
            if($user->photo_url != null) {
                Storage::disk('public')->delete('fotos/'.$user->photo_url);
            }
            Storage::putFileAs('public/fotos', $request->photo_url, $name);

            $user->photo_url = $name;
        }else{
            $user->photo_url = null;
        }

        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }
    public function updatePassword(Request $request, User $user){
        if(Hash::check($request->get('oldPassword'),$user->password)){
           if($request->get('newPassword') === $request->get('confirmationPassword')){
                $user->password = Hash::make($request->get('newPassword'));
                $user->save();
           }
        }
    }

    public function updatePhoto(Request $request, User $user)
    {
        $request->validate([
            'photo' => 'image|mimes:jpeg,jpg,png',
        ]);

        $name = $user->id . '_' . time() . '.' . $request->photo->getClientOriginalExtension();
        if($user->photo_url != null) {
            Storage::disk('public')->delete('fotos/'.$user->photo_url);
        }
        Storage::putFileAs('public/fotos', $request->photo, $name);

        $user->photo_url = $name;
        $user->save();

        return response()->json("User Photo Updated", 200);
    }

    public function destroy(User $user)
    {
        $user->blocked = 1;
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
        return User::whereNotNull('available_at')->where('type','=','EC')->orderBy('available_at','asc')->first();
    }

    public function availableDeliveryman(){
        return User::whereNotNull('available_at')->where('type','=','ED')->get();
    }

    public function availableManagers(){
        return User::whereNotNull('available_at')->where('type','=','EM')->get();
    }

    public function cooker_orders(User $user){
        return $user->cooker_orders;
    }

    public function deliveryman_orders(User $user){
        return $user->deliveryman_orders;
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

    public function notAvailable(User $user){
        $user->available_at = null;
        $user->save();
    }

    public function available(User $user){
        $mytime = Carbon::now();
        $user->available_at = $mytime->toDateTimeString();
        $user->save();
    }

    public function confirmPassword(Request $request, User $user){
        if(Hash::check($request->get('oldPassword'),$user->password)){
            return response()->json(null, 200);
        }
        return response()->json(null, 400);
    }
}
