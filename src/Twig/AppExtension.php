<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{


    public function getFunctions()
    {
        return [

            new TwigFunction('getAvatar', [$this, 'getAvatar']),
            new TwigFunction('getEnv', [$this, 'getEnv']),
            new TwigFunction('prettyPrices', [$this, 'prettyPrices']),
            new TwigFunction('infoMsg', [$this, 'infoMsg']),

        ];
    }


    public function getAvatar($nom, $mail)
    {
        if (empty($nom))
            $name_avatar = $mail;
        else
            $name_avatar = $nom;

        $fallback = 'https://ui-avatars.com/api/' . $name_avatar . '/200/7367F0/ffffff/2';
        return $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($mail))) . "?d=" . urlencode($fallback) . "&s=200";

    }

    public function getEnv($key)
    {
        return $_ENV[$key];
    }

    public function prettyPrices($price, $cts = false, $html = true)
    {

        if ($cts === true) {
            $price = $price / 100;
        }

        $float_price = number_format($price, 2, '.', ' ');
        $parse_prix = explode('.', $float_price);
        if ($html === false)
            return $float_price . ' €';
        return '<strong>' . $parse_prix[0] . '</strong><sup>,' . $parse_prix[1] . '</sup> <sup>€</sup>';
    }

    public function infoMsg($msg)
    {
        return '<div class="chip chip-info m-t-3">
            <div class="chip-body">
                <div class="chip-text"><i class="fa fa-info"></i>&nbsp;' . $msg . '</div>
            </div>
        </div>';
    }


}