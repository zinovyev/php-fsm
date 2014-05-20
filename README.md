PHP Finite-state Machine
=======

Finite-state machine allows you to create an object, containing different states and transitions between them,
that can change its behaivour according to the current state.

## Install:

For installing the FSM machine use Composer. Simply add following to your composer.json file:
```json
    "require": {
        "zinovyev/php-fsm": "v0.65"
    }
```

## Creating a State machine steps:
+ Create State classes and define states:
```php
class StateA extends State
{
    public function foo($name)
    {
        printf("Hello, %s!", $name);
    }
}
```

+ Create a new FSM\Client instance (StateMachine).
+ Bind your States to the Client instance, apply transactions and set initial state:

```php
class StateMachine extends Client
{
    public function __construct()
    {
        parent::__construct();
        
        /* ... */
        
        $this
            ->addState($stateA)
            /* ... */
            ->createTransition('fourth', 'stateA', 'stateB')
            /* ... */
```
or
```php
$stateMachine = new Client;
$stateMachine
    ->addState($stateA)
    ->addState($stateB)
    ->createTransition('fourth', 'stateA', 'stateB')
;
```

+ Use Memento if you want do store current State and parameters and restore them later:
```php
$memento = $stateMachine->createMemento();
```
and restore:
```php
$stateMachine = new StateMachine();
$stateMachine->applyMemento($memento);
```

+ That's all =) Your simple State machine is now configured and ready for use!

## Example code:
```php
<?php
require_once('vendor/autoload.php');

use FSM\Client;
use FSM\State\State;
use FSM\State\StateInterface;

/**
 * StateA class
 */
class StateA extends State
{
    public function foo($f)
    {
        return $f;
    }
}

/**
 * StateB class
 */
class StateB extends State
{
    public function bar($b)
    {
        return $b * 2;
    }
}

/**
 * StateC class
 */
class StateC extends State
{
    public function fooBar($fb)
    {
        return strrev($fb);
    }
    
    public function shuffle(array $array) {
        shuffle($array);
        return $array;
    }
}


/**
 * StateMachine example class
 */
class StateMachine extends Client
{
    public function __construct()
    {
        parent::__construct();
        
        // Create StateA initial type instance of class StateA
        $stateA = new StateA();
        $stateA->setName('stateA');
        $stateA->setType(StateInterface::TYPE_INITIAL);
        
        // Create StateB finite type instance of class StateB
        $stateB = new StateB();
        $stateB->setName('stateB');
        $stateB->setType(StateInterface::TYPE_FINITE);
        
        // Create StateC regular type instance of class StateC
        $stateC = new StateC();
        $stateC->setName('stateC');
        $stateC->setType(StateInterface::TYPE_REGULAR);
        
        // Create StateD regular type instance of class StateB
        $stateD = new StateB();
        $stateD->setName('stateD');
        $stateD->setType(StateInterface::TYPE_REGULAR);        
        
        // Attach states and transitions
        $this
            ->addState($stateA)
            ->addState($stateB)
            ->addState($stateC)
            ->addState($stateD)
            ->setInitialState($stateA)
            ->createTransition('initial', 'stateA', 'stateA')
            ->createTransition('second', 'stateA', 'stateC')
            ->createTransition('secondAlternative', 'stateA', 'stateD')
            ->createTransition('third', 'stateC', 'stateD')
            ->createTransition('thirdAlternative', 'stateD', 'stateC')
            ->createTransition('fourth', 'stateD', 'stateB')
            ->createTransition('fourthAlternative', 'stateC', 'stateB')
            ->createTransition('shortWay', 'stateA', 'stateB')
        ;
        
    }
}


// Create new StateMachine instance
$stateMachine = new StateMachine();
$stateMachine->foo = 'bar'; // Add public property


// Test StateA state
printf("%d) State machine is at state: '%s'. Test function call result is: '%s'\n", 1,
    $stateMachine->getCurrentState()->getName(),            // Get State Name
    $stateMachine->callAction('foo', $properties = [100])   // Call State function
);

// Accept transition "initial"
$stateMachine->acceptTransitionByName('initial');
printf("%d) State machine is at SAME state: '%s'. Test function call result: '%s'\n", 2,
    $stateMachine->getCurrentState()->getName(),            // Get State Name
    $stateMachine->callAction('foo', $properties = [200])   // Call State function
);

// Accept transition "second"
$stateMachine->acceptTransitionByName('second');
printf("%d) State machine is at state: '%s'. Test function call result: '%s'\n", 3,
    $stateMachine->getCurrentState()->getName(),            // Get State Name
    $stateMachine->fooBar('foo bar')                        // Call State function
);

// Create a memento (snapshot) of the current state
$memento = $stateMachine->createMemento();

// Unset StateMachine
$stateMachine = null;
unset($stateMachine);

// Restore StateMachine from a snapshot (Memento)
$stateMachine = new StateMachine();
$stateMachine->applyMemento($memento);

printf("=*= Check property value after restore: \$foo='%s' =*=\n", $stateMachine->foo);

// Accept transition "third"
$stateMachine->acceptTransitionByName('third');
printf("%d) State machine is at state '%s'. Test function call result: '%s'\n", 4,
    $stateMachine->getCurrentState()->getName(),    // Get State Name
    $stateMachine->bar(1)                           // Call State function
);

// Accept transition "fourth"
$stateMachine->acceptTransitionByName('fourth');
printf("%d) State machine is at state '%s'. Test function call result: '%s'\n", 5,
    $stateMachine->getCurrentState()->getName(),    // Get State Name
    $stateMachine->bar(2)                           // Call State function
);
```

## Example code execution result:

*If you run the code in your console, you'll see the following output*:
```
1) State machine is at state: 'stateA'. Test function call result is: '100'
2) State machine is at SAME state: 'stateA'. Test function call result: '200'
3) State machine is at state: 'stateC'. Test function call result: 'rab oof'
=*= Check property value after restore: $foo='bar' =*=
4) State machine is at state 'stateD'. Test function call result: '2'
5) State machine is at state 'stateB'. Test function call result: '4'
```