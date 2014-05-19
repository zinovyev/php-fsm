<?php
namespace FSM;

use FSM\Context\ContextWithPropertiesInterface;
use FSM\State\StateInterface;
use FSM\Transition\TransitionInterface;
use FSM\Exception\ClientInitializationException;
use FSM\Exception\ComponentConfigurationException;
use FSM\Exception\StateExecutionException;

/**
 * Abstract client.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
abstract class AbstractClient
{
	/**
     * @var \FSM\Context\ContextInterface|null
     */
	protected $context = null;
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
	 * @todo Context interface schould be checked
	 * @throws ClientInitializationException
	 * @return \FSM\State\StateInterface
	 */
	public function getCurrentState()
    {   
		if (!is_object($this->context)) {
			throw new ClientInitializationException(
			    "Can not get current state. Context schould be set first."
            );
		}

		return $this->context
		  ->getState();
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
     * @param \FSM\Context\ContextWithPropertiesInterface $context
     * @return \FSM\AbstractClient
     */
	public function setContext(ContextWithPropertiesInterface $context)
    {
		$this->context = $context;
		return $this;
	}

    /**
     * Add state
     * 
     * @todo Context interface schould be checked
     * @param \FSM\State\StateInterface $state
     * @throws \FSM\Exception\ClientInitializationException
     * @return \FSM\AbstractClient
     */
	public function addState(StateInterface $state)
    {        
		if (!is_object($this->context)) {
			throw new ClientInitializationException("Context schould be set first.");
		} elseif (!($state->getName())) {
            throw new ClientInitializationException("Can not register state without a name.");
        } elseif ($this->getStateByName($state->getName()) !== null) {
            throw new ClientInitializationException(
                "Can not register state. The state with the same name already exists."
            );
        } elseif (!($state->isFinite() || $state->isInitial() || $state->isRegular())) {
            throw new ClientInitializationException("Can not add state without a type.");
        }

		$state->setContext($this->context);
		$this->states[$state->getName()] = $state;

		return $this;
	}

    /**
     * Add transition
     * 
     * @param \FSM\Transition\TransitionInterface $transition
     * @param \FSM\State\StateInterface $sourceState
     * @param \FSM\State\StateInterface $targetState
     * @return \FSM\AbstractClient
     */
	public function addTransition(TransitionInterface $transition, StateInterface $sourceState, StateInterface $targetState)
    {
		if (!($transition->getName())) {
			throw new ClientInitializationException("Can not register transition without a name.");
		} elseif ($this->getTransitionByName($transition->getName()) !== null) {
			throw new ClientInitializationException(
			    "Can not register transition. The transition with the same name already exists."
            );
		} elseif (!array_search($sourceState, $this->states) || !array_search($targetState, $this->states)) {
            throw new ClientInitializationException(
                "Can not register transition. Wrong state selected as an end point."
            );
        }

        // Add source and target states
        try {
            $transition->setSourceState($sourceState);
            $transition->setTargetState($targetState);
        } catch (\Exception $ex) {
            throw new ComponentConfigurationException("Can not assign source", 0, $ex);
        }
        
		$this->transitions[$transition->getName()] = $transition;

		return $this;
	}

	/**
	 * Apply initial state
	 * s
	 * @param \FSM\State\StateInterface $state
	 * @throws \FSM\Exception\ClientInitializationException
	 */
	public function setInitialState(StateInterface $state)
	{
	    if (!$state->isInitial()) {
	        throw new ClientInitializationException("The first State should be of type Initial.");
	    } elseif (!in_array($state, $this->states)) {
	        throw new ClientInitializationException(
	            "Can not apply state as initial. "
	            . "The state was never registered."
            );
	    }
	    
	    $this->context
	       ->setState($state);
	}
	
    /**
     * Accept transition
     * 
     * @param \FSM\Transition\TransitionInterface
     * @return \FSM\AbstractClient
     */
	
	/**
	 * Accept transition
	 * 
	 * @param \FSM\Transition\TransitionInterface $transition
	 * @throws \FSM\Exception\ClientInitializationException
	 * @throws \FSM\Exception\ComponentConfigurationException
	 * @return \FSM\AbstractClient
	 */
	public function acceptTransition(TransitionInterface $transition)
    {
		if (!array_search($transition, $this->transitions)) {
			throw new ClientInitializationException(
			    "Error while accepting a transition. Transition doesn't exist for the current Client."
	        );
        } elseif (!$this->getStatesByType(StateInterface::TYPE_FINITE)) {
            throw new ClientInitializationException(
                "Can not accept transition. Not any state of type finite exists."
	        );
        } elseif (!$this->getStatesByType(StateInterface::TYPE_INITIAL)) {
            throw new ClientInitializationException(
                "Can not accept transition. Not any state of type inital exists."
	        );
        } elseif (!($this->context->getState() instanceof StateInterface)) {
            throw new ClientInitializationException(
                "The initial state should be set first."
	        );
        } elseif ($this->context->getState()->isFinite()) {
            throw new ClientInitializationException(
                "Can not run transition in the finite state."
	        );
        } elseif (!$transition->isAcceptible()) {
            throw new ClientInitializationException(
                "Transition can not be accepted"
            );
        }

        // Accept transition
        try {
            $transition->accept();
        } catch (\Exception $ex) {
            throw new ComponentConfigurationException("Can not accept Transition", 0, $ex);
        }
        
        return $this;
	}

	/**
	 * Delegate action call to the context
	 * 
	 * @param string $name
	 * @param array $parameters
	 * @throws \FSM\Exception\ComponentConfigurationException
	 * @throws \FSM\Exception\StateExecutionException
	 * @return mixed
	 */
    public function callAction($name, array $parameters = [])
    {   
        try {
            return $this->context
            ->delegateAction($name, $parameters);
        } catch(\BadMethodCallException $bex) {
            throw new ComponentConfigurationException(
                "Can not call method",
                0,
                $bex
            );
        } catch (\Exception $ex) {
            throw new StateExecutionException(
                "Exception occred while executing a State action.",
                0,
                $ex
            );
        }
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
                $states[$state->getName()] = $state;
            }
        }

        return $states;
    }
}
