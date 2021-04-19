<?php

//--------------------------------------------------------------------

if (!function_exists('url')) {
	/**
	 * Return the base URL to use in views
	 *
	 * @param  mixed  $uri      URI string or array of URI segments
	 * @param  string $protocol
	 * @return string
	 */
	function url($uri = '', string $protocol = null): string
	{
		if (env('app.activeMultilangue') == true) {
			return base_url();
		} else {
			return base_url();
		}
		return base_url();
	}
}

//--------------------------------------------------------------------

if (!function_exists('base_urlFront')) {
	/**
	 * Return the base URL to use in views
	 *
	 * @param  mixed  $uri      URI string or array of URI segments
	 * @param  string $protocol
	 * @return string
	 */
	function base_urlFront($uri = '', string $protocol = null, $html = true, $flag = false): string
	{
		// convert segment array to string
		if (is_array($uri)) {
			$uri = implode('/', $uri);
		}
		if (service('Settings')->setting_activer_multilangue == true) {
			$uri = service('request')->getLocale() . '/' . $uri;
		}

		// We should be using the configured baseURL that the user set;
		// otherwise get rid of the path, because we have
		// no way of knowing the intent...
		$config = \CodeIgniter\Config\Services::request()->config;

		// If baseUrl does not have a trailing slash it won't resolve
		// correctly for users hosting in a subfolder.
		$baseUrl = !empty($config->baseURL) && $config->baseURL !== '/'
			? rtrim($config->baseURL, '/ ') . '/'
			: $config->baseURL;

		$url = new \CodeIgniter\HTTP\URI($baseUrl);
		unset($config);

		// Merge in the path set by the user, if any
		if (!empty($uri)) {
			$url = $url->resolveRelativeURI($uri);
		}

		// If the scheme wasn't provided, check to
		// see if it was a secure request
		if (empty($protocol) && \CodeIgniter\Config\Services::request()->isSecure()) {
			$protocol = 'https';
		}

		if (!empty($protocol)) {
			$url->setScheme($protocol);
		}



		if ($html == false) {
			if ($uri == '/')
				return rtrim((string) $url, '/ ');
			return rtrim((string) $url, '/ ');
		}

		if (!empty($uri) && $uri != service('request')->getLocale() . '/') {
			if (stripos($uri, env('app.suffix_url')) === false) {
				$url = $url . env('app.suffix_url');
			} else {
				$url = str_replace(env('app.suffix_url'), '', $url) . env('app.suffix_url');
			}
		}

		// On flag sont dispos
		$getQuery = service('request')->uri->getQuery();
		if ($getQuery != '') {
			if (stristr($getQuery, 'orderby') === true || stristr($getQuery, 'token') === true) {
				if (service('request')->uri->getQuery() != '') {
					$url = $url . '?' . service('request')->uri->getQuery();
				}
			}
		}





		return rtrim((string) $url, '/ ');
	}
}

if (!function_exists('getLinkPageAdmin')) {
	function getLinkPageAdmin($instance, int $id_lang)
	{


		switch ($instance->getClassEntities()) {
			case 'b_article':
				if (isset($instance->id_post)) {
					$slugArray = [];
					$link_0 = (new \Adnduweb\Ci4_blog\Models\PostModel())->getLink($instance->id_post, $id_lang);
					if ($link_0) {
						$slugArray[0] =  '/' . env('url.blog') . '/' . $link_0->slug;
					}
					asort($slugArray);
					$slug = implode('/', $slugArray);
					return $slug . env('app.suffix_url');
				}
				break;
			case 'category':
				if (isset($instance->id_post)) {
					$slugArray = [];
					$link_0 = (new \Adnduweb\Ci4_blog\Models\CategoryModel())->getLink($instance->id_post, $id_lang);
					if ($link_0) {
						$slugArray[0] =  '/' . env('url.blog') . '/' . $link_0->slug;
					}
					asort($slugArray);
					$slug = implode('/', $slugArray);
					return $slug . env('app.suffix_url');
				}
				break;
			case 'page':
				if (isset($instance->id_page)) {
					$slugArray = [];
					$link_0 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($instance->id_page, $id_lang);
					if ($link_0) {
						$slugArray[0] =  '/' . $link_0->slug;
						$link_1 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_0->id_parent, $id_lang);
						if ($link_1) {
							$slugArray[1] = '/' . $link_1->slug;
							$link_2 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_1->id_parent, $id_lang);
							if ($link_2) {
								$slugArray[2] = '/' . $link_2->slug;
								$link_4 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_2->id_parent, $id_lang);
							}
						}
					}
					if (empty($slugArray))
						return '/';
					asort($slugArray);
					$slug = implode('/', $slugArray);
					//exit;
					//return $instance->getSlug($id_lang) . env('app.suffix_url');
					return $slug . env('app.suffix_url');
				}
				break;
			default:
				return $instance->getSlug($id_lang);
		}
	}
}


if (!function_exists('getLinkPage')) {
	function getLinkPage(string $instance)
	{

		if (!is_int($instance)) {
			$link_0 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLinkBySlug($instance);
			if (empty($link_0)) {
				return  base_urlFront($instance);
			} else {
				return  base_urlFront($link_0->slug);
			}
		} else {
			if (isset($instance->id_page)) {
				$slugArray = [];
				$link_0 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($instance->id_page, service('switchlanguage')->getIdLocale());
				if ($link_0) {
					$slugArray[0] =  '/' . $link_0->slug;
					$link_1 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_0->id_parent, service('switchlanguage')->getIdLocale());
					if ($link_1) {
						$slugArray[1] = '/' . $link_1->slug;
						$link_2 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_1->id_parent, service('switchlanguage')->getIdLocale());
						if ($link_2) {
							$slugArray[2] = '/' . $link_2->slug;
							$link_4 = (new \Adnduweb\Ci4_page\Models\PageModel())->getLink($link_2->id_parent, service('switchlanguage')->getIdLocale());
						}
					}
				}
				if (empty($slugArray))
					return '/';
				asort($slugArray);
				$slug = implode('/', $slugArray);
				//exit;
				//return $instance->getSlug(service('switchlanguage')->getIdLocale()) . env('app.suffix_url');
				return $slug . env('app.suffix_url');
			}
		}
	}
}
