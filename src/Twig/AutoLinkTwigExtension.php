<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AutoLinkTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [new TwigFilter('auto_link', [$this, 'autoLink'], [
            'pre_escape'=>'html',
            'is_safe' => ['html']])];
    }

    static public function autoLink($string)
    {
        $pattern = "/http[s]?:\/\/[a-zA-Z0-9.\-\/?#=&]+/";
        $replacement = "<a href=\"$0\" target=\"_blank\">$0</a>";
        $string = preg_replace($pattern, $replacement, $string);
        return $string;
    }
}