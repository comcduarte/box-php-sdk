<?php
namespace Laminas\Box\API;

Trait RequestExtraFieldsTrait
{
    public $query;
    
    public function add_field(string $field)
    {
        if (isset($this->query['fields'])) {
            $ary_fields = str_getcsv($this->query['fields']);
            $ary_fields[] = $field;
            
            $str_fields = "";
            foreach ($ary_fields as $value) {
                $str_fields .= implode(",", $value) . PHP_EOL;
            }
            
            $this->query['fields'] = $str_fields;
        } else {
            $this->query['fields'] = $field;
        }
        
        return $this;
    }
}