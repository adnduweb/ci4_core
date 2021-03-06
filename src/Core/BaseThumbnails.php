<?php

namespace Adnduweb\Ci4Core\Core;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Adnduweb\Ci4Core\Core\ImageThumbnail;
use Adnduweb\Ci4Core\Config\Thumbnails as ThumbnailsConfig;
use Adnduweb\Ci4Core\Exceptions\ThumbnailsException;
use Adnduweb\Ci4Core\Interfaces\ThumbnailInterface;

class BaseThumbnails
{
	/**
	 * The configuration instance.
	 *
	 * @var ThumbnailsConfig
	 */
	protected $config;

	/**
	 * Output width.
	 *
	 * @var integer
	 */
	protected $width;

	/**
	 * Output height.
	 *
	 * @var integer
	 */
	protected $height;

	/**
	 * The image type constant.
	 *
	 * @var integer
	 *
	 * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
	 */
	protected $imageType;

	/**
	 * Initializes the library with its configuration.
	 *
	 * @param ThumbnailsConfig|null $config
	 */
	public function __construct(ThumbnailsConfig $config = null)
	{
		$this->setConfig($config);
		$this->imageThumbnail = new ImageThumbnail();
	}

	/**
	 * Resets library state to the provided configuration.
	 * Called between each create()
	 *
	 * @return $this
	 */
	public function reset(): self
	{
		foreach (['width', 'height', 'imageType'] as $key) {
			$this->$key = $this->config->$key;
		}


		return $this;
	}

	/**
	 * Sets the configuration to use.
	 *
	 * @param ThumbnailsConfig|null $config
	 *
	 * @return $this
	 */
	public function setConfig(ThumbnailsConfig $config = null): self
	{
		$this->config = $config ?? config('Thumbnails');
		$this->reset();

		return $this;
	}

	/**
	 * Sets the output image type.
	 *
	 * @param integer $imageType
	 *
	 * @return $this
	 */
	public function setImageType(int $imageType): self
	{
		$this->imageType = $imageType;
		return $this;
	}

	/**
	 * Sets the output image width.
	 *
	 * @param integer $width
	 *
	 * @return $this
	 */
	public function setWidth(int $width): self
	{
		$this->width = $width;
		return $this;
	}

	/**
	 * Sets the output image height.
	 *
	 * @param integer $height
	 *
	 * @return $this
	 */
	public function setHeight(int $height): self
	{
		$this->height = $height;
		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Specifies the handler to use instead of matching it automatically.
	 *
	 * @param ThumbnailInterface|string|null $handler
	 *
	 * @return $this
	 */
	public function setHandler($handler = null): self
	{
		if (is_string($handler) && $class = $this->handlers->named($handler)) {
			$handler = new $class();
		}
		$this->handler = $handler;

		return $this;
	}

	/**
	 * Gets all handlers that support a certain file extension.
	 *
	 * @param string $extension The file extension to match
	 *
	 * @return ThumbnailInterface[]
	 */
	public function matchHandlers(string $extension): array
	{
		$handlers = [];

		if (stripos($this->imageThumbnail->attributes['extensions'], $extension) !== false) {
			// Make sure actual matches get preference over generic ones
			array_unshift($handlers, $extension);
		}

		return $handlers;
	}

	//--------------------------------------------------------------------

	/**
	 * Reads and verifies the file then passes to a supported handler to
	 * create the thumbnail.
	 *
	 * @param string $input  Path to the input file
	 * @param string $output Path to the input file
	 *
	 * @return $this
	 * @throws FileNotFoundException
	 * @throws ThumbnailsException
	 */
	public function create(string $input, string $output): self
	{
		// Validate the file
		$file = new File($input);
		if (!$file->isFile()) {
			throw FileNotFoundException::forFileNotFound($input);
		}

		// Get the file extension
		if (!$extension = $file->guessExtension() ?? pathinfo($input, PATHINFO_EXTENSION)) {
			throw new ThumbnailsException(lang('Thumbnails.noExtension'));
		}
		
		// // Determine which handlers to use
		$extensionExist = $this->matchHandlers($extension);

		// // No handlers matched?
		if (empty($extensionExist)) {
			throw new ThumbnailsException(lang('Thumbnails.noHandler', [$extension]));
		}

		

		// Try each handler until one succeeds
		$result = false;
			if ($this->imageThumbnail->create($file, $output, $this->imageType, $this->width, $this->height)) {
				// Verify the output file
				if (exif_imagetype($output) === $this->imageType) {
					$result = true;
				}
			}

		$this->reset();

		if (!$result) {
			throw new ThumbnailsException(lang('Thumbnails.createFailed', [$input]));
		}

		return $this;
	}

}
