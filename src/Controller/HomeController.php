<?php

namespace App\Controller;

use App\Framework\Response;

class HomeController
{
    public function index()
    {
        $template = '<h1 style=" padding: 100px 0;text-align: center;">{{data}}</h1>';
        $message = 'there will be dragons...';

        $content = str_replace('{{data}}', $message, $template);

        return new Response($content);
    }
}
