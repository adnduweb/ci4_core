<?php

namespace Adnduweb\Ci4Admin\Entities;

use Michalsn\Uuid\UuidEntity;

class Notification extends UuidEntity
{

    protected $table      = 'notifications';
    protected $primaryKey = 'id';
    protected $uuids = ['uuid'];

    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];

    public function getNameController(){

        // Natif the
        if (preg_match('/^App/', $this->attributes['type']) == true) {

            $type = str_replace('App\Models\\', '', $this->attributes['type']);
            $type = str_replace('Model', '', $type);
            return $type;

        }else{

            $type = str_replace('Adnduweb\Ci4_', '', $this->attributes['type']);
            $type = strstr($type, '\\', true);
            // $type = str_replace('\\Models', '', $type);
            // $type = str_replace('Model', '', $type);
            return $type;

        }


    }
}
