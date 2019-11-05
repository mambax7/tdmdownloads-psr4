<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use XoopsModules\Tdmdownloads;

/**
 * Review module for xoops
 *
 * @copyright       {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         tdmdownloads
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */
require_once dirname(__DIR__) . '/class/Autoloader.php';
// true param for auto-registration in spl_autoload_register() function.
$loaderPsr4 = new \XoopsModules\Tdmdownloads\Autoloader(true);

// Register a namespace
//$loaderPsr4->registerNamespace('org\\example\\libraries', './org/example/libraries');
//new org\example\libraries\DatabaseLibrary();
$loaderPsr4->registerNamespace('XoopsModules\\Tdmdownloads', dirname(__DIR__) . '/class/');

// Register a new file that has no namespace
//$loaderPsr4->registerFile('NoNamespaceClass', 'org/NoNamespaceClass.php');
//new NoNamespaceClass();

// Register an entire namespace
//$loaderPsr4->registerNamespace('org', 'orgtests');
// Now we can instantiate any of the test classes in org\... namespace
//new org\example\controllers\HomeControllerTest();
//new org\example\libraries\DatabaseLibraryTest();
$loaderPsr4->registerNamespace('tdmdownloads', dirname(__DIR__) . '/tests');

// We can register more locations or directories for one namespace
//$loaderPsr4->registerNamespace('org', __DIR__.'/org');
// now we can instantiate HomeController
//new org\example\controllers\HomeController();
//new org\example\controllers\HomeController();

// Register a Class that has a diferent filename
//$loaderPsr4->registerFile('SomeClassName', 'index.php');
// Overwrite the last filename for "SomeClassName"
//$loaderPsr4->registerFile('SomeClassName',  __DIR__.'/otherClasses/DiferentFileNameAndClassName.php', true);
//new SomeClassName();

// Register more than one class per filename
//$loaderPsr4->registerFile('AnotherClass',  __DIR__.'/otherClasses/DiferentFileNameAndClassName.php', true);
//$loaderPsr4->registerFile('YetAnotherClass',  __DIR__.'/otherClasses/DiferentFileNameAndClassName.php', true);
// It will find it even if the file has not the same name as the class (you should NOT do this but...)
//new AnotherClass();
//new YetAnotherClass();

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$db = \XoopsDatabaseFactory::getDatabaseConnection();

if (!defined('TDMDOWNLOADS_DIRNAME')) {
    define('TDMDOWNLOADS_DIRNAME', basename(dirname(__DIR__))); //$GLOBALS['xoopsModule']->dirname());
    define('TDMDOWNLOADS_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMDOWNLOADS_DIRNAME);
    define('TDMDOWNLOADS_URL', XOOPS_URL . '/modules/' . TDMDOWNLOADS_DIRNAME);
    define('TDMDOWNLOADS_ADMIN', TDMDOWNLOADS_URL . '/admin/index.php');
    define('TDMDOWNLOADS_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMDOWNLOADS_DIRNAME);
    define('TDMDOWNLOADS_AUTHOR_LOGOIMG', TDMDOWNLOADS_URL . '/assets/images/xoopsproject_logo.png');
}

// Define here the place where main upload path
define('TDMDOWNLOADS_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . TDMDOWNLOADS_DIRNAME); // WITHOUT Trailing slash
define('TDMDOWNLOADS_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . TDMDOWNLOADS_DIRNAME); // WITHOUT Trailing slash

//require_once dirname(__DIR__) . '/class/helper.php';

$helper = Tdmdownloads\Helper::getInstance(false);
//$helper      = & Helper::getInstance();
$utilities   = new \XoopsModules\Tdmdownloads\Utilities($db, $helper);
$mainLang    = '_MD_' . mb_strtoupper($helper->getDirname());
$modinfoLang = '_MI_' . mb_strtoupper($helper->getDirname());
$adminLang   = '_AM_' . mb_strtoupper($helper->getDirname());
//define('MODINFO_LANG', 'MI');
//define('ADMIN_LANG', 'AM');
//define('MAIN_LANG', 'MD');

// dossier dans uploads
$uploadFolders = [
    TDMDOWNLOADS_UPLOAD_PATH,
    //    TDMDOWNLOADS_UPLOAD_PATH . '/',
    TDMDOWNLOADS_UPLOAD_PATH . '/downloads',
    TDMDOWNLOADS_UPLOAD_PATH . '/images',
    TDMDOWNLOADS_UPLOAD_PATH . '/images/cats',
    TDMDOWNLOADS_UPLOAD_PATH . '/images/field',
    TDMDOWNLOADS_UPLOAD_PATH . '/images/shots',
];

//$xoopsTpl->assign('uploadFolders', $uploadFolders);

// module information
$mod_copyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . TDMDOWNLOADS_AUTHOR_LOGOIMG . " alt='XOOPS Project'></a>";

//$categoryHandler              = new \XoopsModules\Tdmdownloads\CategoryHandler($db);
//$downloadsHandler             = new \XoopsModules\Tdmdownloads\DownloadsHandler($db);
//$ratingHandler     = new \XoopsModules\Tdmdownloads\RatingHandler($db);
//$fieldHandler        = new \XoopsModules\Tdmdownloads\FieldHandler($db);
//$fielddataHandler    = new \XoopsModules\Tdmdownloads\FielddataHandler($db);
//$brokenHandler       = new \XoopsModules\Tdmdownloads\BrokenHandler($db);
//$modifiedHandler          = new \XoopsModules\Tdmdownloads\ModifiedHandler($db);
//$modifiedfielddataHandler = new \XoopsModules\Tdmdownloads\ModifiedfielddataHandler($db);
