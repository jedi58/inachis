<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractInachisController
{
    /**
     * @Route("/incc/user-management", methods={"GET", "POST"})
     * @param Request $request
     * @return null
     * @throws \Exception
     */
    public function adminList(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $offset = (int) $request->get('offset', 0);
        $limit = $entityManager->getRepository(User::class)->getMaxItemsToShow();
        $this->data['dataset'] = $entityManager->getRepository(User::class)->getAll(
            $offset,
            $limit
        );
        $this->data['form'] = $form->createView();
        $this->data['page']['offset'] = $offset;
        $this->data['page']['limit'] = $limit;
        $this->data['page']['title'] = 'Users';

        return $this->render('inadmin/user__list.html.twig', $this->data);
    }

    /**
     * @Route("/incc/user/{id}", methods={"GET", "POST"})
     * @param Request $request
     * @param string $id
     * @return null
     * @throws \Exception
     */
    public function adminDetails(Request $request, string $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $request->get('id')]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $this->data['user'] = $user;
        $this->data['form'] = $form->createView();
        $this->data['page']['title'] = 'Profile';

        return $this->render('inadmin/profile.html.twig', $this->data);
    }
}
