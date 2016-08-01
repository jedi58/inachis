<?php

namespace Inachis\Component\CoreBundle\Security;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\UserManager;
use ParagonIE\Halite\Symmetric\Crypto;

class Authentication
{
    /**
     * @var UserManager Used for interaction with {@link User} entities
     */
    protected $userManager;
    /**
     * Default constructor for {@link Authentication} - instantiates a new
     * {@link UserManager}
     */
    public function __construct()
    {
        $this->userManager = new UserManager(Application::getInstance()->getService('em'));
    }
    /**
     * Returns the {@link UserManager} object
     * @return UserManager The {@link UserManager} object
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
    /**
     * Returns result of testing if the current user is signed in
     * @return bool The result of testing if the current user is signed in
     */
    public function isAuthenticated()
    {
        return !empty(Application::getInstance()->getService('session')) &&
            Application::getInstance()->getService('session')->hasKey('user') &&
            !empty(Application::getInstance()->getService('session')->get('user')->getUsername());
    }
    /**
     * Attempts to sign the current user in
     * @param string $username The username to sign in with
     * @param string $password The password the user is attempting to sign in with
     * @return bool The result of attempting to sign the user in
     */
    public function login($username, $password)
    {
        $user = $this->userManager->getByUsername($username);
        $ok = !empty($user) && !empty($user->getId()) && $user->validatePasswordHash($password);
        if ($ok) {
            Application::getInstance()->getService('session')->set('user', $user);
            Application::getInstance()->getService('session')->regenerate();
            //Application::getInstance()->getService('log')->add('login', $user->getUsername(), $user->getId());
        }
        return $ok;
    }
    /**
     * Terminates the current user session
     */
    public function logout()
    {
        Application::getInstance()->getService('session')->remove('user');
        Application::getInstance()->getService('session')->regenerate();
        //Application::getInstance()->getService('log')->add('logout', $user->getUsername(), $user->getId());
    }
    /**
     * Creates a new user with the given properties
     * @param string $username The username for the user
     * @param string $password The password for the user
     * @param string[] $properties Additional properties to assign to the user
     * @return bool The result of attempting to create the new user
     */
    public function create($username, $password, $properties = array())
    {
        if (empty($username) || empty($password)) {
            throw new \Exception('Username and password cannot be empty');
        }
        $user = $this->userManager->getByUsername($username);
        if (!empty($user)) {
            return false;
        }
        $properties['username'] = $username;
        $user = $this->userManager->create($properties);
        $user->setPasswordHash($password);
        $this->userManager->save($user);
        return true;
    }
}
