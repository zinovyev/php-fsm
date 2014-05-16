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
    public function __construct()
    {
        $this->context = new Context();
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
    
    public function acceptTransitionByName($name)
    {
        $this->acceptTransition(
            $this->getTransitionByName($name)
        );
        
        return $this;
    }
}