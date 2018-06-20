<?php

namespace App\Controller;

use App\Entity\Url;
use App\Parser\MarkdownFileParser;
use App\Utils\UrlNormaliser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImportController extends AbstractInachisController
{
    /**
     * @Route("/incc/import", methods={"GET"})
     */
    public function index()
    {
        // @todo change text if handheld device
        return $this->render('inadmin/import__main.html.twig', $this->data);
    }

    /**
     * @Route("/incc/import", methods={"POST", "PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function process(Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        foreach ($request->files as $file) {
            if ($file->getError() != UPLOAD_ERR_OK) {
                return $this->json('error', 400);
            }
            $parser = new MarkdownFileParser();
            $post = $parser->parse($this->getDoctrine()->getManager(), file_get_contents($file->getRealPath()));
            if ($post->getTitle() !== '' && $post->getContent() !== '') {
                $post->setAuthor($this->get('security.token_storage')->getToken()->getUser());
                new Url(
                    $post,
                    $post->getPostDateAsLink() . '/' .
                    UrlNormaliser::toUri(
                        $post->getTitle() .
                        ($post->getSubTitle() !== '' ? ' ' . $post->getSubTitle() : '')
                    )
                );
                $this->getDoctrine()->getManager()->persist($post);
                $this->getDoctrine()->getManager()->flush();
            } else {
                return $this->json('error', 400);
            }
        }

        return $this->json('success', 200);
    }
}
