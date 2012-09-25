<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');

class ask_questionModelask_question extends JModelLegacy
{
    public $_id = null;

    public $_table_prefix = null;

    function __construct()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';
        $this->setId((int)JRequest::getInt('pid', 0));
    }

    function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Method to store the records
     *
     * @access public
     * @return boolean
     */
    function store($data)
    {
        $user                  = JFactory::getUser();
        $data['user_id']       = $user->id;
        $data['user_name']     = $data['your_name'];
        $data['user_email']    = $data['your_email'];
        $data['question']      = $data['your_question'];
        $data['product_id']    = $data['pid'];
        $data['published']     = 1;
        $data['question_date'] = time();

        $row              = & $this->getTable('question_detail');
        $data['ordering'] = $this->MaxOrdering();

        if (!$row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    /**
     * Method to get max ordering
     *
     * @access public
     * @return boolean
     */
    function MaxOrdering()
    {
        $query = "SELECT (MAX(ordering)+1) FROM " . $this->_table_prefix . "customer_question " . "WHERE parent_id=0 ";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function sendMailForAskQuestion($data)
    {
        $this->store($data);
        $producthelper = new producthelper();
        $redshopMail   = new redshopMail();

        $url        = JURI::base();
        $option     = JRequest::getVar('option');
        $Itemid     = JRequest::getVar('Itemid');
        $mailbcc    = NULL;
        $fromname   = $data['your_name'];
        $from       = $data['your_email'];
        $subject    = ""; //JText::_('COM_REDSHOP_ASK_QUESTION_ABOUT_PRODUCT' );
        $message    = $data['your_question'];
        $product_id = $data['pid'];

        $mailbody = $redshopMail->getMailtemplate(0, "ask_question_mail");

        $data_add = $message;
        if (count($mailbody) > 0)
        {
            $data_add = $mailbody[0]->mail_body;
            $subject  = $mailbody[0]->mail_subject;
            if (trim($mailbody[0]->mail_bcc) != "")
            {
                $mailbcc = explode(",", $mailbody[0]->mail_bcc);
            }
        }
        $product = $producthelper->getProductById($product_id);

        $data_add = str_replace("{product_name}", $product->product_name, $data_add);
        $data_add = str_replace("{product_desc}", $product->product_desc, $data_add);

        $link        = JRoute::_($url . "index.php?option=" . $option . "&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
        $product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
        $data_add    = str_replace("{product_link}", $product_url, $data_add);
        $data_add    = str_replace("{user_question}", $message, $data_add);
        $data_add    = str_replace("{answer}", "", $data_add);
        $subject     = str_replace("{user_question}", $message, $subject);
        $subject     = str_replace("{shopname}", SHOP_NAME, $subject);
        $data_add    = str_replace("{user_address}", $data['address'], $data_add);
        $data_add    = str_replace("{user_telephone}", $data['telephone'], $data_add);
        if (ADMINISTRATOR_EMAIL != "")
        {
            $sendto = explode(",", ADMINISTRATOR_EMAIL);
            if (JFactory::getMailer()->sendMail($from, $fromname, $sendto, $subject, $data_add, $mode = 1, NULL, $mailbcc))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}

