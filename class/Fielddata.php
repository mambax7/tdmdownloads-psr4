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
class Fielddata extends \XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('iddata', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('fid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('data', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }
}