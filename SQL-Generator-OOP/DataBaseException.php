<?php

	class DataBaseException extends Exception {
		
		public function __construct($message = '') {
			parent::__construct();
			$this->message = $message;
		}
	
	}