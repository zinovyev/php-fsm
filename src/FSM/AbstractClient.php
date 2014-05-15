<?php
namespace FSM;

use FSM\Context\ContextInterface;
use FSM\State\StateInterface;
use FSM\Transition\TransitionInterface;

/**
 * Abstract client
 * 
 */
abstract class AbstractClient
{
    /**
     * @var \FSM\Context\ContextInterface[]
     */
    protected $context;

    /**
     * @var \FSM\Context\ContextInterface[]
     */    
    protected $states;

    /**
     * @var \FSM\Context\ContextInterface
     */
    protected $transitions;


    public function getTransitionsNames()
    {
        $names = [];
        foreach ($this->transactions as $transaction) {
            $names[] = $transaction->getName();
        }

        return $names;
    }

    public function getTransitionByName($name)
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getName() === $name) {
                return $transaction;
            }
        }

        return null;
    }

    public function getCurrentState()
    {
        return $this->context->getState();
    }

    public function getStateByName($name)
    {

        foreach ($this->states as $state) {
            if ($state->getName() === $name) {
                return $state;
            }
        }   

        return null;
    }

    public function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    public function addState(StateInterface $state)
    {
        if (!($this->context instanceof ContextInterfaces)) {
            throw new \Exception('Context schould be set first.');
        }

        $state->setContext($context);
        $this->states[] = $state;

        return $this;
    }

    public function addTransition(TransitionInterface $transition)
    {
        if (!($this->context instanceof ContextInterfaces)) {
            throw new \Exception('Can not register transition. Context schould be set first.');
        }

        if (!($transition->getName())) {
            throw new \Exception('Can not register transition without a name.');
        }

        if ($this->getTransitionByName($transition->getName()) !== null) {
            throw new \Exception('Can not register transition. Transition with the same name already exists.');
        }

        $this->transitions[$transition->getName()] = $transition;

        return $this;
    }

    public function acceptTransition()
    {

    }
}