# Tatter\Schemas
Database schema management, for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-schemas/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-schemas/actions?query=workflow%3A%22PHPUnit)
[![](https://github.com/tattersoftware/codeigniter4-schemas/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-schemas/actions?query=workflow%3A%22PHPStan)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-schemas/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-schemas?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/schemas`
2. Generate a new schema: `> php spark schemas`

## Features

* View your entire database mapped out in a cascading structure
* Read or detect table relationships for easy object-relation mapping (see e.g. [Tatter\Relations](https://github.com/tattersoftware/codeigniter4-relations))
* Get helpful advice on optimizations to your database structure with schema analysis<sup>1</sup>
* Backup, restore, or deploy an entire database structure between servers or environments<sup>1</sup>
* Generate CodeIgniter 4 migration files from an existing database<sup>1</sup>
* Transfer projects to CodeIgniter 4 by reading schema files from other supported formats<sup>1</sup>

<sup>1</sup> Some features are still in development. See **Handlers > Development** for
planned future expansion.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/schemas`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**examples/Schemas.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

**Schemas** has four main functions, each with a variety of handlers available:
* *Draft*: Generates a new schema from a variety of sources
* *Archive*: Stores a copy of a schema for later use
* *Read*: Loads a schema for live access
* *Publish*: (not yet available) Modifies environments to match schema specs

The **Schemas** service is also available to simplify a workflow with convenient wrapper functions.
At its most basic (with automation enabled), the service will draft, archive, and return
a schema with one simple command:

	$schema = service('schemas')->get();

You may want to control when portions of the workflow take place to optimize performance.
Here is an example of one common process, mapping the default database group and storing
the resulting schema to the cache:

```
// Map the database and store the schema in cache
$schemas = service('schemas');
$schemas->draft('database')->archive('cache');

// Load the schema from cache, add Model data, and get the updated schema
$schema = $schemas->read('cache')->draft('model')->get();
```

If you need to deviate from default handler configurations you can inject the handlers yourself:
```
$db = db_connect('alternate_database');
$databaseHandler = new \Tatter\Schemas\Drafter\Handlers\DatabaseHandler(null, $db);
$schema = $schemas->draft($databaseHandler)->get();
```

## Command

**Schemas** comes with a `spark` command for convenient schema generation and display:

	`schemas [-draft handler1,handler2,...] [-archive handler1,... | -print]`

Use the command to test and troubleshoot, or add it to your cron for periodic schema caching:

	php spark schemas -draft database,model -archive cache

## Automation

By default automation is turned on, but this can be configured via the `$automate` toggles
in your config file. Automation will allow the service to fall back on a Reader, or even on
a Drafter should it fail to have a schema already loaded. While automation makes using the
library very easy, it can come at a performance cost if your application is not configured
correctly, since it may draft a schema on every page load. Use automation to help but don't
let it become a crutch.

## Structure

**Schemas** uses foreign keys, indexes, and naming convention to detect relationships
automatically. Make sure your database is setup using the appropriate keys and
foreign keys to assist with the detection. Naming conventions follow the format of
`{table}_id` for foreign keys and `{table1}_{table2}` for pivot tables. For more examples
on relationship naming conventions consult the Rails Guide
[Active Record Associations](https://guides.rubyonrails.org/association_basics.html#the-types-of-associations)
(and please excuse the Ruby reference).

### Intervention

Should autodetection fail or should you need to deviate from conventions there are a few
tools you can use to overwrite or augment the generated schema.

* **Config/Schemas**: the Config file includes a variable for `$ignoredTables` that will let you skip tables entirely. By default this includes the framework's `migrations` table.
* **app/Schemas/{file}.php**: The `DirectoryHandler` will load any schemas detected in your **Schemas** directory - this gives you a chance to specify anything you want. See [tests/_support/Schemas/Good/Products.php](tests/_support/Schemas/Good/Products.php) for an example.

## Drafting

Currently supported handlers:

* Database
* Model
* PHP
* Directory (PHP import only)

## Archiving/reading

* Cache

## Database Support

All CodeIgniter 4 database drivers work but due to some differences in index handling they
may not all report the same results. Example: see skipped tests for SQLite3.

## Development

The eventual goal is to support mapping from and deploying to any source. Planned handler
implementations include:

* `Publisher\DatabaseHandler`: Recreate a live database from its schema
* `MigrationsHandler`: Create a schema from migration files, or vice versa
* `FileHandler`: A wrapper for importing and exporting from popular schema file formats
* Lots more...

And the file-specific handlers:
* `PhpHandler->archive()`: Create a PHP file with a Schema object in `$schema`
* `XmlHandler`: Support for Doctrine-style XML files
* More to come...

Want to help out? All code and issues are managed on GitHub at [Tatter\Schemas](https://github.com/tattersoftware/codeigniter4-schemas)
