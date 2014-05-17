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
    /**
     * @var \FSM\State\StateInterface;
     */
    protected $state;

    /**
     * Context properties
     * 
     * @var array;
     */
    protected $properties;
    
    /**
     * @see \FSM\Context\ContextInterface::getState()
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * @see \FSM\Context\ContextHasPropertiesInterface::getProperties()
     */
    public function getProperties()
    {
        return $this->properties;
    }
    
    /**
     * @see \FSM\Context\ContextHasPropertiesInterface::getProperty()
     */
    public function getProperty($name)
    {
        return $this->properties[$name];
    }
    
    /**
     * @see \FSM\Context\ContextInterface::setState()
     */
    public function setState(StateInterface $state)
    {
        $this->state = $state;
    }
    
    /**
     * @see \FSM\Context\ContextHasPropertiesInterface::setProperty()
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
        
        return $this;
    }
  
    /**
     * @see \FSM\Context\ContextInterface::delegateAction()
     */
    public function delegateAction($name, array $parameters = array())
    {
        return $this->state->handleAction($name, $parameters);
    }
}
