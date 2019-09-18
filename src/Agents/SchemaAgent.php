<?php namespace Tatter\Schemas\Agents;

/* Tatter\Agents
 * Service analysis and assessment for CodeIgniter 4
 * https://github.com/tattersoftware/codeigniter4-agents
 *
 * Install:
 * `composer require tatter\agents`
 * `php spark handlers:register`
 *
 * Collect:
 * `php spark agents:check`
 *
 * Monitor:
 * https://github.com/tattersoftware/headquarters
 */

use CodeIgniter\Config\Services;
use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;

class SchemaAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Schema',
		'uid'        => 'schema',
		'icon'       => 'fas fa-project-diagram',
		'summary'    => 'Map the database structure from the default connection',
	];
	
	public function check($path = null)
	{
		$schemas = Services::schemas();
		if (empty($schemas))
		{
			return false;
		}
		$config = config('Schemas');
		
		// Generate the schema
		$schema = $schemas->import(...$config->defaultHandlers)->get();
		
		
		$this->record('defaultSchema', 'object', $schema);
	}
}
