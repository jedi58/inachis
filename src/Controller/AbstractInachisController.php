<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Flex\Response;

abstract class AbstractInachisController extends AbstractController
{
    protected $security;
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
                'siteTitle' => $this->container->has('app.title') ?
                    $this->container->get('app.title') :
                    null,
                'domain' => $this->getProtocolAndHostname(),
                'google' => [],
                'language' => $this->container->has('locale') ?
                    $this->container->get('locale') :
                    'en',
                'textDirection' => 'ltr',
                'abstract' => '',
                'fb_app_id' => $this->container->has('app.fb_app_id') ?
                    $this->container->get('app.fb_app_id') :
                    null,
                'twitter' => $this->container->has('app.twitter') ?
                    $this->container->get('app.twitter') :
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
     * Returns the result of testing if a user is currently signed in
     * @return bool Status of user authentication
     */
    private function isAuthenticated()
    {
        return $this->security instanceof Security &&
            $this->security->getUser() instanceof User &&
            !empty($this->security->getUser()->getUsername());
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
     * If the user is trying to access a page such as sign-in but is already authenticated
     * they will be redirected to the dashboard.
     *
     * @return RedirectResponse
     */
    public function redirectIfAuthenticated()
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('app_dashboard_default');
        }
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
        $referrer = $request->getSession()->get('referrer');
        if (!empty($referrer)) {
//            return $response->redirect($referrer)->send();
        }
//        return $response->redirect('/inadmin/')->send();

        $response->prepare($request);

        return $response->send();
    }
}
