<?php

namespace Tatter\Schemas\Structures;

class Relation extends Mergeable
{
    /**
     * The related table name.
     *
     * @var string
     */
    public $table;

    /**
     * The type of relationship.
     *
     * @var string
     */
    public $type;

    /**
     * Whether the relation will be to a single object.
     *
     * @var bool
     */
    public $singleton;

    /**
     * Tables and columns for pivot and "through" relationships.
     *
     * @var array of [tableName, fieldName, foreignField]
     */
    public $pivots = [];
}
