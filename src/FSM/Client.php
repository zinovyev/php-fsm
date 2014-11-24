<?php
namespace FSM;

use Doctrine\Common\Collections\ArrayCollection;
use FSM\Exception\LogicException;
use FSM\State\StateInterface;

/**
 * Basic Client.
 * Actually this is a complete Finite State Machine
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Client implements ClientInterface
{
    /**
     * States
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $states;

    /**
     * Transitions
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $transitions;

    /**
     * Current state name
     *
     * @var string
     */
    protected $currentState = State::TYPE_INITIAL;

    public function __constructor()
    {
        $this->states = new ArrayCollection();
        $this->transitions = new ArrayCollection();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::addState()
     */
    public function addState($stateAlias, StateInterface $state)
    {
        if ($this->hasState($stateAlias)) {
            throw new LogicException(sprintf(
                "States overlapping! The state %s already exists",
                $stateAlias
            ));
        }

        // Check if there're no other initial states
        if ($state->isInitial()) {
            {
                throw new LogicException(
                    "Only one initial state should be used!"
            );
        }
        }
        $this->states->set($stateAlias, $state);

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::hasState()
     */
    public function hasState($stateAlias)
    {
        return $this->states->containsKey($stateAlias);
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::removeState()
     */
    public function removeState($stateAlias)
    {
        if (!$this->hasState($stateAlias)) {
            return $this;
        }

        // Drop all related transitions
        $this->transitions = $this->transitions
            ->filter(function ($transition) use ($stateAlias) {
                return !in_array($stateAlias, $transition);
            });

        $this->states->remove($stateAlias);
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getStates()
     */
    public function getStates()
    {
        return $this->states->getKeys();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getCurrentState()
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::isAtInit=ialState()
     */
    public function isAtInitialState()
    {
        $currentState = $this->getCurrentState();
        return $this->states->get($currentState)->isInitial();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::isAtRegularState()
     */
    public function isAtRegularState()
    {
        $currentState = $this->getCurrentState();
        return $this->states->get($currentState)->isRegular();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::isAtFinalState()
     */
    public function isAtFinalState()
    {
        $currentState = $this->getCurrentState();
        return $this->states->get($currentState)->isFinal();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::addTransition()
     */
    public function addTransition($transitionAlias, $sourceStateAlias, $targetStateAlias)
    {
        if (
            !$this->hasState($sourceStateAlias)
            || !$this->hasState($targetStateAlias)
        ) {
            throw new LogicException(
                "Couldn't create a transaction because"
                . " one of the states doesn't exist for the client"
            );
        }

        if ($this->hasTransition($transitionAlias)) {
            throw new LogicException("Transition already exists");
        }

        $this->transitions->set($transitionAlias, [
            $sourceStateAlias,
            $targetStateAlias
        ]);

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::hasTransition()
     */
    public function hasTransition($transitionAlias)
    {
        return $this->transitions->containsKey($transitionAlias);
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::removeTransition()
     */
    public function removeTransition($transitionAlias)
    {
        $this->transitions->remove($transitionAlias);
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getTransitions()
     */
    public function getTransitions()
    {
        return $this->transitions->getKeys();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getTransitionsBySourceState()
     */
    public function getTransitionsBySourceState($stateAlias)
    {
        return $this->transitions->filter(function ($transition) use ($stateAlias) {
            return $transition[0] == $stateAlias;
        })->getKeys();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getTransitionsByTargetState()
     */
    public function getTransitionsByTargetState($stateAlias)
    {
        return $this->transitions->filter(function ($transition) use ($stateAlias) {
            return $transition[1] == $stateAlias;
        })->getKeys();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::getAvailableTransitions()
     */
    public function getAvailableTransitions()
    {
        $currentState = $this->getCurrentState();
        return $this->getTransitionsBySourceState($currentState);
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::isTransitionApplicable()
     */
    public function isTransitionApplicable($transitionAlias)
    {
        $availableTransitions = $this->getAvailableTransitions();

        return in_array(
            $transitionAlias,
            $availableTransitions
        );
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::isTransitionAvailable()
     */
    public function isTransitionAvailable($transitionAlias)
    {
        return $this->isTransitionApplicable($transitionAlias);
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::applyTransition()
     */
    public function applyTransition($transitionAlias)
    {
        if (!$this->isTransitionApplicable($transitionAlias)) {
            throw new LogicException(
                "Transition couldn't be applied."
            );
        }

        // Set transition target state as current
        $transition = $this->transitions->get($transitionAlias);
        $this->currentState = $transition[1];

        return $this;
    }

    /**
     * @return boolean
     */
    protected function verifyInitialStateExists()
    {
        $initialStates = $this->states->filter(function ($state) {
            return $state->isInitial();
        });

        return !$initialStates->isEmpty();
    }

    /**
     * @return boolean
     */
    protected function verifyFinalStateExists()
    {
        $initialStates = $this->states->filter(function ($state) {
            return $state->isFinal();
        });

        return !$initialStates->isEmpty();
    }

    /**
     * (non-PHPdoc)
     * @see \FSM\ClientInterface::verify()
     */
    public function verify()
    {
        if (
            !$this->states->isEmpty()
            && !$this->transitions->isEmpty()
            && $this->verifyInitialStateExists()
            && $this->verifyFinalStateExists()
        ) {
            return true;
        }

        return false;
    }
}