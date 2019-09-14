<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    /**
     * @Route("/resource", name="resource")
     */
    public function index()
    {
        return $this->render('resource/index.html.twig', [
            'controller_name' => 'ResourceController',
        ]);
    }

    /**
     * @Route("/incc/resource/image/upload", methods={"POST", "PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request) : JsonResponse
    {
        dump($request);
    }

    /**
     * @Route("/incc/resource/image/save", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function saveImage(Request $request) : JsonResponse
    {
        $image = null;
//        if (!empty($request->files))
//        {
//
//        }

        if (!empty($request->get('image'))) {
            $image = new Image();
            $form = $this->createForm(ImageType::class, $image);
            $form->handleRequest($request);

            $imageInfo = getimagesize($image->getFilename());
            $image->setDimensionX($imageInfo[0]);
            $image->setDimensionY($imageInfo[1]);
            $image->setFiletype($imageInfo['mime']);
            $image->setChecksum(sha1_file($image->getFilename()));
            unset($imageInfo);

            $this->getDoctrine()->getManager()->persist($image);
            $this->getDoctrine()->getManager()->flush();
        }

//        foreach ($request->files as $file) {
//            if ($file->getError() != UPLOAD_ERR_OK) {
//                return $this->json('error', 400);
//            }
//            $post = $parser->parse($this->getDoctrine()->getManager(), file_get_contents($file->getRealPath()));

        return $this->json([
            'result' => 'success',
            'image' => [
                'id' => $image->getId(),
                'filename' => $image->getFilename(),
                'altText' => $image->getAltText(),
                'title' => $image->getTitle(),
            ]
        ], 200);
    }
}
