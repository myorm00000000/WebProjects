<?php

    /**
     *
     *    @class:        DataBase
     *    @description:  class for executing queries to database. As DB used MySQL database.
     *    @version:      1.5.2
     *    @fields:
     *        private object(Query) @query: object of class Query for generating sql-queries to DB;
     *        private object(MySQLi) @mysqli: object of class MySQLi for executing queries.
     *
     *    Notice: if you don't use spl_autoload functions in your project, you must require Query class to this file!
     *
     */

	class DataBase {

		private $query = NULL;
		private $mysqli = NULL;
		private static $object = NULL;

		public static function getDBO() {
			if (!self::$object)
				self::$object = new DataBase();
			return self::$object;
		}

		private function __construct() {
			$this->mysqli = @new MySQLi(Common::DB_HOST, Common::DB_USER, Common::DB_PASS, Common::DB_NAME);
			if ($this->mysqli->connect_errno)
				exit(Common::ERROR_DB);
			$this->mysqli->set_charset(Common::DB_CHARSET);
			$this->mysqli->query("SET lc_time_names = '".Common::DB_LCTIME."'");
			$this->query = new Query($this->mysqli);
		}

		public function insert($table, array $queryParams) {
			return $this->query->getQuery('insert', $table, $queryParams);
		}

		public function select($table, $resultType, array $queryParams) {
			return $this->query->getQuery('select', $table, $queryParams);
		}

		public function update($table, array $queryParams) {
			return $this->query->getQuery('update', $table, $queryParams);
		}

		public function delete($table, array $queryParams) {
			return $this->query->getQuery('delete', $table, $queryParams);
		}

		private function setResultToArray(mysqli_result $resultSet, $arrayType) {
			if ($arrayType == 'table') {
				$array = [];
				while ($row = $resultSet->fetch_assoc())
					$array[] = $row;
				return $array;
			}
			return $resultSet->fetch_assoc();
		}

	}

?>