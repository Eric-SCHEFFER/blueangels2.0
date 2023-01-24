<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class AutoInternalLinkTwigExtension extends AbstractExtension
{
    public function __construct(
        UrlGeneratorInterface $urlGeneratorInterface
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
        $baseUrlSite = $this->urlGeneratorInterface->generate('home', [], urlGeneratorInterface::ABSOLUTE_URL);

        // TODO: reg
        // $pattern = "/http[s]?:\/\/[a-zA-Z0-9.@:_%()\-\/?#=&]+/";
        $pattern = "/\*\*baseUrlSite\*\*\/([a-zA-Z0-9().:_\/-]+)/";

        $replacement = "<span class=\"bouton bt-small internal-liens-full-page-element\">
            <a href=\"$baseUrlSite$1\" title=\"$1\">
                $1
            </a>
        </span>";

        $string = preg_replace($pattern, $replacement, $string);

        return $string;
    }
}
