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
     * @var strings
     */
    protected $name;
    
    /**
     * @var \FSM\Context\ContextInterface
     */
    protected $context;
    
    /**
     * @var integer
     */
    protected $type;
    
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
        }
    
        return $this;
    }

    /**
     * @see \FSM\State\StateInterface::setContext()
     */
    public function setContext(ContextInterface $context)
    {
        if (!($this->context instanceof ContextInterface)) {
            $this->context = $context;
        }
        
        return $this;
    }
    
    /**
     * @see \FSM\State\StateInterface::handleAction()
     */
    public function handleAction($name, array $parameters = array())
    {
        if (!method_exists($this, $name)) {
            throw new \Exception("Method doesn't exist.");
        }
        if (!is_callable(array($this, $name))) {
            throw new \Exception("Method is not callable.");
        }

        return call_user_func_array(array($this, $name), $parameters);            
    }
}