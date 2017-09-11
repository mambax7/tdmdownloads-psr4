<?php namespace Xoopsmodules\tdmdownloads\common;

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

//require_once __DIR__ . '/xoopsmoduleconstants.php';

/**
 * class Tdmdownloads
 */
//class Tdmdownloads extends XoopsModuleConstants
class Constants
{
    public static $moduleDirName = null;
    public static $mydirname2    = null; //set in constructor

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
