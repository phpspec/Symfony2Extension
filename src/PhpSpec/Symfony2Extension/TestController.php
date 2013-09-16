<?php

namespace PhpSpec\Symfony2Extension;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function flush()
    {
        $this->get('doctrine')->getManager()->flush();
    }

    public function find()
    {
        return $this->get('doctrine')->getRepository('Stuff')->findAll();
    }
}
