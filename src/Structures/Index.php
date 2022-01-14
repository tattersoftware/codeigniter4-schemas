<?php

namespace Tatter\Schemas\Structures;

class Index extends Mergeable
{
    /**
     * The index name.
     *
     * @var string
     */
    public $name;

    public function __construct($indexData = null)
    {
        if (empty($indexData)) {
            return;
        }

        if (is_string($indexData)) {
            $this->name = $indexData;
        } else {
            foreach ($indexData as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
