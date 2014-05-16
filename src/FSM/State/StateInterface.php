<?php
namespace FSM\State;

use FSM\Context\ContextInterface;

/**
 * Basic State interface
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface StateInterface
{
    /**
     * @const Initial state type
     */
    const TYPE_INITIAL = 1;

    /**
     * @const Regular state type
     */    
    const TYPE_REGULAR = 2;

    /**
     * @const Finite state type
     */    
    const TYPE_FINITE  = 3;

    /**
     * Get state name
     * 
     * @return string
     */
    public function getName();

    /**
     * Get transition type
     * 
     * @return integer
     */
    public function getType();
    
    /**
     * Get related context
     * 
     * @return \FSM\Context\ContextInterface
     */
    public function getContext();

    /**
     * Check if state is of type initial
     * 
     * @return boolean
     */
    public function isInitial();

    /**
     * Check if state is of type regular
     * 
     * @return boolean
     */    
    public function isRegular();

    /**
     * Check if state is of type finite
     * 
     * @return boolean
     */    
    public function isFinite();

    /**
     * Bind context
     * 
     * @param \FSM\Context\ContextInterface $context
     * @return \FSM\State\StateInterface
     */
    public function setContext(ContextInterface $context);

    /**
     * Handle state action
     */
    public function handleAction($name, $properties = array());
}