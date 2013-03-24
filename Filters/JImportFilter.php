<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\Filters;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use DavidJegat\JImportBundle\Parser\Parser;

class JImportFilter implements FilterInterface
{
	/**
	 * @var Parser $parser
	 * @access private
	 */
	private $parser;

	/**
	 * @var boolean $isCache
	 * @access private
	 */
	private $isCache;

	public function filterLoad(AssetInterface $asset)
	{
		// reload the file each by touch the asset
		if(!$this->isCache){
			$root = $asset->getSourceRoot();
	        $path = $asset->getSourcePath();

	        $filename = realpath($root . '/' . $path);

	        if (file_exists($filename)) {
	            touch($filename);
	        }
		}
	}

	public function filterDump(AssetInterface $asset)
	{
		$asset->setContent( $this->parser->parse( $asset->getContent() ) );
	}

	/**
	 * Construct the JImportFilter
	 *
	 * @param ServiceContainer $container
	 */
	public function __construct($container)
	{
		$this->parser = $container->get('davidjegat_jimport.parser');
		$this->isCache = $container->getParameter('davidjegat_jimport.cache');
	}
}