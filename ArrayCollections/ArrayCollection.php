<?php

	class ArrayCollection implements ArrayAccess, IteratorAggregate {

		private $storage = [];

		public function __construct($startValues = []) {
			if (!is_array($startValues))
				$this->storage[] = $startValues;
			else
				$this->storage = $startValues;
		}

		public function add($value, $key = NULL) {
			if ($key === NULL) {
				$this->storage[] = $value;
				return $this;
			}
			if ($this->isExistsKey($key))
				throw new OutOfRangeException("Key $key already using");
			$this->storage[$key] = $value;
			return $this;
		}

		public function reindex() {
			$this->storage = array_values($this->storage);
			return $this;
		}

		public function remove($key) {
			if (!$this->isExistsKey($key))
				throw new OutOfRangeException("$key is absent in array");
			unset($this->storage[$key]);
			return $this;
		}

		public function get($key) {
			if (!$this->isExistsKey($key))
				throw new OutOfRangeException("$key is absent in array");
			return $this->storage[$key];
		}

		public function isExistsKey($key) {
			return array_key_exists($key, $this->storage);
		}

		public function isSeted($key) {
			return isset($this->storage[$key]);
		}

		public function isEmpty() {
			return empty($this->storage);
		}

		public function keys() {
			return array_keys($this->storage);
		}

		public function length() {
			return count($this->storage);
		}

		public function findKeyByValue($needle, $strictFlag = false) {
			if (($res = array_search($needle, $this->storage, $strictFlag)) !== false)
				return $res;
			return -1;
		}

		/* return simple array of PHP, not instance of ArrayCollection */
		public function getSimpleArrayFromCollection() {
			return $this->storage;
		}

		public function offsetSet($key, $value) {
			$this->add($value, $key);
		}

		public function offsetUnset($key) {
			$this->del($key);
		}

		public function offsetGet($key) {
			return $this->get($key);
		}

		public function offsetExists($key) {
			return $this->isExistsKey($key);
		}

		function getIterator() {
			return new CollectionIterator(clone $this);
		}

	}
