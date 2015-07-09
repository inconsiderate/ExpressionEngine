<?php

namespace EllisLab\ExpressionEngine\Service\Model;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		https://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Gateway
 *
 * @package		ExpressionEngine
 * @subpackage	Model
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Gateway {

	protected $_field_list_cache;
	protected $_field_values = array();

	/**
	 *
	 */
	public function __set($key, $value)
	{
		if ($this->hasProperty($key))
		{
			$this->setProperty($key, $value);
		}
		elseif ($this->hasField($key))
		{
			$this->setField($key);
		}
	}

	/**
	 *
	 */
	public function getTableName()
	{
		return static::$_table_name;
	}

	/**
	 *
	 */
	public function getFieldList()
	{
		if (isset($this->_field_list_cache))
		{
			return $this->_field_list_cache;
		}

		$vars = get_object_vars($this);
		$fields = array();

		foreach ($vars as $key => $value)
		{
			if ($key[0] != '_')
			{
				$fields[] = $key;
			}
		}

		return $this->_field_list_cache = $fields;
	}

	/**
	 *
	 */
	public function hasField($name)
	{
		return in_array($name, $this->getFieldList());
	}

	/**
	 *
	 */
	public function setField($name, $value)
	{
		$this->_field_values[$name] = $value;
	}

	/**
	 *
	 */
	public function hasProperty($name)
	{
		return (property_exists($this, $name) && $name[0] !== '_');
	}

	/**
	 *
	 */
	public function setProperty($name, $value)
	{
		$this->$name = $value;
		return $value;
	}

	/**
	 *
	 */
	public function getPrimaryKey()
	{
		return static::$_primary_key;
	}

	/**
	 *
	 */
	public function getId()
	{
		$pk = $this->getPrimaryKey();
		return $this->$pk;
	}

	/**
	 *
	 */
	public function fill($values)
	{
		foreach ($values as $key => $value)
		{
			if ($this->hasProperty($key))
			{
				$this->setProperty($key, $value);
			}
			elseif ($this->hasField($key))
			{
				$this->setField($key, $value);
			}
		}
	}

	/**
	 *
	 */
	public function getValues()
	{
		$values = get_object_vars($this);

		foreach ($values as $key => $value)
		{
			if ( ! isset($value))
			{
				unset($values[$key]);
			}

			if ($key[0] == '_')
			{
				unset($values[$key]);
			}
		}

		return array_merge($values, $this->_field_values);
	}
}