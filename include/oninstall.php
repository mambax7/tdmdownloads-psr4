<?php

use XoopsModules\Tdmdownloads\Utility;
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

use \XoopsModules\Tdmdownloads;

/**
 *
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_tdmdownloads(\XoopsModule $module)
{
    require_once  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
    require_once __DIR__ . '/common.php';

    /** @var \XoopsModules\Tdmdownloads\Utility $utility */
    $utility = new \XoopsModules\Tdmdownloads\Utility();

    $xoopsSuccess0 = $utility::checkVerXoops($module);
    $xoopsSuccess  = $utility::checkVerXoops($module);

    $phpSuccess0 = $utility::checkVerPhp($module);
    $phpSuccess  = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $mod_tables =& $module->getInfo('tables');
        foreach ($mod_tables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }
    return $xoopsSuccess && $phpSuccess;
}

/**
 *
 * Performs tasks required during installation of the module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_tdmdownloads(\XoopsModule $module)
{
    require_once  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
    require_once  dirname(__DIR__) . '/include/config.php';
    require_once __DIR__ . '/common.php';
    $moduleDirName = basename(dirname(__DIR__));
    /** @var Tdmdownloads\Utility $utility */
    $utility = new \XoopsModules\Tdmdownloads\Utility();
    //    $utilityTest = $utility;

    //    $fieldHandler = xoops_getModuleHandler('FieldHandler', $moduleDirName);
    $fieldHandler = new \XoopsModules\Tdmdownloads\FieldHandler();
    $obj          = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMHOMEPAGE);
    $obj->setVar('img', 'homepage.png');
    $obj->setVar('weight', 1);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMVERSION);
    $obj->setVar('img', 'version.png');
    $obj->setVar('weight', 2);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMSIZE);
    $obj->setVar('img', 'size.png');
    $obj->setVar('weight', 3);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMPLATFORM);
    $obj->setVar('img', 'platform.png');
    $obj->setVar('weight', 4);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);

    $configurator = include __DIR__ . '/config.php';

    // default Permission Settings ----------------------
    global $xoopsModule;
    $moduleId = $xoopsModule->getVar('mid');
    //    $moduleId2    = $helper->getModule()->mid();
    $grouppermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $grouppermHandler->addRight($moduleDirName . '_approve', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file =  dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }

    //  ---  COPY UPLOAD FOLDERS ---------------
    if (count($configurator->copyFolders) > 0) {
        foreach (array_keys($configurator->copyFolders) as $i) {
            $source = $configurator->copyFolders[$i]['source'];
            $dest   = $configurator->copyFolders[$i]['dest'];
            $utility::rcopy($source, $dest);
        }
    }

    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return true;
}
