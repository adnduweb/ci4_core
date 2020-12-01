<?php

namespace Adnduweb\Ci4Core\Database\Seeds;
 
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use Adnduweb\Ci4Core\Models\LanguageModel;
use Adnduweb\Ci4Core\Models\CurrencyModel;
use Adnduweb\Ci4Core\Models\CountryModel;
use Adnduweb\Ci4Core\Models\TabModel;
use Adnduweb\Ci4Admin\Entities\User;
use Adnduweb\Ci4Admin\Models\UserModel;
use Adnduweb\Ci4Core\Models\CompanyModel;
use Adnduweb\Ci4Core\Models\SettingModel;
use Adnduweb\Ci4Core\Models\AuditModel;
use joshtronic\LoremIpsum;

class InitializeCore extends \CodeIgniter\Database\Seeder
{

    protected $uuid1;
    protected $uuid2;
    protected $uuid3;

    public function run()
    {
        //helper('common');

        cache()->clean();

        $this->createLanguages();

        $this->createCurrency();

        $this->createCrountry();

        $uuid_company = $this->createCompany();

        $this->createUsers($uuid_company);

        $this->createTabsBO();
    }

    public function createLanguages()
    {
        // Define default project langue
        $rows = [
            [
                'name'       => 'Français (French)',
                'active'      => 1,
                'iso_code'      => 'fr',
                'language_code'    => 'fr',
                'locale'    =>  'fr_FR',
                'date_format_lite'  => 'd/m/y',
                'date_format_full'  => 'd/m/y',
            ],
            [
                'name'       => 'Anglais',
                'active'      => 1,
                'iso_code'      => 'en',
                'language_code'    => 'en',
                'locale'    =>  'en_EN',
                'date_format_lite'  => 'y/m/d',
                'date_format_full'  => 'y/m/d',
            ]

        ];

        // Check for and create project langues
        $languages = new languageModel();
        foreach ($rows as $row) {
            $langue = $languages->where('name', $row['name'])->first();

            if (empty($langue)) {
                // No langue - add the row
                $languages->insert($row);
            }
        }
    }

    public function createCurrency()
    {
        // Define default project currency
        $rows = [
            [
                'name'       => 'Euro',
                'iso_code'      => 'EUR',
                'symbol'      => '€',
                'numeric_iso_code'    => 978,
                'precision'    =>  '2',
                'conversion_rate'  => '1.000000',
                'active'  => 1,
            ],
            [
                'name'       => 'Pound',
                'iso_code'      => 'GBP',
                'symbol'      => '£',
                'numeric_iso_code'    => 826,
                'precision'    =>  '2',
                'conversion_rate'  => '0.897195',
                'active'  => 1,
            ]

        ];

        // Check for and create project currency
        $devise = new CurrencyModel();
        foreach ($rows as $row) {
            $deviseRow = $devise->where('name', $row['name'])->first();

            if (empty($deviseRow)) {
                // No currency - add the row
                //print_r($devise);
                $devise->insert($row);
            }
        }
    }

    public function createCrountry()
    {
        // Define default project country
        $languages = new languageModel();
        $langues = $languages->get()->getResult();
        $i = 0;
        foreach ($langues as $langue) {
            foreach ($this->getCountry() as $key => $v) {
                $rows[$i]['id_lang'] = $langue->id;
                $rows[$i]['code_iso'] = $key;
                $rows[$i]['name'] = $v[$langue->iso_code];
                $i++;
            }
        }
        //print_r($rows); exit;

        // Check for and create project country
        $country = new CountryModel();
        foreach ($rows as $row) {
            $countryRow = $country->where('name', $row['name'])->first();

            if (empty($countryRow)) {
                // No langue - add the row
                $country->insert($row);
            }
        }
    }

    public function createUsers($uuid_company)
    {
        $this->uuid1  = service('uuid')->uuid4();
        $this->uuid2  = service('uuid')->uuid4();
        $rowsGroups = [
            [
                'id'                => 1,
                'name'              => 'super adminstrateur',
                'description'       => 'accès à tout',
                'login_destination' => 'dashboard',
                'is_hide'           => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 2,
                'name'              => 'adminstrateur',
                'description'       => 'accès administrateur type compte entreprise/client',
                'login_destination' => 'users',
                'is_hide'           => 0,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 3,
                'name'              => 'collaborateur',
                'description'       => 'accès à l\'adminstrtation simplifié avec permissions type compte Collaborateur',
                'login_destination' => 'dashboard',
                'is_hide'           => 0,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        // on insrére les groupes par défault
        $db = \Config\Database::connect();
        foreach ($rowsGroups as $row) {
            $tabRow =  $db->table('auth_groups')->where('name', $row['name'])->get()->getRow();
            if (empty($tabRow)) {
                // No langue - add the row
                $db->table('auth_groups')->insert($row);
            }
        }

        $rowsUsersSA = [
            'email'        => 'admin@admin.com',
            'username'     => 'Fabrice Loru',
            'lastname'     => 'Super',
            'firstname'    => 'Hero',
            'fonction'     => 'developer',
            'password'     => '123456',
            'phone'        => '+33 6 84 63 53 90',
            'phone_mobile' => '+33 6 84 63 53 90',
            'active'       => 1,
            'lang'         => 'fr',
            'id_pays'      => 74,
            //'uuid_company' => $uuid_company,
            'company_id'   => 1,
            'uuid'         => $this->uuid1->toString(),
            'is_principal' => 0
        ];
        $rowsUsersA = [
            'email'        => 'admin2@admin.com',
            'username'     => 'JohnC Doe',
            'lastname'     => 'Doe2',
            'firstname'    => 'John2',
            'fonction'     => 'marketing',
            'password'     => '123456',
            'phone'        => '+33 6 45 45 78 78',
            'phone_mobile' => '+33 2 45 45 45 45',
            'active'       => 1,
            'lang'         => 'fr',
            'id_pays'      => 74,
            //'uuid_company' => $uuid_company,
            'company_id'   => 1,
            'uuid'         => $this->uuid2->toString(),
            'is_principal' => 1
        ];

        // print_r($rowsUsersSA); exit;
        // On insére le user par défault
        $users = new UserModel(); 

        $userSA = new User($rowsUsersSA);
        // print_r($userSA); 
        // print_r($users->save($userSA)); exit;
        $users->save($userSA);

        $userA = new User($rowsUsersA);
        $users->save($userA);

        $rowsGroupsUsers = [
            [
                'group_id'          => 1,
                'user_id'           => 1,
            ],
            [
                'group_id'          => 2,
                'user_id'           => 1,
            ],
            [
                'group_id'          => 2,
                'user_id'           => 2,
            ],
        ];
        // On insére le role par default au user
        foreach ($rowsGroupsUsers as $row) {
            $tabRow =  $db->table('auth_groups_users')->where(['group_id' => $row['group_id'], 'user_id' => $row['user_id']])->get()->getRow();
            if (empty($tabRow)) {
                // No langue - add the row
                $db->table('auth_groups_users')->insert($row);
            }
        }



        // On créer les réglages par défault de l'application.
        $settings = new SettingModel();

        $setting = [
            [
                'name'       => 'setting_latestRelease',
                'scope'      => 'global',
                'content'    => '1.7.6',
                'protected'  => '0',
                'summary'    => 'Last version Adnduweb',
            ],
            [
                'name'       => 'setting_naneApp',
                'scope'      => 'global',
                'content'    => '1.7.6',
                'protected'  => '0',
                'summary'    => 'Last version Adnduweb',
            ],
            [
                'name'       => 'setting_id_lang',
                'scope'      => 'global',
                'content'    => '1',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_lang_iso',
                'scope'      => 'global',
                'content'    => 'fr',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_devise_default',
                'scope'      => 'global',
                'content'    => '1',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_notification_email',
                'scope'      => 'user',
                'content'    => '1',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_notification_sms',
                'scope'      => 'user',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_connexion_unique',
                'scope'      => 'user',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'settting_force_chgt_password',
                'scope'      => 'user',
                'content'    => '1',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_theme_admin',
                'scope'      => 'user',
                'content'    => 'metronic',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_theme_front',
                'scope'      => 'user',
                'content'    => 'default',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_maintenance',
                'scope'      => 'global',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_maintenance_ip_restrict',
                'scope'      => 'global',
                'content'    => '127,.0.0.1;78.214.106.109',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_env',
                'scope'      => 'global',
                'content'    => 'development',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_demo',
                'scope'      => 'global',
                'content'    => 'false',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_email_fromEmail',
                'scope'      => 'global',
                'content'    => 'fabrice@adnduweb.com',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_email_bcc',
                'scope'      => 'global',
                'content'    => 'fabrice@adnduweb.com',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_email_fromName',
                'scope'      => 'global',
                'content'    => 'Spread Aurora',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_mailchimp_api_key',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_mailchimp_id_list_1',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_mailchimp_id_list_2',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_sendinblue_api_key',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_sendinblue_partner_key',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_activer_front',
                'scope'      => 'global',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_activer_ecommerce',
                'scope'      => 'global',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_add_signature',
                'scope'      => 'global',
                'content'    => '1',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_activer_multilangue',
                'scope'      => 'global',
                'content'    => 0,
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_defaultLocale',
                'scope'      => 'global',
                'content'    => '1|fr',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_supportedLocales',
                'scope'      => 'global',
                'content'    => 'a:1:{i:0;s:4:"1|fr";}',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_image_thumbnail_size',
                'scope'      => 'global',
                'content'    => '150|150',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_image_small_size',
                'scope'      => 'global',
                'content'    => '300|300',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_image_medium_size',
                'scope'      => 'global',
                'content'    => '800|800',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_image_large_size',
                'scope'      => 'global',
                'content'    => '1024|1024',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_reseaux_facebook',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_reseaux_twitter',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_reseaux_instagram',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_reseaux_likedin',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ],
            [
                'name'       => 'setting_reseaux_viadeo',
                'scope'      => 'global',
                'content'    => '',
                'protected'  => '0',
                'summary'    => '',
            ], [
                'name'       => 'setting_aside_back',
                'scope'      => 'user',
                'content'    => '0',
                'protected'  => '0',
                'summary'    => '',
            ],



        ];
        foreach ($setting as $row) {
            $settings->save($row);
        }

        // Et les permissions
        $rowsPermissionsUsers = [
            [
                'name'              => 'CONFIGURE::view',
                'description'       => "Voir l'onglet administration",
                'is_natif'          => '1',
            ],
            [
                'name'              => 'LOCALIZATION::view',
                'description'       => "Voir l'onglet localisation",
                'is_natif'          => '1',
            ],
            [
                'name'              => 'ECOMMERCE::view',
                'description'       => "Voir l'onglet ecommerce",
                'is_natif'          => '1',
            ],
            [
                'name'              => 'STATISTIQUES::view',
                'description'       => "Voir l'onglet statistiques",
                'is_natif'          => '1',
            ],
            [
                'name'              => 'PUBLIC::view',
                'description'       => "Voir l'onglet public",
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Users::view',
                'description'       => 'Voir les utilisateurs',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Users::create',
                'description'       => 'Créer des utilisateurs',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Users::edit',
                'description'       => 'Modifier les utilisateurs',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Users::delete',
                'description'       => 'Supprimer des utilisateurs',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Users::ManageGroup',
                'description'       => 'L\'utilisateur peut changer de groupe',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Companies::view',
                'description'       => 'Voir les sociétés',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Companies::viewOnly',
                'description'       => 'Modifier les sociétés',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Companies::create',
                'description'       => 'Créer des sociétés',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Companies::edit',
                'description'       => 'Modifier les sociétés',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Companies::delete',
                'description'       => 'Supprimer des sociétés',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Groups::view',
                'description'       => 'Voir les groupes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Groups::create',
                'description'       => 'Créer des groupes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Groups::edit',
                'description'       => 'Modifier les groupes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Groups::delete',
                'description'       => 'Supprimer des groupes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Permissions::view',
                'description'       => 'Voir les permissions',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Permissions::create',
                'description'       => 'Créer des permissions',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Permissions::edit',
                'description'       => 'Modifier les permissions',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Permissions::delete',
                'description'       => 'Supprimer des permissions',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Routes::view',
                'description'       => 'Voir/editer les routes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Informations::view',
                'description'       => 'Voir les informations',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Settings::view',
                'description'       => 'Voir les réglages',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Settings::edit',
                'description'       => 'Modifier les réglages',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Translates::view',
                'description'       => 'Voir les traductions',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Currencies::view',
                'description'       => 'Voir les devises',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Currencies::create',
                'description'       => 'Créer des devises',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Currencies::edit',
                'description'       => 'Modifier les devises',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Currencies::delete',
                'description'       => 'Supprimer des devises',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Taxes::view',
                'description'       => 'Voir les taxes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Taxes::create',
                'description'       => 'Créer des taxes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Taxes::edit',
                'description'       => 'Modifier les taxes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Taxes::delete',
                'description'       => 'Supprimer des taxes',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'Logs::view',
                'description'       => 'Voir les logs',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'FicheContact::view',
                'description'       => 'Voir la fiche entreprise',
                'is_natif'          => '1',
            ],
            [
                'name'              => 'FicheContact::edit',
                'description'       => 'Modifier la fiiche entreprise',
                'is_natif'          => '1',
            ],
        ];
        // On insére le role par default au user
        foreach ($rowsPermissionsUsers as $row) {
            $tabRow =  $db->table('auth_permissions')->where(['name' => $row['name']])->get()->getRow();
            if (empty($tabRow)) {
                // No langue - add the row
                $db->table('auth_permissions')->insert($row);
            }
        }


        // on insrére les persmissions par groupes par défault
        $rowsGroupsPermissions = [

            [
                'group_id'      => 2,
                'permission_id' => 1,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 2,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 3,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 4,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 12,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 24,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 25,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 26,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 27,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 28,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 29,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 30,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 31,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 33,
            ],
            [
                'group_id'      => 2,
                'permission_id' => 34,
            ],


        ];
        // On insére le role par default au user
        foreach ($rowsGroupsPermissions as $row) {
            $tabRow =  $db->table('auth_groups_permissions')->where(['group_id' => $row['group_id'], 'permission_id' => $row['permission_id']])->get()->getRow();
            if (empty($tabRow)) {
                // No langue - add the row
                $db->table('auth_groups_permissions')->insert($row);
            }
        }
    }

    public function createTabsBO()
    {

        // Création du menu en BO
        $rows = [
            [
                'id'        => 1,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 4,
                'right'     => 5,
                'position'  => 1,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'dashboard',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M5.84026576,8 L18.1597342,8 C19.1999115,8 20.0664437,8.79732479 20.1528258,9.83390904 L20.8194924,17.833909 C20.9112219,18.9346631 20.0932459,19.901362 18.9924919,19.9930915 C18.9372479,19.9976952 18.8818364,20 18.8264009,20 L5.1735991,20 C4.0690296,20 3.1735991,19.1045695 3.1735991,18 C3.1735991,17.9445645 3.17590391,17.889153 3.18050758,17.833909 L3.84717425,9.83390904 C3.93355627,8.79732479 4.80008849,8 5.84026576,8 Z M10.5,10 C10.2238576,10 10,10.2238576 10,10.5 L10,11.5 C10,11.7761424 10.2238576,12 10.5,12 L13.5,12 C13.7761424,12 14,11.7761424 14,11.5 L14,10.5 C14,10.2238576 13.7761424,10 13.5,10 L10.5,10 Z" fill="#000000" />
                                                    <path d="M10,8 L8,8 L8,7 C8,5.34314575 9.34314575,4 11,4 L13,4 C14.6568542,4 16,5.34314575 16,7 L16,8 L14,8 L14,7 C14,6.44771525 13.5522847,6 13,6 L11,6 C10.4477153,6 10,6.44771525 10,7 L10,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                </g>
                                        </svg>',
                'slug' => 'dashboard',
            ],
            [
                'id'        => 2,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 26,
                'right'     => 45,
                'position'  => 2,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => NULL,
                'class_name'      => 'CONFIGURE',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">         <rect x="0" y="0" width="24" height="24"></rect>         <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>         <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path>     </g> </svg>',
                'slug'      => 'settings-advanced',
            ],
            [
                'id'        => 3,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 27,
                'right'     => 28,
                'position'  => 1,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'company',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'companies',
            ],
            [
                'id'        => 4,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 8,
                'right'     => 9,
                'position'  => 2,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'user',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                        </g>
                                    </svg>',
                'slug'             => 'users',
                'name_controller'       => ''
            ],
            [
                'id'        => 5,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 29,
                'right'     => 30,
                'position'  => 3,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'permission',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'permissions',
            ],
            [
                'id'        => 6,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 31,
                'right'     => 32,
                'position'  => 4,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'role',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'groups',
            ],
            [
                'id'        => 7,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 33,
                'right'     => 34,
                'position'  => 6,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'information',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'informations',
            ],
            [
                'id'              => 8,
                'id_parent'       => 2,
                'depth'           => 2,
                'left'            => 35,
                'right'           => 36,
                'position'        => 6,
                'section'         => 0,
                'module'          => NULL,
                'namespace'       => 'App\Controllers\Admin',
                'class_name'            => 'module',
                'active'          => 0,
                'icon'            => '',
                'slug'            => 'modules',
            ],
            [
                'id'        => 9,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 37,
                'right'     => 38,
                'position'  => 7,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'setting',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'settings',
            ],
            [
                'id'        => 10,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 39,
                'right'     => 40,
                'position'  => 7,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'log',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'logs',
            ],
            [
                'id'        => 11,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 41,
                'right'     => 42,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'tab',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'nav',
            ],
            [
                'id'        => 12,
                'id_parent' => 2,
                'depth'     => 2,
                'left'      => 43,
                'right'     => 44,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'route',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'routes',
            ],
            [
                'id'         => 13,
                'id_parent'  => 0,
                'depth'      => 1,
                'left'       => 46,
                'right'      => 53,
                'position'   => 2,
                'section'    => 0,
                'module'    => NULL,
                'namespace' => NULL,
                'class_name' => 'LOCALIZATION',
                'active'     => 1,
                'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M13,17.0484323 L13,18 L14,18 C15.1045695,18 16,18.8954305 16,20 L8,20 C8,18.8954305 8.8954305,18 10,18 L11,18 L11,17.0482312 C6.89844817,16.5925472 3.58685702,13.3691811 3.07555009,9.22038742 C3.00799634,8.67224972 3.3975866,8.17313318 3.94572429,8.10557943 C4.49386199,8.03802567 4.99297853,8.42761593 5.06053229,8.97575363 C5.4896663,12.4577884 8.46049164,15.1035129 12.0008191,15.1035129 C15.577644,15.1035129 18.5681939,12.4043008 18.9524872,8.87772126 C19.0123158,8.32868667 19.505897,7.93210686 20.0549316,7.99193546 C20.6039661,8.05176407 21.000546,8.54534521 20.9407173,9.09437981 C20.4824216,13.3000638 17.1471597,16.5885839 13,17.0484323 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path d="M12,14 C8.6862915,14 6,11.3137085 6,8 C6,4.6862915 8.6862915,2 12,2 C15.3137085,2 18,4.6862915 18,8 C18,11.3137085 15.3137085,14 12,14 Z M8.81595773,7.80077353 C8.79067542,7.43921955 8.47708263,7.16661749 8.11552864,7.19189981 C7.75397465,7.21718213 7.4813726,7.53077492 7.50665492,7.89232891 C7.62279197,9.55316612 8.39667037,10.8635466 9.79502238,11.7671393 C10.099435,11.9638458 10.5056723,11.8765328 10.7023788,11.5721203 C10.8990854,11.2677077 10.8117724,10.8614704 10.5073598,10.6647638 C9.4559885,9.98538454 8.90327706,9.04949813 8.81595773,7.80077353 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>',
                'slug' => 'international',
            ],
            [
                'id'        => 14,
                'id_parent' => 13,
                'depth'     => 2,
                'left'      => 47,
                'right'     => 48,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'translate',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'translate',
            ],
            [
                'id'        => 15,
                'id_parent' => 13,
                'depth'     => 2,
                'left'      => 49,
                'right'     => 50,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'currency',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'currencies',
            ],
            [
                'id'        => 16,
                'id_parent' => 13,
                'depth'     => 2,
                'left'      => 51,
                'right'     => 52,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'taxe',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'taxes',
            ],
            [
                'id'        => 17,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 12,
                'right'     => 25,
                'position'  => 4,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => NULL,
                'class_name'      => 'PUBLIC',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="9"></circle>
                                                <path d="M11.7357634,20.9961946 C6.88740052,20.8563914 3,16.8821712 3,12 C3,11.9168367 3.00112797,11.8339369 3.00336944,11.751315 C3.66233009,11.8143341 4.85636818,11.9573854 4.91262842,12.4204038 C4.9904938,13.0609191 4.91262842,13.8615942 5.45804656,14.101772 C6.00346469,14.3419498 6.15931561,13.1409372 6.6267482,13.4612567 C7.09418079,13.7815761 8.34086797,14.0899175 8.34086797,14.6562185 C8.34086797,15.222396 8.10715168,16.1034596 8.34086797,16.2636193 C8.57458427,16.423779 9.5089688,17.54465 9.50920913,17.7048097 C9.50956962,17.8649694 9.83857487,18.6793513 9.74040201,18.9906563 C9.65905192,19.2487394 9.24857641,20.0501554 8.85059781,20.4145589 C9.75315358,20.7620621 10.7235846,20.9657742 11.7357634,20.9960544 L11.7357634,20.9961946 Z M8.28272988,3.80112099 C9.4158415,3.28656421 10.6744554,3 12,3 C15.5114513,3 18.5532143,5.01097452 20.0364482,7.94408274 C20.069657,8.72412177 20.0638332,9.39135321 20.2361262,9.6327358 C21.1131932,10.8600506 18.0995147,11.7043158 18.5573343,13.5605384 C18.7589671,14.3794892 16.5527814,14.1196773 16.0139722,14.886394 C15.4748026,15.6527403 14.1574598,15.137809 13.8520064,14.9904917 C13.546553,14.8431744 12.3766497,15.3341497 12.4789081,14.4995164 C12.5805657,13.664636 13.2922889,13.6156126 14.0555619,13.2719546 C14.8184743,12.928667 15.9189236,11.7871741 15.3781918,11.6380045 C12.8323064,10.9362407 11.963771,8.47852395 11.963771,8.47852395 C11.8110443,8.44901109 11.8493762,6.74109366 11.1883616,6.69207022 C10.5267462,6.64279981 10.170464,6.88841096 9.20435656,6.69207022 C8.23764828,6.49572949 8.44144409,5.85743687 8.2887174,4.48255778 C8.25453994,4.17415686 8.25619136,3.95717082 8.28272988,3.80112099 Z M20.9991771,11.8770357 C20.9997251,11.9179585 21,11.9589471 21,12 C21,16.9406923 17.0188468,20.9515364 12.0895088,20.9995641 C16.970233,20.9503326 20.9337111,16.888438 20.9991771,11.8770357 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>',
                'slug' => 'public',
            ],
            [
                'id'        => 18,
                'id_parent' => 17,
                'depth'     => 2,
                'left'      => 13,
                'right'     => 14,
                'position'  => 3,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'media',
                'active'    => 1,
                'icon'      => '',
                'slug'      => 'medias',
            ],
            [
                'id'        => 19,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 7,
                'right'     => 5,
                'position'  => 0,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'ficheContact',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z" fill="#000000"/>
                                        </g>
                                    </svg>',
                'slug'            => 'fiche-contact/compte-entreprise',
            ],
            [
                'id'        => 20,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 10,
                'right'     => 11,
                'position'  => 3,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => NULL,
                'class_name'      => 'ECOMMERCE',
                'active'    => 1,
                'icon'      => '<span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/home/keenthemes/www/metronic/themes/metronic/theme/html/demo1/dist/../src/media/svg/icons/Shopping/ATM.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="5" rx="1"/>
                                                <path d="M5,7 L8,7 L8,21 L7,21 C5.8954305,21 5,20.1045695 5,19 L5,7 Z M19,7 L19,19 C19,20.1045695 18.1045695,21 17,21 L11,21 L11,7 L19,7 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>',
                'slug' => 'ecommerce',
            ],
            [
                'id'        => 21,
                'id_parent' => 0,
                'depth'     => 1,
                'left'      => 2,
                'right'     => 3,
                'position'  => 5,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => NULL,
                'class_name'      => 'STATISTIQUES',
                'active'    => 0,
                'icon'      => '<span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/home/keenthemes/www/metronic/themes/metronic/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(14.000019, 10.749981) scale(1, -1) translate(-14.000019, -10.749981) "/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>',
                'slug' => 'statistiques',
            ],
            [
                'id'        => 22,
                'id_parent' => 17,
                'depth'     => 2,
                'left'      => 17,
                'right'     => 18,
                'position'  => 4,
                'section'   => 0,
                'module'    => NULL,
                'namespace' => 'App\Controllers\Admin',
                'class_name'      => 'form',
                'active'    => 1,
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="9"></circle>
                                                <path d="M11.7357634,20.9961946 C6.88740052,20.8563914 3,16.8821712 3,12 C3,11.9168367 3.00112797,11.8339369 3.00336944,11.751315 C3.66233009,11.8143341 4.85636818,11.9573854 4.91262842,12.4204038 C4.9904938,13.0609191 4.91262842,13.8615942 5.45804656,14.101772 C6.00346469,14.3419498 6.15931561,13.1409372 6.6267482,13.4612567 C7.09418079,13.7815761 8.34086797,14.0899175 8.34086797,14.6562185 C8.34086797,15.222396 8.10715168,16.1034596 8.34086797,16.2636193 C8.57458427,16.423779 9.5089688,17.54465 9.50920913,17.7048097 C9.50956962,17.8649694 9.83857487,18.6793513 9.74040201,18.9906563 C9.65905192,19.2487394 9.24857641,20.0501554 8.85059781,20.4145589 C9.75315358,20.7620621 10.7235846,20.9657742 11.7357634,20.9960544 L11.7357634,20.9961946 Z M8.28272988,3.80112099 C9.4158415,3.28656421 10.6744554,3 12,3 C15.5114513,3 18.5532143,5.01097452 20.0364482,7.94408274 C20.069657,8.72412177 20.0638332,9.39135321 20.2361262,9.6327358 C21.1131932,10.8600506 18.0995147,11.7043158 18.5573343,13.5605384 C18.7589671,14.3794892 16.5527814,14.1196773 16.0139722,14.886394 C15.4748026,15.6527403 14.1574598,15.137809 13.8520064,14.9904917 C13.546553,14.8431744 12.3766497,15.3341497 12.4789081,14.4995164 C12.5805657,13.664636 13.2922889,13.6156126 14.0555619,13.2719546 C14.8184743,12.928667 15.9189236,11.7871741 15.3781918,11.6380045 C12.8323064,10.9362407 11.963771,8.47852395 11.963771,8.47852395 C11.8110443,8.44901109 11.8493762,6.74109366 11.1883616,6.69207022 C10.5267462,6.64279981 10.170464,6.88841096 9.20435656,6.69207022 C8.23764828,6.49572949 8.44144409,5.85743687 8.2887174,4.48255778 C8.25453994,4.17415686 8.25619136,3.95717082 8.28272988,3.80112099 Z M20.9991771,11.8770357 C20.9997251,11.9179585 21,11.9589471 21,12 C21,16.9406923 17.0188468,20.9515364 12.0895088,20.9995641 C16.970233,20.9503326 20.9337111,16.888438 20.9991771,11.8770357 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>',
                'slug' => 'forms',
            ],
        ];

        // Check for and create project langue templates
        $tab = new TabModel();
        $db = \Config\Database::connect();
        $languages = new languageModel();
        $langues = $languages->get()->getResult();
        foreach ($rows as $row) {
            $tabRow = $tab->where('class_name', $row['class_name'])->first();

            if (empty($tabRow)) {
                // No langue - add the row
                //print_r($row); exit;
                $tab->insert($row);
                $i = 0;
                $newInsert = $tab->insertID();
                $tabBO = $this->getTabBO();
                foreach ($langues as $langue) {
                    $rowsLang[$i]['tab_id']   = $newInsert;
                    $rowsLang[$i]['id_lang']  = $langue->id;
                    $rowsLang[$i]['name']     =  $tabBO[$row['class_name']][$langue->iso_code];
                    $i++;
                }
                //print_r($rowsLang); exit;
                foreach ($rowsLang as $rowLang) {
                    $db->table('tabs_langs')->insert($rowLang);
                }
            }
        }
    }

    public function createCompany()
    {
        $lipsum = new LoremIpsum();
        $this->uuid3  = service('uuid')->uuid4();
        $db = \Config\Database::connect();

        // Define default project Company Type
        $rowsCompanyType = [
            [
                'id'       => 1,
                'nom_court'             => 'SARL',
                'nom_long'              => 'Société à responsabilité limitée',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 2,
                'nom_court'             => 'EURL',
                'nom_long'              => 'Entreprise unipersonnelle à responsabilité limitée',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 3,
                'nom_court'             => 'SELARL',
                'nom_long'              => "Société d'exercice libéral à responsabilité limitée",
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 4,
                'nom_court'             => 'SA',
                'nom_long'              => 'Société anonyme',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 5,
                'nom_court'             => 'SAS',
                'nom_long'              => 'Société par actions simplifiée',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 6,
                'nom_court'             => 'SASU',
                'nom_long'              => 'Société par actions simplifiée unipersonnelle',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 7,
                'nom_court'             => 'SNC',
                'nom_long'              => 'Société en nom collectif',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'       => 8,
                'nom_court'             => 'SCP',
                'nom_long'              => 'Société civile professionnelle',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 9,
                'nom_court'  => 'ME',
                'nom_long'   => 'Micro entreprise',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

        ];

        // Check for and create project company Type
        foreach ($rowsCompanyType as $row) {
            $companyRow = $db->table('companies_type')->where('nom_court', $row['nom_court'])->get()->getRow();

            if (empty($companyRow)) {
                // No company type - add the row
                $db->table('companies_type')->insert($row);
            }
        }

        $rowsCompany = [
            [
                'id'               => 1,
                'uuid_company'     => $this->uuid3->toString(),
                'company_type_id'  => 1,
                'country_id'       => 74,
                'raison_social'    => 'Company Name',
                'email'            => 'contact@exemple.fr',
                'adresse'          => '3, street Eminem',
                'adresse2'         => '',
                'ville'            => 'Houston',
                'code_postal'      => '2456 67',
                'telephone_fixe'   => '+33684521245',
                'telephone_mobile' => '+33684521245',
                'fax'              => '+33684521245',
                'siret'            => '78979907890',
                'ape'              => 'z4567',
                'tva'              => 1,
                'is_ttc'           => 0,
                'bio'              => $lipsum->sentence(),
                'logo'             => '',
                'active'           => 1
            ]
        ];

        // Check for and create project company
        $company = new CompanyModel();
        foreach ($rowsCompany as $row) {
            $companyRow = $company->where('raison_social', $row['raison_social'])->first();
            if (empty($companyRow)) {
                // No company - add the row
                $company->insert($row);
            }
        }

        $rowsCompanyLangs = [
            [
                'company_id'         => 1,
                'id_lang'  => 1,
                'bio'   => 'Je suis une bio'
            ],
        ];

        // Check for and create project company Type
        foreach ($rowsCompanyLangs as $row) {
            $companyRow = $db->table('companies_langs')->where('company_id', $row['company_id'])->get()->getRow();

            if (empty($companyRow)) {
                // No company type - add the row
                $db->table('companies_langs')->insert($row);
            }
        }
        return $this->uuid3->toString();
    }

    public function getCountry()
    {
        $countryList = array(
            'AD' => array('en' => 'Andorra', 'fr' => 'Andorre'),
            'AE' => array('en' => 'United Arab Emirates', 'fr' => 'Émirats arabes unis'),
            'AF' => array('en' => 'Afghanistan', 'fr' => 'Afghanistan'),
            'AG' => array('en' => 'Antigua and Barbuda', 'fr' => 'Antigua-et-Barbuda'),
            'AI' => array('en' => 'Anguilla', 'fr' => 'Anguilla'),
            'AL' => array('en' => 'Albania', 'fr' => 'Albanie'),
            'AM' => array('en' => 'Armenia', 'fr' => 'Arménie'),
            'AN' => array('en' => 'Netherlands Antilles', 'fr' => 'Antilles néerlandaises'),
            'AO' => array('en' => 'Angola', 'fr' => 'Angola'),
            'AQ' => array('en' => 'Antarctica', 'fr' => 'Antarctique'),
            'AR' => array('en' => 'Argentina', 'fr' => 'Argentine'),
            'AS' => array('en' => 'American Samoa', 'fr' => 'Samoa américaines'),
            'AT' => array('en' => 'Austria', 'fr' => 'Autriche'),
            'AU' => array('en' => 'Australia', 'fr' => 'Australie'),
            'AW' => array('en' => 'Aruba', 'fr' => 'Aruba'),
            'AX' => array('en' => 'Åland Islands', 'fr' => 'Îles Åland'),
            'AZ' => array('en' => 'Azerbaijan', 'fr' => 'Azerbaïdjan'),
            'BA' => array('en' => 'Bosnia and Herzegovina', 'fr' => 'Bosnie-Herzégovine'),
            'BB' => array('en' => 'Barbados', 'fr' => 'Barbade'),
            'BD' => array('en' => 'Bangladesh', 'fr' => 'Bangladesh'),
            'BE' => array('en' => 'Belgium', 'fr' => 'Belgique'),
            'BF' => array('en' => 'Burkina Faso', 'fr' => 'Burkina Faso'),
            'BG' => array('en' => 'Bulgaria', 'fr' => 'Bulgarie'),
            'BH' => array('en' => 'Bahrain', 'fr' => 'Bahreïn'),
            'BI' => array('en' => 'Burundi', 'fr' => 'Burundi'),
            'BJ' => array('en' => 'Benin', 'fr' => 'Bénin'),
            'BL' => array('en' => 'Saint Barthélemy', 'fr' => 'Saint-Barthélémy'),
            'BM' => array('en' => 'Bermuda', 'fr' => 'Bermudes'),
            'BN' => array('en' => 'Brunei', 'fr' => 'Brunéi Darussalam'),
            'BO' => array('en' => 'Bolivia', 'fr' => 'Bolivie'),
            'BR' => array('en' => 'Brazil', 'fr' => 'Brésil'),
            'BS' => array('en' => 'Bahamas', 'fr' => 'Bahamas'),
            'BT' => array('en' => 'Bhutan', 'fr' => 'Bhoutan'),
            'BV' => array('en' => 'Bouvet Island', 'fr' => 'Île Bouvet'),
            'BW' => array('en' => 'Botswana', 'fr' => 'Botswana'),
            'BY' => array('en' => 'Belarus', 'fr' => 'Bélarus'),
            'BZ' => array('en' => 'Belize', 'fr' => 'Belize'),
            'CA' => array('en' => 'Canada', 'fr' => 'Canada'),
            'CC' => array('en' => 'Cocos [Keeling] Islands', 'fr' => 'Îles Cocos - Keeling'),
            'CD' => array('en' => 'Congo - Kinshasa', 'fr' => 'République démocratique du Congo'),
            'CF' => array('en' => 'Central African Republic', 'fr' => 'République centrafricaine'),
            'CG' => array('en' => 'Congo - Brazzaville', 'fr' => 'Congo'),
            'CH' => array('en' => 'Switzerland', 'fr' => 'Suisse'),
            'CI' => array('en' => 'Côte d’Ivoire', 'fr' => 'Côte d’Ivoire'),
            'CK' => array('en' => 'Cook Islands', 'fr' => 'Îles Cook'),
            'CL' => array('en' => 'Chile', 'fr' => 'Chili'),
            'CM' => array('en' => 'Cameroon', 'fr' => 'Cameroun'),
            'CN' => array('en' => 'China', 'fr' => 'Chine'),
            'CO' => array('en' => 'Colombia', 'fr' => 'Colombie'),
            'CR' => array('en' => 'Costa Rica', 'fr' => 'Costa Rica'),
            'CU' => array('en' => 'Cuba', 'fr' => 'Cuba'),
            'CV' => array('en' => 'Cape Verde', 'fr' => 'Cap-Vert'),
            'CX' => array('en' => 'Christmas Island', 'fr' => 'Île Christmas'),
            'CY' => array('en' => 'Cyprus', 'fr' => 'Chypre'),
            'CZ' => array('en' => 'Czech Republic', 'fr' => 'République tchèque'),
            'DE' => array('en' => 'Germany', 'fr' => 'Allemagne'),
            'DJ' => array('en' => 'Djibouti', 'fr' => 'Djibouti'),
            'DK' => array('en' => 'Denmark', 'fr' => 'Danemark'),
            'DM' => array('en' => 'Dominica', 'fr' => 'Dominique'),
            'DO' => array('en' => 'Dominican Republic', 'fr' => 'République dominicaine'),
            'DZ' => array('en' => 'Algeria', 'fr' => 'Algérie'),
            'EC' => array('en' => 'Ecuador', 'fr' => 'Équateur'),
            'EE' => array('en' => 'Estonia', 'fr' => 'Estonie'),
            'EG' => array('en' => 'Egypt', 'fr' => 'Égypte'),
            'EH' => array('en' => 'Western Sahara', 'fr' => 'Sahara occidental'),
            'ER' => array('en' => 'Eritrea', 'fr' => 'Érythrée'),
            'ES' => array('en' => 'Spain', 'fr' => 'Espagne'),
            'ET' => array('en' => 'Ethiopia', 'fr' => 'Éthiopie'),
            'FI' => array('en' => 'Finland', 'fr' => 'Finlande'),
            'FJ' => array('en' => 'Fiji', 'fr' => 'Fidji'),
            'FK' => array('en' => 'Falkland Islands', 'fr' => 'Îles Malouines'),
            'FM' => array('en' => 'Micronesia', 'fr' => 'États fédérés de Micronésie'),
            'FO' => array('en' => 'Faroe Islands', 'fr' => 'Îles Féroé'),
            'FR' => array('en' => 'France', 'fr' => 'France'),
            'GA' => array('en' => 'Gabon', 'fr' => 'Gabon'),
            'GB' => array('en' => 'United Kingdom', 'fr' => 'Royaume-Uni'),
            'GD' => array('en' => 'Grenada', 'fr' => 'Grenade'),
            'GE' => array('en' => 'Georgia', 'fr' => 'Géorgie'),
            'GF' => array('en' => 'French Guiana', 'fr' => 'Guyane française'),
            'GG' => array('en' => 'Guernsey', 'fr' => 'Guernesey'),
            'GH' => array('en' => 'Ghana', 'fr' => 'Ghana'),
            'GI' => array('en' => 'Gibraltar', 'fr' => 'Gibraltar'),
            'GL' => array('en' => 'Greenland', 'fr' => 'Groenland'),
            'GM' => array('en' => 'Gambia', 'fr' => 'Gambie'),
            'GN' => array('en' => 'Guinea', 'fr' => 'Guinée'),
            'GP' => array('en' => 'Guadeloupe', 'fr' => 'Guadeloupe'),
            'GQ' => array('en' => 'Equatorial Guinea', 'fr' => 'Guinée équatoriale'),
            'GR' => array('en' => 'Greece', 'fr' => 'Grèce'),
            'GS' => array('en' => 'South Georgia and the South Sandwich Islands', 'fr' => 'Géorgie du Sud et les îles Sandwich du Sud'),
            'GT' => array('en' => 'Guatemala', 'fr' => 'Guatemala'),
            'GU' => array('en' => 'Guam', 'fr' => 'Guam'),
            'GW' => array('en' => 'Guinea-Bissau', 'fr' => 'Guinée-Bissau'),
            'GY' => array('en' => 'Guyana', 'fr' => 'Guyana'),
            'HK' => array('en' => 'Hong Kong SAR China', 'fr' => 'R.A.S. chinoise de Hong Kong'),
            'HM' => array('en' => 'Heard Island and McDonald Islands', 'fr' => 'Îles Heard et MacDonald'),
            'HN' => array('en' => 'Honduras', 'fr' => 'Honduras'),
            'HR' => array('en' => 'Croatia', 'fr' => 'Croatie'),
            'HT' => array('en' => 'Haiti', 'fr' => 'Haïti'),
            'HU' => array('en' => 'Hungary', 'fr' => 'Hongrie'),
            'ID' => array('en' => 'Indonesia', 'fr' => 'Indonésie'),
            'IE' => array('en' => 'Ireland', 'fr' => 'Irlande'),
            'IL' => array('en' => 'Israel', 'fr' => 'Israël'),
            'IM' => array('en' => 'Isle of Man', 'fr' => 'Île de Man'),
            'IN' => array('en' => 'India', 'fr' => 'Inde'),
            'IO' => array('en' => 'British Indian Ocean Territory', 'fr' => 'Territoire britannique de l\'océan Indien'),
            'IQ' => array('en' => 'Iraq', 'fr' => 'Irak'),
            'IR' => array('en' => 'Iran', 'fr' => 'Iran'),
            'IS' => array('en' => 'Iceland', 'fr' => 'Islande'),
            'IT' => array('en' => 'Italy', 'fr' => 'Italie'),
            'JE' => array('en' => 'Jersey', 'fr' => 'Jersey'),
            'JM' => array('en' => 'Jamaica', 'fr' => 'Jamaïque'),
            'JO' => array('en' => 'Jordan', 'fr' => 'Jordanie'),
            'JP' => array('en' => 'Japan', 'fr' => 'Japon'),
            'KE' => array('en' => 'Kenya', 'fr' => 'Kenya'),
            'KG' => array('en' => 'Kyrgyzstan', 'fr' => 'Kirghizistan'),
            'KH' => array('en' => 'Cambodia', 'fr' => 'Cambodge'),
            'KI' => array('en' => 'Kiribati', 'fr' => 'Kiribati'),
            'KM' => array('en' => 'Comoros', 'fr' => 'Comores'),
            'KN' => array('en' => 'Saint Kitts and Nevis', 'fr' => 'Saint-Kitts-et-Nevis'),
            'KP' => array('en' => 'North Korea', 'fr' => 'Corée du Nord'),
            'KR' => array('en' => 'South Korea', 'fr' => 'Corée du Sud'),
            'KW' => array('en' => 'Kuwait', 'fr' => 'Koweït'),
            'KY' => array('en' => 'Cayman Islands', 'fr' => 'Îles Caïmans'),
            'KZ' => array('en' => 'Kazakhstan', 'fr' => 'Kazakhstan'),
            'LA' => array('en' => 'Laos', 'fr' => 'Laos'),
            'LB' => array('en' => 'Lebanon', 'fr' => 'Liban'),
            'LC' => array('en' => 'Saint Lucia', 'fr' => 'Sainte-Lucie'),
            'LI' => array('en' => 'Liechtenstein', 'fr' => 'Liechtenstein'),
            'LK' => array('en' => 'Sri Lanka', 'fr' => 'Sri Lanka'),
            'LR' => array('en' => 'Liberia', 'fr' => 'Libéria'),
            'LS' => array('en' => 'Lesotho', 'fr' => 'Lesotho'),
            'LT' => array('en' => 'Lithuania', 'fr' => 'Lituanie'),
            'LU' => array('en' => 'Luxembourg', 'fr' => 'Luxembourg'),
            'LV' => array('en' => 'Latvia', 'fr' => 'Lettonie'),
            'LY' => array('en' => 'Libya', 'fr' => 'Libye'),
            'MA' => array('en' => 'Morocco', 'fr' => 'Maroc'),
            'MC' => array('en' => 'Monaco', 'fr' => 'Monaco'),
            'MD' => array('en' => 'Moldova', 'fr' => 'Moldavie'),
            'ME' => array('en' => 'Montenegro', 'fr' => 'Monténégro'),
            'MF' => array('en' => 'Saint Martin', 'fr' => 'Saint-Martin'),
            'MG' => array('en' => 'Madagascar', 'fr' => 'Madagascar'),
            'MH' => array('en' => 'Marshall Islands', 'fr' => 'Îles Marshall'),
            'MK' => array('en' => 'Macedonia', 'fr' => 'Macédoine'),
            'ML' => array('en' => 'Mali', 'fr' => 'Mali'),
            'MM' => array('en' => 'Myanmar [Burma]', 'fr' => 'Myanmar'),
            'MN' => array('en' => 'Mongolia', 'fr' => 'Mongolie'),
            'MO' => array('en' => 'Macau SAR China', 'fr' => 'R.A.S. chinoise de Macao'),
            'MP' => array('en' => 'Northern Mariana Islands', 'fr' => 'Îles Mariannes du Nord'),
            'MQ' => array('en' => 'Martinique', 'fr' => 'Martinique'),
            'MR' => array('en' => 'Mauritania', 'fr' => 'Mauritanie'),
            'MS' => array('en' => 'Montserrat', 'fr' => 'Montserrat'),
            'MT' => array('en' => 'Malta', 'fr' => 'Malte'),
            'MU' => array('en' => 'Mauritius', 'fr' => 'Maurice'),
            'MV' => array('en' => 'Maldives', 'fr' => 'Maldives'),
            'MW' => array('en' => 'Malawi', 'fr' => 'Malawi'),
            'MX' => array('en' => 'Mexico', 'fr' => 'Mexique'),
            'MY' => array('en' => 'Malaysia', 'fr' => 'Malaisie'),
            'MZ' => array('en' => 'Mozambique', 'fr' => 'Mozambique'),
            'NA' => array('en' => 'Namibia', 'fr' => 'Namibie'),
            'NC' => array('en' => 'New Caledonia', 'fr' => 'Nouvelle-Calédonie'),
            'NE' => array('en' => 'Niger', 'fr' => 'Niger'),
            'NF' => array('en' => 'Norfolk Island', 'fr' => 'Île Norfolk'),
            'NG' => array('en' => 'Nigeria', 'fr' => 'Nigéria'),
            'NI' => array('en' => 'Nicaragua', 'fr' => 'Nicaragua'),
            'NL' => array('en' => 'Netherlands', 'fr' => 'Pays-Bas'),
            'NO' => array('en' => 'Norway', 'fr' => 'Norvège'),
            'NP' => array('en' => 'Nepal', 'fr' => 'Népal'),
            'NR' => array('en' => 'Nauru', 'fr' => 'Nauru'),
            'NU' => array('en' => 'Niue', 'fr' => 'Niue'),
            'NZ' => array('en' => 'New Zealand', 'fr' => 'Nouvelle-Zélande'),
            'OM' => array('en' => 'Oman', 'fr' => 'Oman'),
            'PA' => array('en' => 'Panama', 'fr' => 'Panama'),
            'PE' => array('en' => 'Peru', 'fr' => 'Pérou'),
            'PF' => array('en' => 'French Polynesia', 'fr' => 'Polynésie française'),
            'PG' => array('en' => 'Papua New Guinea', 'fr' => 'Papouasie-Nouvelle-Guinée'),
            'PH' => array('en' => 'Philippines', 'fr' => 'Philippines'),
            'PK' => array('en' => 'Pakistan', 'fr' => 'Pakistan'),
            'PL' => array('en' => 'Poland', 'fr' => 'Pologne'),
            'PM' => array('en' => 'Saint Pierre and Miquelon', 'fr' => 'Saint-Pierre-et-Miquelon'),
            'PN' => array('en' => 'Pitcairn Islands', 'fr' => 'Pitcairn'),
            'PR' => array('en' => 'Puerto Rico', 'fr' => 'Porto Rico'),
            'PS' => array('en' => 'Palestinian Territories', 'fr' => 'Territoire palestinien'),
            'PT' => array('en' => 'Portugal', 'fr' => 'Portugal'),
            'PW' => array('en' => 'Palau', 'fr' => 'Palaos'),
            'PY' => array('en' => 'Paraguay', 'fr' => 'Paraguay'),
            'QA' => array('en' => 'Qatar', 'fr' => 'Qatar'),
            'RE' => array('en' => 'Réunion', 'fr' => 'Réunion'),
            'RO' => array('en' => 'Romania', 'fr' => 'Roumanie'),
            'RS' => array('en' => 'Serbia', 'fr' => 'Serbie'),
            'RU' => array('en' => 'Russia', 'fr' => 'Russie'),
            'RW' => array('en' => 'Rwanda', 'fr' => 'Rwanda'),
            'SA' => array('en' => 'Saudi Arabia', 'fr' => 'Arabie saoudite'),
            'SB' => array('en' => 'Solomon Islands', 'fr' => 'Îles Salomon'),
            'SC' => array('en' => 'Seychelles', 'fr' => 'Seychelles'),
            'SD' => array('en' => 'Sudan', 'fr' => 'Soudan'),
            'SE' => array('en' => 'Sweden', 'fr' => 'Suède'),
            'SG' => array('en' => 'Singapore', 'fr' => 'Singapour'),
            'SH' => array('en' => 'Saint Helena', 'fr' => 'Sainte-Hélène'),
            'SI' => array('en' => 'Slovenia', 'fr' => 'Slovénie'),
            'SJ' => array('en' => 'Svalbard and Jan Mayen', 'fr' => 'Svalbard et Île Jan Mayen'),
            'SK' => array('en' => 'Slovakia', 'fr' => 'Slovaquie'),
            'SL' => array('en' => 'Sierra Leone', 'fr' => 'Sierra Leone'),
            'SM' => array('en' => 'San Marino', 'fr' => 'Saint-Marin'),
            'SN' => array('en' => 'Senegal', 'fr' => 'Sénégal'),
            'SO' => array('en' => 'Somalia', 'fr' => 'Somalie'),
            'SR' => array('en' => 'Suriname', 'fr' => 'Suriname'),
            'ST' => array('en' => 'São Tomé and Príncipe', 'fr' => 'Sao Tomé-et-Principe'),
            'SV' => array('en' => 'El Salvador', 'fr' => 'El Salvador'),
            'SY' => array('en' => 'Syria', 'fr' => 'Syrie'),
            'SZ' => array('en' => 'Swaziland', 'fr' => 'Swaziland'),
            'TC' => array('en' => 'Turks and Caicos Islands', 'fr' => 'Îles Turks et Caïques'),
            'TD' => array('en' => 'Chad', 'fr' => 'Tchad'),
            'TF' => array('en' => 'French Southern Territories', 'fr' => 'Terres australes françaises'),
            'TG' => array('en' => 'Togo', 'fr' => 'Togo'),
            'TH' => array('en' => 'Thailand', 'fr' => 'Thaïlande'),
            'TJ' => array('en' => 'Tajikistan', 'fr' => 'Tadjikistan'),
            'TK' => array('en' => 'Tokelau', 'fr' => 'Tokelau'),
            'TL' => array('en' => 'Timor-Leste', 'fr' => 'Timor oriental'),
            'TM' => array('en' => 'Turkmenistan', 'fr' => 'Turkménistan'),
            'TN' => array('en' => 'Tunisia', 'fr' => 'Tunisie'),
            'TO' => array('en' => 'Tonga', 'fr' => 'Tonga'),
            'TR' => array('en' => 'Turkey', 'fr' => 'Turquie'),
            'TT' => array('en' => 'Trinidad and Tobago', 'fr' => 'Trinité-et-Tobago'),
            'TV' => array('en' => 'Tuvalu', 'fr' => 'Tuvalu'),
            'TW' => array('en' => 'Taiwan', 'fr' => 'Taïwan'),
            'TZ' => array('en' => 'Tanzania', 'fr' => 'Tanzanie'),
            'UA' => array('en' => 'Ukraine', 'fr' => 'Ukraine'),
            'UG' => array('en' => 'Uganda', 'fr' => 'Ouganda'),
            'UM' => array('en' => 'U.S. Minor Outlying Islands', 'fr' => 'Îles Mineures Éloignées des États-Unis'),
            'US' => array('en' => 'United States', 'fr' => 'États-Unis'),
            'UY' => array('en' => 'Uruguay', 'fr' => 'Uruguay'),
            'UZ' => array('en' => 'Uzbekistan', 'fr' => 'Ouzbékistan'),
            'VA' => array('en' => 'Vatican City', 'fr' => 'État de la Cité du Vatican'),
            'VC' => array('en' => 'Saint Vincent and the Grenadines', 'fr' => 'Saint-Vincent-et-les Grenadines'),
            'VE' => array('en' => 'Venezuela', 'fr' => 'Venezuela'),
            'VG' => array('en' => 'British Virgin Islands', 'fr' => 'Îles Vierges britanniques'),
            'VI' => array('en' => 'U.S. Virgin Islands', 'fr' => 'Îles Vierges des États-Unis'),
            'VN' => array('en' => 'Vietnam', 'fr' => 'Viêt Nam'),
            'VU' => array('en' => 'Vanuatu', 'fr' => 'Vanuatu'),
            'WF' => array('en' => 'Wallis and Futuna', 'fr' => 'Wallis-et-Futuna'),
            'WS' => array('en' => 'Samoa', 'fr' => 'Samoa'),
            'YE' => array('en' => 'Yemen', 'fr' => 'Yémen'),
            'YT' => array('en' => 'Mayotte', 'fr' => 'Mayotte'),
            'ZA' => array('en' => 'South Africa', 'fr' => 'Afrique du Sud'),
            'ZM' => array('en' => 'Zambia', 'fr' => 'Zambie'),
            'ZW' => array('en' => 'Zimbabwe', 'fr' => 'Zimbabwe')
        );
        return $countryList;
    }

    public function getTabBO()
    {
        $tabBO = array(
            'dashboard'    => array('en' => 'dashboard', 'fr' => 'tableau de bord'),
            'ficheContact' => array('en' => 'fiche contact', 'fr' => 'fiche contact'),
            'CONFIGURE'                   => array('en' => 'adminstration', 'fr' => 'adminstration'),
            'AdminAdvancedParameters'     => array('en' => 'settings', 'fr' => 'Paramétrage'),
            'company'      => array('en' => 'company', 'fr' => 'sociétés'),
            'user'         => array('en' => 'users', 'fr' => 'utilisateurs'),
            'permission'   => array('en' => 'permissions', 'fr' => 'permissions'),
            'role'         => array('en' => 'role', 'fr' => 'rôles'),
            'module'       => array('en' => 'module', 'fr' => 'modules'),
            'information'  => array('en' => 'informations', 'fr' => 'informations'),
            'setting'      => array('en' => 'settings', 'fr' => 'reglages'),
            'log'          => array('en' => 'logs', 'fr' => 'logs'),
            'tab'          => array('en' => 'menu', 'fr' => 'menu'),
            'route'        => array('en' => 'routes', 'fr' => 'routes'),
            'LOCALIZATION'                => array('en' => 'localization', 'fr' => 'localisation'),
            'translate'    => array('en' => 'translate', 'fr' => 'traduction'),
            'currency'     => array('en' => 'currency', 'fr' => 'devises'),
            'taxe'         => array('en' => 'taxe', 'fr' => 'taxes'),
            'PUBLIC'                      => array('en' => 'web', 'fr' => 'public'),
            'media'        => array('en' => 'Image Manager', 'fr' => 'médias'),
            'ECOMMERCE'                   => array('en' => 'ecommerce', 'fr' => 'ecommerce'),
            'STATISTIQUES'                => array('en' => 'statistiques', 'fr' => 'statistiques'),
            'form'         => array('en' => 'forms', 'fr' => 'formulaires'),




        );
        return $tabBO;
    }
}
