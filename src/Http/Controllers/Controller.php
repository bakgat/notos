<?php

namespace Bakgat\Notos\Http\Controllers;

use Bakgat\Serializer\SerializerBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var  Serializer $serializer */
    private $serializer;
    /** @var  SerializationContext $context */
    private $context;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create();
        $this->context = SerializationContext::create();
    }

    public function json($data, $groups = null)
    {
        if ($groups) {
            $this->context->setGroups($groups);
        }
        $serializedData = $this->serializer->serialize($data, 'json', $this->context);

        return $serializedData;
    }

    public function jsonResponse($data, $groups = null)
    {
        $headers = ['Content-type' => 'application/json; charset=utf-8'];

        return Response::create(
            $this->json($data, $groups),
            200,
            $headers,
            JSON_UNESCAPED_UNICODE
        );
    }

    public function destroyedResponse()
    {
        return Response::create(null, 204);
    }
}
