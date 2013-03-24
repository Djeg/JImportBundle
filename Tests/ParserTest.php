<?php
namespace DavidJegat\JImportBundle\Tests;

use DavidJegat\JImportBundle\Container\FunctionContainer;
use DavidJegat\JImportBundle\Functions\SampleFunction;
use DavidJegat\JImportBundle\Parser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
	private $container;

	private $parser;

	public function testParser1()
	{
		$sample_file = 'var foo = @sample("bar", 10, 1.1, true);';
		
		$this->assertEquals("\n".'var foo = ["bar",10,1.1,true];'."\n", $this->parser->parse($sample_file));
	}

	public function testParser2()
	{

		$sample_file = 'var foo = @sample("bar"  ,   \'foo\');';
		
		$this->assertEquals("\n".'var foo = ["bar","foo"];'."\n", $this->parser->parse($sample_file));
	}

	public function testParser3()
	{

		$sample_file = 'var foo = @sample(some error, dude);';
		
		$this->assertEquals("\n".'var foo = [false,false];'."\n", $this->parser->parse($sample_file));
	}

	public function testParser4()
	{

		$sample_file = 'var foo = @sample("array", [ "foo" | "bar" ]);';
		
		$this->assertEquals("\n".'var foo = ["array",["foo","bar"]];'."\n", $this->parser->parse($sample_file));
	}

	public function testParser5()
	{

		$sample_file = 'var foo = @sample("array", [ "foo" : "bar" | "bar" : 10 ]);';
		
		$this->assertEquals("\n".'var foo = ["array",{"foo":"bar","bar":10}];'."\n", $this->parser->parse($sample_file));
	}

	public function __construct()
	{
		$this->container = new FunctionContainer();

		$this->container->add(new SampleFunction());

		$this->parser = new Parser($this->container);
	}
}