<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class AutoInternalLinkTwigExtension extends AbstractExtension
{
    private $baseUrlSite;

    public function __construct(
        UrlGeneratorInterface $urlGeneratorInterface,
    ) {
        $this->urlGeneratorInterface = $urlGeneratorInterface;
    }

    public function getFilters()
    {
        return [new TwigFilter('auto_internal_link', [$this, 'autoInternalLink'], [
            'pre_escape' => 'html',
            'is_safe' => ['html']
        ])];
    }

    public function autoInternalLink($string)
    {
        $pattern = "/\*\*baseUrlSite\*\*([a-zA-Z0-9().:_\/-]+)/";

        // $replacement = "<span class=\"bouton bt-small internal-liens-full-page-element\">
        //     <a href=\"$baseUrlSite$1\" title=\"$1\">
        //         $1
        //     </a>
        // </span>";

        // $string = preg_replace($pattern, $replacement, $string);

        $this->baseUrlSite = $this->urlGeneratorInterface->generate('home', [], urlGeneratorInterface::ABSOLUTE_URL);
        $this->baseUrlSite = substr_replace($this->baseUrlSite, "", -1, 1);
        $string = preg_replace_callback($pattern, array($this, 'replace'), $string);
        return $string;
    }

    private function replace($match)
    {
        $url = $this->baseUrlSite . $match[1];
        $voir = "ici";
        if ($match[1] === "/") {
            $voir = "accueil";
        }
        $lienCustom =
            "<span >
                <a class=\"bouton bt-small internal-liens-full-page-element\" href=\"$url\" title=\"$url\">
                    $voir
                </a>
            </span>";
        return $lienCustom;
    }
}
