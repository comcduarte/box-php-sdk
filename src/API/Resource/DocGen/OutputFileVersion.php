<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource\DocGen;

use comcduarte\Box\API\Resource\BaseResourceTrait;

class OutputFileVersion
{
    use BaseResourceTrait;
    
    public BoxDocGenBatch $batch;
    public OutputFile $output_file;
    public OutputFileVersion $output_file_version;
    public string $output_type;
    public string $status;
    public TemplateFile $template_file;
    public TemplateFileVersion $template_file_version;
   
    
}