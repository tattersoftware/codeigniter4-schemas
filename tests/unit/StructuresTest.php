<?php

use Tatter\Schemas\Structures\Mergeable;

class StructuresTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function testMergeable()
	{
		$object1 = new Mergeable();
		$object1->foo = 'yes';
		
		$object2 = new Mergeable();
		$object2->bar = 'no thanks';
		
		$object1->merge($object2);
		
		$this->assertEquals($object1->bar, 'no thanks');
	}
}
