<?php
/**
 * This file is a part of JImport. Please read the 
 * LICENSE or README.md files for more informations about
 * this software.
 *
 * @author david jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JImport
 */

namespace DavidJegat\JImportBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidJegat\JImportBundle\DependencyInjection\Compiler\FunctionContainerPass;

/**
 * JImportBundle
 * 
 * @author david jegat <david.jegat@gmail.com>
 */
class DavidJegatJImportBundle extends Bundle 
{
	/**
	 * Build the bundle with the FunctionContainerPass
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new FunctionContainerPass());
	}
}