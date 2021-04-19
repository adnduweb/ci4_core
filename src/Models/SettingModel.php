<?php namespace Adnduweb\Ci4Core\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
	protected $table      = 'settings';
	protected $primaryKey = 'id';

	protected $returnType     = 'object';
	protected $localizeFile   = 'Adnduweb\Ci4_page\Models\SettingModel';
	protected $useSoftDeletes = true;

	protected $allowedFields = ['name', 'scope', 'content', 'summary', 'protected'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;


	public function getExist(string $name, string $scope, $content)
    {
        $builder = $this->db->table('settings');
        $setting =  $this->db->table('settings')->where(['name' => $name, 'scope' => $scope])->get()->getRow();
        if (empty($setting)) {
            $data = [
                'name'       => $name,
                'scope'      => $scope,
                'content'    => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
           $builder->insert($data);
        }else{
            $data = [
                'name'       => $name,
                'scope'      => $scope,
                'content'    => $content,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
           // print_r( $data);
           $builder->set($data);
           $builder->where(['id' => $setting->id]);
           return $builder->update();
        }
    }

}
