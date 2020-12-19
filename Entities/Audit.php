<?php

namespace Adnduweb\Ci4Core\Entities;

use CodeIgniter\Entity;

class Audit extends Entity
{	
    protected $dates = ['created_at'];
    
    public function getIdItem()
	{
		return $this->attributes['id'] ?? null;
    }
    
	
	public function setData()
    {
        $this->attributes['details'] = 'bonjour';
        return $this;
    }

    public function getData()
    {
        $this->attributes['details'] = 'bonjour';
        return $this;
    }
}