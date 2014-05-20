<?php
namespace FSM\ContextMemento;

/**
 * Originator snapshot
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface MementoInterface
{
    /**
     * Create Originator snapshot
     * 
     * @param string $stateName
     * @param array $properties
     */
    public function __construct($stateName, array $properties = []);
    
    /**
     * Get saved state name
     * 
     * @return string
     */
    public function getStateName();
    
    /**
     * Get saved properties
     * 
     * @return array
     */
    public function getProperties();
}