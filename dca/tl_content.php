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
 * Extend all tl_content palettes
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('TemplateOverride', 'updatePalettes');


/**
 * Add a field to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['ce_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ce_template'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_template', 'getContentTemplates'),
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);


/**
 * Class tl_content_template
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_content_template extends Backend
{

	/**
	 * Return all content element templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getContentTemplates(DataContainer $dc)
	{
		$intTheme = 0;
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		$objPage = $this->Database->prepare("SELECT id FROM tl_page WHERE id=(SELECT pid FROM tl_article WHERE id=?)")
								  ->limit(1)
								  ->execute($intPid);

		// Get the current theme
		if ($objPage->numRows)
		{
			$objPage = $this->getPageDetails($objPage->id);

			if ($objPage->layout)
			{
				$objLayout = $this->Database->prepare("SELECT pid FROM tl_layout WHERE id=?")
											->limit(1)
											->execute($objPage->layout);

				// Set the current theme ID
				if ($objLayout->numRows)
				{
					$intTheme = $objLayout->pid;
				}
			}
		}

		$arrTemplates = array();

		// Return only templates with prefixes specified in configuration
		if (is_array($GLOBALS['TEMPLATE_OVERRIDE']['CTE']) && !empty($GLOBALS['TEMPLATE_OVERRIDE']['CTE']))
		{
			foreach ($GLOBALS['TEMPLATE_OVERRIDE']['CTE'] as $strPrefix)
			{
				$arrTemplates = array_merge($arrTemplates, $this->getTemplateGroup($strPrefix, $intTheme));
			}
		}

		return array_unique($arrTemplates);
	}
}
