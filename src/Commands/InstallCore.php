<?php

namespace Adnduweb\Ci4Core\Commands;

use Config\Autoload;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class InstallCore extends BaseCommand
{
    protected $group       = 'Adnduweb';
    protected $name        = 'install:core';
    protected $description = 'Installation de l\'application.';


    public function run(array $params)
    {
        // Migration
        // consume or prompt for a setting name
        $name = array_shift($params);

        // On vérifie le sytème te les droit écriture
        $this->testApp();

        // DATABASE
        if (empty($hostname))
            $hostname = CLI::prompt('Hostname', null, 'required');
        if (empty($database))
            $database = CLI::prompt('Database', null, 'required');
        if (empty($username))
            $username = CLI::prompt('Username', null, 'required');
        if (empty($password))
            $password = CLI::prompt('password', null, 'required');
        if (empty($DBPrefix))
            $DBPrefix = CLI::prompt('DBPrefix', null, 'required');
        if (empty($port))
            $port = CLI::prompt('port', null, 'required');


        try {
            // On test la connection à la base de donnée
            $this->testDatabase($hostname, $username, $password, $database, $DBPrefix, $port);
            $content = file_get_contents(ROOTPATH . 'env.exemple');
            $this->writeFileEnv($content);

            InstallCore::replaceInfile('database.default.hostname = ""', 'database.default.hostname = "' . $hostname . '"');
            InstallCore::replaceInfile('database.default.database = ""', 'database.default.database = "' . $database . '"');
            InstallCore::replaceInfile('database.default.username = ""', 'database.default.username = "' . $username . '"');
            InstallCore::replaceInfile('database.default.password = ""', 'database.default.password = "' . $password . '"');
            InstallCore::replaceInfile('database.default.DBPrefix = ""', 'database.default.DBPrefix = "' . $DBPrefix . '"');
            InstallCore::replaceInfile('database.default.port = ""', 'database.default.port = "' . $port . '"');
        } catch (\Exception $e) {
            $this->showError($e);
        }

        // if (empty($name))
        //     $name = CLI::prompt('Nom de l\'application', null, 'required');
        if (empty($url))
            $url = CLI::prompt('Url de l\'application', null, 'required');
        if (empty($CI_AREA_ADMIN))
            $CI_AREA_ADMIN = CLI::prompt('Login caché', null, 'required');

        try {
            //InstallCore::replaceInfile('app.nameApp = ""', 'app.nameApp = "' . $name . '"');
            InstallCore::replaceInfile('app.baseURL = "https://www.exemple.com"', 'app.baseURL = "' . $url . '"');
            InstallCore::replaceInfile('app.areaAdmin = ""', 'app.areaAdmin = "' . $CI_AREA_ADMIN . '"');
        } catch (\Exception $e) {
            $this->showError($e);
        }

        // On crée les dossiers images de l'application
        @mkdir(ROOTPATH . "public/uploads", 0775);
        @mkdir(ROOTPATH . "public/uploads/thumbnail", 0775);
        @mkdir(ROOTPATH . "public/uploads/small", 0775);
        @mkdir(ROOTPATH . "public/uploads/medium", 0775);
        @mkdir(ROOTPATH . "public/uploads/large", 0775);
        @mkdir(ROOTPATH . "public/uploads/custom", 0775);
        @mkdir(ROOTPATH . "public/uploads/original", 0775);

        // Install encryption
        command('key:generate');

        CLI::write(CLI::color('Creation de l\'application : ', 'green') . $name);
        CLI::write('Vous devez lancer maintenant la commande suivante :  php spark install:migrate -all');
    }

    public static function replaceInfile($find, $replace)
    {
        if ($find != $replace) {
            //recupere la totalité du fichier
            $str = file_get_contents(ROOTPATH . '.env');
            if ($str === false) {
                return false;
            } else {
                //effectue le remplacement dans le texte
                $str = str_replace($find, $replace, $str);
                //remplace dans le fichier
                if (file_put_contents(ROOTPATH . '.env', $str) === false) {
                    return false;
                }
            }
        }
        return true;
    }

    public function testDatabase($hostname, $username, $password, $database, $DBPrefix, $port)
    {
        $custom = [
            'DSN'      => '',
            'hostname' => $hostname,
            'username' => $username,
            'password' => $password,
            'database' => $database,
            'DBDriver' => 'MySQLi',
            'DBPrefix' => $DBPrefix,
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'production'),
            'cacheOn'  => false,
            'cacheDir' => '',
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => $port,
        ];
        try {
            $db = \Config\Database::connect($custom);
            $db->simpleQuery('SHOW TABLES');
        } catch (\Exception $e) {
            //$this->showError($e);
            CLI::error('Error : ' . $e->getMessage());
            $this->run([]);
            // CLI::write(CLI::color('Aborded : ', 'red') . $e->getMessage());

            exit;
        }
    }

    public function testApp()
    {
        CLI::write('PHP Version: ' . CLI::color(phpversion(), 'yellow'));
        CLI::write('CI Version: ' . CLI::color(\CodeIgniter\CodeIgniter::CI_VERSION, 'yellow'));
        CLI::write('APPPATH: ' . CLI::color(APPPATH, 'yellow'));
        CLI::write('SYSTEMPATH: ' . CLI::color(SYSTEMPATH, 'yellow'));
        CLI::write('ROOTPATH: ' . CLI::color(ROOTPATH, 'yellow'));
        CLI::write('Included files: ' . CLI::color(count(get_included_files()), 'yellow'));

        if (!is_writable(ROOTPATH . 'writable/cache/'))
            CLI::write(CLI::color('cache not writable: ', 'red') .  ROOTPATH . 'writable/cache/');
        else
            CLI::write(CLI::color('cache writable: ', 'yellow') .  ROOTPATH . 'writable/cache/');

        if (!is_writable(ROOTPATH . 'writable/logs/'))
            CLI::write(CLI::color('Logs not writable: ', 'red') .  ROOTPATH . 'writable/logs/');
        else
            CLI::write(CLI::color('Logs writable: ', 'yellow') .  ROOTPATH . 'writable/logs/');

        if (!is_writable(ROOTPATH . 'writable/uploads/'))
            CLI::write(CLI::color('Uploads not writable: ', 'red') .  ROOTPATH . 'writable/uploads/');
        else
            CLI::write(CLI::color('Uploads writable: ', 'yellow') .  ROOTPATH . 'writable/uploads/');

        try {
            if (
                phpversion() >= '7.2' &&
                extension_loaded('intl') &&
                extension_loaded('curl') &&
                extension_loaded('json') &&
                extension_loaded('mbstring') &&
                extension_loaded('mysqlnd') &&
                extension_loaded('xml')
            ) {
                //silent
            }
        } catch (\Exception $e) {
            CLI::write('Erreur avec une extension php : ' . CLI::color($e->getMessage(), 'red'));
            exit;
        }

        $continue = CLI::prompt('Voulez vous continuer?', ['y', 'n']);
        if ($continue == 'n') {
            CLI::error('Au revoir');
            exit;
        }
    }


    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFileEnv(string $content)
    {

        try {
            write_file(ROOTPATH . '.env', $content);
        } catch (\Exception $e) {
            $this->showError($e);
            exit();
        }
        CLI::write(CLI::color('  created: ', 'green') . ROOTPATH . '.env');
    }
}
