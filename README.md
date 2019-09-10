# Tatter\Schemas
Database schema management, for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/schemas`
2. Map a new schema: `> php spark schemas:map`

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

