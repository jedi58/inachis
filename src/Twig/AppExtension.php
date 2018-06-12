<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('activeMenu', [$this, 'activeMenuFilter']),
        ];
    }

    public function activeMenuFilter($menuOption, $selectedOption = '')
    {
        return !empty($menuOption) && $menuOption == $selectedOption ? 'menu__active' : '';
    }
}
