<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
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

    public function store(StoreCustomerRequest $request)
    {
        $validatedData = $request->validated();
        $user = new User();
        $user->fill($validatedData);
        $user->password = bcrypt($user->password);
        $user->type = "C";
        $user->save();
        $customer = new Customer();
        $customer->fill($validatedData);
        $user->customers()->save($customer);
        return response()->json(new CustomerResource($customer), 201);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validatedData = $request->validated();
        $customer->fill($validatedData);
        $customer->save();
        $user = $customer->users()->find($customer->id);
        $user->fill($validatedData);
        $user->save();
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        $customer->users()->delete();
        return response()->json(null, 204);
    }

    public function getOrders(Customer $customer){
        return $customer->orders;
    }
}
