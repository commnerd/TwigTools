<?php

namespace BESTGRANT\Utils;

use System\Components\Model;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;
use Twig_Environment;

/**
 * Wrapper class for Twig
 */
class TemplateSystem extends Twig_Environment
{

	private $_variableInjections = array();
	private $_basePageTemplate   = "layouts/page.html";
	private $_overrideTemplate;
	private $_format             = "html";


	/**
	 * Constructor for app's twig environment
	 * 
	 * @param Twig_Loader_Filesystem $loader  The loader to pass to the parent class
	 * @param array                  $options The options to pass to the parent class
	 * @return  void
	 */
	public function __construct(Twig_Loader_Filesystem $loader, $options)
	{
		parent::__construct($loader, $options);

		$this->_addFilters();

		$this->_addFunctions();

		if(isset($_GET['format'])) {
			$this->setFormat($_GET['format']);
		}
		if(isset($_GET['template'])) {
			$this->setBasePageTemplate($_GET['template']);
		}
	}

	/**
	 * Format to return the data
	 * 
	 * @param string $format "html", "php", "json"
	 * @return  void
	 */
	public function setFormat($format)
	{
		if(isset($_GET['format'])) {
			$format = $_GET['format'];
		}
		$this->_format = $format;
	}

	/**
	 * Base page template
	 * 
	 * @param string $basePageTemplate "page.html", "BlankPage.html"
	 * @return  void
	 */
	public function setBasePageTemplate($basePageTemplate)
	{
		if(isset($_GET['template'])) {
			$basePageTemplate = $_GET['template'];
		}
		if (!empty($basePageTemplate)) {
			$this->_basePageTemplate = $basePageTemplate;
		}
	}

	/**
	 * Override rendering template
	 * 
	 * @param string $templateName
	 * @return  void
	 */
	public function setOverrideTemplate($templateName)
	{
		$this->_overrideTemplate = $templateName;
	}

	/**
	 * Unset Override rendering template
	 * 
	 * @return  void
	 */
	public function unsetOverrideTemplate()
	{
		unset($this->_overrideTemplate);
	}

	/**
	 * Render different types of output
	 * 
	 * @param  string $template The template to bind the data
	 * @param  array  $bindings The data to bind
	 * @return string           The data
	 */
	public function render($template, $bindings = array())
	{
		$bindings = $this->_arrayify($bindings);
		$bindings['basePageTemplate'] = empty($this->_overrideTemplate) ? $this->_basePageTemplate : $this->_overrideTemplate;
                $bindings['formBuilder'] = new FormBuilder($this);
		$bindings = array_merge($bindings, $this->_variableInjections);
		switch($this->_format) {
			case 'bindings':
				return $bindings;
			case 'json':
				header('Content-Type', 'application/json');
				return json_encode($bindings);
			case 'debug':
			case 'print_r':
				return "<pre>".print_r($bindings, true)."</pre>";
			case 'var_dump':
				return "<pre>".var_dump($bindings)."</pre>";
			case 'template':
				return "<pre>".file_get_contents(getcwd().'/../src/views/'.$template)."</pre>";
			default:
				return parent::render($template, $bindings);
		}
	}

	/**
	 * Set a variable to be injected into bindings before rendering
	 * @param  string $name  Variable label for template renderer
	 * @param  mixed  $value The variable to be utilized by the template
	 * @return void
	 */
	public function injectVariable($name, $value)
	{
		$this->_variableInjections[$name] = $value;
	}

	/**
	 * Add filters to the apps templating system
	 */
	private function _addFilters()
	{
		$this->addFilter(new Twig_SimpleFilter('phone', function ($num) {
        	return ($num)?'('.substr($num,0,3).') '.substr($num,3,3).'-'.substr($num,6,4):'&nbsp;';
		}));
	}

	/**
	 * Add functions to the app's templating system
	 */
	private function _addFunctions()
	{
		$functions = array(
			array(
				'method' => 'route',
				'function' => function($name, $params = array()) {
		        	GLOBAL $router;
		            $route = $router->generate($name, $params);
					return $route;
		        },
			),
			array(
				'method' => 'checkBlankDisplay',
				'function' => function($valuePrimary, $default) {
		        	if(empty($valuePrimary)) {
		        		return $default;
		        	}
		        	return $valuePrimary;
		        },
			),
			array(
				'method' => 'statusIcons',
				'function' => function($value) {
					GLOBAL $twig;
		        	if($value) {
		        		return '<div class="center-content">'.$twig->render('complete-image.html').'</div>';
		        	}
		        	return '<div class="center-content">'.$twig->render('incomplete-image.html').'</div>';
		        },
			),
			array(
				'method' => 'checkSectionStatus',
				'function' => function($inputString) {
					if($inputString > 0) {
						return true;
					}
					return false;
				},
			),
			array(
				'method' => 'base_path',
				'function' => function() {
					return dirname($_SERVER['PHP_SELF']);
				},
			),
			array(
				'method' => 'url',
				'function' => function($relativePath) {
					return dirname($_SERVER['PHP_SELF']).DIRECTORY_SEPARATOR.$relativePath;
				},
			),
		);

		foreach($functions as $def) {
			$twigFunction = new \Twig_SimpleFunction($def['method'], $def['function']);
			$this->addFunction($twigFunction);
		}
	}

	/**
	 * Arrayify values for twig template
	 * 
	 * @param  array $bindings Bindings to be passed to twig
	 * @return array           Arrayified bindings
	 */
	private function _arrayify($bindings) {
		if(is_array($bindings)) {
			foreach($bindings as $index => $binding) {
				$bindings[$index] = $this->_arrayify($binding);
			}
			return $bindings;
		}
		if($bindings instanceof Model) {
			return $bindings->toArray();
		}
		return $bindings;
	}
}

