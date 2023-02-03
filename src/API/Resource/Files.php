<?php
namespace Laminas\Box\API\Resource;

use Laminas\Http\Response;
use Laminas\Hydrator\ClassMethodsHydrator;

class Files extends AbstractResources
{
    /**
     * 
     * @param string $response
     * @return \Laminas\Box\API\Resource\Files
     */
    public function hydrate($response)
    {
        $hydrator = new ClassMethodsHydrator();
        $object = Response::fromString($response);
        $array = json_decode($object->getContent());
        $hydrator->hydrate($array, $this);
        return $this;
    }
}