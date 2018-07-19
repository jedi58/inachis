<?php

namespace App\Controller;

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
