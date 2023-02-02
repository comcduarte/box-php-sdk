<?php
namespace Laminas\Box\API\Resource;

use Laminas\Http\Response;
use Laminas\Hydrator\ClassMethodsHydrator;

class Files
{
    /**
     * A list of Files.
     * @var File[]
     */
    public $entries = [];
    
    /**
     * The number of files.
     * @var int
     */
    public $total_count;
    
    public function hydrate($response)
    {
        $hydrator = new ClassMethodsHydrator();
        $object = Response::fromString($response);
        $array = json_decode($object->getContent());
        $hydrator->hydrate($array, $this);
        return $this;
    }
}