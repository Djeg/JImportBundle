<?php
namespace DavidJegat\JImportBundle\Functions;

use DavidJegat\JImportBundle\Parser\Parser;

class SampleFunction implements FunctionInterface
{
	public function getName()
	{
		return 'sample';
	}

	public function execute(array $args, Parser $parser)
	{
		return json_encode($args).";";
	}
}