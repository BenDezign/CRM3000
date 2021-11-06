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



}