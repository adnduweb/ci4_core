<?php

namespace Adnduweb\Ci4Core\Traits;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Adnduweb\Ci4Core\Entities\Notification;
use Adnduweb\Ci4Core\Models\NotificationModel;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait NotificationsTrait
{

    public function __getNotificationsForms()
    {
        $formAttenteCount = (new NotificationModel())->getAllListeAttNotification(0);
        $formAttenteDetails = (new NotificationModel())->getAllListeAttNotification(1);

        $response =  [
            'forms' =>
            [
                'count' => count($formAttenteCount),
                'details' => $formAttenteDetails,
            ]
        ];
        // print_r($response);
        // exit;
        return $response;
    }


    // takes an array of model $returnTypes and returns an array of Audits, arranged by object and event
	// optionally filter by $events (string or array of strings)
	public function getNotifications($events = null, $limit = 10)
	{
        $instance = [];
        $this->notifications = new NotificationModel();
        if (!empty($events)) {
            $this->notifications->where('event', $events);
        }
        $this->notifications->where('notifiable_id', user()->id);
        $this->notifications->where('read_at', null);
        $notifications =  $this->notifications->get();
        $result =  $notifications->getResult();
        if (!empty($result)) {
            foreach ($result as $tes) {
                $instance[$tes->type]['count'] = count($result);
                $instance[$tes->type]['details'][] = new Notification((array)$tes);
            }
        }
        return $instance;
    }


     // takes an array of model $returnTypes and returns an array of Audits, arranged by object and event
	// optionally filter by $events (string or array of strings)
	public function getCountNotificationByType($type = null)
	{
        $instance = [];
        $this->notifications = new NotificationModel();
        if (!empty($events)) {
            $this->notifications->where('type', $type);
        }
        $this->notifications->where('read_at', null);
        $notifications =  $this->notifications->get();
        $result =  $notifications->getResult();
        if (!empty($result)) {
            foreach ($result as $tes) {
                $instance[$tes->type]['count'] = count($result);
            }
        }
        return $instance;
    }


    // record successful insert events
	protected function notificationAdminInsert($data)
	{
        if (! $data['result'])
            return false;

		$notification = [
			//'source'    => $this->table,
            //'source_id' => $this->db->insertID(),
            'uuid'            => service('uuid')->uuid4()->toString(),
            'event'           => $this->notifications,
            'type'            => $this->localizeFile,
            'notifiable_type' => 'App\Models\user',
            'notifiable_id'   => '1',  //TODO Notifie qu'aux admins et super admins
            'data'            => json_encode($data['data']),
            'read_at'         => NULL,
		
		];
		Services::notifications()->add($notification);
		return $data;
    } 
    
    protected function markAsReadAllNotification(string $event){
        Services::notifications()->markAsReadAll($event);

    }
}
