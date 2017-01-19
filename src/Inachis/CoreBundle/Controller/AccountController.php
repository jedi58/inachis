<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\Page;
use Inachis\Component\CoreBundle\Entity\PageManager;
use Inachis\Component\CoreBundle\Form\FormBuilder;
use Inachis\Component\CoreBundle\Form\Fields\ButtonType;
use Inachis\Component\CoreBundle\Form\Fields\ChoiceType;
use Inachis\Component\CoreBundle\Form\Fields\FieldsetType;
use Inachis\Component\CoreBundle\Form\Fields\FileUploadType;
use Inachis\Component\CoreBundle\Form\Fields\HiddenType;
use Inachis\Component\CoreBundle\Form\Fields\NumberType;
//use Inachis\Component\CoreBundle\Form\Fields\ReCaptchaType;
use Inachis\Component\CoreBundle\Form\Fields\SelectType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionGroupType;
//use Inachis\Component\CoreBundle\Form\Fields\TableType;
use Inachis\Component\CoreBundle\Form\Fields\TextType;
use Inachis\Component\CoreBundle\Form\Fields\TextAreaType;
use Inachis\Component\CoreBundle\Storage\Cookie;

class AccountController extends AbstractController
{
    /**
     * @Route("/setup")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getSetup($request, $response, $service, $app)
    {
        self::redirectIfAuthenticated($response);
        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() > 0) {
            return $response->redirect('/inadmin/signin')->send();
        }
        if ($request->method('post') && !empty($request->paramsPost()->get('username')) && !empty($request->paramsPost()->get('password'))) {
            if (Application::getInstance()->getService('auth')->create(
                $request->paramsPost()->get('username'),
                $request->paramsPost()->get('password'),
                array(
                    'displayName' => $request->paramsPost()->get('name'),
                    'email' => $request->paramsPost()->get('email')
                )
            )) {
                return $response->redirect('/inadmin/signin')->send();
            }
        }
        if ($response->isLocked()) {
            return null;
        }
        self::adminInit($request, $response);
        self::$data['page'] = array('title' => 'Inachis Install - Step 1');
        self::$data['form'] = (new FormBuilder(array(
                'action' => '/setup',
                'autocomplete' => false,
                'cssClasses' => 'form form__login form__setup'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Setup your web application'
            )))
            ->addComponent(new TextType(array(
                'name' => 'siteName',
                'label' => 'Site name',
                'placeholder' => 'e.g. My awesome site',
                'required' => true
            )))
            ->addComponent(new TextType(array(
                'name' => 'siteUrl',
                'label' => 'URL',
                'required' => true,
                'type' => 'url',
                'value' => 'http' . ($request->isSecure() ? 's' : '') . '://' .
                    $request->server()->get('HTTP_HOST') .
                    str_replace('/setup', '', $request->server()->get('REQUEST_URI'))
            )))

            ->addComponent(new FieldsetType(array(
                'legend' => 'Administrator'
            )))
            ->addComponent(new TextType(array(
                'name' => 'username',
                'label' => 'Username',
                'required' => true,
                'value' => 'admin'
            )))
            ->addComponent(new TextType(array(
                'name' => 'password',
                'label' => 'Password',
                'required' => true,
                'type' => 'password'
            )))
            ->addComponent(new TextType(array(
                'name' => 'name',
                'label' => 'Name',
                'placeholder' => 'e.g. John Smith',
                'required' => true
            )))
            ->addComponent(new TextType(array(
                'name' => 'email',
                'label' => 'Email Address',
                'placeholder' => 'e.g. somebody@example.com',
                'required' => true,
                'type' => 'email'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Actions'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Continue…'
            )));
        return $response->body($app->twig->render('setup__stage-1.html.twig', self::$data));
    }

    /**
     * @Route("/inadmin/signin")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getSignin($request, $response, $service, $app)
    {
        self::redirectIfAuthenticated($response);
        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() === 0) {
            return $response->redirect('/setup')->send();
        }
        // Check if user has cookies to indicate persistent sign-in
        if (Application::getInstance()->getService('auth')->getSessionPersist($request->server()->get('HTTP_USER_AGENT'))) {
            return self::redirectToReferrerOrDashboard($response);
        }
        if ($response->isLocked()) {
            return null;
        }
        // Handle sign-in
        if ($request->method('post') && !empty($request->paramsPost()->get('loginUsername'))
                && !empty($request->paramsPost()->get('loginPassword'))) {
            if (Application::getInstance()->getService('auth')->login(
                $request->paramsPost()->get('loginUsername'),
                $request->paramsPost()->get('loginPassword')
            )) {
                if (!empty($request->paramsPost()->get('rememberMe')) && (bool) $request->paramsPost()->get('rememberMe')) {
                    Application::getInstance()->getService('auth')->setSessionPersist(
                        $request->server()->get('HTTP_USER_AGENT'),
                        $request->server()->get('HTTP_HOST')
                    );
                }
                return $response->redirect('/inadmin/')->send();
            } else {
                self::$errors['username'] = 'Authentication Failed.';
            }
        }
        self::adminInit($request, $response);
        self::$data['page'] = array('title' => 'Sign In');
        self::$data['form'] = (new FormBuilder(array(
                'action' => '/inadmin/signin',
                'autocomplete' => false,
                'cssClasses' => 'form form__login'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Sign in using email address'
            )))
            ->addComponent(new TextType(array(
                'name' => 'loginUsername',
                'autofocus' => true,
                'label' => 'Username',
                'id' => 'form-login__username',
                'labelId' => 'form-login__username-label',
                'cssClasses' => 'text',
                'placeholder' => 'Username',
                'ariaAttributes' => array(
                    'required' => true,
                    'labelledby' => 'form-login__username-label'
                )
            )))
            ->addComponent(new TextType(array(
                'name' => 'loginPassword',
                'type' => 'password',
                'label' => 'Password',
                'id' => 'form-login__password',
                'labelId' => 'form-login__password-label',
                'cssClasses' => 'text',
                'placeholder' => 'Password',
                'ariaAttributes' => array(
                    'required' => true,
                    'labelledby' => 'form-login__password-label'
                )
            )))
            ->addComponent(new ChoiceType(array(
                'name' => 'rememberMe',
                'label' => 'Keep me logged in',
                'cssClasses' => 'checkbox',
                'value' => '1'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Log In'
            )));
        self::$data['data'] = array(
            'loginUsername' => $request->paramsPost()->get('loginUsername'),
            'rememberMe' => !empty($request->paramsPost()->get('rememberMe')) ?
                $request->paramsPost()->get('rememberMe') :
                $request->cookies()->get('rememberMe')
        );
        //self::$data['errors'] = self::$errors;
        return $response->body($app->twig->render('admin__signin.html.twig', self::$data));
    }

    /**
     * @Route("/inadmin/signout")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @return mixed
     */
    public static function getSignout($request, $response)
    {
        Application::getInstance()->requireAuthenticationService()->logout();
        return $response->redirect('/inadmin/signin')->send();
    }

    /**
     * @Route("/inadmin/forgot-password")
     * @Method({"GET"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getForgotPassword($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        $data = array(
            'form' => (new FormBuilder(array(
                'action' => '/inadmin/forgot-password',
                'autocomplete' => false,
                'cssClasses' => 'form form__login form__forgot'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Enter your Email address / Username'
            )))
            ->addComponent(new TextType(array(
                'name' => 'forgot_email',
                'cssClasses' => 'text',
                'label' => 'Enter your email address to reset login',
                'placeholder' => 'user@example.com',
                'ariaAttributes' => array( 'required' => true ),
            )))
            //->addComponent(new ReCaptchaType(array()))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Reset password'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'button',
                'cssClasses' => 'button button--negative',
                'label' => 'Cancel'
            ))),
            'data' => array(
                "resetEmailAddress" => $request->paramsPost()->get('resetEmailAddress')
            ),
            'error' =>array(
                //validate email address format
            )
        );
        return $response->body($app->twig->render('admin__forgot-password.html.twig', $data));
    }

    /**
     * @Route("/inadmin/forgot-password")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getForgotPasswordSent($request, $response, $service, $app)
    {
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/')->send();
        }
        if (false) { // @todo if request contains errors then use
            return self::getForgotPassword($request, $response, $service, $app);
        }
        if ($response->isLocked()) {
            return;
        }
        return $response->body($app->twig->render('admin__forgot-password-sent.html.twig', array()));
    }

    /**
     * @Route("/inadmin/user-management")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @return mixed
     */
    public static function getAdminList($request, $response)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return null;
        }
        return $response->body('Show all admins');
    }

    /**
     * @Route("/inadmin/user/{id}")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @return mixed
     */
    public static function getAdminDetails($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return null;
        }
        self::adminInit($request, $response);
        self::$data['page'] = array('title' => 'Profile');
        return $response->body($app->twig->render('admin__profile.html.twig', self::$data));
    }

    /**
     * @Route("@^/inadmin/?$")
     * @Method({"GET"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getAdminDashboard($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        $pageManager = new PageManager(Application::getInstance()->getService('em'));
        self::adminInit($request, $response);
        self::$data['page'] = array('title' => 'Dashboard');
        self::$data['data'] = array(
            'draftCount' => $pageManager->getAllCount(array(
                'q.status = :status',
                array('status' => Page::DRAFT)
            )),
            'publishCount' => $pageManager->getAllCount(array(
                'q.status = :status AND q.postDate <= :postDate',
                array(
                    'status' => Page::PUBLISHED,
                    'postDate' => new \DateTime()
                )
            )),
            'upcomingCount' => $pageManager->getAllCount(array(
                'q.status = :status AND q.postDate > :postDate',
                array(
                    'status' => Page::PUBLISHED,
                    'postDate' => new \DateTime()
                )
            )),
            'drafts' => $pageManager->getAll(
                0,
                5,
                array(
                    'q.status = :status',
                    array('status' => Page::DRAFT)
                ),
                'q.postDate ASC, q.modDate'
            ),
            'upcoming' => $pageManager->getAll(
                0,
                5,
                array(
                    'q.status = :status AND q.postDate > :postDate',
                    array(
                        'status' => Page::PUBLISHED,
                        'postDate' => new \DateTime()
                    )
                ),
                'q.postDate ASC, q.modDate'
            ),
            'posts' => $pageManager->getAll(
                0,
                5,
                array(
                    'q.status = :status AND q.postDate <= :postDate',
                    array(
                        'status' => Page::PUBLISHED,
                        'postDate' => new \DateTime()
                    )
                ),
                'q.postDate ASC, q.modDate'
            )
        );
        return $response->body($app->twig->render('admin__dashboard.html.twig', self::$data));
    }

    /**
     * @Route("/inadmin/settings")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @return mixed
     */
    public static function getAdminSettingsMain($request, $response)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        return $response->body('Show settings page for signed in admin');
    }
}
