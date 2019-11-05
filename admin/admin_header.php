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

// Include xoops admin header
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

//require_once  dirname(dirname(dirname(__DIR__))) . '/class/xoopsformloader.php';

require dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

$myts = \MyTextSanitizer::getInstance();

if ($xoopsUser) {
    $xoopsModule = \XoopsModule::getByDirname($moduleDirName);
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

// Include language file
xoops_loadLanguage('admin', 'system');
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
$helper->loadLanguage('common');

//paramétres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/' . $moduleDirName . '/downloads/';
// pour les captures d'�cran fichiers
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/field/';
