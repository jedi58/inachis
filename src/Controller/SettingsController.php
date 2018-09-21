<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Page;
use App\Entity\Series;
use App\Entity\Tag;
use App\Entity\Url;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingsController extends AbstractInachisController
{
    /**
     * @Route("/incc/settings")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->data['storage']['percent'] = 75; //@todo calculate hdd space from allowance - usage
        $this->data['counts']['page'] = $this->getDoctrine()->getManager()->getRepository(Page::class)->getAllCount();
        $this->data['counts']['series'] = $this->getDoctrine()->getManager()->getRepository(Series::class)->getAllCount();
        $this->data['counts']['tag'] = $this->getDoctrine()->getManager()->getRepository(Tag::class)->getAllCount();
        $this->data['counts']['url'] = $this->getDoctrine()->getManager()->getRepository(Url::class)->getAllCount();
        return $this->render('inadmin/settings.html.twig', $this->data);
    }

    /**
     * @Route("/incc/settings/check")
     */
    public function check()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->data['check'] = [
            'cache_writable' => '',
            'php' => [
                'required_version' => '7.0.0',
                'current_version' => phpversion(),

                'accelerator' => $this->getOpCacheStatus(),
                'magic_quotes_disabled' => !get_magic_quotes_gpc(),
                'short_tags_disabled' => ini_get('short_open_tag'),
            ],
            'timezone' => [
                'current' => date_default_timezone_get(),
                'valid' => in_array(date_default_timezone_get(), timezone_identifiers_list()),
            ],
            'extensions' => [
                'iconv' => extension_loaded('iconv'),
                'intl' => extension_loaded('intl'),
            ],
        ];
        $this->data['check']['php']['version_ok'] = version_compare(
            $this->data['check']['php']['current_version'],
            $this->data['check']['php']['required_version'],
            '>'
        );
        return $this->render('inadmin/settings__check.html.twig', $this->data);
    }

    /**
     * @Route("/incc/settings/wipe", methods={"POST"})
     */
    public function wipe(LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->get('confirm')) {
            $this->getDoctrine()->getRepository(Image::class)->wipe($logger);
            $this->getDoctrine()->getRepository(Page::class)->wipe($logger);
            $this->getDoctrine()->getRepository(Series::class)->wipe($logger);
            $this->getDoctrine()->getRepository(Tag::class)->wipe($logger);
            $this->getDoctrine()->getRepository(Url::class)->wipe($logger);
        }
        return $this->redirectToRoute('app_settings_index');
    }

    /**
     * @return bool
     */
    private function getOpCacheStatus()
    {
        if (function_exists('opcache_get_status') && opcache_get_status()) {
            return 'PHP OpCache';
        }
        if (extension_loaded('apc') && ini_get('apc.enabled')) {
            return 'APC';
        }
        return '';
    }
}
