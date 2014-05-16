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
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function isInitial()
    {
        return $this->type === self::TYPE_INITIAL;
    }

    public function isRegular()
    {
        return $this->type === self::TYPE_REGULAR;
    }
    
    public function isFinite()
    {
        return $this->type === self::TYPE_FINITE;
    }
    
    public function setContext(ContextInterface $context)
    {
        if (!($this->context instanceof ContextInterface)) {
            $this->context = $context;
        }
        
        return $this;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setType($type)
    {
        if ($type === self::TYPE_INITIAL || $type === self::TYPE_REGULAR || $type === self::TYPE_FINITE) {
            $this->type = $type;
        }
        
        return $this;
    }
    
    public function handleAction($name, $parameters = array())
    {
        return call_user_func_array(array($this, $name), $parameters);
    }
}