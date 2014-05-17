<?php
namespace FSM\Context;

use FSM\State\StateInterface;

/**
 * Basic context interface
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ContextInterface
{
    /**
     * Get current state
     * 
     * @return \FSM\State\StateInterface
     */
    public function getState();

    /**
     * Set current state
     * 
     * @param \FSM\State\StateInterface $state
     * @return \FSM\Context\ContextInterface
     */
    public function setState(StateInterface $state);

    /**
     * Delegate action handle to the current state
     * 
     * @param string $name
     * @param array $properties
     * @return mixed
     */
    public function delegateAction($name, array $properties);
}