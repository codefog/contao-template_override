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
 * Extend all tl_module palettes
 */
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array('TemplateOverride', 'updatePalettes');


/**
 * Add a field to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['module_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['module_template'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_template', 'getModuleTemplates'),
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50')
);


/**
 * Class tl_module_template
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_module_template extends Backend
{

	/**
	 * Return all module templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getModuleTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		$arrTemplates = array();

		// Return only templates with prefixes specified in configuration
		if (is_array($GLOBALS['TEMPLATE_OVERRIDE']['MOD']) && !empty($GLOBALS['TEMPLATE_OVERRIDE']['MOD']))
		{
			foreach ($GLOBALS['TEMPLATE_OVERRIDE']['MOD'] as $strPrefix)
			{
				$arrTemplates = array_merge($arrTemplates, $this->getTemplateGroup($strPrefix, $intPid));
			}
		}

		return array_unique($arrTemplates);
	}
}
