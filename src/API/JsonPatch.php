<?php 
namespace Laminas\Box\API;

class JsonPatch
{
    /**
     * Array of operations
     * @var array
     */
    public $operations = [];
    
    public function add(string $path, string $value)
    {
        $this->operations[] = [
            'op' => 'add',
            'path' => $path,
            'value' => $value,
        ];
    }
    
    public function copy(string $from, string $path)
    {
        $this->operations[] = [
            'op' => 'copy',
            'from' => $from,
            'path' => $path,
        ];
    }
    
    public function move(string $from, string $path)
    {
        $this->operations[] = [
            'op' => 'copy',
            'from' => $from,
            'path' => $path,
        ];
    }
    
    public function replace(string $path, string $value)
    {
        $this->operations[] = [
            'op' => 'replace',
            'path' => $path,
            'value' => $value,
        ];
    }
    
    public function remove(string $path)
    {
        $this->operations[] = [
            'op' => 'remove',
            'path' => $path,
        ];
    }
    
    public function test(string $path, string $value)
    {
        $this->operations[] = [
            'op' => 'test',
            'path' => $path,
            'value' => $value,
        ];
    }
}