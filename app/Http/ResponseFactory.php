<?php

namespace App\Http;

use Zend\Config\Config;
use Zend\Config\Writer\Xml;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Laravel\Lumen\Http\ResponseFactory as Response;

class ResponseFactory extends Response
{
    public function make($content = '', $status = 200, array $headers = [])
    {
        $request = app('request');
        $acceptHeader = $request->header('accept');

        $result = '';

        switch ($acceptHeader) {
            case 'application/xml':
                $result = parent::make($this->getXML($content), $status, $headers);
                break;
            case '':
            case '*/*':
            case 'application/json':
            default:
                $result = $this->json($content, $status, $headers);
                break;
        }

        return $result;
    }

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
