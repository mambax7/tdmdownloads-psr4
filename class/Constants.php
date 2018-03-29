<?php namespace XoopsModules\Tdmdownloads;

/*
     * Module: TDMDownloads
     *
     * You may not change or alter any portion of this comment or credits
     * of supporting developers from this source code or any supporting source code
     * which is considered copyrighted (c) material of the original comment or credit authors.
     *
     * PHP version 5
     *
     * @category        Module
     * @package         tdmdownloads
     * @author          zyspec <owners@zyspec.com>
     * @author          XOOPS Development Team
     * @copyright       20014 XOOPS Project
     * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
     * @link            https://xoops.org/
     * @since           1.63
     */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

//require_once __DIR__ . '/xoopsmoduleconstants.php';
//require_once dirname(__DIR__) . '/xoopsmoduleconstants.php';

/**
 * class Tdmdownloads
 */
//class Tdmdownloads extends XoopsModuleConstants
class Constants
{
    public static $moduleDirName;
    public static $mydirname2; //set in constructor

    /**
     *
     */
    public function __construct()
    {
        self::$mydirname2 = basename(dirname(__DIR__));
    }

    /**
     * @return null|string
     */
    public static function mydirname()
    {
        if (null === self::$moduleDirName) {
            self::$moduleDirName = basename(dirname(__DIR__));
        }

        return self::$moduleDirName;
    }

    public static function myFunction()
    {
        echo 'this is a xoops class/function';
    }

    public static function myFunction2()
    {
        echo 'this is a XOOPS class/function2';
    }
}
