<?php

namespace Tatter\Schemas\Structures;

class ForeignKey extends Mergeable
{
    /**
     * The foreign key constraint name.
     *
     * @var string
     */
    public $constraint_name;

    public function __construct($foreignKeyData = null)
    {
        if (empty($foreignKeyData)) {
            return;
        }

        if (is_string($foreignKeyData)) {
            $this->constraint_name = $foreignKeyData;
        } else {
            foreach ($foreignKeyData as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
