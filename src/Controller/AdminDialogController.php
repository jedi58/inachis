<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Page;
use App\Entity\Tag;
use App\Form\ImageType;
use App\Parser\ArrayToMarkdown;
use App\Repository\PageRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AdminDialogController extends AbstractInachisController
{
    /**
     * @Route("/incc/ax/categoryManager/get", methods={"POST"})
     *
     * @return mixed
     */
    public function getCategoryManagerContent()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->data['categories'] = $this->entityManager->getRepository(Category::class)->findByParent(null);

        return $this->render('inadmin/dialog/categoryManager.html.twig', $this->data);
    }

    /**
     * @Route("/incc/ax/imageManager/get", methods={"POST"})
     * @return mixed
     */
    public function getImageManagerList()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->data['form'] = $this->createForm(ImageType::class)->createView();
        $this->data['allowedTypes'] = Image::ALLOWED_TYPES;
        // @todo add pagination
        $this->data['images'] = $this->entityManager->getRepository(Image::class)->getAll();
        $this->data['image_count'] = sizeof($this->data['images']);
        return $this->render('inadmin/dialog/imageManager.html.twig', $this->data);
    }

    /**
     * @Route("incc/ax/categoryList/get", methods={"POST"})
     *
     * @param Request         $request
     * @param LoggerInterface $logger
     *
     * @return string
     */
    public function getCategoryManagerListContent(Request $request, LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categories = empty($request->get('q')) ?
            $this->entityManager->getRepository(Category::class)->findByParent(null) :
            $this->entityManager->getRepository(Category::class)->findByTitleLike($request->request->get('q'));
        $result = [];
        // Below code is used to handle where categories exist with the same name under multiple locations
        if (!empty($categories)) {
            $result['items'] = [];
            foreach ($categories as $category) {
                $title = $category->getTitle();
                if (isset($result['items'][$title])) {
                    $result['items'][$result['items'][$title]->path] = $result['items'][$title];
                    $result['items'][$result['items'][$title]->path]->text = $result['items'][$title]->path;
                    unset($result['items'][$title]);
                    $title = $category->getFullPath();
                }
                $result['items'][$title] = (object) [
                    'id'   => $category->getId(),
                    'text' => $title,
                    'path' => $category->getFullPath(),
                ];
            }
            $result = array_values($result['items']);
        }

        return new JsonResponse(
            [
                'items'      => $result,
                'totalCount' => count($result),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("incc/ax/categoryManager/save", methods={"POST"})
     *
     * @param Request $request
     *
     * @return string
     */
    public function saveCategoryManagerContent(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $category = $this->entityManager->getRepository(Category::class)->create($request->request->all());
        $category->setParent(
            $this->entityManager->getRepository(Category::class)->findOneById($request->request->get('parentID'))
        );
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_OK);
    }
}
