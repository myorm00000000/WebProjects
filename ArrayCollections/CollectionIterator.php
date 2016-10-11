<?php

	class CollectionIterator implements Iterator {

		private $storage;
		private $current;
		private $keys;

		function __construct(ArrayCollection $arrayCollection) {
			$this->storage = $arrayCollection;
			$this->keys = $this->storage->keys();
		}

		function rewind() {
			$this->current = 0;
		}

		function valid() {
			return $this->current < $this->storage->length();
		}

		function key() {
			return $this->keys[$this->current];
		}

		function next() {
			$this->current++;
		}

		function current() {
			return $this->storage->get($this->keys[$this->current]);
		}

	}