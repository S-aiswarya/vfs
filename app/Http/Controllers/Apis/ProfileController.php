<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\Customer as ResourcesCustomer;
use App\Http\Resources\CropResourceCollection;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function edit(){
        $customer = auth()->user();
        return new ResourcesCustomer($customer);
    }

    public function update(CustomerRequest $request){
        $request->validated();
        $customer_id = auth()->user()->id;
        $customer = Customer::find($customer_id);
        $customer->name = $request->name;
        $customer->whatsapp_number = $request->whatsapp_number;
        $customer->address = $request->address;
        $customer->landmark = $request->landmark;
        $customer->aadhar_number = $request->aadhar_number;
        $customer->image = $request->image;
        $customer->save();
        return new ResourcesCustomer($customer);
    }

    public function crops(){
        $customer = auth()->user();
        $crops = $customer->crops;
        return new CropResourceCollection($crops);
    }
}
