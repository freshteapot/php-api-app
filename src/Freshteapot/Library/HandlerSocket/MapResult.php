<?php
namespace Freshteapot\Library\HandlerSocket;

class MapResult extends \CachingIterator
{
    static protected $instance;

    private $map;
    private $mappedData;
    function __construct($map, $data)
    {
        $this->map = $map;
        $it=new \RecursiveArrayIterator(new \ArrayObject($data) );
        /*** pass the iterator to the parent ***/
        parent::__construct($it);
        foreach ($this as $v);
    }

    public function current()
    {
        $row = parent::current();
        $item = array();
        foreach ( $this->map as $index => $key ) {
            $item[$key] = $row[$index];
        }
        $this->mappedData[] = $item;
    }

    function fetchAll()
    {
        return $this->mappedData;
    }

    function fetch()
    {
        if (isset($this->mappedData["0"])) {
            return $this->mappedData["0"];
        } else {
            return NULL;
        }
    }
} /*** end of class ***/
