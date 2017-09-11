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
 * @copyright   Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class TDMDownloadsDownlimitHandler
 */
class DownlimitHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|\XoopsDatabase|XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'tdmdownloads_downlimit', 'Xoopsmodules\tdmdownloads\Downlimit', 'downlimit_id', 'downlimit_lid');
    }
}