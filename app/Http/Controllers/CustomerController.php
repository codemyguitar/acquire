<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\NewCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Carbon\Carbon;

/**
 * @api {get} /user/:id Request User information
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiParam {Number} id User's unique ID.
 *
 * @apiSuccess {String} firstname Firstname of the User.
 * @apiSuccess {String} lastname  Lastname of the User.
 */

class CustomerController extends Controller
{
    public function __invoke()
    {
        return view('customers');

    }

    public function index()
    {
        return CustomerResource::collection(request()->user()->customers);
    }

    public function store(NewCustomerRequest $request)
    {
        $customer = $request->user()->customers()->create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'age' => $request->age,
            'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
            'email' => $request->email,
            'creation_date' => now()
        ]);

        return response()->json(['id' => $customer->id]);
    }

    public function get(Customer $customer)
    {
        return CustomerResource::make($customer);
    }

    public function update(EditCustomerRequest $request, Customer $customer)
    {
        $customer->first_name = $request->firstName;
        $customer->last_name = $request->lastName;
        $customer->age = $request->age;
        $customer->dob = Carbon::parse($request->dob)->format('Y-m-d');
        $customer->email = $request->email;
        $customer->save();

        return CustomerResource::make($customer);
    }

    public function delete(Customer $customer)
    {
        $customer->delete();

        return response()->json();
    }
}
