<?php
namespace comcduarte\Box\API\Resource;

use Laminas\Http\Response;
use Laminas\Hydrator\ArraySerializableHydrator;

trait HydrationTrait
{
    public function exchangeArray(array $data)
    {
        foreach ($data as $property => $value) {
            
            //-- Skip if no value was passed --//
            if (empty($value) || !property_exists($this, $property)) {
                continue;
            }
            
            $reflectionProperty = new \ReflectionProperty($this, $property);
            if ($reflectionProperty->hasType()) {
                //-- Property has a declared type --//
                $reflectionType = $reflectionProperty->getType();
                switch ($reflectionType)
                {
                    case 'string':
                    case 'array':
                    case 'int':
                    case 'bool':
                        $this->$property = $value;
                        break;
                    default:
                        $property = lcfirst(str_replace('_', '', ucwords($property, '_')));
                        $setter   = sprintf('set%s', ucfirst($property));
                        $callable = [$this, $setter];
                        if (!is_callable($callable)) {
                            throw new \Exception(
                                sprintf('Unable to call %s', $setter)
                                );
                        }
                        call_user_func($callable, $value);
                        break;
                }
                
            } else {
                //-- Property does not have a declared type --//
                $this->$property = $value;
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