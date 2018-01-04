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

//require_once __DIR__ . '/../autoloader.php';

use XoopsModules\Tdmdownloads;

//use XoopsModules\Tdmdownloads\Common;

/*
if (!defined('XXXXXX_MODULE_PATH')) {
    define('XXXXXX_DIRNAME', basename(dirname(__DIR__)));
    define('XXXXXX_URL', XOOPS_URL . '/modules/' . XXXXXX_DIRNAME);
    define('XXXXXX_IMAGE_URL', XXXXXX_URL . '/assets/images/');
    define('XXXXXX_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . XXXXXX_DIRNAME);
    define('XXXXXX_IMAGE_PATH', XXXXXX_ROOT_PATH . '/assets/images');
    define('XXXXXX_ADMIN_URL', XXXXXX_URL . '/admin/');
    define('XXXXXX_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . XXXXXX_DIRNAME);
    define('XXXXXX_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . XXXXXX_DIRNAME);
}
xoops_loadLanguage('common', XXXXXX_DIRNAME);
*/

//require_once XXXXXX_ROOT_PATH . '/include/functions.php';
//require_once XXXXXX_ROOT_PATH . '/include/constants.php';
//require_once XXXXXX_ROOT_PATH . '/include/seo_functions.php';
//require_once XXXXXX_ROOT_PATH . '/class/metagen.php';
//require_once XXXXXX_ROOT_PATH . '/class/session.php';
//require_once XXXXXX_ROOT_PATH . '/class/xoalbum.php';
//require_once XXXXXX_ROOT_PATH . '/class/request.php';

$db = \XoopsDatabaseFactory::getDatabase();

/** @var XoopsObjectHandler $categoryHandler */
//$sbcolumnsHandler = xoops_getModuleHandler('sbcolumns', $moduleDirName);

//$categoryHandler = new Tdmdownloads\CategoryHandler($db);

/** @var XoopsObjectHandler $downloadsHandler */
//$sbarticlesHandler = xoops_getModuleHandler('sbarticles', $moduleDirName);
//$downloadsHandler = new Tdmdownloads\DownloadsHandler($db);

/** @var XoopsObjectHandler $brokenHandler */
//$sbvotedataHandler = xoops_getModuleHandler('sbvotedata', $moduleDirName);
//$brokenHandler = new Tdmdownloads\DownloadsbrokenHandler($db);

/** @var XoopsObjectHandler $testHandler */
//$testHandler = xoops_getModuleHandler('test', $moduleDirName);
//$testHandler = new Tdmdownloads\TestHandler($db);

$helper                   = Tdmdownloads\Helper::getInstance();
$utility                  = new Tdmdownloads\Utility();
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
