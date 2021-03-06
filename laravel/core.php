<?php namespace Laravel;

/**
 * Define all of the constants that we will need to use the framework.
 * These are things like file extensions, as well as all of the paths
 * used by the framework. All of the paths are built on top of the
 * basic application, laravel, and public paths.
 */
define('EXT', '.php');
define('CRLF', "\r\n");
define('BLADE_EXT', '.blade.php');
define('APP_PATH', realpath($application).'/');
define('BUNDLE_PATH', realpath($bundles).'/');
define('PUBLIC_PATH', realpath($public).'/');
define('STORAGE_PATH', realpath($storage).'/');
define('SYS_PATH', realpath($laravel).'/');
define('CACHE_PATH', STORAGE_PATH.'cache/');
define('DATABASE_PATH', STORAGE_PATH.'database/');
define('SESSION_PATH', STORAGE_PATH.'sessions/');
define('DEFAULT_BUNDLE', 'application');
define('MB_STRING', (int) function_exists('mb_strtolower'));

unset($application, $bundles, $storage, $laravel, $public);

/**
 * Require all of the classes that can't be loaded by the auto-loader.
 * These are typically classes that the auto-loader itself relies upon
 * to load classes, such as the array and configuration classes.
 */
require SYS_PATH.'bundle'.EXT;
require SYS_PATH.'config'.EXT;
require SYS_PATH.'helpers'.EXT;
require SYS_PATH.'autoloader'.EXT;

/**
 * Register the Autoloader's "load" method on the auto-loader stack.
 * This method provides the lazy-loading of all class files, as well
 * as any PSR-0 compliant libraries used by the application.
 */
spl_autoload_register(array('Laravel\\Autoloader', 'load'));

/**
 * Build the Laravel framework class map. This provides a super fast
 * way of resolving any Laravel class name to its appropriate path.
 * More mappings can also be registered by the developer as needed.
 */
Autoloader::$mappings = array(
	'Laravel\\Asset' => SYS_PATH.'asset'.EXT,
	'Laravel\\Benchmark' => SYS_PATH.'benchmark'.EXT,
	'Laravel\\Blade' => SYS_PATH.'blade'.EXT,
	'Laravel\\Bundle' => SYS_PATH.'bundle'.EXT,
	'Laravel\\Cache' => SYS_PATH.'cache'.EXT,
	'Laravel\\Config' => SYS_PATH.'config'.EXT,
	'Laravel\\Cookie' => SYS_PATH.'cookie'.EXT,
	'Laravel\\Crypter' => SYS_PATH.'crypter'.EXT,
	'Laravel\\Database' => SYS_PATH.'database'.EXT,
	'Laravel\\Event' => SYS_PATH.'event'.EXT,
	'Laravel\\File' => SYS_PATH.'file'.EXT,
	'Laravel\\Form' => SYS_PATH.'form'.EXT,
	'Laravel\\HTML' => SYS_PATH.'html'.EXT,
	'Laravel\\Input' => SYS_PATH.'input'.EXT,
	'Laravel\\IoC' => SYS_PATH.'ioc'.EXT,
	'Laravel\\Lang' => SYS_PATH.'lang'.EXT,
	'Laravel\\Memcached' => SYS_PATH.'memcached'.EXT,
	'Laravel\\Messages' => SYS_PATH.'messages'.EXT,
	'Laravel\\Paginator' => SYS_PATH.'paginator'.EXT,
	'Laravel\\Redirect' => SYS_PATH.'redirect'.EXT,
	'Laravel\\Redis' => SYS_PATH.'redis'.EXT,
	'Laravel\\Request' => SYS_PATH.'request'.EXT,
	'Laravel\\Response' => SYS_PATH.'response'.EXT,
	'Laravel\\Section' => SYS_PATH.'section'.EXT,
	'Laravel\\Session' => SYS_PATH.'session'.EXT,
	'Laravel\\Str' => SYS_PATH.'str'.EXT,
	'Laravel\\URI' => SYS_PATH.'uri'.EXT,
	'Laravel\\URL' => SYS_PATH.'url'.EXT,
	'Laravel\\Validator' => SYS_PATH.'validator'.EXT,
	'Laravel\\View' => SYS_PATH.'view'.EXT,
	'Laravel\\Cache\\Drivers\\APC' => SYS_PATH.'cache/drivers/apc'.EXT,
	'Laravel\\Cache\\Drivers\\Driver' => SYS_PATH.'cache/drivers/driver'.EXT,
	'Laravel\\Cache\\Drivers\\File' => SYS_PATH.'cache/drivers/file'.EXT,
	'Laravel\\Cache\\Drivers\\Memcached' => SYS_PATH.'cache/drivers/memcached'.EXT,
	'Laravel\\Cache\\Drivers\\Redis' => SYS_PATH.'cache/drivers/redis'.EXT,
	'Laravel\\Database\\Connection' => SYS_PATH.'database/connection'.EXT,
	'Laravel\\Database\\Expression' => SYS_PATH.'database/expression'.EXT,
	'Laravel\\Database\\Query' => SYS_PATH.'database/query'.EXT,
	'Laravel\\Database\\Connectors\\Connector' => SYS_PATH.'database/connectors/connector'.EXT,
	'Laravel\\Database\\Connectors\\MySQL' => SYS_PATH.'database/connectors/mysql'.EXT,
	'Laravel\\Database\\Connectors\\Postgres' => SYS_PATH.'database/connectors/postgres'.EXT,
	'Laravel\\Database\\Connectors\\SQLite' => SYS_PATH.'database/connectors/sqlite'.EXT,
	'Laravel\\Database\\Grammars\\Grammar' => SYS_PATH.'database/grammars/grammar'.EXT,
	'Laravel\\Database\\Grammars\\MySQL' => SYS_PATH.'database/grammars/mysql'.EXT,
	'Laravel\\Routing\\Controller' => SYS_PATH.'routing/controller'.EXT,
	'Laravel\\Routing\\Filter' => SYS_PATH.'routing/filter'.EXT,
	'Laravel\\Routing\\Filter_Collection' => SYS_PATH.'routing/filter'.EXT,
	'Laravel\\Routing\\Route' => SYS_PATH.'routing/route'.EXT,
	'Laravel\\Routing\\Router' => SYS_PATH.'routing/router'.EXT,
	'Laravel\\Session\\Payload' => SYS_PATH.'session/payload'.EXT,
	'Laravel\\Session\\Drivers\\APC' => SYS_PATH.'session/drivers/apc'.EXT,
	'Laravel\\Session\\Drivers\\Cookie' => SYS_PATH.'session/drivers/cookie'.EXT,
	'Laravel\\Session\\Drivers\\Database' => SYS_PATH.'session/drivers/database'.EXT,
	'Laravel\\Session\\Drivers\\Driver' => SYS_PATH.'session/drivers/driver'.EXT,
	'Laravel\\Session\\Drivers\\File' => SYS_PATH.'session/drivers/file'.EXT,
	'Laravel\\Session\\Drivers\\Memcached' => SYS_PATH.'session/drivers/memcached'.EXT,
	'Laravel\\Session\\Drivers\\Redis' => SYS_PATH.'session/drivers/redis'.EXT,
	'Laravel\\Session\\Drivers\\Sweeper' => SYS_PATH.'session/drivers/sweeper'.EXT,
);

/**
 * Register all of the core class aliases. These aliases provide a
 * convenient way of working with the Laravel core classes without
 * having to worry about the namespacing. The developer is also
 * free to remove aliases when they extend core classes.
 */
Autoloader::$aliases = Config::get('application.aliases');