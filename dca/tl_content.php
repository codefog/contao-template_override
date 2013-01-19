<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Kamil Kuzminski 2012 
 * @author     Kamil Kuzminski <kamil.kuzminski@gmail.com>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    TemplateOverride 
 * @license    LGPL 
 * @filesource
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
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50')
);


/**
 * Class tl_content_template
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Kamil Kuzminski 2012 
 * @author     Kamil Kuzminski <kamil.kuzminski@gmail.com>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    TemplateOverride 
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

?>