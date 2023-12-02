<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('activeMenu', [$this, 'activeMenuFilter']),
        ];
    }

    /**
     * @param $menuOption
     * @param string $selectedOption
     * @return string
     */
    public function activeMenuFilter($menuOption, $selectedOption = '')
    {
        return !empty($menuOption) && $menuOption == $selectedOption ? 'menu__active' : '';
    }

    /**
     * @param $bytes
     * @return string
     */
    public function bytesToMinimumUnit($bytes)
    {
        $symbols = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        $exp = (int) floor(log($bytes)/log(1024));

        return sprintf('%.2f '. $symbols[$exp], ($bytes/pow(1024, floor($exp))));
    }
}
