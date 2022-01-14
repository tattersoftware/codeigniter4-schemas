<?php

namespace Tatter\Schemas\Structures;

class Field extends Mergeable
{
    /**
     * The field name.
     *
     * @var string
     */
    public $name;

    /**
     * Whether this is a primary key.
     *
     * @var bool
     */
    public $primary_key;

    public function __construct($fieldData = null)
    {
        if (empty($fieldData)) {
            return;
        }

        if (is_string($fieldData)) {
            $this->name = $fieldData;
        } else {
            foreach ($fieldData as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
