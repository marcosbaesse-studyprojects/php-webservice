<?php

namespace App\Soap;

use App\Client;
use Zend\Config\Config;
use App\Types\ClientType;
use Zend\Config\Writer\Xml;
use Illuminate\Contracts\Support\Arrayable;

// use Illuminate\Http\Request;
// use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientsSoapController
{   
    /**
    * @return string
    */
    public function listAll()
    {
        return $this->getXML(Client::all());
    }

    /**
    * @param \App\Types\ClientType $type 
    * @return string
    */
    public function create(ClientType $type)
    {
      echo 'ok';
        $data = [
            'name' => $type->name,
            'email' => $type->email,
            'phone' => $type->phone
        ];

        $client = Client::create($data);

        return $this->getXML($client);
    }

    // public function update($id, Request $request)
    // {
    //     if (! ($client = Client::find($id))) {
    //         throw new ModelNotFoundException("Client Not Found");
    //     }

    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required',
    //         'phone' => 'required'
    //     ]);

    //     $client->fill($request->all());
    //     $client->save();

    //     return son_response($client, 200);
    // }

    // public function destroy ($id)
    // {
    //     if (!($client = Client::find($id))) {
    //         throw new ModelNotFoundException('Client Not Found');
    //     }

    //     $client->delete();

    //     return son_response('', 204);
    // }

    protected function getXML($data)
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $config = new Config(['result' => $data], true);
        $xmlWriter = new Xml();

        return $xmlWriter->toString($config);
    }
}
