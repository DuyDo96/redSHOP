<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class addressfields_listingViewaddressfields_listing extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_FIELDS'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ADDRESS_FIELD_MANAGEMENT'), 'redshop_fields48');

		$uri =& JFactory::getURI();

		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'field_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$fields = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$section_id = $mainframe->getUserStateFromRequest($context . 'section_id', 'section_id', 0);

		$sectionlist = array(
			JHTML::_('select.option', '7', JText::_('COM_REDSHOP_CUSTOMER_ADDRESS')),
			JHTML::_('select.option', '8', JText::_('COM_REDSHOP_COMPANY_ADDRESS')),
			JHTML::_('select.option', '14', JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS')),
			JHTML::_('select.option', '15', JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS'))
		);

		$option = array();
		$option[0]->value = "0";
		$option[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (count($sectionlist) > 0)
		{
			$option = @array_merge($option, $sectionlist);
		}

		$lists['addresssections'] = JHTML::_('select.genericlist', $option, 'section_id',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'value',
			'text',
			$section_id
		);

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('fields', $fields);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
