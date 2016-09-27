<?php

	/**
	 *
	 *    @class:        Query
	 *    @description:  class for generation sql-queries to database
	 *    @version:      2.1
	 *    @fields:
	 *                   private object(MySQLi) @mysqli: MySQLi object takes as init parameter;
	 *                   private string @table: name of table for sql-query.
	 *                   private string @fields: list of fields for select qiery.
	 *                   private string @where: predicate for queries.
	 *                   private string @limit: limit of selected rows.
	 *                   private string @group: group modificator of select query.
	 *    @param:        MySQLi object
	 *
	 */

	class Query {

		private $mysqli = NULL;
		private $table  = '';
		private $fields = ' * ';
		private $where  = '';
		private $order  = '';
		private $limit  = '';
		private $group  = '';

		public function __construct(MySQLi $mysqli) {
			$this->mysqli = $mysqli;
		}
		
		public function insert(array $fields) {
			$columns = ''; $values = '';
			foreach ($fields as $key => $value) {
				$columns .= '`'.$this->mysqli->real_escape_string($key).'`, ';
				$values .= "'".$this->mysqli->real_escape_string($value)."', ";
			}
			$columns = substr($columns, 0, -2);
			$values = substr($values, 0, -2);
			$query = 'INSERT INTO'.$this->table.'('.$columns.') VALUES ('.$values.')';
			$this->unsetValues();
			return $query;
		}
		
		public function select() {
			$query = 'SELECT '.$this->fields.'FROM '.$this->table.$this->where.
				$this->order.$this->limit.$this->group;
			$this->unsetValues();
			return $query;
		}
		
		public function update(array $values) {
			$update = '';
			foreach ($values as $key => $value) {
				$field = '`'.$this->mysqli->real_escape_string($key).'`';
				if (is_array($value) && isset($value[0], $value[1]))
					$val = '`'.$this->mysqli->real_escape_string($key).'` '.$value[0].$value[1];
				else
					$val = "'".$value."'";
				$update .= $field." = ".$this->mysqli->real_escape_string($val).", ";
			}
			$update = stripcslashes(substr($update, 0, -2));
			$query = 'UPDATE'.$this->table.'SET '.$update.' '.$this->where;
			$this->unsetValues();
			return $query;
		}
		
		public function delete() {
			$query = 'DELETE FROM'.$this->table.$this->where;
			$this->unsetValues();
			return $query;
		}
		
		public function setTable($table) {
			if (!is_string($table))
				throw new DataBaseException(Common::ERROR_SQL_PARAM);
			$this->table = ' `'.Common::DB_PREFIX.$this->mysqli->real_escape_string($table).'` ';
		}
		
		public function setWhere(array $where) {
			if (isset($where[0]) && count($where[0]) == 1)
				$this->where = $where[0];
			else {
				$whereString = 'WHERE ';
				$i = 0; $count = (isset($where[1])) ? count($where[1]) : 0;
				foreach ($where[0] as $key => $value)
					$whereString .= ' `'.$this->mysqli->real_escape_string($key).'` '.
									" = '".$this->mysqli->real_escape_string($value)."' "
									. (($i < $count) ? $where[1][$i++].' ' : '');
				$this->where = $whereString;
			}
		}
		
		public function setFields(array $fields) {
			if (!empty($fields)) {
				if ($fields[0] == 'COUNT')
					$this->fields = ' COUNT(`id`) ';
				else {
					$this->fields = '';
					$count = count($fields);
					for ($i = 0; $i < $count; $i++)
						$this->fields .= ' `'.$this->mysqli->real_escape_string($fields[$i]).'`, ';
					$this->fields = substr($this->fields, 0, -2).' ';
				}
			}
		}
		
		public function setOrder(array $order) {
			$this->order = $this->setOrderLimit($order, ' ORDER BY `', '`', ' ', 'is_string');
		}
		
		public function setLimit(array $limit) {
			$this->limit = $this->setOrderLimit($limit, ' LIMIT ', '', ', ', 'is_integer');
		}
		
		private function setOrderLimit(array $values, $type, $str1, $str2, $callback) {
			if (empty($values) || !$callback($values[0]) || !$values[0])
				throw new DataBaseException(Common::ERROR_SQL_PARAM);
			$tmp = $type.$this->mysqli->real_escape_string($values[0]).$str1;
			if (isset($values[1]))
				if ($callback($values[1]))
					$tmp .= $str2.$this->mysqli->real_escape_string($values[1]);
				else
					throw new DataBaseException(Common::ERROR_SQL_PARAM);
			return $tmp;
		}
		
		public function setGroup($group) {
			if (!isset($group) || !is_string($group) || !$group)
				throw new DataBaseException(Common::ERROR_SQL_PARAM);
			else
				$this->group = ' GROUP BY `'.$this->mysqli->real_escape_string($group).'` ';
		}
		
		private function unsetValues() {
			$this->table  = '';
			$this->fields = ' * ';
			$this->where  = '';
			$this->order  = '';
			$this->limit  = '';
			$this->group  = '';
		}
		
	}

?>