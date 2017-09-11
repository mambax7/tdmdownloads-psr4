<?php namespace Xoopsmodules\tdmdownloads;

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
     * @link            http://xoops.org/
     * @since           1.63
     */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Interface TdmdownloadsConstants
 */
class ModuleConstants
{
    /**#@+
     * Constant definition
     */

    //    const MODULE_DIRNAME0 = basename(__DIR__);
    //    static $moddir = basename(dirname(__DIR__));

    public static $moduleDirName;
    public static $mydirname2; //set in constructor

    public static $moduleUrl;
    public static $modulePath;
    public static $imagesUrl;
    public static $imagesPath;
    public static $adminUrl;
    public static $adminPath;
    public static $uploadsUrl;
    public static $uploadsPath;

    //    const ROOT = $_SERVER['DOCUMENT_ROOT']."project/";
    //    const MODULE_DIRNAME = basename(dirname(__DIR__));
    //    const MODULE_URL = XOOPS_URL . '/modules/' . self::MODULE_DIRNAME;
    //    const IMAGES_URL = PUBLISHER_URL . '/assets/images';
    //    const ADMIN_URL = PUBLISHER_URL . '/admin';
    //    const UPLOADS_URL = XOOPS_URL . '/uploads/' . self::MODULE_DIRNAME;
    //    const MODULE_PATH = XOOPS_ROOT_PATH . '/modules/' . self::MODULE_DIRNAME;
    //    const UPLOADS_PATH = XOOPS_ROOT_PATH . '/uploads/' . self::MODULE_DIRNAME;

    //Application Folders (from xHelp module)
    /*
    define('BASE_PATH', XOOPS_ROOT_PATH.'/modules/'. XHELP_DIR_NAME);
    define('CLASS_PATH', XHELP_BASE_PATH.'/class');
    define('BASE_URL', XHELP_SITE_URL .'/modules/'. XHELP_DIR_NAME);
    define('UPLOAD_PATH', XOOPS_ROOT_PATH."/uploads/".XHELP_DIR_NAME);
    define('INCLUDE_PATH', XHELP_BASE_PATH.'/include');
    define('INCLUDE_URL', XHELP_BASE_URL.'/include');
    define('IMAGE_PATH', XHELP_BASE_PATH.'/images');
    define('IMAGE_URL', XHELP_BASE_URL.'/images');
    define('ADMIN_URL', XHELP_BASE_URL.'/admin');
    define('ADMIN_PATH', XHELP_BASE_PATH.'/admin');
    define('PEAR_PATH', XHELP_CLASS_PATH.'/pear');
    define('CACHE_PATH', XOOPS_ROOT_PATH.'/cache');
    define('CACHE_URL', XHELP_SITE_URL .'/cache');
    define('SCRIPT_URL', XHELP_BASE_URL.'/scripts');
    define('JPSPAN_PATH', XHELP_INCLUDE_PATH.'/jpspan');
    define('FAQ_ADAPTER_PATH', XHELP_CLASS_PATH.'/faq');
    define('REPORT_PATH', XHELP_BASE_PATH .'/reports');
    define('REPORT_URL', XHELP_BASE_URL .'/reports');
    define('JPGRAPH_PATH', XHELP_INCLUDE_PATH .'/jpgraph');
    define('JPGRAPH_URL', XHELP_INCLUDE_URL .'/jpgraph');
    define('RPT_RENDERER_PATH', XHELP_CLASS_PATH .'/reportRenderer');
    */

    /**
     *  do not allow
     */
    const DISALLOW = 0;
    /**
     *  allow
     */
    const ALLOW = 1;
    /**
     *  top level Category ID
     */
    const ALLOW_MEMBERS = 2;
    /**
     *  top level Category ID
     */
    const TOP_LEVEL_CID = 0;
    /**
     *  indicates default number of feed items to show
     */
    const DEFAULT_FEED_COUNT = 10;
    /**
     *  maximum number of characters for feed description
     */
    const MAX_FEED_DESC_COUNT = 1000;
    /**
     *  feed image height default
     */
    const FEED_IMG_HEIGHT_DEFAULT = 31;
    /**
     *  feed image height maximum
     */
    const FEED_IMG_HEIGHT_MAX = 400;
    /**
     *  feed image width default
     */
    const FEED_IMG_WIDTH_DEFAULT = 88;
    /**
     *  feed image width maximum
     */
    const FEED_IMG_WIDTH_MAX = 144;
    /**
     *  google magic used for page rank
     */
    const GOOGLE_MAGIC = 0xE6359A60;
    /**
     *  anonymous user's ID
     */
    const ANON_USER_ID = 0;
    /**
     *  default feed type
     */
    const DEFAULT_FEED_TYPE = 'RSS';
    /**
     *  number of subcategories to display
     */
    const SHOW_SUBCAT_COUNT = 5;
    /**
     * allow HTML in WYSIWYG editor
     */
    const ALLOW_HTML = 1;
    /**
     * do not allow HTML in WYSIWYG editor
     */
    const DISALLOW_HTML = 0;
    /**
     * no delay XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_NONE = 0;
    /**
     * short XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_SHORT = 1;
    /**
     * medium XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_MEDIUM = 3;
    /**
     * long XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_LONG = 7;
    /**
     * maximum acceptable rating
     */
    const RATING_MAX = 10;
    /**
     * minimum acceptable rating
     */
    const RATING_MIN = 1;
    /**
     * days between ratings from single IP
     */
    const RATING_WAIT = 1;
    /**
     * sort list by popularity
     */
    const SORT_BY_POPULARITY = 1;
    /**
     * sort list by rating
     */
    const SORT_BY_RATING = 2;
    /**
     * sort list by most recent
     */
    const SORT_BY_MOST_RECENT = 3;
    /**
     * link status - inactive
     */
    const STATUS_INACTIVE = 0;
    /**
     * new link status
     */
    const STATUS_NEW = 1;
    /**
     * modified link status
     */
    const STATUS_UPDATED = 1;

    /**#@-*/

    public function __construct()
    {
        self::$mydirname2 = basename(dirname(__DIR__));
    }

    /**
     * @return string
     */
    public static function mydirname()
    {
        if (null === self::$moduleDirName) {
            self::$moduleDirName = basename(dirname(__DIR__));
        }

        return self::$moduleDirName;
    }
}
