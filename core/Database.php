<?php

namespace core;


class Database {

	/** @var Database $singleton */
	static private $singleton = null;

	private $pdo;

	public function __construct($host, $dbname, $user, $password) {
		try {
			$this->pdo = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
			$this->pdo->query('SET NAMES utf8');
		} catch (\Exception $e) {
			echo "Fehler 18!";
			echo $e->getMessage();
			echo print_r($e->getTrace(),1);
			exit;
		}
	}

	/**
	 * @return Database
	 */
	public static function get_singleton() {
		if (self::$singleton === null) {
			Error::quit("Please initialize Database singelton first: <code>\\core\\Database::init();</code>", 1);
		}
		return self::$singleton;
	}

	public static function init($host, $dbname, $user, $password) {

		if(self::$singleton!==null){
			Error::quit("Database is already initialized!", 1);
		}

		self::$singleton = new Database($host, $dbname, $user, $password);
		return self::$singleton;
	}



}