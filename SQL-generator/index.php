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
	 * @version: 1.0.11
	 *
	 */

	echo "<h2>Examples of generation:</h2>";

	echo "<b>Test insert:</b><br />";

	echo DataBase::getDBO()->insert(
		'tableName',
		[
			'field1' => 'value1',
			'field2' => 'value2',
			'field3' => 3
		]
	);

	echo "<br /><br /><br /><b>Test select:</b><br />";

	echo "<i>\"full select query\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => ['filed1', 'field2'],
			'where' => [
				[
					'field1' => 'value1',
					'field2' => 'value2'
				],
				['OR']
			],
			'limit' => [1, 15],
			'order' => ['field_name', 'DESC']
		]
	), "<br />";

	echo "<i>\"test order with 1 parameter, error limit\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => [],
			'where' => [],
			'limit' => [1, -12],
			'order' => ['field_name']
		]
	), "<br />";

	echo "<i>\"test limit with 1 parameter\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => [],
			'where' => [],
			'limit' => [5],
			'order' => ['field_name']
		]
	), "<br />";

	echo "<i>\"test where with 1 parameter, full order\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => [],
			'where' => [['field1' => 'value1']],
			'limit' => [],
			'order' => ['field_name', 'DESC']
		]
	), "<br />";

	echo "<i>\"test where as string\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => [],
			'where' => "WHERE `field1` = 'value1' AND `field2` = 'value2'",
			'limit' => [],
			'order' => []
		]
	), "<br />";

	echo "<i>\"test COUNT\"</i> <br />", DataBase::getDBO()->select(
		'tableName',
		'resultType',
		[
			'fields' => ['COUNT'],
			'where' => [],
			'limit' => [],
			'order' => []
		]
	);

	echo "<br /><br /><br /><b>Test update:</b><br />";

	echo DataBase::getDBO()->update(
		'tableName',
		[
			'values' => [
				'field0' => 'value0',
				'field1' => 'value1'
			],
			'where' => []
		]
	);

	echo "<br /><br /><br /><b>Test delete:</b><br />";

	echo "<i>\"test where with many parameters and functions\"</i> <br />", DataBase::getDBO()->delete(
		'tableName',
		[
			'where' => [
				[
					'colname0' => 'value0',
					'colname1' => 'value1',
					'colname2' => 'value2'
				],
				['AND', 'OR']
			]
		]
	);

	echo "<br /><br /><br /><h2>Speed generation tests:</h2>";

	echo "<b>Test insert:</b><br />";

	$start = microtime(true);
	DataBase::getDBO()->insert('tableName', ['field1' => 'value1', 'field2' => 1423234, 'field3' => "<p>Text\Plain HTML</p>", 'field4' => 'value4', 'field5' => 'value5', 'field6' => 'value6', 'field7' => 3]);
	echo microtime(true) - $start;

	echo "<br /><br /><b>Test select:</b><br />";

	$start = microtime(true);
	DataBase::getDBO()->select('tableName', 'resultType', ['fields' => ['filed1', 'field2'], 'where' => [['field1' => 'value1', 'field2' => 'value2'], ['OR']], 'limit' => [1, 15], 'order' => ['field_name', 'DESC']]);
	echo microtime(true) - $start;

	echo "<br /><br /><b>Test update:</b><br />";

	$start = microtime(true);
	DataBase::getDBO()->update('tableName', ['values' => ['field0' => 'value0', 'field1' => 'value1'], 'where' => [['colname0' => 'value0', 'colname1' => 'value1', 'colname2' => 'value2'], ['AND', 'OR']]]);
	echo microtime(true) - $start;

	echo "<br /><br /><b>Test delete:</b><br />";

	$start = microtime(true);
	DataBase::getDBO()->delete('tableName', ['where' => [['colname0' => 'value0', 'colname1' => 'value1', 'colname2' => 'value2'], ['AND', 'OR']]]);
	echo microtime(true) - $start;

?>