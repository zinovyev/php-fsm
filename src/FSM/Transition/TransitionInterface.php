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
     * Check if transition is acceptible for the current context state
     */
    public function isAcceptible();

    /**
     * Bind source state
     * 
     * @param FSM\State\StateInterface
     */
    public function setSourceState(StateInterface $state);

    /**
     * Bind target state
     * 
     * @param FSM\State\StateInterface
     */
    public function setTargetState(StateInterface $state);

    /**
     * Accept transition
     */
    public function accept();
}