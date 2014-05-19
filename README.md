php-fsm
=======

# PHP Finite-state Machine

Finite-state machine allows you to create an object, containing different states and transitions between them,
that can change its behaivour according to the current state.

## Install:

For installing the FSM machine use Composer. Simply add following to your composer.json file:
```json
    "require": {
        "zinovyev/php-fsm": "v0.6"
    }
```

## Creating a State machine steps:
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

class StateC extends State
{
    public function fooBar($fb)
    {
        return strrev($fb);
    }
}


// Create StateA initial instance of class StateA
$stateA = new StateA();
$stateA->setName('stateA');
$stateA->setType(StateInterface::TYPE_INITIAL);

// Create StateB finite instance of class StateB
$stateB = new StateB();
$stateB->setName('stateB');
$stateB->setType(StateInterface::TYPE_FINITE);

// Create StateC regular instance of class StateC
$stateC = new StateC();
$stateC->setName('stateC');
$stateC->setType(StateInterface::TYPE_REGULAR);

// Create StateD regular instance of class StateB
$stateD = new StateB();
$stateD->setName('stateD');
$stateD->setType(StateInterface::TYPE_REGULAR);



// Configure state machine by adding the states and setting a transition
$stateMachine = new StateMachine();
$stateMachine->addState($stateA);
$stateMachine->addState($stateB);
$stateMachine->addState($stateC);
$stateMachine->addState($stateD);
$stateMachine->setInitialState($stateA);
$stateMachine->createTransition('initial', 'stateA', 'stateA');
$stateMachine->createTransition('second', 'stateA', 'stateC');
$stateMachine->createTransition('secondAlternative', 'stateA', 'stateD');
$stateMachine->createTransition('third', 'stateC', 'stateD');
$stateMachine->createTransition('thirdAlternative', 'stateD', 'stateC');
$stateMachine->createTransition('fourth', 'stateD', 'stateB');
$stateMachine->createTransition('fourthAlternative', 'stateC', 'stateB');
$stateMachine->createTransition('shortWay', 'stateA', 'stateB');

// Test StateA state
printf("%d) State machine is at state: '%s'. Test function call result is: '%s\n", 1,
    $stateMachine->getCurrentState()->getName(),            // Get State name
    $stateMachine->callAction('foo', $properties = [100])   // Call State function
);

// Accept transition "initial"
$stateMachine->acceptTransitionByName('initial');
printf("%d) State machine is at SAME state: '%s'. Test function call result is: '%s\n", 2,
    $stateMachine->getCurrentState()->getName(),            // Get State name
    $stateMachine->callAction('foo', $properties = [200])   // Call State function
);

// Accept transition "second"
$stateMachine->acceptTransitionByName('second');
printf("%d) State machine is at SAME state: '%s'. Test function call result is: '%s\n", 3,
    $stateMachine->getCurrentState()->getName(),            // State name
    $stateMachine->fooBar('foo bar')                        // Call State function
);


// Accept transition "third"
$stateMachine->acceptTransitionByName('third');
printf("%d) State machine is at SAME state: '%s'. Test function call result is: '%s\n", 4,
    $stateMachine->getCurrentState()->getName(),    // name
    $stateMachine->bar(1)                           // Call State function
);

// Accept transition "fourth"
$stateMachine->acceptTransitionByName('fourth');
printf("%d) State machine is at SAME state: '%s'. Test function call result is: '%s\n", 5,
    $stateMachine->getCurrentState()->getName(),    // name
    $stateMachine->bar(2)                           // Call State function
);
```

## Example code execution result:

*If you run the code in your console, you'll see the following output*:
```
1) State machine is at state: 'stateA'. Test function call result is: '100
2) State machine is at SAME state: 'stateA'. Test function call result is: '200
3) State machine is at state: 'stateC'. Test function call result is: 'rab oof
4) State machine is at state: 'stateD'. Test function call result is: '2
5) State machine is at state: 'stateB'. Test function call result is: '4
```