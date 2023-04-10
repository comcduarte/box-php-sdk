<?php
namespace Laminas\Box\API;


Trait RepresentationsTrait
{
    use RequestExtraFieldsTrait;
    
    public $representations = [];
    
    public function list_all_representations()
    {
        $this->add_field('representations');
        return $this;
    }
    
    abstract public function request_desired_representation();
    
    abstract public function download_file_representation();

}
