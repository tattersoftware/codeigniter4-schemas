<?php namespace Tatter\Schemas\Interfaces;

use Tatter\Schemas\Structures\Schema;

interface SchemaHandlerInterface
{
	public function import(): ?Schema;
	
	public function export(Schema $schema): bool;
}
