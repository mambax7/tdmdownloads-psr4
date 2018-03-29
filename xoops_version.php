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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);
xoops_load('xoopseditorhandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$xoops_url     = parse_url(XOOPS_URL);

$modversion = [
    'name'                => _MI_TDMDOWNLOADS_NAME,
    'version'             => '2.0',
    'module_status'       => 'Alpha 1',
    'release_date'        => '2017/10/10',
    'description'         => _MI_TDMDOWNLOADS_DESC,
    'credits'             => 'G. Mage, Mamba',
    'author'              => 'G. Mage',
    'nickname'            => 'Mage',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'Support site',
    'help'                => 'page=help',
    'license'             => 'GNU GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html',
    'official'            => 0,
    // ------------------- Folders & Files -------------------
    'dirname'             => $moduleDirName,
    'image'               => 'assets/images/logo.png',
    'release_file'        => XOOPS_URL . '/modules/' . $moduleDirName . '/docs/changelog.txt',
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    // ------------------- Min Requirements -------------------
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.8',
    'min_admin'           => '1.1',
    'min_db'              => ['mysql' => '5.0.7', 'mysqli' => '5.0.7'],
    // ------------------- Admin Menu -------------------
    'hasAdmin'            => 1,
    'system_menu'         => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Mysql -------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],

    // ------------------- Tables -------------------
    'tables'              => [
        $moduleDirName . '_broken',
        $moduleDirName . '_cat',
        $moduleDirName . '_downloads',
        $moduleDirName . '_mod',
        $moduleDirName . '_votedata',
        $moduleDirName . '_field',
        $moduleDirName . '_fielddata',
        $moduleDirName . '_modfielddata',
        $moduleDirName . '_downlimit'
    ],
    // ------------------- Blocks -------------------
    'blocks'              => [
        [
            'file'        => 'tdmdownloads_top.php',
            'name'        => _MI_TDMDOWNLOADS_BNAME1,
            'description' => _MI_TDMDOWNLOADS_BNAMEDSC1,
            'show_func'   => 'b_tdmdownloads_top_show',
            'edit_func'   => 'b_tdmdownloads_top_edit',
            'options'     => 'date|10|19|1|1|1|left|90|0',
            'template'    => $moduleDirName . '_block_new.tpl'
        ],
        [
            'file'        => 'tdmdownloads_top.php',
            'name'        => _MI_TDMDOWNLOADS_BNAME2,
            'description' => _MI_TDMDOWNLOADS_BNAMEDSC2,
            'show_func'   => 'b_tdmdownloads_top_show',
            'edit_func'   => 'b_tdmdownloads_top_edit',
            'options'     => 'hits|10|19|1|1|1|left|90|0',
            'template'    => $moduleDirName . '_block_top.tpl'
        ],
        [
            'file'        => 'tdmdownloads_top.php',
            'name'        => _MI_TDMDOWNLOADS_BNAME3,
            'description' => _MI_TDMDOWNLOADS_BNAMEDSC3,
            'show_func'   => 'b_tdmdownloads_top_show',
            'edit_func'   => 'b_tdmdownloads_top_edit',
            'options'     => 'rating|10|19|1|1|1|left|90|0',
            'template'    => $moduleDirName . '_block_rating.tpl'
        ],
        [
            'file'        => 'tdmdownloads_top.php',
            'name'        => _MI_TDMDOWNLOADS_BNAME4,
            'description' => _MI_TDMDOWNLOADS_BNAMEDSC4,
            'show_func'   => 'b_tdmdownloads_top_show',
            'edit_func'   => 'b_tdmdownloads_top_edit',
            'options'     => 'rating|10|19|1|1|1|left|90|0',
            'template'    => $moduleDirName . '_block_random.tpl'
        ],
        [
            'file'        => 'tdmdownloads_search.php',
            'name'        => _MI_TDMDOWNLOADS_BNAME5,
            'description' => _MI_TDMDOWNLOADS_BNAMEDSC5,
            'show_func'   => 'b_tdmdownloads_search_show',
            'edit_func'   => '',
            'options'     => '',
            'template'    => $moduleDirName . '_block_search.tpl'
        ]
    ],
    // ------------------- Menu -------------------
    'hasMain'             => 1,
    'sub'                 => [
        [
            'name' => _MI_TDMDOWNLOADS_SMNAME1,
            'url'  => 'submit.php'
        ],
        [
            'name' => _MI_TDMDOWNLOADS_SMNAME2,
            'url'  => 'search.php'
        ]
    ],
    // ------------------- Search -------------------

    'hasSearch' => 1,
    'search'    => [
        'file' => 'include/search.inc.php',
        'func' => 'tdmdownloads_search'
    ],
    // ------------------- Comments -------------------

    'hasComments' => 1,
    'comments'    => [
        'itemName'     => 'lid',
        'pageName'     => 'singlefile.php',
        'extraParams'  => ['cid'],
        'callbackFile' => 'include/comment_functions.php',
        'callback'     => [
            'approve' => 'tdmdownloads_com_approve',
            'update'  => 'tdmdownloads_com_update',
        ]
    ],
    // ------------------- Templates -------------------

    'templates' => [
        ['file' => $moduleDirName . '_brokenfile.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_download.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_index.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_modfile.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_ratefile.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_singlefile.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_submit.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_viewcat.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_liste.tpl', 'description' => ''],
        ['file' => $moduleDirName . '_rss.tpl', 'description' => '']
    ],
    // ------------------- Preferences -------------------

    'config' => [
        [
            'name'        => 'general_break_line',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_GENERAL',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'popular',
            'title'       => '_MI_TDMDOWNLOADS_POPULAR',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 100
        ],
        [
            'name'        => 'autosummary',
            'title'       => '_MI_TDMDOWNLOADS_AUTO_SUMMARY',
            'description' => 'NCADMINDSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        [
            'name'        => 'showupdated',
            'title'       => '_MI_TDMDOWNLOADS_SHOW_UPDATED',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'useshots',
            'title'       => '_MI_TDMDOWNLOADS_USESHOTS',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'shotwidth',
            'title'       => '_MI_TDMDOWNLOADS_SHOTWIDTH',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 90
        ],
        [
            'name'        => 'img_float',
            'title'       => '_MI_TDMDOWNLOADS_IMGFLOAT',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'text',
            'default'     => 'left',
            'options'     => [_MI_TDMDOWNLOADS_IMGFLOAT_LEFT => 'left', _MI_TDMDOWNLOADS_IMGFLOAT_RIGHT => 'right']
        ],
        [
            'name'        => 'platform',
            'title'       => '_MI_TDMDOWNLOADS_PLATEFORM',
            'description' => '_MI_TDMDOWNLOADS_PLATEFORM_DSC',
            'formtype'    => 'textarea',
            'valuetype'   => 'text',
            'default'     => 'None|XOOPS 2.0.x|XOOPS 2.2.x|XOOPS 2.3.x|XOOPS 2.4.x|XOOPS 2.5.x|XOOPS 2.6.x|Other'
        ],
        [
            'name'        => 'usetellafriend',
            'title'       => '_MI_TDMDOWNLOADS_USETELLAFRIEND',
            'description' => '_MI_TDMDOWNLOADS_USETELLAFRIENDDSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        [
            'name'        => 'usetag',
            'title'       => '_MI_TDMDOWNLOADS_USETAG',
            'description' => '_MI_TDMDOWNLOADS_USETAGDSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        [
            'name'        => 'editor',
            'title'       => '_MI_TDMDOWNLOADS_FORM_OPTIONS',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'text',
            'default'     => 'dhtmltextarea',
            'options'     => array_flip($editorHandler->getList())
        ],
        [
            'name'        => 'break_user',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_USER',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'perpage',
            'title'       => '_MI_TDMDOWNLOADS_PERPAGE',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 10
        ],
        [
            'name'        => 'nb_dowcol',
            'title'       => '_MI_TDMDOWNLOADS_NBDOWCOL',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 1,
            'options'     => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5]
        ],
        [
            'name'        => 'newdownloads',
            'title'       => '_MI_TDMDOWNLOADS_NEWDLS',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 10
        ],
        [
            'name'        => 'toporder',
            'title'       => '_MI_TDMDOWNLOADS_TOPORDER',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 1,
            'options'     => [
                '_MI_TDMDOWNLOADS_TOPORDER1' => 1,
                '_MI_TDMDOWNLOADS_TOPORDER2' => 2,
                '_MI_TDMDOWNLOADS_TOPORDER3' => 3,
                '_MI_TDMDOWNLOADS_TOPORDER4' => 4,
                '_MI_TDMDOWNLOADS_TOPORDER5' => 5,
                '_MI_TDMDOWNLOADS_TOPORDER6' => 6,
                '_MI_TDMDOWNLOADS_TOPORDER7' => 7,
                '_MI_TDMDOWNLOADS_TOPORDER8' => 8
            ]
        ],
        [
            'name'        => 'perpageliste',
            'title'       => '_MI_TDMDOWNLOADS_PERPAGELISTE',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 15
        ],
        [
            'name'        => 'searchorder',
            'title'       => '_MI_TDMDOWNLOADS_SEARCHORDER',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 8,
            'options'     => [
                '_MI_TDMDOWNLOADS_TOPORDER1' => 1,
                '_MI_TDMDOWNLOADS_TOPORDER2' => 2,
                '_MI_TDMDOWNLOADS_TOPORDER3' => 3,
                '_MI_TDMDOWNLOADS_TOPORDER4' => 4,
                '_MI_TDMDOWNLOADS_TOPORDER5' => 5,
                '_MI_TDMDOWNLOADS_TOPORDER6' => 6,
                '_MI_TDMDOWNLOADS_TOPORDER7' => 7,
                '_MI_TDMDOWNLOADS_TOPORDER8' => 8
            ]
        ],
        [
            'name'        => 'nbsouscat',
            'title'       => '_MI_TDMDOWNLOADS_SUBCATPARENT',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 5
        ],
        [
            'name'        => 'nb_catcol',
            'title'       => '_MI_TDMDOWNLOADS_NBCATCOL',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 3,
            'options'     => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5]
        ],
        [
            'name'        => 'bldate',
            'title'       => '_MI_TDMDOWNLOADS_BLDATE',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'blpop',
            'title'       => '_MI_TDMDOWNLOADS_BLPOP',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'blrating',
            'title'       => '_MI_TDMDOWNLOADS_BLRATING',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'nbbl',
            'title'       => '_MI_TDMDOWNLOADS_NBBL',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 5
        ],
        [
            'name'        => 'longbl',
            'title'       => '_MI_TDMDOWNLOADS_LONGBL',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 20
        ],
        [
            'name'        => 'show_bookmark',
            'title'       => '_MI_TDMDOWNLOADS_BOOKMARK',
            'description' => '_MI_TDMDOWNLOADS_BOOKMARK_DSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'show_social',
            'title'       => '_MI_TDMDOWNLOADS_SOCIAL',
            'description' => '_MI_TDMDOWNLOADS_SOCIAL_DSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'download_float',
            'title'       => '_MI_TDMDOWNLOADS_DOWNLOADFLOAT',
            'description' => '_MI_TDMDOWNLOADS_DOWNLOADFLOAT_DSC',
            'formtype'    => 'select',
            'valuetype'   => 'text',
            'default'     => 'ltr',
            'options'     => [
                _MI_TDMDOWNLOADS_DOWNLOADFLOAT_LTR => 'ltr',
                _MI_TDMDOWNLOADS_DOWNLOADFLOAT_RTL => 'rtl'
            ]
        ],
        [
            'name'        => 'show_latest_files',
            'title'       => '_MI_TDMDOWNLOADS_SHOW_LATEST_FILES',
            'description' => '_MI_TDMDOWNLOADS_SHOW_LATEST_FILES_DSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'break_admin',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_ADMIN',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'perpageadmin',
            'title'       => '_MI_TDMDOWNLOADS_PERPAGEADMIN',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 15
        ],
        [
            'name'        => 'break_downloads',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_DOWNLOADS',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'permission_download',
            'title'       => '_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 1,
            'options'     => [
                '_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD1' => 1,
                '_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD2' => 2
            ]
        ],
        [
            'name'        => 'newnamedownload',
            'title'       => '_MI_TDMDOWNLOADS_DOWNLOAD_NAME',
            'description' => '_MI_TDMDOWNLOADS_DOWNLOAD_NAMEDSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 1
        ],
        [
            'name'        => 'prefixdownloads',
            'title'       => '_MI_TDMDOWNLOADS_DOWNLOAD_PREFIX',
            'description' => '_MI_TDMDOWNLOADS_DOWNLOAD_PREFIXDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'text',
            'default'     => 'downloads_'
        ],
        [
            'name'        => 'maxuploadsize',
            'title'       => '_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 1048576
        ],
        [
            'name'        => 'mimetype',
            'title'       => '_MI_TDMDOWNLOADS_MIMETYPE',
            'description' => '_MI_TDMDOWNLOADS_MIMETYPE_DSC',
            'formtype'    => 'textarea',
            'valuetype'   => 'text',
            'default'     => 'image/gif|image/jpeg|image/pjpeg|image/x-png|image/png|application/x-zip-compressed|application/zip|application/rar|application/pdf|
        application/x-gtar|application/x-tar|application/msword|application/vnd.ms-excel|application/vnd.oasis.opendocument.text|
        application/vnd.oasis.opendocument.spreadsheet|application/vnd.oasis.opendocument.presentation|
        application/vnd.oasis.opendocument.graphics|application/vnd.oasis.opendocument.chart|application/vnd.oasis.opendocument.formula|
        application/vnd.oasis.opendocument.database|application/vnd.oasis.opendocument.image|application/vnd.oasis.opendocument.text-master'
        ],
        [
            'name'        => 'check_host',
            'title'       => '_MI_TDMDOWNLOADS_CHECKHOST',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        //$xoops_url = parse_url(XOOPS_URL);
        [
            'name'        => 'referers',
            'title'       => '_MI_TDMDOWNLOADS_REFERERS',
            'description' => '',
            'formtype'    => 'textarea',
            'valuetype'   => 'array',
            'default'     => [$xoops_url['host']]
        ],
        [
            'name'        => 'downlimit',
            'title'       => '_MI_TDMDOWNLOADS_DOWNLIMIT',
            'description' => '_MI_TDMDOWNLOADS_DOWNLIMITDSC',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        [
            'name'        => 'limitglobal',
            'title'       => '_MI_TDMDOWNLOADS_LIMITGLOBAL',
            'description' => '_MI_TDMDOWNLOADS_LIMITGLOBALDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 10
        ],
        [
            'name'        => 'limitlid',
            'title'       => '_MI_TDMDOWNLOADS_LIMITLID',
            'description' => '_MI_TDMDOWNLOADS_LIMITLIDDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 2
        ],
        [
            'name'        => 'break_paypal',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_PAYPAL',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'use_paypal',
            'title'       => '_MI_TDMDOWNLOADS_USEPAYPAL',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ],
        [
            'name'        => 'currency_paypal',
            'title'       => '_MI_TDMDOWNLOADS_CURRENCYPAYPAL',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'text',
            'default'     => 'EUR',
            'options'     => [
                'AUD' => 'AUD',
                'BRL' => 'BRL',
                'CAD' => 'CAD',
                'CHF' => 'CHF',
                'CZK' => 'CZK',
                'DKK' => 'DKK',
                'EUR' => 'EUR',
                'GBP' => 'GBP',
                'HKD' => 'HKD',
                'HUF' => 'HUF',
                'ILS' => 'ILS',
                'JPY' => 'JPY',
                'MXN' => 'MXN',
                'NOK' => 'NOK',
                'NZD' => 'NZD',
                'PHP' => 'PHP',
                'PLN' => 'PLN',
                'SEK' => 'SEK',
                'SGD' => 'SGD',
                'THB' => 'THB',
                'TWD' => 'TWD',
                'USD' => 'USD'
            ]
        ],
        [
            'name'        => 'image_paypal',
            'title'       => '_MI_TDMDOWNLOADS_IMAGEPAYPAL',
            'description' => '_MI_TDMDOWNLOADS_IMAGEPAYPALDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'text',
            'default'     => 'https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif'
        ],
        [
            'name'        => 'break_rss',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_RSS',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ],
        [
            'name'        => 'perpagerss',
            'title'       => '_MI_TDMDOWNLOADS_PERPAGERSS',
            'description' => '_MI_TDMDOWNLOADS_PERPAGERSSDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 10
        ],
        [
            'name'        => 'timecacherss',
            'title'       => '_MI_TDMDOWNLOADS_TIMECACHERSS',
            'description' => '_MI_TDMDOWNLOADS_TIMECACHERSSDSC',
            'formtype'    => 'textbox',
            'valuetype'   => 'int',
            'default'     => 60
        ],
        [
            'name'        => 'logorss',
            'title'       => '_MI_TDMDOWNLOADS_LOGORSS',
            'description' => '',
            'formtype'    => 'textbox',
            'valuetype'   => 'text',
            'default'     => '/modules/' . $moduleDirName . '/assets/images/mydl_slogo.png'
        ],
        [
            'name'        => 'break_comment',
            'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_COMNOTI',
            'description' => '',
            'formtype'    => 'line_break',
            'valuetype'   => 'textbox',
            'default'     => 'odd'
        ]
    ],
    // ------------------- Notifications -------------------

    'hasNotification' => 1,
    'notification'    => [
        'lookup_file' => 'include/notification.inc.php',
        'lookup_func' => 'tdmdownloads_notify_iteminfo',
        'category'    => [
            [
                'name'           => 'global',
                'title'          => _MI_TDMDOWNLOADS_GLOBAL_NOTIFY,
                'description'    => _MI_TDMDOWNLOADS_GLOBAL_NOTIFYDSC,
                'subscribe_from' => ['index.php', 'viewcat.php', 'singlefile.php']
            ],
            [
                'name'           => 'category',
                'title'          => _MI_TDMDOWNLOADS_CATEGORY_NOTIFY,
                'description'    => _MI_TDMDOWNLOADS_CATEGORY_NOTIFYDSC,
                'subscribe_from' => ['viewcat.php', 'singlefile.php'],
                'item_name'      => 'cid',
                'allow_bookmark' => 1
            ],
            [
                'name'           => 'file',
                'title'          => _MI_TDMDOWNLOADS_FILE_NOTIFY,
                'description'    => _MI_TDMDOWNLOADS_FILE_NOTIFYDSC,
                'subscribe_from' => 'singlefile.php',
                'item_name'      => 'lid',
                'allow_bookmark' => 1
            ]
        ],
        'event'       => [
            [
                'name'          => 'new_category',
                'category'      => 'global',
                'title'         => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC,
                'mail_template' => 'global_newcategory_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ
            ],
            [
                'name'          => 'file_modify',
                'category'      => 'global',
                'admin_only'    => 1,
                'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC,
                'mail_template' => 'global_filemodify_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ
            ],
            [
                'name'          => 'file_submit',
                'category'      => 'global',
                'admin_only'    => 1,
                'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC,
                'mail_template' => 'global_filesubmit_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ
            ],
            [
                'name'          => 'file_broken',
                'category'      => 'global',
                'admin_only'    => 1,
                'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC,
                'mail_template' => 'global_filebroken_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ
            ],
            [
                'name'          => 'new_file',
                'category'      => 'global',
                'title'         => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFY,
                'description'   => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC,
                'mail_template' => 'global_newfile_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ
            ],
            [
                'name'          => 'file_submit',
                'category'      => 'global',
                'admin_only'    => 1,
                'title'         => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC,
                'mail_template' => 'category_filesubmit_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ
            ],
            [
                'name'          => 'new_file',
                'category'      => 'global',
                'title'         => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC,
                'mail_template' => 'category_newfile_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ
            ],
            [
                'name'          => 'approve',
                'category'      => 'global',
                'admin_only'    => 1,
                'title'         => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFY,
                'caption'       => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYCAP,
                'description'   => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYDSC,
                'mail_template' => 'file_approve_notify',
                'mail_subject'  => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYSBJ
            ]
        ]
    ]
];
