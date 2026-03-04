<?php
namespace comcduarte\Box\API\Resource;

class Representation extends AbstractResource
{
    public $etag;
    public $id;
    public $representation;
    public $dimension;
    public $paged;
    public $thumb;
    public $info;
    public $content;
    public $status_state;
    
    const TYPE_JPG = 'jpg';
    const TYPE_PDF = 'pdf';
    const TYPE_PNG = 'png';
    
    const DIMENSION_32x32 = '32x32';
    const DIMENSION_94x94 = '94x94';
    const DIMENSION_160x160 = '160x160';
    const DIMENSION_320x320 = '320x320';
    const DIMENSION_1024x1024 = '1024x1024';
    const DIMENSION_2048x2048 = '2048x2048';
    
}