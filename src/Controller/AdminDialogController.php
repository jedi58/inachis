<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Page;
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

    /**
     * @Route("/incc/ax/export/get", methods={"POST"})
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//        $this->data['form'] = $this->createForm(ImageType::class)->createView();
//        $this->data['allowedTypes'] = Image::ALLOWED_TYPES;
//        $this->data['images'] = $this->entityManager->getRepository(Image::class)->getAll(0, 250);
//        $this->data['image_count'] = sizeof($this->data['images']);
        return $this->render('inadmin/dialog/export.html.twig', $this->data);
    }

    /**
     * @Route("/incc/ax/export/output", methods={"POST"})
     * @param Request $request
     * @param Serializer\ $serializer
     * @return mixed
     */
    public function performExport(Request $request, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $posts = [];
        if (empty($request->request->get('postId'))) {
            return new Response(null, Response::HTTP_EXPECTATION_FAILED);
        }
        $posts = $this->entityManager->getRepository(Page::class)->getFilteredIds(
            $request->request->get('postId')
        )->getIterator()->getArrayCopy();
        if (empty($posts)) {
            return new Response(null, Response::HTTP_EXPECTATION_FAILED);
        }

        $normalisedAttributes = [
            'title',
            'subTitle',
            'postDate',
            'content',
        ];
        if (!empty($request->request->get('export_categories'))) {
            $normalisedAttributes[] = 'categories';
        }
        $posts = $serializer->normalize(
            $posts,
            null,
            [
                AbstractNormalizer::ATTRIBUTES => $normalisedAttributes,
            ]
        );

        $format = $request->request->get('export_format');
        $response = new Response();
        switch($format) {
            case 'json':
                $posts = json_encode($posts);
                $response->headers->set('Content-Type', 'application/json');
                break;

            case 'xml':
                $encoder = new XmlEncoder();
                $posts = $encoder->encode($posts, '');
                $response->headers->set('Content-Type', 'text/xml');
                break;

            case 'md':
            default:
                $format = 'md';
                $markdownPosts = [];
                // @todo use https://packagist.org/packages/maennchen/zipstream-php to zip contents
                // export_zip
                foreach ($posts as $post) {
                    $markdownPosts[] = ArrayToMarkdown::parse($post);
                }
        }
        $response->setContent($posts);

        $filename = date('YmdHis');
        if (!empty($request->request->get('export_name'))) {
            $filename = $request->request->get('export_name');
        }
        $filename .= '.' . $format;

        $response->headers->set(
            'Content-Disposition',
            $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename
            )
        );

        return $response;
    }
}
