<?php

namespace Inachis\Component\CoreBundle\Security;

use Inachis\Component\CoreBundle\Application;

class SessionManager
{
    /**
     * @var string The name of the session
     */
    protected $sessionName;
    /**
     * @var bool Flag indicating if a session has been started
     */
    protected $started = false;
    /**
     * Default constructor for {@link SessionManager} - will forcibly enable
     * use of cookies for session, and will set the session name to be used
     * if/when applicable
     * If a cookie of the same name has been sent the session will be started
     * @param string $sessionName The name of the session to create. Default: inAdmin
     */
    public function __construct($sessionName = 'inAdmin')
    {
        ini_set('session.use_cookies', 1);
        $this->sessionName  = $sessionName;
        if (isset($_COOKIE[$sessionName])) {
            $this->start();
        }
    }
    /**
     * Starts/resumes a session
     * @return void
     * @throws \Exception
     */
    public function start()
    {
        if ($this->started || \PHP_SESSION_DISABLED === session_status()) {
            throw new \Exception('Session cannot started');
        }
        session_name($this->sessionName);
        session_start();
        if (!$this->isValid()) {
            $this->end();
            return;
        }
        $this->started = true;
        if (empty($this->get('remoteAddr')) && empty($this->get('userAgent'))) {
            $this->set(
                'remoteAddr',
                Application::getInstance()->getRouter()->getRequest()->server()->get('REMOTE_ADDR')
            );
            $this->set(
                'userAgent',
                Application::getInstance()->getRouter()->getRequest()->server()->get('HTTP_USER_AGENT')
            );
        }
        $this->set('lastActive', time());
    }
    /**
     * Ends the current session
     */
    public function end()
    {
        $_SESSION = array();
        session_destroy();
    }
    /**
     * Returns the result of checking if the user's IP address has changed during the current session
     * @return bool The result of testing if the IP in the session matches the current request's IP
     */
    private function hasRemoteAddrChanged()
    {
        return !empty($this->session) &&
            $this->get('remoteAddr') !== Application::getInstance()->getRouter()->getRequest()->server()->get('REMOTE_ADDR');
    }
    /**
     * Returns the result of checking if the user's user-agent has changed during the current session
     * @return bool The result of testing if the User-agent in the session matches the current request's one
     */
    private function hasUserAgentChanged()
    {
        return !empty($this->session) &&
            $this->get('userAgent') !== Application::getInstance()->getRouter()->getRequest()->server()->get('HTTP_USER_AGENT');
    }
    /**
     * Checks if the current session has expired
     * @param int $ttl The time (in minutes) for the session to last for. Default: 30
     * @return bool The result of testing if the current session has expired
     */
    private function hasExpired($ttl = 30)
    {
        $lastActive = $this->get('lastActive');
        if (empty($lastActive)) {
            return false;
        }
        return time() - $lastActive > $ttl * 60;
    }
    /**
     * Returns the value of a specific session value by key
     * @param string $key The key to return the value for
     * @return mixed The value in the session given by {@link $key}
     */
    public function get($key)
    {
        return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    /**
     * Sets the value of $_SESSION[$key] to $value - will start the session if not
     * already started
     * @param string $key The key to set the value for
     * @param mixed $value The value to apply to the key
     */
    public function set($key, $value)
    {
        if (!$this->started) {
            $this->start();
        }
        $_SESSION[$key] = $value;
    }
    /**
     * Unsets the specified key in the session
     * @param string $key The key to unset
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
    /**
     * Returns the result of checking if a specific key is in use
     * @param string $key The key to check for
     * @return bool The result of checking for the specific key
     */
    public function hasKey($key)
    {
        return isset($_SESSION[$key]);
    }
    /**
     * Checks if the current session is still valid based on expiry time, user-agent, and IP
     * @return bool The result of testing if the current session is valid
     */
    public function isValid()
    {
        return !$this->hasExpired() &&
            !$this->hasUserAgentChanged() &&
            !$this->hasRemoteAddrChanged();
    }
    /**
     * Generates a new ID for the current session if active
     */
    public function regenerate()
    {
        if (\PHP_SESSION_ACTIVE !== session_status()) {
            return false;
        }
        session_regenerate_id();
    }
}
