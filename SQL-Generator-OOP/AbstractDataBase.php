<?php

	/**
     *
     *    @class:        AbstractDataBase
     *    @description:  class for executing queries to database. As DB used MySQL database.
     *    @version:      1.5.2
     *    @fields:
     *        private object(Query) @query: object of class Query for generating sql-queries to DB;
     *        private object(MySQLi) @mysqli: object of class MySQLi for executing queries.
	 *    @exceptions:   throw DataBaseException with message
     *
     *    Notice: if you don't use spl_autoload functions in your project, you must require Query class to this file!
     *
     */

	abstract class AbstractDataBase {

		private $query = NULL;
		private $mysqli = NULL;

		protected function __construct() {
			$this->mysqli = @new MySQLi(Common::DB_HOST, Common::DB_USER, Common::DB_PASS, Common::DB_NAME);
			if ($this->mysqli->connect_errno)
				exit(Common::DB_NAME);
			$this->mysqli->set_charset(Common::DB_CHARSET);
			$this->mysqli->query("SET lc_time_names = '".Common::DB_LCTIME."'");
			$this->query = new Query($this->mysqli);
		}
		
		public function insert(array $fields) {
			return $this->query->insert($fields);
		}
		
		public function update(array $fields) {
			return $this->query->update($fields);
		}
		
		public function delete() {
			return $this->query->delete();
		}
		
		public function select($arrayType = 'table', $callback = 'fetch_assoc') {
			return $this->query->select();
		}
		
		public function table($table) {
			$this->query->setTable($table);
			return $this;
		}
		
		public function fields() {
			$this->query->setFields(func_get_args());
			return $this;
		}
		
		public function where() {
			$this->query->setWhere(func_get_args());
			return $this;
		}
		
		public function order() {
			$this->query->setOrder(func_get_args());
			return $this;
		}
		
		public function limit() {
			$this->query->setLimit(func_get_args());
			return $this;
		}
		
		public function group($field) {
			$this->query->setGroup($field);
			return $this;
		}
		
		private function fetch($mysqliResult, $callback, $arrayType) {
			if (gettype($mysqliResult) == 'boolean')
				return [];
			if ($arrayType == 'table') {
				$array = [];
				while ($row = $mysqliResult->$callback())
					$array[] = $row;
				return $array;
			}
			return $mysqliResult->$callback();
		}
		
		public function __destruct() {
			if ($this->mysqli && !$this->mysqli->connect_errno)
				$this->mysqli->close();
		}

	}
