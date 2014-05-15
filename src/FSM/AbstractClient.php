<?php
namespace FSM;

use FSM\Context\ContextInterface;
use FSM\State\StateInterface;
use FSM\Transition\TransitionInterface;

/**
 * Abstract client.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
abstract class AbstractClient
{
	/**
     * @var \FSM\Context\ContextInterface
     */
	protected $context;
	/**
     * @var \FSM\Context\ContextInterface[]
     */
	protected $states = array();
	/**
     * @var \FSM\Context\ContextInterface[]
     */
	protected $transitions = array();

    /**
     * Get all transitions names
     * 
     * @return array
     */
	public function getTransitionsNames()
    {
		return array_keys($this->transitions);
	}

    /**
     * Select single transition by name
     * 
     * @param string $name Transition name
     * @return \FSM\Transition\TransitionInterface|nulls
     */
	public function getTransitionByName($name)
    {
		if (array_key_exists($name, $this->transitions)) {
			return $this->transitions[$name];
		}

		return null;
	}

	/**
     * Get current state
     * 
     * @return \FSM\State\StateInterface
     */
	public function getCurrentState()
    {
		if (!($this->context instanceof ContextInterfaces)) {
			throw new \Exception('Can not get current state. Context schould be set first.');
		}

		return $this->context->getState();
	}

    /**
     * Select state by name
     * 
     * @param string $name
     * @return \FSM\State\StateInterface|null
     */
	public function getStateByName($name)
    {
        if (array_key_exists($name, $this->states)) {
            return $this->states[$name];
        }


		return null;
	}

    /**
     * Set context
     * 
     * @param \FSM\Context\ContextInterface $context
     * @return \FSM\AbstractClient
     */
	public function setContext(ContextInterface $context)
    {
		$this->context = $context;
		return $this;
	}

    /**
     * Add state
     * 
     * 
     * @param \FSM\State\StateInterface $state
     * @return \FSM\AbstractClient
     */
	public function addState(StateInterface $state)
    {
		if (!($this->context instanceof ContextInterfaces)) {
			throw new \Exception("Context schould be set first.");
		}
        if (!($state->getName())) {
            throw new \Exception("Can not register state without a name.");
        }        
        if ($this->getStateByName($state->getName()) !== null) {
            throw new \Exception("Can not register state. The state with the same name already exists.");
        }        
        if (!($state->isFinite() || $state->isInitial() || $state->isRegular())) {
            throw new \Exception("Can not add state withou a type.");
        }

		$state->setContext($this->context);
		$this->states[$state->getName()] = $state;

		return $this;
	}

    /**
     * Add state
     * 
     * @param \FSM\Transition\TransitionInterface $transition
     * @param \FSM\State\StateInterface $sourceState
     * @param \FSM\State\StateInterface $targetState
     * @return \FSM\AbstractClient
     */
	public function addTransition(TransitionInterface $transition, StateInterface $sourceState, StateInterface $targetState)
    {
		if (!($transition->getName())) {
			throw new \Exception("Can not register transition without a name.");
		}
		if ($this->getTransitionByName($transition->getName()) !== null) {
			throw new \Exception("Can not register transition. The transition with the same name already exists.");
		}
        if (!array_search($sourceState, $this->states) || !array_search($targetState, $this->states)) {
            throw new \Exception("Can not register transition. Wrong state selected as an end point.");
        }

        $transition->setSourceState($sourceState);
        $transition->setTargetState($targetState);
		$this->transitions[$transition->getName()] = $transition;

		return $this;
	}

    /**
     * Accept transition
     * 
     * @param \FSM\Transition\TransitionInterface
     * @return \FSM\AbstractClient
     */
	public function acceptTransition(TransitionInterface $transition)
    {
		if (!array_search($transition, $this->transitions)) {
			throw new \Exception("Error while accepting a transition. Transition doesn't exist for the current client.");
		}
        if (!$transition->isAcceptible()) {
            throw new \Exception("Transition can not be accepted");
        }
        if ($this->getStatesByType(StateInterface::TYPE_FINITE)) {
            throw new \Exception("Can not accept transition. Not any state of type finite exists.");
        }
        if ($this->getStatesByType(StateInterface::TYPE_INITIAL)) {
            throw new \Exception("Can not accept transition. Not any state of type inital exists.");
        }
        if ($this->context->getCurrentState()->isFinite()) {
            throw new \Exception("Can not run transition in the finite state.");
        }

        $transition->accept();

        return $this;
	}

    /**
     * Delegate action call to the context
     */
    public function callContextAction($name, $parameters)
    {
        $this->context->delegate($name, $parameters);
    }

    /**
     * Select states by type
     * 
     * @param string $param
     * @return array $states
     */
    protected function getStatesByType($type)
    {
        $states = [];
        foreach ($this->states as $state) {
            if ($state->getType() === $type) {
                $states[] = $state;
            }
        }

        return $states;
    }
}
