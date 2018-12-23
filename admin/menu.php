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
// // defined('XOOPS_ROOT_PATH') || die('Restricted access');

use XoopsModules\Tdmdownloads;

//require_once  dirname(__DIR__) . '/include/common.php';
/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}


$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => 'assets/images/admin/home.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => 'assets/images/admin/category.png',
    //'menu' =>  'assets/images/admin/menu_category.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU3,
    'link'  => 'admin/downloads.php',
    'icon'  => 'assets/images/admin/downloads.png',
    //'menu' =>  'assets/images/admin/menu_downloads.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU4,
    'link'  => 'admin/broken.php',
    'icon'  => 'assets/images/admin/broken.png',
    //'menu' =>  'assets/images/admin/menu_broken.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU5,
    'link'  => 'admin/modified.php',
    'icon'  => 'assets/images/admin/modified.png',
    //'menu' =>  'assets/images/admin/menu_modified.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU6,
    'link'  => 'admin/field.php',
    'icon'  => 'assets/images/admin/field.png',
    //'menu' =>  'assets/images/admin/menu_field.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU7,
    'link'  => 'admin/import.php',
    'icon'  => 'assets/images/admin/import.png',
    //'menu' =>  'assets/images/admin/menu_import.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU8,
    'link'  => 'admin/permissions.php',
    'icon'  => 'assets/images/admin/permissions.png',
    //'menu' =>  'assets/images/admin/menu_permissions.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU9,
    'link'  => 'admin/about.php',
    'icon'  => 'assets/images/admin/about.png',
    //'menu' =>  'assets/images/admin/menu_about.png',
];
