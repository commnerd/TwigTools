<?php

namespace TwigTools;

use Twig_Environment;
use Twig_Markup;

class FormBuilder {

    private $baseDir = 'FormBuilder';
    private $_twig;
    private $_model;
    private $_radioName;

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
     * @param string|array $slugLabel A string used as both the label and slug or the $hash (described below)
     * @param array        $hash      The values passed for the template, optional if $slugLabel is an array
     * @return                        The rendered form section
     */
    public function checkbox($slugLabel, array $hash = array())
    {
        $hash = $this->_manageLabelHashCombo($slugLabel, $hash);

        return $this->_render('checkbox.html', $hash);
    }

    /**
     * Set the name for the radio buttons that follow
     *
     * @param string $name The name assigned to the following radio buttons
     */
    public function radioName($name) {
        $this->_radioName = $name;
    }

    /**
     * Render a radio button
     *
     * @param string|array $slugLabel A string used as both the label and slug or the $hash (described below)
     * @param array        $hash      The values passed for the template, optional if $slugLabel is an array
     * @return                        The rendered form section
     */
    public function radio($slugLabel, array $hash = array())
    {
        $hash = $this->_manageLabelHashCombo($slugLabel, $hash);

        $hash['name'] = $this->_radioName;

        return $this->_render('radio.html', $hash);
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
    public function textinput(array $hash)
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
     * Produce hash from lable/hash setup
     *
     * @param string|array $slugLabel A string used as both the label and slug or the $hash (described below)
     * @param array        $hash      The values passed for the template, optional if $slugLabel is an array
     * @return                        The hash to use for rendering
     */
    private function _manageLabelHashCombo($slugLabel, array $hash) {
        if(is_array($slugLabel)) {
            return array_merge($slugLabel, $hash);
        }
        if(is_string($slugLabel)) {
            return array_merge(array($slugLabel => $slugLabel), $hash);
        }
        return $hash;
    }

    /**
     * Render Twig Markup for use in templates
     *
     * @param string $template Twig template reference
     * @param array  $hash     The values passed from the template
     * @return                 The rendered form section
     */
    private function _render($template, array $hash) {
        if(!isset($hash['slug']) && !isset($hash['label'])) {
            $hash['slug']  = key($hash);
            $hash['label'] = $hash[$hash['slug']];
        }
        if(isset($this->_model) && !isset($hash['model'])) {
            $hash['model'] = $this->_model;
        }
        return new Twig_Markup($this->_twig->render($this->baseDir.'/'.$template, $hash), 'UTF-8');
    }
}
