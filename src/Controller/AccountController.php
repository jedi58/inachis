<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Token;

class AccountController extends AbstractInachisController
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }
    /**
     * @Route("/incc/login", name="app_account_login", methods={"GET"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response The response the controller results in
     */
    public function login(Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(LoginType::class, []);
        $form->handleRequest($request);

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
        $this->data['page']['title'] = 'Sign In';


//        if ($form->isSubmitted() && $form->isValid()) {
//            $submittedCredentials = $form->getData();
//        }

        $this->data['form'] = $form->createView();
        return $this->render('inadmin/signin.html.twig', $this->data);
    }

    /**
     * @Route("/incc/login", name="app_account_login_action", methods={"POST"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response The response the controller results in
     */
    public function loginAction(Request $request, TranslatorInterface $translator)
    {
        $this->addFlash('error', 'Something went wrong I think');
        $username = $request->request->get('login')['loginUsername'];
        $password = $request->request->get('login')['loginPassword'];
        if (empty($username) || empty($password)) {
            return new Response(
                'Username or password not provided',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }
        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->findUserByUsername($username);
        if(!$user){
            return new Response(
                'Username doesnt exists',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $salt = $user->getSalt();
        if(!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return new Response(
                'Username or Password not valid.',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        // If the firewall name is not main, then the set value would be instead:
        $this->get('session')->set('_security_main', serialize($token));

        // Fire the login event manually
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        /*
         * Now the user is authenticated !!!!
         * Do what you need to do now, like render a view, redirect to route etc.
         */
        return new Response(
            'Welcome '. $user->getUsername(),
            Response::HTTP_OK,
            array('Content-type' => 'application/json')
        );
    }

    /**
     * @Route("/incc/signout", methods={"POST"})
     */
//    public function signout()
//    {
//        Application::getInstance()->requireAuthenticationService()->logout();
//        return $this->redirectToRoute('app_account_signin');
        //return new Response('', 401);
//    }

    /**
     * @Route("/incc/forgot-password", methods={"GET"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function forgotPassword(Request $request, TranslatorInterface $translator)
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return;
//        }
        $this->data['page']['title'] = 'Reset your password';
        $form = $this->createFormBuilder([], [
            'attr' => [
                'autocomplete' => 'false',
                'class' => 'form form__login form__forgot',
            ]
        ])
//                ->addComponent(new FieldsetType(array(
//                    'legend' => 'Enter your Email address / Username'
//                )))
            ->add('forgot_email', TextType::class,[
                'attr' => [
                    'aria-labelledby' => 'form-login__username-label',
                    'aria-required' => 'true',
                    'autofocus' => 'true',
                    'class' => 'text',
                    'id' => 'form-forgot__email',
                    'placeholder' => $translator->trans('admin.email_example'),
                ],
                'label' => $translator->trans('admin.reset.enter_email_address'),
                'label_attr' => [
                    'id' => 'forgot__email-label',
                ],
            ])
            ->add('resetPassword', SubmitType::class, [
                'label' => $translator->trans('admin.reset_password'),
                'attr' => [
                    'class' => 'button button--positive'
                ]
            ])
            ->add('cancel', ButtonType::class, [
                'label' => $translator->trans('admin.button.cancel'),
                'attr' => [
                    'class' => 'button button--negative'
                ]
            ])
            ->getForm();
//            'data' => array(
//                "resetEmailAddress" => $request->paramsPost()->get('resetEmailAddress')
//            ),
//            'error' =>array(
//                //validate email address format
//            )
//        );
        $this->data['form'] = $form->createView();
        return $this->render('inadmin/forgot-password.html.twig', $this->data);
    }

    /**
     * @Route("/incc/forgot-password", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function forgotPasswordSent(Request $request)
    {
//        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
//            return $response->redirect('/incc')->send();
//        }
//        if (false) { // @todo if request contains errors then use
//            return $response->redirect('/incc/forgot-password')->send();
//        }
//        if ($response->isLocked()) {
//            return;
//        }
        return $this->render('inadmin/forgot-password-sent.html.twig', array());
    }
}
