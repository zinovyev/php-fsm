<?php
namespace FSM\Context;

/**
 * Context with properties
 * 
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ContextHasPropertiesInterface extends ContextInterface
{

    /**
     * Get all properties at one time
     * 
     * @return array
     */
    public function getProperties();

    /**
     * Get property by name
     * 
     * @param $name string
     * @return mixed
     */
    public function getProperty($name);

    /**
     * Set property
     * 
     * @param $name string
     * @param $value mixed
     * @return \FSM\Context\ContextHasPropertiesInterface
     */
    public function setProperty($name, $value);
}