<?php
namespace FSM\Tests;

use FSM\Client;
use FSM\State\State;

/**
 * Test ClientTest.
 * Basic client unit test
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testClientInitialization()
    {
        $client = new Client();
        $this->assertFalse($client->verify(), "Empty client is invalid");

        $initialState = new State();
        $initialState->setType(State::TYPE_INITIAL);

        $firstRegularState = new State();
        $secondRegularState = new State();
        $thirdRegularState = new State();

        $finalSuccessState = new State();
        $finalSuccessState->setType(State::TYPE_FINAL);

        $finalFailureState = new State();
        $finalFailureState->setType(State::TYPE_FINAL);

        $client
            ->addState('initial', $initialState)
            ->addState('regular_1', $firstRegularState)
            ->addState('regular_2', $secondRegularState)
            ->addState('regular_3', $thirdRegularState)
            ->addState('won', $finalSuccessState)
            ->addState('loss', $finalFailureState)
            ->addTransition('initial_1', 'initial', 'regular_1')
        ;

        $this->assertTrue($client->verify(), "Verification failed");
    }
}