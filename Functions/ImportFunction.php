<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\Functions;

use DavidJegat\JImportBundle\Functions\FunctionInterface;
use DavidJegat\JImportBundle\Parser\Parser;

/**
 * This class implement the @import(...) function for all
 * your jimport filter files
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
class ImportFunction implements FunctionInterface
{
	/**
	 * @var Kernel $kernel
	 * @access private
	 */
	private $kernel;

	/**
	 * @var array $bundles
	 * @access private
	 */
	private $bundles;

	private $log;

	public function getName()
	{
		return 'import';
	}

	/**
	 * Recursively parse a directory and import all the content.
	 * You can precised an import.json file at the root level if the
	 * directories for defined wich files will be imports
	 * 
	 * @param Parser $parser
	 * @param string $path
	 * @param string $content = ''
	 * @return string
	 */
	private function parseDirectory(Parser $parser, $path, $content = '')
	{
		if( substr($path, -1) != '/' ){
			$path .= '/';
		}
		// test the import.json file
		if( file_exists($path.'import.json') ) {
			// get the import.json file :
			if( $imports = json_decode( file_get_contents($path.'import.json') ) === null ){
				throw new \UnexpectedValueException($path.'import.json is not a correct json !');
			}
		} else {
			// get the all directory
			$imports = scandir($path);
		}

		// loop on each imports
		foreach( $imports as $item ) {
			if( $item == '..' or $item == '.' ){
				continue;
			}

			if( is_dir($path.$item) ) {
				// recursively include it :
				$content .= $this->parseDirectory($parser, $path.$item);
			} elseif( file_exists($path.$item) ) {
				$content .= $parser->parse( file_get_contents($path.$item) );
			}
		}

		// finaly return the content
		return $content;
	}

	/**
	 * Execute the @import statement
	 * 
	 * @param array $args
	 * @return string
	 */
	public function execute(array $args, Parser $parser)
	{
		if( !isset($args[0]) ){
			return '';
		}

		$file = $args[0];

		// Loop on each registered bundles
		foreach( $this->bundles as $bundle ){
			// Try to get the actual bundle : directory Resources/public
			try {
				$bundle_path = $this->kernel->locateResource('@'.$bundle);
			} catch(Exception $e) {
				// no file has been found so ... continue
				continue;
			}

			$file_path = $bundle_path.'/Resources/public/'.$file;

			// test if it's a directory
			if( is_dir($file_path) ) {
				// get the directory content
				$content = $this->parseDirectory($parser, $file_path);		
			} elseif( file_exists($file_path) ) {
				// get the content
				$content = $parser->parse( file_get_contents($file_path) );
			}

			return $content;
		}
	}

	/**
	 * Construct the ImportFunction
	 * 
	 * @param ServiceContainer $container
	 */
	public function __construct($container)
	{
		$this->kernel = $container->get('kernel');
		$this->bundles = $container->getParameter('assetic.bundles');
		$this->log = $container->get('logger');
	}
}