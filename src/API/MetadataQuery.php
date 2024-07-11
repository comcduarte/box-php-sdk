<?php
namespace Laminas\Box\API;

use Laminas\Box\API\Resource\AbstractResource;
use Laminas\Box\API\Resource\MetadataQuerySearchResults;

class MetadataQuery extends AbstractResource
{
    /**
     * The ID of the folder that you are restricting the query to. A value of zero will return results from all folders you have access to. A non-zero value will only return results found in the folder corresponding to the ID or in any of its subfolders.
     * @var string
     */
    public $ancestor_folder_id;
    
    /**
     * By default, this endpoint returns only the most basic info about the items for which the query matches. This attribute can be used to specify a list of additional attributes to return for any item, including its metadata.
     * @var array
     */
    public $fields;
    
    /**
     * Specifies the template used in the query. Must be in the form scope.templateKey. 
     * Not all templates can be used in this field, most notably the built-in, Box-provided classification templates can not be used in a query.
     * @var string
     */
    public $from;
    
    /**
     * A value between 0 and 100 that indicates the maximum number of results to return for a single request. This only specifies a maximum boundary and will not guarantee the minimum number of results returned.
     * default: 100 min: 0 max: 100
     * @var integer
     */
    public $limit;
    
    /**
     * Marker to use for requesting the next page.
     * @var string
     */
    public $marker;
    
    /**
     * A list of template fields and directions to sort the metadata query results by.
     * The ordering direction must be the same for each item in the array.
     * @var array
     */
    public $order_by;
    
    /**
     * The query to perform. A query is a logical expression that is very similar to a SQL SELECT statement. Values in the search query can be turned into parameters specified in the query_param arguments list to prevent having to manually insert search values into the query string.
     * @var string
     */
    public $query;
    
    /**
     * Set of arguments corresponding to the parameters specified in the query. The type of each parameter used in the query_params must match the type of the corresponding metadata template field.
     * @var array
     */
    public $query_params;
    
    /**
     * Create a search using SQL-like syntax to return items that match specific metadata.
     * By default, this endpoint returns only the most basic info about the items for which the query matches. To get additional fields for each item, including any of the metadata, use the fields attribute in the query.
     * @param string $ancestor_folder_id
     * @param array $fields
     * @param string $from
     * @param int $limit
     * @param string $marker
     * @param array $order_by
     * @param string $query
     * @param array $query_params
     * @return \Laminas\Box\API\Resource\MetadataQuerySearchResults|\Laminas\Box\API\Resource\ClientError
     */
    public function metadata_query(
        string $ancestor_folder_id = null, 
        string $from = null,
        string $query = null,
        array $query_params = null)
    {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            if (!isset($arg_list[$i])) {
                return false;
            }
        }
        
        $endpoint = 'https://api.box.com/2.0/metadata_queries/execute_read';
        
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $data = [
            'ancestor_folder_id' => $ancestor_folder_id,
            'from' => $from,
            'query' => $query,
            'query_params' => $query_params,            
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a list of files and folders that match this metadata query.
                 */
                $metadata_query_search_results = new MetadataQuerySearchResults();
                $metadata_query_search_results->hydrate($this->response);
                return $metadata_query_search_results;
            case 400:
                /**
                 * Returns an error when the request body is not valid.
                 */
            case 404:
                /**
                 * Returns an error when a metadata template with the given scope and templateKey can not be found. The error response will include extra details.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}