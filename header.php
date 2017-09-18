<?php
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

//require_once __DIR__ . '/include/setup.php';
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$moduleDirName = basename(__DIR__);

require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar("dirname") . '/include/functions.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName  . '/include/functions.php';

//permission
$gpermHandler = xoops_getHandler('groupperm');
$groups       = XOOPS_GROUP_ANONYMOUS;
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
}
xoops_loadLanguage('admin', $moduleDirName);

$perm_submit      = $gpermHandler->checkRight('tdmdownloads_ac', 4, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_modif       = $gpermHandler->checkRight('tdmdownloads_ac', 8, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_vote        = $gpermHandler->checkRight('tdmdownloads_ac', 16, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_upload      = $gpermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_autoapprove = $gpermHandler->checkRight('tdmdownloads_ac', 64, $groups, $xoopsModule->getVar('mid')) ? true : false;

//parameters
// for category images
$uploaddir = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/cats/';
// for downloads
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/' . $moduleDirName . '/downloads/';
// for logos
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/shots/';
// for extra fields images:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/field/';
