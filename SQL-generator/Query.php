<?php

	/**
	 *
	 *    @class:        Query
	 *    @description:  class for generation sql-queries to database
	 *    @version:      1.3.15.0
	 *    @fields:
	 *                   private object(MySQLi) @mysqli: MySQLi object takes as init parameter;
	 *                   private array @params: array with params for sql-query.
	 *                   private string @table: name of table for sql-query.
	 *    @param:        MySQLi object
	 *
	 */

	class Query {

		private $mysqli = NULL;
		private $params = [];
		private $table = '';

		public function __construct(MySQLi $mysqli) {
			$this->mysqli = $mysqli;
		}

		public function getQuery($method, $tableName, array $params) {
			$this->params = $params;
			$this->table = $tableName;
			return $this->$method();
		}

		private function insert() {
			$columns = ''; $values = '';
			foreach ($this->params as $key => $value) {
				$columns .= '`'.$key.'`, ';
				$values .= "'".$this->mysqli->real_escape_string($value)."', ";
			}
			$columns = substr($columns, 0, -2);
			$values = substr($values, 0, -2);
			return 'INSERT INTO'.$this->getTableName().'('.$columns.') VALUES ('.$values.')';
		}

		private function select() {
			return 'SELECT'.$this->getFieldsList().'FROM'.$this->getTableName().
					$this->getWhere().$this->getOrder().$this->getLimit().$this->getGroup();
		}

		private function update() {
			$update = '';
			foreach ($this->params['values'] as $key => $value) {
				$field = '`'.$this->mysqli->real_escape_string($key).'`';
				if (is_array($value) && isset($value[0], $value[1]))
					$val = '`'.$this->mysqli->real_escape_string($key).'` '.$value[0].$value[1];
				else
					$val = "'".$value."'";
				$update .= $field." = ".$this->mysqli->real_escape_string($val).", ";
			}
			$update = stripcslashes(substr($update, 0, -2));
			return 'UPDATE'.$this->getTableName().'SET '.$update.' '.$this->getWhere();
		}

		private function delete() {
			return 'DELETE FROM'.$this->getTableName().$this->getWhere();
		}

		private function getWhere() {
			if (!$this->params['where'])
				return '';
			if (is_string($this->params['where']))
				return $this->params['where'];
			$whereString = 'WHERE ';
			$i = 0; $count = (isset($this->params['where'][1])) ? count($this->params['where'][1]) : 0;
			foreach ($this->params['where'][0] as $key => $value)
				$whereString .= ' `'.$this->mysqli->real_escape_string($key).'` '.
								" = '".$this->mysqli->real_escape_string($value)."' "
								. (($i < $count) ? $this->params['where'][1][$i++].' ' : '');
			return $whereString;
		}

		private function getOrder() {
			if (!$this->params['order'] || !is_string($this->params['order'][0]))
				return '';
			$order = ' ORDER BY `'.$this->mysqli->real_escape_string($this->params['order'][0]).'`';
			if (isset($this->params['order'][1]) && is_string($this->params['order'][1]))
				return $order.' '.$this->mysqli->real_escape_string($this->params['order'][1]);
			return $order;
		}

		private function getLimit() {
			if (!$this->params['limit'] || isset($this->params['limit'][1]) && $this->params['limit'][1] <= 0)
				return '';
			$limit = ' LIMIT '.
				$this->mysqli->real_escape_string($this->params['limit'][0]);
			if (!isset($this->params['limit'][1]))
				return $limit;
			return $limit.', '.$this->mysqli->real_escape_string($this->params['limit'][1]);
		}
		
		private function getGroup() {
			if (!isset($this->params['group']) || !is_string($this->params['group']))
				return '';
			return 'GROUP BY `'.$this->mysqli->real_escape_string($this->params['group']).'` ';
		}

		private function getTableName() {
			return ' `'.Common::DB_PREFIX.$this->mysqli->real_escape_string($this->table).'` ';
		}

		private function getFieldsList() {
			if (!$this->params['fields'] || !is_array($this->params['fields']))
				return ' * ';
			if ($this->params['fields'][0] == 'COUNT')
				return ' COUNT(`id`) ';
			$fieldString = '';
			for ($i = 0, $count = count($this->params['fields']); $i < $count; $i++)
				$fieldString .= ' `'.$this->mysqli->real_escape_string($this->params['fields'][$i]).'`, ';
			return substr($fieldString, 0, -2).' ';
		}

	}

?>