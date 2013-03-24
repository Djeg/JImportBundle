<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\Parser;

use DavidJegat\JImportBundle\Container\FunctionContainer;

/**
 * This class parse a given content for detecting nodes
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
class Parser
{
	/**
	 * @var FunctionContainer $funcs
	 * @access private
	 */
	private $funcs;

	/**
	 * Parse the given arguements string. The accepted arguments type are
	 * the following : 
	 * 	- string ('argu' or "argu" works)
	 * 	- integer (10, 156 ... etc) 
	 * 	- float (10.2465 ... etc)
	 * 	- boolean (true or false)
	 *  - array ([ ... ], { ... })
	 * 
	 * CAUTION ! All the arguments must be on one line please ... more easy
	 * to maintain and code algorithm on one line so .. thanks ;)
	 * 
	 * @param array $args
	 * @param string $exp = ','
	 * @param boolean $first = true
	 * @return array
	 */
	public function parseArguments(array $args, $exp = ',', $first = true, $array = false)
	{
		$finalArgs = array();
		foreach( $args as $k => $arg ){

			$exploder = explode($exp, $arg);
			$argus = array();
			foreach( $exploder as $key => $member ){
				$argus[] = trim($member);

				if( $array and preg_match('/^[\'"](.*)[\'"]\s*:\s*(.*)$/', $argus[$key], $mas) ){
					// Array with key detected :
					list($argus[$mas[1]]) = $this->parseArguments(array($mas[2]), ',', false);
					unset($argus[$key]);
					continue;
				}

				// Detect the argument type
				if( preg_match('/^[\'"](.*)[\'"]$/', $argus[$key], $matches) ) {
					// String detected :
					$argus[$key] = $matches[1];

				} elseif( preg_match('/^[0-9]+$/', $argus[$key], $matches) ) {
					// Integer detected :
					$argus[$key] = intval($matches[0]);

				} elseif( preg_match('/^[0-9,.]+$/', $argus[$key], $matches) ) {
					// Float detected :
					$argus[$key] = floatval($matches[0]);

				} elseif( preg_match('/^\[(.*)\]$/', $argus[$key], $matches) ){
					// Array detected
					$argus[$key] = $this->parseArguments(array($matches[1]), '|', false, true);

				} else {
					// The rest is boolean (false if not == to true) :
					$argus[$key] = ( strtolower($argus[$key]) === 'true' ) ?
						true :
						false;
				}
			}
			if( $first ){
				$finalArgs[] = $argus;
			} else {
				$finalArgs = $argus;
			}
		}

		return $finalArgs;
	}

	/**
	 * Parse a given content with the given functions
	 * 
	 * @param string $content
	 * @return string
	 */
	public function parse($content)
	{
		$regex = '/@({{pattern}})\((.*)\)\s*;/m';
		// Loop on each funcs
		foreach( $this->funcs as $function ){
			$re = str_replace('{{pattern}}', $function->getName(), $regex);

			// try to find it into the given content :
			if( !preg_match_all($re, $content, $m) ) {
				continue;
			}

			// replace the content
			$funcArgs = $this->parseArguments($m[2]);
			$funcNames = $m[1];

			foreach( $funcNames as $fk => $f ){

				$newContent = $function->execute($funcArgs[$fk], $this);

				$content = str_replace($m[0][$fk], $newContent, $content);
			}
		}

		// return the modfied content :
		return "\n".$content."\n";
	}

	/**
	 * Construct a parser object
	 * 
	 * @param FunctionContainer $container
	 */
	public function __construct(FunctionContainer $container)
	{
		$this->funcs = $container;
	}
	
}