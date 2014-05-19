<?php
namespace FSM;

use FSM\Transition\Transition;
use FSM\Context\Context;

/**
 * Default client implementation.
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Client extends AbstractClient
{
    /**
     * Create new context object
     */
    public function __construct()
    {
        $this->context = new Context();
    }

    /**
     * Call state function
     * 
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function __call($name, array $parameters = [])
    {
        return $this->callAction($name, $parameters);
    }
    
    /**
     * Set property
     * 
     * @param string $name
     * @param mixed $value
     * @return \FSM\Client
     */
    public function __set($name, $value)
    {
        $this->context
            ->addProperty($name, $value);
        
        return $this;
    }
    
    /**
     * Get property
     * 
     * @param string $name
     */
    public function __get($name) {
        return $this->getProperty($name);
    }
    
    /**
     * Transition factory
     * 
     * @param string $name
     * @param string $sourceStateName
     * @param string $targetStateName
     * @return \FSM\Client
     */
    public function createTransition($name, $sourceStateName, $targetStateName)
    {
        $this->addTransition(
            new Transition($name),
            $this->getStateByName($sourceStateName),
            $this->getStateByName($targetStateName)      
        );
        
        return $this;
    }
    
    /**
     * Accept transaction using its name
     * 
     * @param string $name
     * @return \FSM\Client
     */
    public function acceptTransitionByName($name)
    {
        $this->acceptTransition(
            $this->getTransitionByName($name)
        );
        
        return $this;
    }
}