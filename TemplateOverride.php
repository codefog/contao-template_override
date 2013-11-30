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
 * Class TemplateOverride
 *
 * Provide methods to override default templates.
 */
class TemplateOverride
{

	/**
	 * Update the DCA palettes
	 * @param DataContainer
	 */
	public function updatePalettes(DataContainer $dc)
	{
		switch ($dc->table)
		{
			case 'tl_module':
				$strField = 'module_template';
				break;

			case 'tl_content':
				$strField = 'ce_template';
				break;

			default:
				return;
		}

		// Add the template field to all palettes
		foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $name => $palette)
		{
			// Skip non-string palettes
			if (!is_string($palette))
			{
				continue;
			}

			if (stripos($palette, 'template_legend') !== false)
			{
				$GLOBALS['TL_DCA'][$dc->table]['palettes'][$name] = preg_replace('#(\{template_legend(:hide)?\}[^;]*)(;)?#i', '$1,'.$strField.'$3', $palette);
			}
			else
			{
				$GLOBALS['TL_DCA'][$dc->table]['palettes'][$name] .= ';{template_legend:hide},' . $strField;
			}
		}
	}


	/**
	 * Override the default template
	 * @param object
	 */
	public function parseTemplate($objTemplate)
	{
		if ($objTemplate->module_template != '')
		{
			$objTemplate->setName($objTemplate->module_template);
		}

		if ($objTemplate->ce_template != '')
		{
			$objTemplate->setName($objTemplate->ce_template);
		}
	}
}
