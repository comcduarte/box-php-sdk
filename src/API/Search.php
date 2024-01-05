<?php
namespace Laminas\Box\API;

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
    
    const SCOPE_USER         = 'user_conent';
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
    
    
    public $deleted_user_ids;
    
    
    public $direction;
    
    public $file_extensions;
    
    public $include_recent_shared_links;
    
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
    
    public $owner_user_ids;
    
    /**
     * 
     * @var string
     */
    public $query;
    
    public $recent_updater_user_ids;
    
    /**
     * Limits the search results to either the files that the user has access to, or to files available to the entire enterprise.
     * The scope defaults to user_content, which limits the search results to content that is available to the currently authenticated user.
     * The enterprise_content can be requested by an admin through our support channels. Once this scope has been enabled for a user, it will allow that use to query for content across the entire enterprise and not only the content that they have access to.
     * @var string
     */
    public $scope;
    
    public $size_range;
    
    public $sort;
    
    public $trash_content;
    
    public $updated_at_range;
    
    /**
     * 
     * @return \Laminas\Box\API\Resource\ClientError|\Laminas\Box\API\Resource\SearchResults
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
                $error = new Resource\ClientError();
                $error->hydrate($this->response);
                return $error;
        }
    }

    public function query($query) 
    {
        $this->query = ['query' => $query];
        return $this;
    }
}