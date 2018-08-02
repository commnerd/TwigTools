<?php

namespace TwigTools;

use Twig_Environment;

class FormBuilder {

    private $_twig;

    public function __construct(Twig_Environment $twig) {
        $this->_twig = $twig;
    }

    public function textarea(array $hash): string
    {
        return "Hello World";
    }
}
