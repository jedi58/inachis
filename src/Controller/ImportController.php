<?php

namespace App\Controller;

use App\Entity\Url;
use App\Parser\MarkdownFileParser;
use App\Utils\UrlNormaliser;
use Doctrine\ORM\EntityManager;
use PHPUnit\Util\Json;
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
    public function process(Request $request) : JsonResponse
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        foreach ($request->files->get('markdownFiles') as $file) {
            if ($file->getError() != UPLOAD_ERR_OK) {
                return $this->json('error', 400);
            }
            $parser = new MarkdownFileParser();
            $post = $parser->parse($this->getDoctrine()->getManager(), file_get_contents($file->getRealPath()));
            if ($post->getTitle() !== '' && $post->getContent() !== '') {
                $newLink = $post->getPostDateAsLink() . '/' .
                    UrlNormaliser::toUri(
                        $post->getTitle() .
                        ($post->getSubTitle() !== '' ? ' ' . $post->getSubTitle() : '')
                );
                if (!empty(
                    $this->getDoctrine()->getManager()->getRepository(Url::class)->findOneByLink($newLink)
                )) {
                    // @todo should it prompt to rename?
                    return $this->json('error', 409);
                }
                $post->setAuthor($this->get('security.token_storage')->getToken()->getUser());
                new Url(
                    $post,
                    $newLink
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
