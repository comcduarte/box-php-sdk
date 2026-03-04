<?php
namespace comcduarte\Box\API;

use comcduarte\Box\API\Resource\ClientError;
use comcduarte\Box\API\Resource\MetadataQuerySearchResults;

/**
 * 
 * @author Duartec
 * @property Search $AND
 */
class Search extends Resource\AbstractResource
{
    const TYPE_FILE      = 'file';
    const TYPE_FOLDER    = 'folder';
    const TYPE_WEBLINK   = 'web_link';
    
    const CONTENT_TYPE_NAME          = 'name';
    const CONTENT_TYPE_DESCRIPTION   = 'description';
    const CONTENT_TYPE_FILE_CONTENT  = 'file_content';
    const CONTENT_TYPE_COMMENTS      = 'comments';
    const CONTENT_TYPE_TAGS          = 'tags';
    
    const OP_AND   = 'AND';
    const OP_OR    = 'OR';
    const OP_NOT   = 'NOT';
    
    const SCOPE_USER         = 'user_content';
    const SCOPE_ENTERPRISE   = 'enterprise_content';
    
    /**
     * Limits the search results to any items of this type. This parameter only takes one value. By default the API returns items that match any of these types.
     * 
     * file - Limits the search results to files
     * folder - Limits the search results to folders
     * web_link - Limits the search results to web links, also known as bookmarks
     * @var string
     */
    public $type;
    
    /**
     * Limits the search results to items within the given list of folders, defined as a comma separated lists of folder IDs.
     * 
     * example: 4535234,234123235,2654345
     * 
     * Search results will also include items within any subfolders of those ancestor folders.
     * The folders still need to be owned or shared with the currently authenticated user. If the folder is not accessible by this user, or it does not exist, a HTTP 404 error code will be returned instead.
     * To search across an entire enterprise, we recommend using the enterprise_content scope parameter which can be requested with our support team.
     * @var string array
     */
    public $ancestor_folder_ids;
    
    /**
     * Limits the search results to any items that match the search query for a specific part of the file, for example the file description.
     * Content types are defined as a comma separated lists of Box recognized content types. The allowed content types are as follows.
     * 
     * name - The name of the item, as defined by its name field.
     * description - The description of the item, as defined by its description field.
     * file_content - The actual content of the file.
     * comments - The content of any of the comments on a file or folder.
     * tags - Any tags that are applied to an item, as defined by its tags field.
     * @var string array
     */
    public $content_types;
    
    /**
     * example: 2014-05-15T13:35:01-07:00,2014-05-17T13:35:01-07:00
     * @var string array
     */
    public $created_at_range;
    
    /**
     * @TODO Determine syntax for created/deleted at range.  Examples given in documentation differ.
     * example: ["2014-05-15T13:35:01-07:00","2014-05-17T13:35:01-07:00"]
     * @var string array
     */
    public $deleted_at_range;
    
    /**
     * Limits the search results to items that were deleted by the given list of users, defined as a list of comma separated user IDs.
     * The trash_content parameter needs to be set to trashed_only.
     * If searching in trash is not performed, an empty result set is returned. The items need to be owned or shared with the currently authenticated user for them to show up in the search results.
     * If the user does not have access to any files owned by any of the users, an empty result set is returned.
     * Data available from 2023-02-01 onwards.
     * @var string array
     */
    public $deleted_user_ids;
    
    /**
     * example ASC default "DESC"
     * Defines the direction in which search results are ordered. This API defaults to returning items in descending (DESC) order unless this parameter is explicitly specified.
     * When results are sorted by relevance the ordering is locked to returning items in descending order of relevance, and this parameter is ignored.
     * Value is one of DESC,ASC
     * @var string
     */
    public $direction;
    
    /**
     * example pdf,png,gif
     * Limits the search results to any files that match any of the provided file extensions. This list is a comma-separated list of file extensions without the dots.
     * @var string|array
     */
    public $file_extensions;
    
    /**
     * example true default false
     * Defines whether the search results should include any items that the user recently accessed through a shared link.
     * When this parameter has been set to true, the format of the response of this API changes to return a list of Search Results with Shared Links
     * @var boolean
     */
    public $include_recent_shared_links;
    
    /**
     * example ["extension","created_at","item_status","metadata.enterprise_1234.contracts","metadata.enterprise
     * By default, this endpoint returns only the most basic info about the items for which the query matches. This attribute can be used to specify a list of additional attributes to return for any item, including its metadata.
     * This attribute takes a list of item fields, metadata template identifiers, or metadata template field identifiers.
     * 
     * For example:
     * created_by will add the details of the user who created the item to the response.
     * metadata.<scope>.<templateKey> will return the mini-representation of the metadata instance identified by the scope and templateKey.
     * metadata.<scope>.<templateKey>.<field> will return all the mini-representation of the metadata instance identified by the scope and templateKey plus the field specified by the field name. Multiple fields for the same scope and templateKey can be defined.
     * @var array
     */
    public $fields;
    
    /**
     * Specifies the template used in the query. Must be in the form scope.templateKey. Not all templates can be used in this field,
     * most notably the built-in, Box-provided classification templates can not be used in a query.
     * @var string
     */
    public $from; 
        
    /**
     * Defines the maximum number of items to return as part of a page of results.
     * example:100 default:30 max:200
     * @var integer
     */
    public $limit;
    
    /**
     * A list of metadata templates to filter the search results by.
     * Required unless query parameter is defined.
     * @var array
     */
    public $mdfilters;
    
    /**
     * The offset of the item at which to begin the response.
     * Queries with offset parameter value exceeding 10000 will be rejected with a 400 response.
     * @var integer
     */
    public $offset;
    
    /**
     * example 123422,23532,3241212 
     * Limits the search results to any items that are owned by the given list of owners, defined as a list of comma separated user IDs.
     * The items still need to be owned or shared with the currently authenticated user for them to show up in the search results. If the user does not have access to any files owned by any of the users an empty result set will be returned.
     * To search across an entire enterprise, we recommend using the enterprise_content scope parameter which can be requested with our support team.
     * @var string|array
     */
    public $owner_user_ids;
    
    /**
     * 
     * @var string
     */
    public $query;
 
    /**
     * example "100"
     * The value for the argument being used in the metadata search.
     * The type of this parameter must match the type of the corresponding metadata template field.
     * @var array
     */
    public $query_params;
    
    /**
     * example 123422,23532,3241212
     * Limits the search results to any items that have been updated by the given list of users, defined as a list of comma separated user IDs.
     * The items still need to be owned or shared with the currently authenticated user for them to show up in the search results. If the user does not have access to any files owned by any of the users an empty result set will be returned.
     * This feature only searches back to the last 10 versions of an item.
     * @var string|array
     */
    public $recent_updater_user_ids;
    
    /**
     * Limits the search results to either the files that the user has access to, or to files available to the entire enterprise.
     * The scope defaults to user_content, which limits the search results to content that is available to the currently authenticated user.
     * The enterprise_content can be requested by an admin through our support channels. Once this scope has been enabled for a user, it will allow that use to query for content across the entire enterprise and not only the content that they have access to.
     * @var string
     */
    public $scope;
    
    /**
     * example 1000000,5000000
     * Limits the search results to any items with a size within a given file size range. This applied to files and folders.
     * Size ranges are defined as comma separated list of a lower and upper byte size limit (inclusive).
     * The upper and lower bound can be omitted to create open ranges.
     * @var integer|array
     */
    public $size_range;
    
    /**
     * example modified_at default "relevance"
     * Defines the order in which search results are returned. This API defaults to returning items by relevance unless this parameter is explicitly specified.
     *     relevance (default) returns the results sorted by relevance to the query search term. The relevance is based on the occurrence of the search term in the items name, description, content, and additional properties.
     *     modified_at returns the results ordered in descending order by date at which the item was last modified.
     * Value is one of modified_at,relevance
     * @var string
     */
    public $sort;
    
    /**
     * example non_trashed_only default "non_trashed_only"
     * Determines if the search should look in the trash for items.
     * By default, this API only returns search results for items not currently in the trash (non_trashed_only).
     *     trashed_only - Only searches for items currently in the trash
     *     non_trashed_only - Only searches for items currently not in the trash
     *     all_items - Searches for both trashed and non-trashed items.
     * Value is one of non_trashed_only,trashed_only,all_items
     * @var string
     */
    public $trash_content;
    
    /**
     * example 2014-05-15T13:35:01-07:00,2014-05-17T13:35:01-07:00
     * Limits the search results to any items updated within a given date range.
     * Date ranges are defined as comma separated RFC3339 timestamps.
     * If the start date is omitted (,2014-05-17T13:35:01-07:00) anything updated before the end date will be returned.
     * If the end date is omitted (2014-05-15T13:35:01-07:00,) the current date will be used as the end date instead.
     * @var string|array
     */
    public $updated_at_range;
    
    
    /**
     * 
     * @return \comcduarte\Box\API\Resource\ClientError|\comcduarte\Box\API\Resource\SearchResults
     */
    public function search_for_content()
    {
        /**
         * @TODO Tabling search.  Does not produce consistent results.  Focusing on Metadata Queries.
         */
        if (!isset($this->query) && !isset($this->mdfilters)) {
            $error = new Resource\ClientError();
            $error->status = '400';
            $error->message = 'missing_parameter - Please provide at least the query or mdfilters query parameter in a search.';
            return $error;
        }
        
        $endpoint = 'https://api.box.com/2.0/search';
        
        $params = [
            
        ];
        
        $this->generate_query();
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                $search_results = new Resource\SearchResults();
                $search_results->hydrate($this->response);
                return $search_results;
            case 400:
                /**
                 * Returns an error when the request was not valid. This can have multiple reasons and the context_info object will provide you with more details.
                 * missing_parameter - Please provide at least the query or mdfilters query parameter in a search.
                 * invalid_parameter - Any of the fields might not be in the right format. This could for example mean that one of the RFC3339 dates is incorrect, or a string is provided where an integer is expected.
                 */
            case 403:
                /**
                 * Returns an error when the user does not have the permission to make this API call.
                 * The developer provided a scope of enterprise_content but did not request this scope to be enabled for the user through our support channels.
                 */
            case 404:
                /**
                 * Returns an error when the user does not have access to an item mentioned in the request.
                 * The developer provided a folder ID in ancestor_folder_ids that either does not exist or the user does not have access to.
                 */ 
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function query_files_by_metadata()
    {
        
    }
    
    /**
     * Create a search using SQL-like syntax to return items that match specific metadata.
     * By default, this endpoint returns only the most basic info about the items for which the query matches. 
     * To get additional fields for each item, including any of the metadata, use the fields attribute in the query.
     * @return MetadataQuerySearchResults|ClientError
     */
    public function query_folders_by_metadata() 
    {
        $endpoint = 'https://api.box.com/2.0/metadata_queries/execute_read';
        $params = [
        ];
        
        $data = [
            'ancestor_folder_id' => $this->ancestor_folder_ids,
            'from' => $this->from,
            'query' => $this->query,
            'query_params' => $this->query_params,
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
                 * invalid_query - Any of the provided body parameters might be incorrect. This can mean the query is incorrect, as well as some cases where the from value does not represent a valid template.
                 * unexpected_json_type - An argument from the query string is not present in query_param. For example, query of name = :name requires the query_param to include a value for the name argument, for example { "name": "Box, Inc" }.
                 */
            case 404:
                /**
                 * Returns an error when a metadata template with the given scope and templateKey can not be found. The error response will include extra details.
                 * instance_not_found - The template was not found. Please make sure to use the full template scope including the enterprise ID, like enterprise_12345.
                 */
            default:
                return $this->error();
        }
    }

    /**
     * Creates the query string used with search_for_content().
     */
    private function generate_query()
    {
        $query = [];
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        /**
         * @var \ReflectionProperty $property
         */
        foreach ($properties as $property) {
            $name = $property->name;
            if (isset($this->$name)) {
                $query[$name] = $this->$name;
            }
        }
        $this->query = $query;
        return;
    }
}