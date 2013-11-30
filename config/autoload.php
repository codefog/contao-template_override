<?php

/**
 * template_override extension for Contao Open Source CMS
 *
 * Copyright (C) 2013 Codefog
 *
 * @package template_override
 * @link    http://codefog.pl
 * @author  Codefog <http://codefog.pl>
 * @author  Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 * @license LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'TemplateOverride' => 'system/modules/template_override/TemplateOverride.php'
));
