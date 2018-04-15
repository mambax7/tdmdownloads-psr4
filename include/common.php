<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Tdmdownloads;
include  dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper   = strtoupper($moduleDirName); //$capsDirName


/** @var \XoopsDatabase $db */
/** @var Tdmdownloads\Helper $helper */
/** @var Tdmdownloads\Utility $utility */
$db      = \XoopsDatabaseFactory::getDatabaseConnection();
$helper  = Tdmdownloads\Helper::getInstance();
$utility = new Tdmdownloads\Utility();
//$configurator = new Tdmdownloads\Common\Configurator();

$helper->loadLanguage('common');

//handlers
//$categoryHandler     = new xxxxx\CategoryHandler($db);
//$downloadHandler     = new xxxxx\DownloadHandler($db);

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    define($moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__)));
    define($moduleDirNameUpper . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_URL', XOOPS_URL . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_IMAGE_URL', constant($moduleDirNameUpper . '_URL') . '/assets/images/');
    define($moduleDirNameUpper . '_IMAGE_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/assets/images');
    define($moduleDirNameUpper . '_ADMIN_URL', constant($moduleDirNameUpper . '_URL') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN', constant($moduleDirNameUpper . '_URL') . '/admin/index.php');
    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}


/** @var \XoopsObjectHandler $categoryHandler */
//$sbcolumnsHandler = xoops_getModuleHandler('sbcolumns', $moduleDirName);

//$categoryHandler = new Tdmdownloads\CategoryHandler($db);

/** @var \XoopsObjectHandler $downloadsHandler */
//$sbarticlesHandler = xoops_getModuleHandler('sbarticles', $moduleDirName);
//$downloadsHandler = new Tdmdownloads\DownloadsHandler($db);

/** @var \XoopsObjectHandler $brokenHandler */
//$sbvotedataHandler = xoops_getModuleHandler('sbvotedata', $moduleDirName);
//$brokenHandler = new Tdmdownloads\DownloadsbrokenHandler($db);

/** @var \XoopsObjectHandler $testHandler */
//$testHandler = xoops_getModuleHandler('test', $moduleDirName);
//$testHandler = new Tdmdownloads\TestHandler($db);

//$helper                   = Tdmdownloads\Helper::getInstance();
//$utility                  = new Tdmdownloads\Utility();
$utilities                = new Tdmdownloads\Utilities();
$brokenHandler            = new Tdmdownloads\BrokenHandler($db);
$categoryHandler          = new Tdmdownloads\CategoryHandler($db);
$downlimitHandler         = new Tdmdownloads\DownlimitHandler($db);
$downloadsHandler         = new Tdmdownloads\DownloadsHandler($db);
$fielddataHandler         = new Tdmdownloads\FielddataHandler($db);
$fieldHandler             = new Tdmdownloads\FieldHandler($db);
$modifiedfielddataHandler = new Tdmdownloads\ModifiedfielddataHandler($db);
$modifiedHandler          = new Tdmdownloads\ModifiedHandler($db);
$ratingHandler            = new Tdmdownloads\RatingHandler($db);

$debug = false;

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

$moduleDirName = basename(dirname(__DIR__));
$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
