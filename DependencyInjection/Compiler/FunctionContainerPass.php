<?php
/**
 * This file is a part of JImportBundle. please read the LICENSE
 * and README.md files for more informations about this software
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImportBundle
 */

namespace DavidJegat\JImportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Defined a compiler pass for the FunctionContainer class
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
class FunctionContainerPass implements CompilerPassInterface
{
	/**
	 * Process on the compiler pass
	 * 
	 * @param ContainerBuilder $container
	 */
	public function process(ContainerBuilder $container)
	{
		// testing container definition
		if( !$container->hasDefinition('davidjegat_jimport.container') ){
			return;
		}

		$definition = $container->getDefinition('davidjegat_jimport.container');

		$taggedServices = $container->findTaggedServiceIds('davidjegat_jimport.function');
		foreach( $taggedServices as $id => $attr ){
			$definition->addMethodCall('add', array( new Reference($id) ));
		}
	}
}