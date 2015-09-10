<?php

namespace Bakgat\Notos\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var  Serializer $serializer */
    private $serializer;

    public function __construct()
    {
        $serializer = SerializerBuilder::create()
            ->build();

        $this->serializer = $serializer;
    }

    public function json($data)
    {
        $serializedData = $this->serializer->serialize($data, 'json');
        return $serializedData;
    }
}
