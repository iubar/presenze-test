<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Iubar\Build\Selenium_RoboTask;

class RoboFile extends Selenium_RoboTask {

	private $config = [];

	public function __construct() {
		parent::__construct(__DIR__);
	}

	public function create_db(){
		$db_file = __DIR__ . '/../../php-presenze/db/sql/db.sql';
		$this->executeMysqlCmd("< $db_file");
	}

	public function install_db_data(){
		$data_file = __DIR__ . '/../../php-presenze/db/sql/dati.sql';
		$this->executeMysqlCmd("< $data_file");
	}

	public function install_db_data_example(){
		$data_file = __DIR__ . '/../../php-presenze/db/sql/dati-esempio.sql';
		$this->executeMysqlCmd("< $data_file");
	}

	private function executeMysqlCmd($cmd){
		$this->loadConfig();
		$db_host = $this->config['app.db.host'];
		$db_user = $this->config['app.db.user'];

		$mysql_cmd = "mysql -h $db_host -u $db_user -p $cmd";
		$this->taskExec($mysql_cmd)->run();
	}

	private function loadConfig(){
		$file = __DIR__ . '/../../php-presenze/www/config/local-dev.php';
		if (!file_exists($file)){
			$this->say('Config file not found: "' . $file . '"');
			die();
		}

		$this->config = require_once $file;
	}
}