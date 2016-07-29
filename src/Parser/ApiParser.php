<?php

namespace Buses\Parser;

class ApiParser
{


    public function __construct()
    {

    }

    /**
     * @param object $data
     * @return null|object
     */
    public function parse($data)
    {
        if (!isset($data->Departures) || empty($data->Departures)) {
            return null;
        }

        // We only care about 2 bits of data; the destination, and the time as a string.
        $departure = $data->Departures[0];
        return (object) ['destination' => $departure->Destination, 'time' => $departure->DepartureTimeAsString];
    }
}