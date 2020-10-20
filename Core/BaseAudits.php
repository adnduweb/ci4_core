<?php

namespace Adnduweb\Ci4Core\Core;

use CodeIgniter\Config\BaseConfig;
use Adnduweb\Ci4Core\Models\AuditModel;

//use Adnduweb\Ci4Core\Exceptions\AuditsException;

/*** CLASS ***/
class BaseAudits
{
    /**
     * Our configuration instance.
     *
     * @var \Tatter\Audits\Config\Audits
     */
    protected $config;

    /**
     * Audit rows waiting to add to the database.
     *
     * @var array
     */
    protected $queue = [];

    /**
     * Store the configuration
     *
     * @param BaseConfig $config  The Audits configuration to use
     */
    public function __construct(BaseConfig $config)
    {
        $this->config = $config;
    }


    // checks for a logged in user based on config
    // returns user ID, 0 for "not logged in", -1 for CLI
    public function sessionUserId(): int
    {
        if (is_cli()) {
            return -1;
        }
        return session($this->config->sessionUserId) ?? 0;
    }

    // add an audit row to the queue
    public function add($audit)
    {
        if (empty($audit)) {
            return false;
        }

        // add common data
        $audit['user_id'] = $this->sessionUserId();
        $audit['created_at'] = date('Y-m-d H:i:s');
        // @TODO 2019-11-22 18:57:57
        //print_r($audit);
        $this->queue[] = $audit;
        if ($audit['event'] == "delete") {
            $audits = new AuditModel();
            $audits->insertBatch($this->queue);
        }
    }

    // batch insert all audits from the queue
    public function save(): self
    {
        if (! empty($this->queue))
		{
            //print_r($this->queue); exit;
            $audits = new AuditModel();
			$audits->insertBatch($this->queue);
		}

		return $this;
    }
}
