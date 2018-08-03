<?php

namespace TwigTools;

use Twig_Loader_Filesystem;
use Twig_SimpleFilter;
use Twig_Environment;

class TwigTools {
    
    private $_twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->_twig = $twig;
    }

    public function init()
    {
        $this->_twig->addGlobal('formBuilder', new FormBuilder($this->_twig));
    }
}
