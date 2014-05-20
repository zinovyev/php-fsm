<?php
namespace FSM\ContextMemento;

/**
 * Source object to be saved
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface OriginatorInterface
{
    /**
     * Create Memento of the current State
     * 
     * @return \FSM\Memento\MementoInterface
     */
    public function createMemento();
    
    /**
     * Restore State stored in Memento
     * 
     * @return \FSM\Memento\OriginatorInterface
     */
    public function applyMemento(MementoInterface $memento);
}