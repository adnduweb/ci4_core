<?php 

namespace Adnduweb\Ci4Core\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

/**
 * Class FrameworkException
 *
 * A collection of exceptions thrown by the framework
 * that can only be determined at run time.
 *
 * @package CodeIgniter\Exceptions
 */

class AdnduwebException extends \RuntimeException implements ExceptionInterface
{

	public static function forInvalidFile(string $path)
	{
		return new static(lang('Core.invalidFile', [$path]));
	}

	public static function forCopyError(string $path)
	{
		return new static(lang('Core.copyError', [$path]));
	}

	public static function forMissingExtension(string $extension)
	{
		return new static(lang('Core.missingExtension', [$extension]));
	}

	public static function forNoHandlers(string $class)
	{
		return new static(lang('Core.noHandlers', [$class]));
	}

	public static function NoMethodsExits(string $method)
	{
		return new static(lang('Core.NoMethodsExits', [$method]));
	}

	public static function noResponseServiceApi(string $method)
	{
		return new static(lang('Core.noResponseServiceApi', [$method]));
	}

	public static function serviceIsUnavailable(string $message)
	{
		return new static(lang('Core.serviceIsUnavailable : {0}', [$message]), 503);
	}

	public static function emailNoFormated(string $message)
	{
		return new static(lang('Core.emailNoFormated : {0}', [$message]), 400);
	}
	public static function NotProductFound(string $message)
	{
		return new static(lang('Core.emailNoFormated : {0}', [$message]), 400);
	}

}
