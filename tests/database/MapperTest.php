<?php

class MapperTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testMapper()
	{
		$test = $this->mapper->run();
		
		dd($test);
	}
}
