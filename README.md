php-fsm
=======

# PHP Finite-state Machine

Finite-state machine allows you to create an object, containing different states and transitions between them,
that can change its behaivour according to the current state.

## Creating a State machine:
1. Create an FSM\Client instance
2. Define your states classes, names and types
3. Add your states to the State machine, define transitions and an initial state
4. That's all =) Your simple State machine is now configured and ready for use!

## Example code:
```php
<?php
require_once('vendor/autoload.php');

use FSM\Client as StateMachine;
use FSM\State\State;
use FSM\State\StateInterface;

// Declare states classes
class StateA extends State
{
    public function foo($f)
    {
        return $f;
    }
}

class StateB extends State
{
    public function bar($b)
    {
        return $b * 2;
    }
}

// Create StateA initial instance
$stateA = new StateA();
$stateA->setName('stateA');
$stateA->setType(StateInterface::TYPE_INITIAL);

// Create StateB finite instance
$stateB = new StateB();
$stateB->setName('stateB');
$stateB->setType(StateInterface::TYPE_FINITE);

// Configure state machine by adding the states and setting a transition
$stateMachine = new StateMachine();
$stateMachine->addState($stateA);
$stateMachine->addState($stateB);
$stateMachine->setInitialState($stateA);
$stateMachine->createTransition('initial', 'stateA', 'stateB');

// Test StateA state
var_dump('stateMachine is at state: ' . $stateMachine->getCurrentState()->getName());
var_dump($stateMachine->callAction('foo', $properties = [100]));

// Accept transition
$stateMachine->acceptTransitionByName('initial');

// Test StateB state
var_dump('stateMachine is at state: ' . $stateMachine->getCurrentState()->getName());
var_dump($stateMachine->callAction('bar', $properties = [100]));
```