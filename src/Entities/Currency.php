<?php namespace Adnduweb\Ci4Core\Entities;

use CodeIgniter\Entity;

class Currency extends Entity
{
    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = [];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];

    public function getIdItem()
	{
		return $this->attributes['id'] ?? null;
	}

}
