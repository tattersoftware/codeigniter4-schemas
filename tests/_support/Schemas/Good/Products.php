<?php namespace CIModuleTests\Support;

use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

/* SCHEMA */
$schema = new Schema();

/* TABLES */
$schema->tables->products = new Table('products');
$schema->tables->workers  = new Table('workers');

/* RELATIONS */
// Products->Workers
$relation         = new Relation();
$relation->type   = 'belongsTo';
$relation->table  = 'workers';
$relation->pivots = [
	['products', 'worker_id', 'workers', 'id'],
];
$schema->tables->products->relations->workers = $relation;

// Workers->Products
$relation         = new Relation();
$relation->type   = 'hasMany';
$relation->table  = 'products';
$relation->pivots = [
	['workers', 'id', 'products', 'worker_id'],
];
$schema->tables->workers->relations->products = $relation;

/* CLEANUP */
unset($relation);
