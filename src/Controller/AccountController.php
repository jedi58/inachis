<?php

namespace Inachis\CoreBundle\Controller;

use Inachis\CoreBundle\Controller\AbstractInachisController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractInachisController
{
    /**
     * @Route("/inadmin/signin", methods={"GET", "POST"})
     * @return Response
     */
    public function signin()
    {
//        self::redirectIfAuthenticated($response);
//        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() === 0) {
//            return $response->redirect('/setup')->send();
//        }
//        // Check if user has cookies to indicate persistent sign-in
//        if (Application::getInstance()->getService('auth')->getSessionPersist($request->server()->get('HTTP_USER_AGENT'))) {
//            return self::redirectToReferrerOrDashboard($response);
//        }
//        if ($response->isLocked()) {
//            return null;
//        }
//        // Handle sign-in
//        if ($request->method('post') && !empty($request->paramsPost()->get('loginUsername'))
//            && !empty($request->paramsPost()->get('loginPassword'))) {
//            if (Application::getInstance()->getService('auth')->login(
//                $request->paramsPost()->get('loginUsername'),
//                $request->paramsPost()->get('loginPassword')
//            )) {
//                if (!empty($request->paramsPost()->get('rememberMe')) && (bool) $request->paramsPost()->get('rememberMe')) {
//                    Application::getInstance()->getService('auth')->setSessionPersist(
//                        $request->server()->get('HTTP_USER_AGENT'),
//                        $request->server()->get('HTTP_HOST')
//                    );
//                }
//                return $response->redirect('/inadmin/')->send();
//            }
//            self::$errors['username'] = 'Authentication Failed.';
//        }
//        self::adminInit($request, $response);
//        self::$data['page'] = array('title' => 'Sign In');
//        self::$data['form'] = (new FormBuilder(array(
//            'action' => '/inadmin/signin',
//            'autocomplete' => false,
//            'cssClasses' => 'form form__login'
//        )))
//            ->addComponent(new FieldsetType(array(
//                'legend' => 'Sign in using email address'
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'loginUsername',
//                'autofocus' => true,
//                'label' => 'Username',
//                'id' => 'form-login__username',
//                'labelId' => 'form-login__username-label',
//                'cssClasses' => 'text',
//                'placeholder' => 'Username',
//                'ariaAttributes' => array(
//                    'required' => true,
//                    'labelledby' => 'form-login__username-label'
//                )
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'loginPassword',
//                'type' => 'password',
//                'label' => 'Password',
//                'id' => 'form-login__password',
//                'labelId' => 'form-login__password-label',
//                'cssClasses' => 'text',
//                'placeholder' => 'Password',
//                'ariaAttributes' => array(
//                    'required' => true,
//                    'labelledby' => 'form-login__password-label'
//                )
//            )))
//            ->addComponent(new ChoiceType(array(
//                'name' => 'rememberMe',
//                'label' => 'Keep me logged in',
//                'cssClasses' => 'checkbox',
//                'value' => '1'
//            )))
//            ->addComponent(new ButtonType(array(
//                'type' => 'submit',
//                'cssClasses' => 'button button--positive',
//                'label' => 'Log In'
//            )));
//        self::$data['data'] = array(
//            'loginUsername' => $request->paramsPost()->get('loginUsername'),
//            'rememberMe' => !empty($request->paramsPost()->get('rememberMe')) ?
//                $request->paramsPost()->get('rememberMe') :
//                $request->cookies()->get('rememberMe')
//        );
//        //self::$data['errors'] = self::$errors;
        return $this->render('inadmin/signin.html.twig', self::$data);
    }

    /**
     * @Route("/inadmin/signout", methods={"POST"})
     */
    public function signout()
    {
//        Application::getInstance()->requireAuthenticationService()->logout();
        return $this->redirectToRoute('app_account_signin', [], 401);
    }

    /**
     * @Route("/inadmin/forgot-password", methods={"GET"})
     */
    public function forgotPassword()
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return;
//        }
//        $data = array(
//            'form' => (new FormBuilder(array(
//                'action' => '/inadmin/forgot-password',
//                'autocomplete' => false,
//                'cssClasses' => 'form form__login form__forgot'
//            )))
//                ->addComponent(new FieldsetType(array(
//                    'legend' => 'Enter your Email address / Username'
//                )))
//                ->addComponent(new TextType(array(
//                    'name' => 'forgot_email',
//                    'cssClasses' => 'text',
//                    'label' => 'Enter your email address to reset login',
//                    'placeholder' => 'user@example.com',
//                    'ariaAttributes' => array( 'required' => true ),
//                )))
//                //->addComponent(new ReCaptchaType(array()))
//                ->addComponent(new ButtonType(array(
//                    'type' => 'submit',
//                    'cssClasses' => 'button button--positive',
//                    'label' => 'Reset password'
//                )))
//                ->addComponent(new ButtonType(array(
//                    'type' => 'button',
//                    'cssClasses' => 'button button--negative',
//                    'label' => 'Cancel'
//                ))),
//            'data' => array(
//                "resetEmailAddress" => $request->paramsPost()->get('resetEmailAddress')
//            ),
//            'error' =>array(
//                //validate email address format
//            )
//        );
        return $this->render('inadmin/forgot-password.html.twig', self::$data);
    }

    /**
     * @Route("/inadmin/forgot-password", methods={"POST"})
     */
    public function forgotPasswordSent()
    {
//        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
//            $response->redirect('/inadmin/')->send();
//        }
//        if (false) { // @todo if request contains errors then use
//            return self::getForgotPassword($request, $response, $service, $app);
//        }
//        if ($response->isLocked()) {
//            return;
//        }
        return $this->render('inadmin/forgot-password-sent.html.twig', array());
    }


}
