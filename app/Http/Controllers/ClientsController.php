<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientsController extends Controller
{
    public function index()
    {
        return son_response(Client::all());
    }

    public function show($id)
    {
        if (! ($client = Client::find($id))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        return son_response($client);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        $client = Client::create($request->all());

        return son_response($client, 201);
    }

    public function update($id, Request $request)
    {
        if (! ($client = Client::find($id))) {
            throw new ModelNotFoundException("Client Not Found");
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        $client->fill($request->all());
        $client->save();

        return son_response($client, 200);
    }

    public function destroy ($id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException('Client Not Found');
        }

        $client->delete();

        return son_response('', 204);
    }
}
