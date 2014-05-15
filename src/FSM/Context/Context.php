<?php
namespace FSM\Context;

use FSM\State\StateInterface;

/**
 * Default context interface
 * 
 * @author vanya
 */
class Context implements ContextInterface, ContextHasPropertiesInterface
{
    protected $state;
    protected $properties;
    
    
    public function getState()
    {
        return $this->state;
    }
    
    public function getProperties()
    {
        return $this->properties;
    }
    
    public function getProperty($name)
    {
        return $this->properties[$name];
    }
    
    public function setState(StateInterface $state)
    {
        $this->state = $state;
    }
    
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
        
        return $this;
    }
  
    public function delegate()
    {
        
    }
}
