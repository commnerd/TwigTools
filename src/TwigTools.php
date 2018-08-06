<?php

namespace TwigTools;

use Twig_Loader_Filesystem;
use Twig_SimpleFilter;
use Twig_Environment;

class TwigTools {
    
    private $_twig;

    /**
     * Construct TwigTools library
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->_twig = $twig;
    }

    /**
     * Initialize library
     */
    public function init()
    {
        $this->_twig->addGlobal('formBuilder', new FormBuilder($this->_twig));
    }
}
