<?php
namespace comcduarte\Box\API\Resource;

use Laminas\Http\Response;
use Laminas\Hydrator\ArraySerializableHydrator;

trait HydrationTrait
{
    public function exchangeArray(array $array)
    {
        foreach (array_keys(get_object_vars($this)) as $var) {
            if (!empty($array[$var])) {
                $this->$var = $array[$var];
            }
        }
    }
    
    public function getArrayCopy()
    {
        $data = [];
        foreach (array_keys(get_object_vars($this)) as $var) {
            $data[$var] = $this->{$var};
        }
        return $data;
    }
    
    public function hydrate($response)
    {
        $hydrator = new ArraySerializableHydrator();
        
        if (is_a($response, Response::class)) {
            $data = json_decode($response->getContent(), true);
            $hydrator->hydrate($data, $this);
        } elseif (is_array($response)) {
            $hydrator->hydrate($response, $this);
        } else {
            throw new \Exception('Invalid parameter in hydrate function.  Must be of type array or Response.');
        }
        return $this;
    }
}