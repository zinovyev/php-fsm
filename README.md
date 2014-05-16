php-fsm
=======

PHP Final State Machine

```php
<?php
require_once('vendor/autoload.php');

use FSM\Client as StateMachine;
use FSM\State\State;
use FSM\State\StateInterface;

// Create StateA
$stateA = new State();
$stateA->setName('stateA');
$stateA->setType(StateInterface::TYPE_INITIAL);

// Create StateB
$stateB = new State();
$stateB->setName('stateB');
$stateB->setType(StateInterface::TYPE_FINITE);

// Configure state machine
$stateMachine = new StateMachine();
$stateMachine->addState($stateA);
$stateMachine->addState($stateB);
$stateMachine->setInitialState($stateA);
$stateMachine->addTransition('initial', 'stateA', 'stateB');

var_dump('stateMachine is at state: ' . $stateMachine->getCurrentState()->getName());

// Accept transition
$stateMachine->acceptTransition(
    $stateMachine->getTransitionByName('initial')
);

var_dump('stateMachine is at state: ' . $stateMachine->getCurrentState()->getName());
```