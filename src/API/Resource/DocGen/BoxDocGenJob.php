<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\DocGen;

use comcduarte\Box\API\Enum\ResourceType;
use comcduarte\Box\API\Enum\StatusType;
use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Resource\BaseResource;
use comcduarte\Box\API\Resource\ClientError;

class BoxDocGenJob extends AbstractResource
{

    public BoxDocGenBatch $batch;

    public string $create_at;

    public BaseResource $create_by;

    public BaseResource $enterprise;

    public BaseResource $output_file;

    public BaseResource $output_file_version;

    public ResourceType $output_type;

    public string $source;

    public StatusType $status;

    public BaseResource $template_file;

    public BaseResource $template_file_version;

    public string $_version = '2025.0';

    public function list_all_doc_gen_jobs(): BoxDocGenJobs|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_jobs';
        $params = [ // -- No Parameters Required --//
        ];

        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);

        switch ($this->response->getStatusCode()) {
            case 200:
                /**
                 * A list of Box Doc Gen jobs.
                 */
                $jobs = new BoxDocGenJobs();
                $jobs->hydrate($this->response);
                return $jobs;
            case 400:
            /**
             * Returned if the user has passed in a malformed marker or limit value.
             */
            case 403:
            /**
             * The client does not have access rights to the content or resource requested.
             */
            case 429:
            /**
             * The user has sent too many requests in a given amount of time.
             */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function get_box_doc_gen_job(string $job_id): self|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_jobs/:job_id';
        $params = [
            ':job_id' => $job_id
        ];

        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);

        switch ($this->response->getStatusCode()) {
            case 200:
                /**
                 * Details of the Box Doc Gen job.
                 */
                $this->hydrate($this->response);
                return $this;
            case 403:
            /**
             * The client does not have access rights to the content or resource requested.
             */
            case 404:
            /**
             * Returned if the job is not found or the user does not have access to the associated job.
             */
            case 429:
            /**
             * The user has sent too many requests in a given amount of time.
             */
            default:
                return $this->error();
        }
    }
    
    public function get_box_doc_gen_job_by_batch(BoxDocGenBatch $batch): BoxDocGenJobs | ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_batch_jobs/:batch_id';
        $params = [
            'batch_id' => $batch->id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode()) {
            case 200:
                /**
                 * Returns a list of Box Doc Gen jobs in a Box Doc Gen batch.
                 */
                $jobs = new BoxDocGenJobs();
                $jobs->hydrate($this->response);
                return $jobs;
            case 400:
                /**
                 * Returned if the user has passed in a malformed marker or limit value.
                 */
            case 403:
                /**
                 * The client does not have access rights to the content or resource requested.
                 */
            case 404:
                /**
                 * Returned if the job is not found or the user does not have access to the associated job.
                 */
            case 429:
                /**
                 * The user has sent too many requests in a given amount of time.
                 */
            default:
                return $this->error();
        }
    }

    /**
     *
     * @param BaseResource $destination_folder
     * @param array $document_generation_data
     * @param BaseResource $file
     *            A Box Doc Gen template that is used to generate the document.
     */
    public function generate_document(BaseResource $destination_folder, array $document_generation_data, BaseResource $file, string $input_source, string $output_type)
    {
        $endpoint = 'https://api.box.com/2.0/docgen_batches';
        $params = [];

        $data = [
            'destination_folder' => $destination_folder,
            'document_generation_data' => $document_generation_data,
            'file' => $file,
            'input_source' => $input_source,
            'output_type' => $output_type
        ];

        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->post($uri, $data);

        switch ($this->response->getStatusCode()) {
            case 202:
                /**
                 * Returns the created batch ID (Base)
                 */
                $batch = new BoxDocGenBatch();
                $batch->hydrate($this->response);
                return $batch;
            case 403:
            /**
             * The client does not have access rights to the content or resource requested.
             */
            case 429:
            /**
             * The user has sent too many requests in a given amount of time.
             */
            default:
                return $this->error();
        }
    }
}