<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetupController extends AbstractInachisController
{
    /**
     * @Route("/setup", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function stage1(Request $request)
    {
//        self::redirectIfAuthenticated($response);
//        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() > 0) {
//            return $response->redirect('/inadmin/signin')->send();
//        }
//        if ($request->method('post') && !empty($request->paramsPost()->get('username')) && !empty($request->paramsPost()->get('password'))) {
//            if (Application::getInstance()->getService('auth')->create(
//                $request->paramsPost()->get('username'),
//                $request->paramsPost()->get('password'),
//                array(
//                    'displayName' => $request->paramsPost()->get('name'),
//                    'email' => $request->paramsPost()->get('email')
//                )
//            )) {
//                return $response->redirect('/inadmin/signin')->send();
//            }
//        }
//        if ($response->isLocked()) {
//            return null;
//        }
//        self::adminInit($request, $response);
        $this->data['page']['title'] = 'Inachis Install - Step 1';
        $form = $this->createFormBuilder([], [
            'attr' => [
                'autocomplete' => 'false',
                'class' => 'form form__login form__setup'
            ]
        ])
// Setup your web application
            ->add('siteName', TextType::class, [
                'attr' => [
                    'placeholder' => 'e.g. My awesome site'
                ],
                'label' => 'Site name',
                'required' => true,
            ])
            ->add('siteUrl', UrlType::class, [
                'data' => 'https://' . $request->getHttpHost(),
                'label' => 'URL',
                'required' => true,
            ])
//fieldset - Administrator
            ->add('username', TextType::class, [
                'data' => 'admin',
                'label' => 'Username',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'e.g. Jane Doe',
                ],
                'label' => 'Name',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'e.g. somebody@example.com',
                ],
                'label' => 'Email Address',
                'required' => true,
            ])
// Fieldset - Actions
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button--positive'
                ],
                'label' => 'Continue…',
            ])
            ->getForm();

        $this->data['form'] = $form->createView();


//                'value' => 'http' . ($request->isSecure() ? 's' : '') . '://' .
//                    $request->server()->get('HTTP_HOST') .
//                    str_replace('/setup', '', $request->server()->get('REQUEST_URI'))
//            )))
        return $this->render('setup/stage-1.html.twig', $this->data);
    }
}
