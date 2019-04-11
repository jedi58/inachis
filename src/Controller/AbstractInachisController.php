<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Flex\Response;

abstract class AbstractInachisController extends AbstractController
{
    protected $entityManager;
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var array
     */
    protected $data = [];

    public function setDefaults()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->data = [
            'settings' => [
                'siteTitle' => $this->container->hasParameter('app.title') ?
                    $this->container->getParameter('app.title') :
                    null,
                'domain' => $this->getProtocolAndHostname(),
                'google' => [],
                'language' => $this->container->hasParameter('locale') ?
                    $this->container->getParameter('locale') :
                    'en',
                'textDirection' => 'ltr',
                'abstract' => '',
                'fb_app_id' => $this->container->hasParameter('app.fb_app_id') ?
                    $this->container->getParameter('app.fb_app_id') :
                    null,
                'twitter' => $this->container->hasParameter('app.twitter') ?
                    $this->container->getParameter('app.twitter') :
                    null,
            ],
            'notifications' => [],
            'page'          => [
                'self'          => '',
                'tab'           => '',
                'title'         => '',
                'description'   => '',
                'keywords'      => '',
                'modDate'       => '',
            ],
            'post' => [
                'featureImage' => '',
            ],
            'session' => $this->get('security.token_storage')->getToken(),
        ];
    }

    /**
     * @return string
     */
    private function getProtocolAndHostname() : string
    {
        $protocol = $this->isSecure() ? 'https://' : 'http://';

        return $protocol . (!empty($_ENV['APP_DOMAIN']) ? $_ENV['APP_DOMAIN'] : '');
    }

    /**
     * @return bool
     */
    private function isSecure() : bool
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
            || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'
        ) {
            $isSecure = true;
        }

        return $isSecure;
    }

    /**
     * Returns all current errors on the page.
     *
     * @return string[] The array of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns a specific error message given by it's unique name.
     *
     * @param string $error The name of the error message to retrieve
     *
     * @return string|null The requested error message if set
     */
    public function getError($error)
    {
        return isset($this->errors[$error]) ? $this->errors[$error] : null;
    }

    /**
     * Adds an error to the current controller to be displayed/handled on
     * by the view.
     *
     * @param string $error   Unique identifier for the error
     * @param string $message The friendly message for the error
     */
    public function addError($error, $message)
    {
        $this->errors[$error] = (string) $message;
    }

    /**
     * @return RedirectResponse
     */
    public function redirectIfNoAdmins()
    {
        if ($this->entityManager->getRepository(User::class)->count([]) == 0) {
            return $this->redirectToRoute('app_setup_stage1');
        }
    }

    /**
     * Used when the request requires authentication; if the not authenticated
     * then the user's requested page URL is stored in the session and then
     * redirected to the sign-in page. Otherwise, it also tests if their password
     * has expired.
     *
     * @param Request $request The request object from the router
     *
     * @return mixed
     */
    public function redirectIfNotAuthenticated(Request $request)
    {
        die('redirectIfNotAuthenticated');
        if (true) { // !Application::getInstance()->requireAuthenticationService()->isAuthenticated()
            $redirect = new RedirectResponse('/incc/signin');
            $referrer = parse_url($request->server->get('REQUEST_URI'));
            if (!empty($referrer) && (empty($referrer['host']) || $referrer['host'] == $request->server->get('HTTP_HOST'))) {
                $request->getSession()->set('referrer', $request->server->get('REQUEST_URI'));
            }
            $redirect->prepare($request);

            return $redirect->send();
        }
//        return self::redirectIfPasswordExpired($request, $response);
    }

    /**
     * If the user is trying to access a page such as sign-in but is already authenticated
     * they will be redirected to the dashboard.
     *
     * @param Request  $request
     * @param Response $response The response object from the router
     *
     * @return RedirectResponse
     */
//    public function redirectIfAuthenticated(Request $request, Response $response)
//    {
//        if (true) { //Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
//            $response = new RedirectResponse('/incc');
//            $response->prepare($request);
//            return $response->send();
//        }
//    }

    /**
     * If the user's password has expired their current request URL will be stored in the session
     * and will then be redirected to change-password.
     *
     * @param Request  $request  The request object from the router
     * @param Response $response The response object from the router
     */
    public function redirectIfPasswordExpired(Request $request, Response $response)
    {
//        if ($response->isLocked()) {
//            return;
//        }
//        if (Application::getInstance()->getService('session')->get('user')->hasCredentialsExpired()) {
//            $referrer = parse_url($request->server()->get('HTTP_REFERER'));
//            if (!empty($referrer) &&
//                (empty($referrer['host']) || $referrer['host'] == $request->server()->get('HTTP_HOST')) &&
//                strpos($referrer, 'change-password') === false
//            ) {
//                Application::getInstance()->requireSessionService()->set('referrer', $request->server()->get('HTTP_REFERER'));
//            }
//            $response->redirect('/inadmin/change-password')->send();
//        }
    }

    /**
     * If the user has a referrer set they will be redirected to it otherwise they will be redirected to
     * the dashboard.
     *
     * @param Request  $request
     * @param Response $response The response object from the router
     *
     * @return mixed
     */
    public function redirectToReferrerOrDashboard(Request $request, Response $response)
    {
//        if ($response->isLocked()) {
//            return null;
//        }
        $referrer = $request->getSession()->get('referrer');
        if (!empty($referrer)) {
//            return $response->redirect($referrer)->send();
        }
//        return $response->redirect('/inadmin/')->send();

        $response->prepare($request);

        return $response->send();
    }
}
