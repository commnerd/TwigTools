<?php

namespace TwigTools;

use Twig_Environment;
use Twig_Markup;

class FormBuilder {

    /**
     * Twig reference
     */
    private $_twig;

    /**
     * Load the library
     */
    public function __construct(Twig_Environment $twig) {
        $this->_twig = $twig;
    }

    /**
     * Text area definition
     */
    public function textarea(array $hash)
    {
        return $this->_render('textarea.html', $hash);
    }

    /**
     * Text input definition
     */
    public function textinput(array $hash)
    {
        return $this->_render('textinput.html', $hash);
    }

    /**
     * Render logic for returning a Twig_Markup object (so escaping doesn't get executied)
     */
    private function _render($template, array $hash)
    {
        return new Twig_Markup($this->_twig->render("FormBuilder/$template", $hash), 'UTF-8');
    }
}
