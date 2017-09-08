<?php

namespace App\Http\Controllers;

use App\Client;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressController extends Controller
{
    public function index($clientId)
    {
        if (! ($client = Client::find($clientId))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        return son_response()->make(Address::where('client_id', $clientId)->get());
    }

    public function show($id, $clientId)
    {
        if (! ($client = Client::find($clientId))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        if (! ($address = Address::find($id))) {
            throw new ModelNotFoundException("Address Not Found");
        }

        return son_response()->make($address);
    }

    public function store(Request $request, $clientId)
    {
        if (! ($client = Client::find($clientId))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        $this->validate($request, [
            "address" => 'required',
            "city" => 'required',
            "state" => 'required',
            "zipcode" => 'required'
        ]);

        $address = $client->addresses()->create($request->all());

        return son_response($address, 201);
    }

    public function update(Request $request, $id, $clientId)
    {
        if (! ($client = Client::find($clientId))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        if (! ($address = Address::find($id))) {
            throw new ModelNotFoundException("Address Not Found");
        }

        $this->validate($request, [
            "address" => 'required',
            "city" => 'required',
            "state" => 'required',
            "zipcode" => 'required'
        ]);

        $address->fill($request->all());
        $address->save();

        return son_response($address, 200);
    }

    public function destroy ($id, $clientId)
    {
        if (!($client = Client::find($clientId))) {
            throw new ModelNotFoundException('Client Not Found');
        }

        if (! ($address = Address::find($id))) {
            throw new ModelNotFoundException("Address Not Found");
        }

        $address->delete();

        return son_response('', 204);
    }
}
