<?php

namespace TwigTools;

use Twig_Environment;
use Twig_Markup;

class FormBuilder {

    private $baseDir = 'FormBuilder';
    private $_twig;
    private $_model;

    /**
     * Initialize FormBuilder object
     *
     * @param Twig_Environment $twig The environment to use to render templates
     */
    public function __construct(Twig_Environment $twig) {
        $this->_twig = $twig;
    }

    /**
     * Set model for a group of inputs
     *
     * @param object $model The model to pass to subtemplates
     * @return              Return empty string for rendering (hack for templating)
     */
    public function model($model)
    {
        $this->_model = $model;
        return '';
    }

    /**
     * Render checkbox
     *
     * @param array $hash The values passed from the template
     * @return            The rendered form section
     */
    public function checkbox(array $hash)
    {
        return $this->_render('checkbox.html', $hash);
    }

    /**
     * Render text area
     *
     * @param array $hash The values passed from the template
     * @return            The rendered form section
     */
    public function textarea(array $hash)
    {
        return $this->_render('textarea.html', $hash);
    }

    /**
     * Render text input 
     *
     * @param array $hash The values passed from the template
     * @return            The rendered form section
     */
    public function textarea(array $hash)
    {
        return $this->_render('textinput.html', $hash);
    }

    /**
     * Render display data
     *
     * @param array $hash The values passed from the template
     * @return            The rendered form section
     */
    public function data(array $hash)
    {
        return $this->_render('data.html', $hash);
    }

    /**
     * Render Twig Markup for use in templates
     *
     * @param string $template Twig template reference
     * @param array  $hash     The values passed from the template
     * @return                 The rendered form section
     */
    private function _render($template, array $hash) {
        if(isset($this->_model) && !isset($hash['model'])) {
            $hash['model'] = $this->_model;
        }
        return new Twig_Markup($this->_twig->render($this->baseDir.'/'.$template, $hash), 'UTF-8');
    }
}
