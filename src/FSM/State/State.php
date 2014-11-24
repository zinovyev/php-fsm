<?php
namespace FSM\State;

use FSM\Context\ContextInterface;

/**
 * Default State class
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class State implements StateInterface
{
    /**
     * State name
     *
     * @var strings
     */
    protected $name;

    /**
     * State type
     *
     * @var string
     */
    protected $type = self::TYPE_REGULAR;

    /**
     * State Context subject
     *
     * @var \FSM\Context\ContextInterface
     */
    protected $context;

    /**
     * @see \FSM\State\StateInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see \FSM\State\StateInterface::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @see \FSM\State\StateInterface::getContext()
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @see \FSM\State\StateInterface::isInitial()
     */
    public function isInitial()
    {
        return $this->type === self::TYPE_INITIAL;
    }

    /**
     * @see \FSM\State\StateInterface::isRegular()
     */
    public function isRegular()
    {
        return $this->type === self::TYPE_REGULAR;
    }

    /**
     * @see \FSM\State\StateInterface::isFinite()
     */
    public function isFinite()
    {
        return $this->type === self::TYPE_FINITE;
    }

    /**
     * Set state name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $type
     * @return \FSM\State\State
     */
    public function setType($type)
    {
        if ($type === self::TYPE_INITIAL || $type === self::TYPE_REGULAR || $type === self::TYPE_FINITE) {
            $this->type = $type;

            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            "Wrong state type: '%s'. State type can be of type: StateInterface::TYPE_INITIAL,"
            . "StateInterface::TYPE_REGULAR or StateInterface::TYPE_FINITE",
            $type
        ));
    }

    /**
     * @see \FSM\State\StateInterface::setContext()
     */
    public function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @see \FSM\State\StateInterface::handleAction()
     */
    public function handleAction($name, array $parameters = array())
    {
        if (!method_exists($this, $name)) {
            throw new \BadMethodCallException(sprintf(
                "Method '%s' doesn't exist.",
                $name
            ));
        } elseif (!is_callable(array($this, $name))) {
            throw new \BadMethodCallException(
                "Method '%s' is not callable.",
                $name
            );
        }

        return call_user_func_array(array($this, $name), $parameters);
    }
}