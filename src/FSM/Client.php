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
     * @see \FSM\AbstractClient::addTransition()
     */
    public function addTransition($name, $sourceStateName, $targetStateName)
    {
        $transition = new Transition(
	        $name,
            $this->getStateByName($targetStateName),
            $this->getStateByName($sourceStateName)
        );
        
        parent::addTransition(new Transition(), $sourceState, $targetState);
        
        return $this;
    }
}