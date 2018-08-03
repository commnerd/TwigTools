<?php

namespace TwigTools;

use Twig_Environment;
use Twig_Markup;

class FormBuilder {

    private $_twig;

    public function __construct(Twig_Environment $twig) {
        $this->_twig = $twig;
    }

    public function textarea(array $hash)
    {
        return new Twig_Markup($this->_twig->render('FormBuilder/textarea.html', $hash), 'UTF-8');
    }
}
