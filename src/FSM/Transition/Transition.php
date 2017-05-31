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
     * @see \FSM\Transition\TransitionInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @see \FSM\Transition\TransitionInterface::setName()
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @see \FSM\Transition\TransitionInterface::getSourceState()
     */
    public function getSourceState()
    {
        return $this->sourceState;
    }
    
    /**
     * @see \FSM\Transition\TransitionInterface::setSourceState()
     */
    public function setSourceState(StateInterface $state)
    {
        if (
            $this->targetState instanceof StateInerface
            && $this->targetState->getContext() !== $state->getContext()
        ) {
            throw new \InvalidArgumentException(
                "Invalid source state applied for transaction. The source "
                . "State context MUST be attached to the same Context class as "
                . "the target State."
            );
        }

        $this->sourceState = $state;
        return $this;
    }
    
    /**
     * 
     * @see \FSM\Transition\TransitionInterface::getTargetState()
     */
    public function getTargetState()
    {
        return $this->targetState;
    }
    
    /**
     * @see \FSM\Transition\TransitionInterface::setTargetState()
     */
    public function setTargetState(StateInterface $state)
    {
        if (
        $this->sourceState instanceof StateInerface
        && $this->sourceState->getContext() !== $state->getContext()
        ) {
            throw new \InvalidArgumentException(
                "Invalid target state applied for transaction. The target "
                . "State context MUST be attached to the same Context class as "
                . "the source State."
            );
        }
        

        $this->targetState = $state;
        return $this;
    }
    
    /**
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
     * @see \FSM\Transition\TransitionInterface::accept()
     */
    public function accept()
    {
        if ($this->isAcceptible()) {
            $this->sourceState
                ->getContext()
                ->setState(
                    $this->targetState
                );

            return $this;
        }
        
       throw new \BadMethodCallException(
	       "Current transition can not be accepted"
       );
    }
}