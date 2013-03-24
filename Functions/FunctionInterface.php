<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\Functions;

use DavidJegat\JImportBundle\Parser\Parser;

/**
 * Defined JImport Functions behavior
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
interface FunctionInterface
{
	/**
	 * Return your function name
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Execute the function
	 * 
	 * @param array $arguments
	 * @param Parser $parser
	 * @return string, the function relacement
	 */
	public function execute(array $arguments, Parser $parser);
}