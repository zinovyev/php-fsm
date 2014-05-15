<?php
namespace FSM\Context;

use FSM\State\StateInterface;

interface ContextInterface
{
    public function getState();
    public function setState(StateInterface $state);
    public function request();
}