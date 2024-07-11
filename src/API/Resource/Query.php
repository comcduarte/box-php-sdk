<?php
declare(strict_types=1);

namespace Laminas\Box\API\Resource;

class Query
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';
    
    /**
     * The direction to sort results in. This can be either in alphabetical ascending (ASC) or descending (DESC) order.
     * example ASC
     * @var string
     */
    public $direction;
    
    /**
     * A comma-separated list of attributes to include in the response. This can be used to request fields that are not normally returned in a standard response.
     * Be aware that specifying this parameter will have the effect that none of the standard fields are returned in the response unless explicitly specified, instead only fields for the mini representation are returned, additional to the fields requested.
     * Additionally this field can be used to query any metadata applied to the file by specifying the metadata field as well as the scope and key of the template to retrieve, for example 
     * example id,type,name
     * @var string|array
     */
    public $fields;
    
    /**
     * The maximum number of items to return per page.
     * example 1000 max 1000
     * @var integer
     */
    public $limit;
    
    /**
     * Defines the position marker at which to begin returning results. This is used when paginating using marker-based pagination.
     * example JV9IRGZmieiBasejOG9yDCRNgd2ymoZIbjsxbJMjIs3kioVii
     * @var string
     */
    public $marker;
    
    /**
     * The offset of the item at which to begin the response.
     * Queries with offset parameter value exceeding 10000 will be rejected with a 400 response.
     * example 1000 default 0
     * @var integer
     */
    public $offset;
    
    /**
     * Defines the second attribute by which items are sorted.
     * The folder type affects the way the items are sorted:
     *      Standard folder: Items are always sorted by their type first, with folders listed before files, and files listed before web links.
     *      Root folder: This parameter is not supported for marker-based pagination on the root folder
     * (the folder with an id of 0).
     *      Shared folder with parent path to the associated folder visible to the collaborator: Items are always sorted by their type first, with folders listed before files, and files listed before web links.
     * Value is one of id,name,date,size
     * @var string
     */
    public $sort;
    
    /**
     * Specifies whether to use marker-based pagination instead of offset-based pagination. Only one pagination method can be used at a time.
     * By setting this value to true, the API will return a marker field that can be passed as a parameter to this endpoint to get the next page of the response.
     * @var boolean
     */
    public $usemarker;
    
    public function getArrayCopy() : array
    {
        $data = [];
        foreach (array_keys(get_object_vars($this)) as $var) {
            if (!is_null($this->{$var})) {
                $data[$var] = $this->{$var};
            }
        }
        return $data;
    }
    
}