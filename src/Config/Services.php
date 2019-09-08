<?php namespace Tatter\Schemas\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Database\ConnectionInterface;
use Tatter\Schemas\Interfaces\ExporterInterface;
use Tatter\Schemas\Interfaces\ImporterInterface;

class Services extends BaseService
{
    public static function schemas(BaseConfig $config = null, ImporterInterface $import = null, ExporterInterface $export = null, bool $getShared = true)
    {
		if ($getShared):
			return static::getSharedInstance('schemas', $config);
		endif;

		// If no config was injected then load one
		if (empty($config))
			$config = config('Schemas');
		
		if (is_null($import))
		{
			$import = new \Tatter\Schemas\Importers\DatabaseImporter();
		}
		
		if (is_null($export))
		{
			$import = new \Tatter\Schemas\Exporters\CacheExporter();
		}
		
		return new \Tatter\Schemas\Schemas($config, $import, $export);
	}
}
