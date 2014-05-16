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
        parent::addTransition(
            new Transition($name),
            $this->getStateByName($sourceStateName),
            $this->getStateByName($targetStateName)
        );
        
        return $this;
    }
}