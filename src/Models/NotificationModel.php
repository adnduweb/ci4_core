<?php

namespace Adnduweb\Ci4Core\Models;

use Michalsn\Uuid\UuidModel;
use Adnduweb\Ci4Admin\Entities\Notification;

class NotificationModel extends UuidModel
{
    protected $table      = 'notifications';
    protected $primaryKey = 'id';
    protected $uuidFields = ['uuid'];

    protected $returnType     = Notification::class;
    protected $localizeFile   = 'App\Models\NotificationModel';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['uuid', 'event', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at'];

    protected $useTimestamps = true;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('notifications');
    }

    public function markAsReadAll(string $event){
        $date = date("Y-m-d H:i:s");
       if(!$this->db->query("UPDATE `prod_notifications` SET `read_at` = '" . $date . "' WHERE `prod_notifications`.`event` = '" . $event . "'")){
        return $this->db->error(); // Has keys 'code' and 'message'
       }
    }

}
