<?php

	mb_internal_encoding('UTF8');

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	set_include_path(get_include_path());
	spl_autoload_extensions('.php');
	spl_autoload_register();

	/**
	 *
	 * @name: ArrayAsCollections
	 * @author: Sergey Grishin
	 * @version: 1.0.11
	 * @files: CollectionIterator -- class to iterate over the array
	 *         ArrayCollection    -- class OOP-wrapper for PHP-arrays
	 * @description: OOP-wrapper around arrays in PHP to more simple work with them.
	 * @notice: 1) supports fluent interface.
	 *          2) throws OutOfRangeException, when trying to access a non-existent index.
	 *             methods: add, get, remove
	 *          3) supports standart arrays using: $array['key'] = 'value'; But OOP-style preferable.
	 *
	 * Can be iterated by cycles foreach
	 *
	 */

	$a = new ArrayCollection();

	$a->add(1)->add('string')->add(true)->add(234)->add('123')->add('value');

	foreach ($a as $key => $value) {
		echo $key, " => ", $value, "<br />";
	}

	echo $a->length(), "<br />";

	$a->remove(2);

	foreach ($a as $key => $value) {
		echo $key, " => ", $value, "<br />";
	}

	try {
	// try to remove nonexistent index
		$a->remove(1000);
	} catch (OutOfRangeException $e) {
		echo $e->getMessage(), "<br />";
	}

	try {
	// try to add value on already existed key
		$a->add('value', 1);
	} catch (OutOfRangeException $e) {
		echo $e->getMessage(), "<br />";
	}

	try {
	// try to get nonexistent index
		$a->get(1000);
	} catch (OutOfRangeException $e) {
		echo $e->getMessage(), "<br />";
	}

	// make new array from starting value. Can be PHP-array, or atomic type variable
	$array1 = new ArrayCollection([1,2,3,4,5,6,7,8,9]);
	$array2 = new ArrayCollection('string');
	$array3 = new ArrayCollection(123);

	foreach ($array1 as $key => $value) {
		echo $key, " => ", $value, "<br />";
	}

?>