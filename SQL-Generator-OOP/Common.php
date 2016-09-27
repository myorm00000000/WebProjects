<?php

	abstract class Common {

		const DB_HOST = 'localhost';
		const DB_USER = 'root';
		const DB_PASS = '';
		const DB_NAME = 'framestudio';
		const DB_PREFIX = 'fs_';
		const DB_CHARSET = 'UTF8';
		const DB_LCTIME = 'uk_UA';
		const ERROR_CONNECT_DB = 'Ошибка соединения с базой данных';
		const ERROR_SQL_QUERY = 'Невозможно выполнить SQL-запрос.';
		const ERROR_SQL_PARAM = 'Неверный тип параметра SQL-запроса.';

	}
