<?php
namespace FSM\Transition;

use FSM\State\StateInterface;

/**
 * Default Transition class
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Transition implements TransitionInterface
{
    /**
     * Transition name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Source state
     * 
     * @var \FSM\State\StateInterface
     */
    protected $sourceState = null;
    
    /**
     * Target state
     * 
     * @var \FSM\State\StateInterface
     */
    protected $targetState = null;
    
    public function __construct($name = null, StateInterface $targetState = null, StateInterface $sourceState = null)
    {
        if ($name) $this->setName($name);
        if ($targetState) $this->setTargetState($targetState);
        if ($sourceState) $this->setSourceState($sourceState);
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::setName()
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::setSourceState()
     */
    public function setSourceState(StateInterface $state)
    {
        if (
            $this->targetState instanceof StateInerface
            && $this->targetState->getContext() !== $state->getContext()
        ) {
            throw new \Exception('Invalid source state applied for transaction');
        }

        $this->sourceState = $state;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::setTargetState()
     */
    public function setTargetState(StateInterface $state)
    {
        if (
        $this->sourceState instanceof StateInerface
        && $this->sourceState->getContext() !== $state->getContext()
        ) {
            throw new \Exception('Invalid target state applied for transaction');
        }
        

        $this->targetState = $state;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::isAcceptible()
     */
    public function isAcceptible()
    {
        return
            $this->sourceState instanceof StateInterface
            && $this->targetState instanceof StateInterface
            && ($this->sourceState->getContext()->getState() === $this->sourceState);
    }
    
    /**
     * (non-PHPdoc)
     * @see \FSM\Transition\TransitionInterface::accept()
     */
    public function accept()
    {
        $this->sourceState->getContext()->setState($this->targetState);
    }
}