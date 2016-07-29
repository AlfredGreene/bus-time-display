<?php

namespace Buses\Service;

use GuzzleHttp\Client;

class ApiService
{
    private $connection;
    
    private $stop;
    
    private $base_uri = 'http://bh.buscms.com/';
    private $departures = 'BrightonBuses/api/XmlEntities/v1/departureboard.aspx';
    
    public function __construct()
    {
        $this->connection = new Client([
            'base_uri' => $this->base_uri
        ]);
    }
    
    public function setStop($stop)
    {
        $this->stop = $stop;
    }
    
    public function getData()
    {
        $response = $this->connection->get(
            $this->departures,
            [
                'query' => [
                    'stopid' => $this->stop
                ]
            ]
        );
        
        if ($response->getStatusCode() >= 400) {
            return null;
        }

        return $response->getBody()->getContents();
    }
}