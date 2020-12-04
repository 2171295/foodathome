<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        dd("aqui");
        if ($request->has('page')) {
            return CustomerResource::collection(Customer::paginate(5));
        } else {
            return CustomerResource::collection(Customer::all());
        }
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function store(StoreUserRequest $requestUser)
    {
        $user = new User();
        $user->fill($requestUser->validated());
        $user->password = bcrypt($user->password);
        $user->type = "C";
        $user->save();
        dd($user);
        $customer = new Customer();
        $customer->fill($requestCustomer->validated());
        $customer->id = $user->id;
        $customer->save();
        return response()->json(new CustomerResource($customer), 201);
    }


    public function update(UpdateUserRequest  $requestUser, UpdateCustomerRequest $requestCustomer, Customer $customer)
    {
        DB::table("customers")->where("id", $customer->id)->update($requestCustomer->validated());
        DB::table("users")->where("id", $customer->id)->update($requestUser->validated());
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        DB::table("customers")->where("id", $customer->id)->delete();
        DB::table("users")->where("id", $customer->id)->delete();
        return response()->json(null, 204);
    }
}
