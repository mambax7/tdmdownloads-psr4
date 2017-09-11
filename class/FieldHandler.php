<?php namespace Xoopsmodules\tdmdownloads;

/**
 * TDMDownload
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   Gregory Mage (Aka Mage)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class FieldHandler
 */
class FieldHandler extends \XoopsPersistableObjectHandler
{
    //    private $field;
    //    private $db;
    /**
     * @param null|mixed $db
     */
    //    public function __construct(\XoopsDatabase $db = null)
    //    {
    //        $this->db = $db;
    //        $this->field = new Field();
    //        parent::__construct($db, "tdmdownloads_field", 'Xoopsmodules\tdmdownloads\Field', 'fid', 'title');
    //    }

    /**
     * @param null|mixed $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'tdmdownloads_field', 'Xoopsmodules\tdmdownloads\Field', 'fid', 'title');
    }
}
