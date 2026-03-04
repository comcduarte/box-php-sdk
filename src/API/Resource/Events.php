<?php
namespace comcduarte\Box\API\Resource;

class Events extends AbstractResources
{
    /**
     * The number of events returned in this response.
     * @var integer
     */
    public $chunk_size;
    
    /**
     * The stream position of the start of the next page (chunk) of events.
     * @var string
     */
    public $next_stream_position;
}