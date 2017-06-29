<?php

namespace FSM\Transition;

use FSM\State\StateInterface;

interface TransitionInterface
{

    /**
     * Get transition name
     * 
     * @return string
     */
    public function getName();

    /**
     * Set transition name
     * 
     * @return \FSM\Transition\TransitionInterface
     */
    public function setName($name);

    /**
     * Get source state of the transition
     * 
     * @return FSM\State\StateInterface
     */
    public function getSourceState();

    /**
     * Bind source state
     * 
     * @param \FSM\State\StateInterface
     */
    public function setSourceState(StateInterface $state);

    /**
     * Get target state of the transition
     * 
     * @return FSM\State\StateInterface
     */
    public function getTargetState();

    /**
     * Bind target state
     * 
     * @param \FSM\State\StateInterface
     */
    public function setTargetState(StateInterface $state);

    /**
     * Check if transition is acceptible for the current context state
     * 
     * @return boolean
     */
    public function isAcceptible();

    /**
     * Accept transition
     * 
     * @return \FSM\Transition\TransitionInterface
     */
    public function accept();
}
