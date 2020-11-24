<?php 

namespace Adnduweb\Ci4Core\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class ThumbnailsException extends \RuntimeException implements ExceptionInterface
{
	public static function forNoHandler($extension)
	{
		return new static(lang('Thumbnails.noHandler', [$extension]));
	}
}