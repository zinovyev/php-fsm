<?php
namespace FSM\ContextMemento;

/**
 * Caretaker is used to store Originators snapshot
 * called Memento (eg. to the DB) and get saved
 * Memento back when is needed.
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface CaretakerInterface
{
    /**
     * Restores saved Memento
     */
    public function getMemento();
    
    /**
     * Store Memento
     * 
     * @param MementoInterface $memento
     */
    public function setMemento(MementoInterface $memento);
}