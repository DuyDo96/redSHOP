<?php
/**
 * @package     redSHOP
 * @subpackage  Tables
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

class Tablewrapper_detail extends JTable
{
    var $wrapper_id = 0;

    var $product_id = null;

    var $category_id = null;

    var $wrapper_price = null;

    var $wrapper_name = null;

    var $wrapper_image = null;

    var $wrapper_use_to_all = 0;

    var $published = 1;

    var $createdate = 0;

    public function __construct(& $db)
    {
        $this->_table_prefix = '#__redshop_';
        parent::__construct($this->_table_prefix . 'wrapper', 'wrapper_id', $db);
    }
}
