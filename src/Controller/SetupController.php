<?php

namespace Inachis\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetupController extends Controller
{
    /**
     * @Route("/setup", methods={"GET", "POST"})
     * @return Response
     */
    public function setup()
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
//        self::$data['page'] = array('title' => 'Inachis Install - Step 1');
//        self::$data['form'] = (new FormBuilder(array(
//            'action' => '/setup',
//            'autocomplete' => false,
//            'cssClasses' => 'form form__login form__setup'
//        )))
//            ->addComponent(new FieldsetType(array(
//                'legend' => 'Setup your web application'
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'siteName',
//                'label' => 'Site name',
//                'placeholder' => 'e.g. My awesome site',
//                'required' => true
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'siteUrl',
//                'label' => 'URL',
//                'required' => true,
//                'type' => 'url',
//                'value' => 'http' . ($request->isSecure() ? 's' : '') . '://' .
//                    $request->server()->get('HTTP_HOST') .
//                    str_replace('/setup', '', $request->server()->get('REQUEST_URI'))
//            )))
//            ->addComponent(new FieldsetType(array(
//                'legend' => 'Administrator'
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'username',
//                'label' => 'Username',
//                'required' => true,
//                'value' => 'admin'
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'password',
//                'label' => 'Password',
//                'required' => true,
//                'type' => 'password'
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'name',
//                'label' => 'Name',
//                'placeholder' => 'e.g. John Smith',
//                'required' => true
//            )))
//            ->addComponent(new TextType(array(
//                'name' => 'email',
//                'label' => 'Email Address',
//                'placeholder' => 'e.g. somebody@example.com',
//                'required' => true,
//                'type' => 'email'
//            )))
//            ->addComponent(new FieldsetType(array(
//                'legend' => 'Actions'
//            )))
//            ->addComponent(new ButtonType(array(
//                'type' => 'submit',
//                'cssClasses' => 'button button--positive',
//                'label' => 'Continue…'
//            )));
        return $this->render('setup/stage-1.html.twig', self::$data);
    }
}
