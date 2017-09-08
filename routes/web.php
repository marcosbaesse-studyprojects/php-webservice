<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group([
    'prefix' => 'api/clients',
    'namespace' => '\App\Http\Controllers'
], function () use ($app) {
    $app->get('', 'ClientsController@index');
    $app->get('{id}', 'ClientsController@show');
    $app->post('', 'ClientsController@store');
    $app->put('{id}', 'ClientsController@update');
    $app->delete('{id}', 'ClientsController@destroy');
});

$app->group([
    'prefix' => 'api/clients/{client}/addresses',
    'namespace' => '\App\Http\Controllers'
], function () use ($app) {
    $app->get('', 'AddressController@index');
    $app->get('{id}', 'AddressController@show');
    $app->post('', 'AddressController@store');
    $app->put('{id}', 'AddressController@update');
    $app->delete('{id}', 'AddressController@destroy');
});

$app->get('tcu', function () {
    $client = new \Zend\Soap\Client('http://contas.tcu.gov.br/debito/CalculoDebito?wsdl');

    echo "Server's information";
    print_r($client->getOptions());

    echo "Funções";
    print_r($client->getFunctions());

    echo "Tipos";
    print_r($client->getTypes());

    // com o soap é possível chamar metodos diretamente
    print_r($client->obterSaldoAtualizado([
        'parcelas' => [
            'parcela' => [
                'data' => '1998-12-28',
                'tipo' => 'D',
                'valor' => 20
            ]
        ],
        'aplicaJuros' => true,
        'dataAtualizacao' => '2016-01-01'
    ]));
});

/**
* @return int
**/
function soma ($num1, $num2) {
   return $num1 + $num2;
};

$uri = 'http://ca-marcosbaesse.codeanyapp.com/';
// $uri = 'http://dev.lumen';

$app->get('son-soap.wsdl', function () use ($uri) {
    $autoDiscover = new \Zend\Soap\AutoDiscover();
    $autoDiscover->setUri("$uri/server");
    $autoDiscover->setServiceName('SONSOAP');
    $autoDiscover->addFunction('soma');
    $autoDiscover->handle();
});

$app->post('server', function () use ($uri) {
    $server = new \Zend\Soap\Server("$uri/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);
    $server->setUri("$uri/server");
    return $server
        ->setReturnResponse(true)
        ->addFunction('soma')
        ->handle();
});

$app->get('soap-test', function () use ($uri) {
    $client = new \Zend\Soap\Client("$uri/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);
   var_dump($client->soma(100, 200));
});

// SOAP CLIENT
// $uriClient = 'http://dev.lumen/client';
$uriClient = 'http://ca-marcosbaesse.codeanyapp.com/client';

$app->get('client/son-soap.wsdl', function () use ($uriClient) {
    $autoDiscover = new \Zend\Soap\AutoDiscover();
    $autoDiscover->setUri("$uriClient/server");
    $autoDiscover->setServiceName('SONSOAP');
    $autoDiscover->setClass(\App\Soap\ClientsSoapController::class);
    $autoDiscover->handle();
});

$app->post('client/server', function () use ($uriClient) {
    $server = new \Zend\Soap\Server("$uriClient/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);
    $server->setUri("$uriClient/server");
    return $server
        ->setReturnResponse(true)
        // ->addFunction('soma')
        ->setClass(\App\Soap\ClientsSoapController::class)
        ->handle();
});

$app->get('client/soap-client', function () use ($uriClient) {
    $client = new \Zend\Soap\Client("$uriClient/son-soap.wsdl", [
        'cache_wsdl' => WSDL_CACHE_NONE
    ]);
  
    $new_client = new App\Types\ClientType();
    $new_client->name = 'Test Example';
    $new_client->email = 'testexample@example.com';
    $new_client->phone = '0654';
  
    print_r($client->create($new_client));
  exit;
    $type = $new_client;
  
    $data = [
            'name' => $type->name,
            'email' => $type->email,
            'phone' => $type->phone
        ];

    $client = App\Client::create($data);
  
    print_r($client);
  
//     print_r($client->listAll());
});
