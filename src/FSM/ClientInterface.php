<?php
namespace FSM;

use FSM\State\StateInterface;

/**
 * Interface ClientInterface.
 * Basic interface that describes most of the Finite State Machine futures.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ClientInterface
{
    /**
     * Add a new state.
     * If the alias already exists an Exception (LogicException) will be invoked.
     * The reserved words ('initial' and 'final') also should not be used.
     *
     * @param string $stateAlias
     * @param \FSM\State\StateInterface $state
     * @throws \FSM\Exception\LogicException
     */
    public function addState($stateAlias, StateInterface $state);

    /**
     * Check if an alias already exists.
     *
     * @param string $stateAlias
     * @return boolean
     */
    public function hasState($stateAlias);

    /**
     * Remove state and all corresponding transitions.
     *
     * @param string $stateAlias
     * @return \FSM\ClientInterface
     */
    public function removeState($stateAlias);

    /**
     * Get an array of aliases of the registered states.
     *
     * @return array
     */
    public function getStates();

    /**
     * Get current state alias.
     *
     * @return string
     */
    public function getCurrentState();

    /**
     * Check if current state is an initial one.
     *
     * @return boolean
     */
    public function isAtInitialState();

    /**
     * Check if the current state
     * is of a regular type (neither initial nor final).
     *
     * @return boolean
     */
    public function isAtRegularState();

    /**
     * Check if current state is final.
     *
     * @return boolean
     */
    public function isAtFinalState();

    /**
     * Create a transition.
     *
     * @param string $transitionAlias
     * @param string $sourceStateAlias
     * @param string $targetStateAlias
     * @return \FSM\ClientInterface
     * @throws \FSM\Exception\LogicException
     */
    public function addTransition($transitionAlias, $sourceStateAlias, $targetStateAlias);

    /**
     * Check if transition exists.
     *
     * @param string $transitionAlias
     * @return boolean
     */
    public function hasTransition($transitionAlias);

    /**
     * Remove a transition.
     *
     * @param string $transitionAlias
     * @return \FSM\ClientInterface
     */
    public function removeTransition($transitionAlias);

    /**
     * Get list of available transitions.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTransitions();

    /**
     * Get transitions, starting at the concrete state.
     *
     * @param string $stateAlias
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTransitionsBySourceState($stateAlias);

    /**
     * Get transitions, ending with the concrete state.
     *
     * @param string $stateAlias
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTransitionsByTargetState($stateAlias);

    /**
     * Get available (for the current state) transitions.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAvailableTransitions();

    /**
     * Check if transition is available the current state.
     *
     * @param string $transitionAlias
     * @return boolean
     */
    public function isTransitionApplicable($transitionAlias);

    /**
     * Check if transition is available the current state.
     * Pseudonym for the ClientInterface::isTransitionAvailable() method.
     *
     * @param string $transitionAlias
     * @return boolean
     */
    public function isTransitionAvailable($transitionAlias);

    /**
     * Apply the transition.
     * If the transition couldn't be applied an
     * exception (LogicalException) will be invoked.
     *
     * @param string $transitionAlias
     * @return \FSM\ClientInterface
     * @throws \FSM\Exception\LogicException
     */
    public function applyTransition($transitionAlias);

    /**
     * Check if client is well configured and ready for use.
     * @return boolean
     */
    public function verify();
}