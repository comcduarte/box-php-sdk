<?php
namespace comcduarte\Box\API\Form;

use comcduarte\Box\API\Resource\MetadataTemplate;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\InputFilter\InputFilter;

class MetadataForm extends Form
{
    /**
     * 
     * @var MetadataTemplate
     */
    public $metadata_template;
    
    public function init()
    {
        $inputFilter = new InputFilter();
        
        foreach ($this->metadata_template->fields as $field) {
            switch ($field->type) {
                case 'enum':
                    $array = explode(',', $field->options);
                    $this->add([
                        'name' => $field->key,
                        'type' => Select::class,
                        'attributes' => [
                            'id' => $field->key,
                            'class' => 'form-select',
                            'required' => true,
                        ],
                        'options' => [
                            'label' => $field->displayName,
                            'value_options' => array_combine($array, $array),
                        ],
                    ]);
                    break;
                case 'date':
                    $this->add([
                        'name' => $field->key,
                        'type' => Date::class,
                        'attributes' => [
                            'id' => $field->key,
                            'class' => 'form-control',
                            'required' => true,
                        ],
                        'options' => [
                            'label' => $field->displayName,
//                             'format' => 'c',
                        ],
                    ]);
                    
//                     $input = new Input($field->key);
//                     $input->getFilterChain()->attachByName('DateTimeFormatter',
//                         [
//                             'options' => [
//                                 'format' => 'c'
//                             ],
//                         ]);
//                     $inputFilter->add($input);
                    break;
                case 'multiSelect':
                case 'string':
                case 'float':
                default:
                    $this->add([
                        'name' => $field->key,
                        'type' => Text::class,
                        'attributes' => [
                            'id' => $field->key,
                            'class' => 'form-control',
                            'required' => true,
                        ],
                        'options' => [
                            'label' => $field->displayName,
                        ],
                    ]);
                    break;
            }
        }
            
        $this->add([
            'name' => 'FILE',
            'type' => File::class,
            'attributes' => [
                'id' => 'FILE',
                'class' => 'form-control',
                'required' => true,
            ],
            'options' => [
                'label' => 'Upload File',
            ],
        ]);
        
        $this->add([
            'name' => 'FILE_ID',
            'type' => Hidden::class,
            'attributes' => [
                'id' => 'FILE_ID',
            ],
        ]);
        
        $this->add(new Csrf('SECURITY'));
        
        $this->add([
            'name' => 'SUBMIT',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-primary',
                'id' => 'SUBMIT',
            ],
        ]);
        
        /**
         * Add Template Key Hidden Field
         */
        $this->add([
            'name' => 'template_key',
            'type' => Hidden::class,
            'attributes' => [
                'id' => 'template_key',
                'class' => 'form-control',
                'value' => $this->metadata_template->templateKey,
            ],
            'options' => [
                
            ],
        ],['priority' => 0]);
        
        
        
        $this->setInputFilter($inputFilter);
    }
    
    public function getMetadataTemplate()
    {
        return $this->metadata_template;
    }
    
    public function setMetadataTemplate($template)
    {
        $this->metadata_template = $template;
        return $this;
    }
    
    
}