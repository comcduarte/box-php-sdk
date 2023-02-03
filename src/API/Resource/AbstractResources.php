<?php
namespace Laminas\Box\API\Resource;

abstract class AbstractResources
{
    /**
     * Array of Resources
     * @var array
     */
    public $entries = [];
    
    /**
     * The limit that was used for these entries. This will be the same as the limit query parameter unless that value exceeded the maximum value allowed. The maximum value varies by API.
     * @var integer
     */
    public $limit;
    
    /**
     * The 0-based offset of the first entry in this set. This will be the same as the offset query parameter.
     * This field is only returned for calls that use offset-based pagination. For marker-based paginated APIs, this field will be omitted.
     * @var integer
     */
    public $offset;
    
    /**
     * The order by which items are returned.
     * This field is only returned for calls that use offset-based pagination. For marker-based paginated APIs, this field will be omitted.
     * @var array
     */
    public $order = [];
    
    /**
     * One greater than the offset of the last entry in the entire collection. The total number of entries in the collection may be less than total_count.
     * This field is only returned for calls that use offset-based pagination. For marker-based paginated APIs, this field will be omitted.
     * @var integer
     */
    public $total_count;
    
    /**
     * The marker for the start of the next page of results.
     * @var integer
     */
    public $next_marker;
    
    /**
     * The marker for the start of the previous page of results.
     * @var integer
     */
    public $prev_marker;
}