<?php

namespace Inachis\Component\CoreBundle\Storage;

/**
 * Object for managing the use of cookies
 */
class Cookie
{
	/**
	 * @const Indicates the cookie should be destroyed at the end of the session
	 */
	const SESSION = 0;
	/**
	 * @const Indicates the cookie should last for one day
	 */
	const ONE_DAY = 86400;
	/**
	 * @const Indicates the cookie should last for seven days
	 */
	const SEVEN_DAYS = 604800;
	/**
	 * @const Indicates the cookie should last for thirty days
	 */
	const THIRTY_DAYS = 2592000;
	/**
	 * @const Indicates the cookie should last for six months (approx based on 30.5 days)
	 */
	const SIX_MONTHS = 15811200;
	/**
	 * @const Indicates the cookie should last for one year (excludes leap days)
	 */
	const ONE_YEAR = 31536000;
	/**
	 * Get the value of the given cookie. If the cookie does not exist the value
	 * of $default will be returned.
	 * @param string $name The name of the cookie to return the value for
	 * @param string $default The default value to return if the cookie isn't set
	 * @return string The contents of the cookie
	 */
	public static function get($name, $default = '')
	{
		return isset($_COOKIE[$name]) ? urldecode($_COOKIE[$name]) : $default;
	}
	/**
	 * Set a cookie. Silently does nothing if headers have already been sent.
	 * @param string $name  The name of the cookie
	 * @param string $value The contents of the cookie
	 * @param int $expire Number of seconds after which the cookie should expire
	 * @param string $path The path on the site the cookie should be available for
	 * @param string $domain The domain the cookie should be available for
	 * @param bool $secure Flag indicating if cookie is for secure connections only
	 * @param bool $httpOnly Flag indicating if cookie can only be requested by HTTP requests
	 * @return bool The result of setting the cookie
	 * @throws \InvalidArgumentException
	 */
	public static function set(
		$name,
		$value = null,
		$expire = self::ONE_YEAR,
		$path = '/',
		$domain = '',
		$secure = false,
		$httpOnly = true
	) {
		if (!headers_sent()) {
			if (empty($name)) {
				throw new \InvalidArgumentException('Cookie name cannot be empty');
			} elseif (preg_match('/[\;\,\s\x00\x1F\x7F]/', $name)) {
				throw new \InvalidArgumentException('Cookie name cannot contain semi-colon, comma, or white-space');
			}
			if (isset($_SERVER['HTTPS'])) {
				$secure = true;
			}
			return setcookie($name, urlencode($value), time() + $expire, $path, $domain, (bool) $secure, (bool) $httpOnly);
		}
		return false;
	}
	/**
	 * Returns true if there is a cookie with this name.
	 * @param string $name The name of the cookie to check for
	 * @return bool The result of testing if the cookie exists
	 */
	public static function exists($name)
	{
		return isset($_COOKIE[$name]);
	}
	/**
	 * Deletes the specified cookie
	 * @param string $name The name of the cookie to remove
	 * @param string $path The path the cookie is set for
	 * @param string $domain The domain the cookie is set for
	 */
	public static function delete($name, $path = '/', $domain = '')
	{
		if (!headers_sent() && isset($_COOKIE[$name])) {
			setcookie($name, '', time() - 3600, $path, $domain);
			unset($_COOKIE[$name]);
		}
	}
}
