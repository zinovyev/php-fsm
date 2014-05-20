<?php
namespace FSM\ContextMemento;

class Memento implements MementoInterface
{
    /**
     * State name
     * 
     * @var string
     */
    protected $stateName;
    
    /**
     * Context properties
     * 
     * @var array
     */
    protected $properties;
    
    /**
     * @param string $stateName
     * @param array $properties
     */
    public function __construct($stateName, array $properties = [])
    {
        $this->stateName = $stateName;
        $this->properties = $properties;
    }
   
    /**
     * @see \FSM\ContextMemento\MementoInterface::getStateName()
     */
    public function getStateName()
    {
        return $this->stateName;
    }
    
    /**
     * @see \FSM\ContextMemento\MementoInterface::getProperties()
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
