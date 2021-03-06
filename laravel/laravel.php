<?php namespace Laravel;

/**
 * Bootstrap the core framework components like the IoC container,
 * configuration class, and the class auto-loader. Once this file
 * has run, the framework is essentially ready for use.
 */
require 'core.php';

/**
 * Register the default timezone for the application. This will be
 * the default timezone used by all date / timezone functions in
 * the entire application.
 */
date_default_timezone_set(Config::get('application.timezone'));

/**
 * Create the exception logging function. All of the error logging
 * is routed through here to avoid duplicate code. This Closure
 * will determine if the actual logging Closure should be called.
 */
$logger = function($exception)
{
	if (Config::get('error.log'))
	{
		call_user_func(Config::get('error.logger'), $exception);
	}
};

/**
 * Create the exception handler function. All of the error handlers
 * registered by the framework call this closure to avoid duplicate
 * code. This Closure will pass the exception to the developer
 * defined handler in the configuration file.
 */
$handler = function($exception) use ($logger)
{
	$logger($exception);

	if (Config::get('error.detail'))
	{
		echo "<html><h2>Unhandled Exception</h2>
			  <h3>Message:</h3>
			  <pre>".$exception->getMessage()."</pre>
			  <h3>Location:</h3>
			  <pre>".$exception->getFile()." on line ".$exception->getLine()."</pre>
			  <h3>Stack Trace:</h3>
			  <pre>".$exception->getTraceAsString()."</pre></html>";
	}
	else
	{
		Response::error('500')->send();
	}

	exit(1);
};

/**
 * Register the PHP exception handler. The framework throws exceptions
 * on every error that cannot be handled. All of those exceptions will
 * be sent through this closure for processing.
 */
set_exception_handler(function($exception) use ($handler)
{
	$handler($exception);
});

/**
 * Register the PHP error handler. All PHP errors will fall into this
 * handler, which will convert the error into an ErrorException object
 * and pass the exception into the common exception handler. Suppressed
 * errors are ignored and errors in the developer configured whitelist
 * are silently logged.
 */
set_error_handler(function($code, $error, $file, $line) use ($logger)
{
	if (error_reporting() === 0) return;

	$exception = new \ErrorException($error, $code, 0, $file, $line);

	if (in_array($code, Config::get('error.ignore')))
	{
		return $logger($exception);
	}

	throw $exception;
});

/**
 * Register the PHP shutdown handler. This function will be called
 * at the end of the PHP script or on a fatal PHP error. If an error
 * has occured, we will convert it to an ErrorException and pass it
 * to the common exception handler for the framework.
 */
register_shutdown_function(function() use ($handler)
{
	if ( ! is_null($error = error_get_last()))
	{
		extract($error, EXTR_SKIP);

		$handler(new \ErrorException($message, $type, 0, $file, $line));
	}	
});

/**
 * Setting the PHP error reporting level to -1 essentially forces
 * PHP to report every error, and is guranteed to show every error
 * on future versions of PHP.
 *
 * If error detail is turned off, we will turn off all PHP error
 * reporting and display since the framework will be displaying a
 * generic message and we don't want any sensitive details about
 * the exception leaking into the views.
 */
error_reporting(-1);

ini_set('display_errors', 'Off');

/**
 * Load the session and session manager instance. The session
 * payload will be registered in the IoC container as an instance
 * so it can be retrieved easily throughout the application.
 */
if (Config::get('session.driver') !== '')
{
	Session::start(Config::get('session.driver'));

	Session::load(Cookie::get(Config::get('session.cookie')));
}

/**
 * Gather the input to the application based on the current request.
 * The input will be gathered based on the current request method and
 * will be set on the Input manager.
 */
$input = array();

switch (Request::method())
{
	case 'GET':
		$input = $_GET;
		break;

	case 'POST':
		$input = $_POST;
		break;

	case 'PUT':
	case 'DELETE':
		if (Request::spoofed())
		{
			$input = $_POST;
		}
		else
		{
			parse_str(file_get_contents('php://input'), $input);
		}
}

/**
 * The spoofed request method is removed from the input so it is not
 * unexpectedly included in Input::all() or Input::get(). Leaving it
 * in the input array could cause unexpected results if an Eloquent
 * model is filled with the input.
 */
unset($input[Request::spoofer]);

Input::$input = $input;

/**
 * Start all of the bundles that are specified in the configuration
 * array of auto-loaded bundles. This gives the developer the ability
 * to conveniently and automatically load bundles that are used on
 * every request to their application.
 */
foreach (Config::get('application.bundles') as $bundle)
{
	Bundle::start($bundle);
}

/**
 * Load the "application" bundle. Though the application folder is
 * not typically considered a bundle, it is started like one and
 * essentially serves as the "default" bundle.
 */
Bundle::start(DEFAULT_BUNDLE);

/**
 * If the first segment of the request URI corresponds with a bundle,
 * we will start that bundle. By convention, bundles handle all URIs
 * which begin with their bundle name.
 */
$bundle = URI::segment(1);

if ( ! is_null($bundle) and Bundle::routable($bundle))
{
	Bundle::start($bundle);
}

/**
 * Route the request to the proper route in the application. If a
 * route is found, the route will be called with the current request
 * instance. If no route is found, the 404 response will be returned
 * to the browser.
 */
Request::$route = Routing\Router::route(Request::method(), URI::current());

if ( ! is_null(Request::$route))
{
	$response = Request::$route->call();
}
else
{
	$response = Response::error('404');
}

/**
 * Close the session and write the active payload to persistent
 * storage. The session cookie will also be written and if the
 * driver is a sweeper, session garbage collection might be
 * performed depending on the "sweepage" probability.
 */
if (Config::get('session.driver') !== '')
{
	Session::save();
}

$response->send();