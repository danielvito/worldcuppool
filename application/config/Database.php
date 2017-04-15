<?php defined('SYSPATH') OR die('No direct access allowed.');
$arrDB = array(
	'default' => Util_App::config('default:database'),
	'alternate' => Util_App::config('alternate:database'),
);

return $arrDB;

/*
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				'hostname'   => 'localhost',
				'database'   => 'appweb',
				'username'   => 'root',
				'password'   => 'root',
				'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
		'alternate' => array(
			'type'       => 'pdo',
			'connection' => array(
				'dsn'        => 'mysql:host=localhost;dbname=kohana',
				'username'   => 'root',
				'password'   => 'r00tdb',
				'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);
*/