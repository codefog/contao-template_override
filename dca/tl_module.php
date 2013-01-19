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
 * @copyright  Kamil Kuzminski 2012 
 * @author     Kamil Kuzminski <kamil.kuzminski@gmail.com>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    TemplateOverride 
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

?>