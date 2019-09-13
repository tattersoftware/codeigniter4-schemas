<?php namespace Tatter\Schemas\Interfaces;

use Tatter\Schemas\Structures\Schema;

interface SchemaHandlerInterface
{
	public function get(): ?Schema;
	
	public function save(Schema $schema): bool;
}
