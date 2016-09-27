<?php

	/**
     *
     *    @class:        DataBase
	 *    @parent:       AbstractDataBase
     *    @description:  class-wrapper for AbstractDataBase. Need for supporting Singleton-pattern.
     *    @version:      1.0
     *
     *    Notice: if you don't use spl_autoload functions in your project, you must require AbstractDataBase class to this file!
     *
     */

	class DataBase extends AbstractDataBase {
	
		private static $object = NULL;
		
		public static function getObject() {
			if (!self::$object)
				self::$object = new DataBase();
			return self::$object;
		}
	
	}