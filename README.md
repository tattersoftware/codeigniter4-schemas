# Tatter\Schema
Database schema mapper, for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/schema`
2. Generate the schema: `> php spark schema:generate`

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/schema`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**bin/Schema.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

