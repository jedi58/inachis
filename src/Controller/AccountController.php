<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AccountController.
 */
class AccountController extends AbstractInachisController
{
    /**
     * @Route("/incc/login", name="app_account_login", methods={"GET","POST"})
     *
     * @param Request             $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response The response the controller results in
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) : Response
    {
        $redirectTo = $this->redirectIfAuthenticatedOrNoAdmins();
        if (!empty($redirectTo)) {
            return $this->redirectToRoute($redirectTo);
        }
        $form = $this->createForm(LoginType::class, [
            'loginUsername' => $authenticationUtils->getLastUsername(),
        ]);
        $form->handleRequest($request);
        $this->data['page']['title'] = 'Sign In';
        $this->data['form'] = $form->createView();
        $this->data['error'] = $authenticationUtils->getLastAuthenticationError();

        return $this->render('inadmin/signin.html.twig', $this->data);
    }

    /**
     * @Route("/incc/signout", name="app_logout", methods={"GET"})
     *
     * @throws \Exception
     */
    public function logout(): never
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/incc/forgot-password", methods={"GET","POST"})
     *
     * @param Request             $request
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function forgotPassword(Request $request, TranslatorInterface $translator)
    {
        $redirectTo = $this->redirectIfAuthenticatedOrNoAdmins();
        if (!empty($redirectTo)) {
            return $this->redirectToRoute($redirectTo);
        }

        $this->data['page']['title'] = 'Request a password reset';
        $form = $this->createFormBuilder([
            'forgot_email' => $request->get('forgot_email'),
        ])
//                ->addComponent(new FieldsetType(array(
//                    'legend' => 'Enter your Email address / Username'
//                )))
            ->add('forgot_email', TextType::class, [
                'attr' => [
                    'aria-labelledby' => 'form-login__username-label',
                    'aria-required'   => 'true',
                    'autofocus'       => 'true',
                    'class'           => 'text',
                    'id'              => 'form-forgot__email',
                    'placeholder'     => $translator->trans('admin.email_example'),
                ],
                'label'      => $translator->trans('admin.reset.enter_email_address'),
                'label_attr' => [
                    'id' => 'forgot__email-label',
                ],
            ])
            ->add('resetPassword', SubmitType::class, [
                'label' => $translator->trans('admin.reset_password'),
                'attr'  => [
                    'class' => 'button button--positive',
                ],
            ])
            ->getForm();
        $this->data['form'] = $form->createView();

        return $this->render('inadmin/forgot-password.html.twig', $this->data);
    }

    /**
     * @Route("/incc/forgot-password", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPasswordSent(Request $request)
    {
        $redirectTo = $this->redirectIfAuthenticatedOrNoAdmins();
        if (!empty($redirectTo)) {
            return $this->redirectToRoute($redirectTo);
        }

//        if (false) { // @todo if request contains errors then use
//            return $response->redirect('/incc/forgot-password')->send();
//        }
        return $this->render('inadmin/forgot-password-sent.html.twig');
    }

    public function register(UserPasswordHasherInterface $passwordHasher): Response
    {
        // ... e.g. get the user data from a registration form
//        $user = new User(...);
//        $plaintextPassword = ...;
//
//        // hash the password (based on the security.yaml config for the $user class)
//        $hashedPassword = $passwordHasher->hashPassword(
//            $user,
//            $plaintextPassword
//        );
//        $user->setPassword($hashedPassword);

        // ...
    }
}
