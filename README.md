# Tatter\Schemas
Database schema management, for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/schemas`
2. Generate a new schema: `> php spark schemas`

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/schemas`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**bin/Schemas.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

**Schemas** works on an import/export model, allowing dynamic loading and storing of schemas
from a variety of handlers. The **Schemas** service is also available to simplify a workflow
with convenient wrapper functions.

Here is an example of one common process, mapping the default database group and storing
the resulting schema to the cache:

```
// Map the database and store the schema in cache
$schemas = service('schemas');
$this->schemas->import('database')->export('cache');

// Load the schema from cache add Model data and grab a copy
$schema = $this->schemas->import('cache', 'model')->get();
```

If you need to deviate from the default configuration you can inject the handlers yourself:
```
$db = db_connect('alternate_database');
$databaseHandler = new (\Tatter\Schemas\Handlers\DatabaseHandler(null, $db);
$schema = $this->schemas->import($databaseHandler)->get();
```

## Handlers

Right now only a few basic handlers are available:
* Database (import only)
* Model (imports only)
* Cache

The eventual goal is to support mapping and deploying schemas from any possible source,
so **Schemas** functions as an alternative method of database management.

**COMING SOON**
* `DatabaseHandler->export()`: Recreate a live database from its schema
* `MigrationsHandler`: Create a schema from migration files, or vice versa
* `FileHandler`: A wrapper for importing and exporting from popular schema file formats
* `XmlHandler`: The first file handler, supporting Doctrine-style XML files

