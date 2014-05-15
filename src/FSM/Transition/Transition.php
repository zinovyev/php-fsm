<?php
namespace FSM\Transition;

/**
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
     * 
     * @var unknown
     */
    protected $sourceState;
    protected $targetState;
}