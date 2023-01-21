<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AutoLinkTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [new TwigFilter('auto_link', [$this, 'autoLink'], [
            'pre_escape' => 'html',
            'is_safe' => ['html']
        ])];
    }
    // Génère un lien html quand on passe une url
    public function autoLink($string)
    {
        // Pattern de départ avant modif (ajout double point et underscore qui n'était pas compris comme inclus dans un url)
        // $pattern = "/http[s]?:\/\/[a-zA-Z0-9.\-\/?#=&]+/";
        // $replacement de départ
        // $replacement = "<a href=\"$0\" target=\"_blank\">$0</a>";

        $pattern = "/http[s]?:\/\/[a-zA-Z0-9.@:_%()\-\/?#=&]+/";
        $replacement = "<span class=\"liens-full-page-element\">
            <a href=\"$0\" title=\"$0\" target=\"_blank\">
                $0
            </a>
        </span>";
        $string = preg_replace($pattern, $replacement, $string);
        
        // 2e méthode avec preg_replace_callback.
        // $string = preg_replace_callback($pattern, array($this, 'fonction'), $string);
        
        return "$string";
    }

    // // 2e méthode avec preg_replace_callback. On peut acceder au "match" de la reg, pour éventuellement y ajouter des filtres.
    // private function fonction($url)
    // {
    //     $breakUrl = substr($url[0], 0, 78);
    //     return
    //         "<a href=" . $url[0] . " target=\"_blank\">" . $breakUrl . "...</a>";
    // }
}
