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
require_once __DIR__ . '/../autoloader.php';

use Xoopsmodules\tdmdownloads;

//use Xoopsmodules\tdmdownloads\common;

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

//$categoryHandler = new tdmdownloads\CategoryHandler($db);

/** @var XoopsObjectHandler $downloadsHandler */
//$sbarticlesHandler = xoops_getModuleHandler('sbarticles', $moduleDirName);
//$downloadsHandler = new tdmdownloads\DownloadsHandler($db);

/** @var XoopsObjectHandler $downloadsbrokenHandler */
//$sbvotedataHandler = xoops_getModuleHandler('sbvotedata', $moduleDirName);
//$downloadsbrokenHandler = new tdmdownloads\DownloadsbrokenHandler($db);

/** @var XoopsObjectHandler $testHandler */
//$testHandler = xoops_getModuleHandler('test', $moduleDirName);
//$testHandler = new tdmdownloads\TestHandler($db);

$helper                       = tdmdownloads\Helper::getInstance();
$utility                      = new tdmdownloads\Utility();
$utilities                    = new tdmdownloads\Utilities();
$categoryHandler              = new tdmdownloads\CategoryHandler($db);
$downloadsHandler             = new tdmdownloads\DownloadsHandler($db);
$downloadsvotedataHandler     = new tdmdownloads\RatingHandler($db);
$downloadsfieldHandler        = new tdmdownloads\FieldHandler($db);
$downloadsfielddataHandler    = new tdmdownloads\FielddataHandler($db);
$downloadsbrokenHandler       = new tdmdownloads\BrokenHandler($db);
$downloadsmodHandler          = new tdmdownloads\ModifiedHandler($db);
$downloadsfieldmoddataHandler = new tdmdownloads\ModifiedfielddataHandler($db);

$debug = false;
