<?php

/**
 * template_override extension for Contao Open Source CMS
 * 
 * Copyright (C) 2012 Codefog
 * 
 * @package template_override
 * @link    http://codefog.pl
 * @author  Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 * @license LGPL
 */


/**
 * Extension version
 */
@define('TEMPLATE_OVERRIDE_VERSION', '1.1');
@define('TEMPLATE_OVERRIDE_BUILD', '0');


/**
 * Register a hook to override front end templates
 */
if (TL_MODE == 'FE')
{
	$GLOBALS['TL_HOOKS']['parseTemplate'][] = array('TemplateOverride', 'parseTemplate');
}


/**
 * Extension configuration
 */
$GLOBALS['TEMPLATE_OVERRIDE'] = array
(
	'MOD' => array('mod_'),
	'CTE' => array('ce_')
);
