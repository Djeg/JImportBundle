<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\Container;

use DavidJegat\JImportBundle\Functions\FunctionInterface;

/**
 * This class represent the storing factory for all the jimport functions
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
class FunctionContainer implements \Iterator
{
	/**
	 * @var array $container
	 * @access private
	 */
	private $container = array();

	/**
	 * @var integer offset
	 * @access private
	 */
	private $offset = 0;

	/**
	 * return the current offset
	 * 
	 * @return FunctionInterface
	 */
	public function current()
	{
		return $this->container[$this->offset];
	}

	/**
	 * Return the offset
	 * 
	 * @return integer
	 */
	public function key()
	{
		return $this->offset;
	}

	/**
	 * Return the next offset
	 */
	public function next()
	{
		++$this->offset;
	}

	/**
	 * Rewind the iterator
	 */
	public function rewind()
	{
		$this->offset = 0;
	}

	/**
	 * Testthe given offset
	 * 
	 * @return boolean
	 */
	public function valid()
	{
		return isset($this->container[$this->offset]);
	}

	/**
	 * Add a function to the JImportFunctionContainer
	 * 
	 * @param FunctionInterface $function
	 * @return JImportFunctionContainer
	 */
	public function add(FunctionInterface $function)
	{
		$this->container[] = $function;
	}
}