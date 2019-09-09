<?php namespace Tatter\Schemas\Config;

use CodeIgniter\Config\BaseService;
use Tatter\Schemas\Structures\Schema;

class Services extends BaseService
{
    public static function schemas(BaseConfig $config = null, Schema $schema = null, bool $getShared = true)
    {
		if ($getShared) {
			return static::getSharedInstance('schemas', $config, $schema);
		}

		// If no config was injected then load one
		if (empty($config))
		{
			$config = config('Schemas');
		}
		
		// If no starting schema provided then use a blank one
		if (is_null($schema))
		{
			$schema = new Schema();
		}
		
		return new \Tatter\Schemas\Schemas($config, $schema);
	}
}
