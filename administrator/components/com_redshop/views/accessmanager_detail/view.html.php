<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');

class accessmanager_detailVIEWaccessmanager_detail extends JViewLegacy
{
    public function display($tpl = null)
    {
        $section       = JRequest::getVar('section');
        $model         = $this->getModel('accessmanager_detail');
        $accessmanager = $model->getaccessmanager();

        /**
         * get groups
         */
        $groups = $this->getGroup();

        /**
         * format groups
         */
        $groups = $this->formatGroup($groups);

        JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER') . ': <small><small>[ ' . $section . ' ]</small></small>', 'redshop_catalogmanagement48');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();

        $this->assignRef('groups', $groups);

        $this->assignRef('accessmanager', $accessmanager);

        parent::display($tpl);
    }

    public function getGroup()
    {

        // Compute usergroups
        $db    = JFactory::getDbo();
        $query = "SELECT a.*,COUNT(DISTINCT c2.id) AS level
  FROM `#__usergroups` AS a  LEFT  OUTER JOIN `#__usergroups` AS c2  ON a.lft > c2.lft  AND a.rgt < c2.rgt  GROUP BY a.id
  ORDER BY a.lft asc";

        $db->setQuery($query);
        // echo $db->getQuery();
        $groups = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum())
        {
            JError::raiseNotice(500, $db->getErrorMsg());
            return null;
        }

        return ($groups);
    }

    public function formatGroup($groups)
    {
        $returnable = array();
        foreach ($groups as $key=> $val)
        {
            $returnable[$val->id] = str_repeat('<span class="gi">|&mdash;</span>', $val->level) . $val->title;
        }
        return $returnable;
    }
}
