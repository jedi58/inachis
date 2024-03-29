<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function setDefaults()
    {
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
                'geotagContent' => false,
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
            'session' => $this->security->getUser(),
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
     * @return string
     */
    public function redirectIfNoAdmins()
    {
        if ($this->entityManager->getRepository(User::class)->count([]) == 0) {
            return 'app_setup_stage1';
        }
    }

    /**
     * If the user is trying to access a page such as sign-in but is already authenticated
     * they will be redirected to the dashboard.
     *
     * @return string
     */
    public function redirectIfAuthenticated()
    {
        if ($this->isAuthenticated()) {
            return 'app_dashboard_default';
        }
    }

    /**
     * @return string
     */
    public function redirectIfAuthenticatedOrNoAdmins()
    {
        return $this->redirectIfAuthenticated() ?: $this->redirectIfNoAdmins();
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
//        return $response->redirect('/incc/')->send();

        $response->prepare($request);

        return $response->send();
    }
}
