<?php

	mb_internal_encoding('UTF8');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	set_include_path(get_include_path());
	spl_autoload_extensions('.php');
	spl_autoload_register();

	/**
	 *
	 * @description:
	 * Examples and tests of SQL-Queries generator.
	 * In this bundle there are 2 files: Query class for generation queries and DataBase class
	 * for demonstration work of generator. In real project in class DataBase need to paste code to methods
	 * insert\select\update\delete which will be execute the query to DB.
	 *
	 * @author: Sergey Grishin
	 * @version: 3.0
	 *
	 */

	$start = microtime(true);
	echo DataBase::getObject()
		->table('table')
		->fields()
		->where(['f1' => 'val', 'f2' => 'val1'], ['and'])
		->order('id', 'ASC')
		->limit(15, 20)
		->group('id')
		->select(), "<br />";
	echo microtime(true) - $start, "<br />";
	
	$start = microtime(true);
	echo DataBase::getObject()
		->table('table')
		->insert(['f1' => 'v1', 'f2' => 'v2']), "<br />";
	echo microtime(true) - $start, "<br />";
	
	$start = microtime(true);
	echo DataBase::getObject()
		->table('table')
		->where('WHERE `f1` = \'v1\'')
		->update(['f1' => ['+', 2], 'f2' => 'v2']), "<br />";
	echo microtime(true) - $start, "<br />";
	
	$start = microtime(true);
	echo DataBase::getObject()
		->table('table')
		->where(['f1' => 'val', 'f2' => 'val1'], ['and'])
		->delete(), "<br />";
	echo microtime(true) - $start, "<br />";

?>