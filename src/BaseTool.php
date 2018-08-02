<?php

namespace TwigTools;

use Twig_Loader_Filesystem;
use Twig_SimpleFilter;
use Twig_Environment;

class TwigTools {
    
    protected $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function init()
    {
        $this->addGlobal('formBuilder', new FormBuilder());
    }

    public function addGlobal($toolName, $object) {
        $this->twig->addGlobal($toolName, $object);
    }
}
