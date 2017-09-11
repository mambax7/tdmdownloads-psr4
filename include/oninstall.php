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

use \Xoopsmodules\tdmdownloads;

/**
 *
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_tdmdownloads(\XoopsModule $module)
{
    require_once __DIR__ . '/../../../mainfile.php';
    require_once __DIR__ . '/common.php';
    $moduleDirName = basename(dirname(__DIR__));
    /** @var tdmdownloads\Utility $utilityClass */
    $utilityClass  = 'Xoopsmodules\tdmdownloads\Utility';
    $utilityClass0 = new tdmdownloads\Utility();
    if (!class_exists($utilityClass)) {
        xoops_load('Utility', $moduleDirName);
    }

    $xoopsSuccess0 = $utilityClass::checkVerXoops($module);
    $xoopsSuccess  = $utility::checkVerXoops($module);

    $phpSuccess0 = $utilityClass::checkVerPhp($module);
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
function xoops_module_install_tdmdownloads(XoopsModule $module)
{
    require_once __DIR__ . '/../../../mainfile.php';
    require_once __DIR__ . '/../include/config.php';
    require_once __DIR__ . '/common.php';
    $moduleDirName = basename(dirname(__DIR__));
    /** @var tdmdownloads\Utility $utilityClass */
    $utilityClass = new Xoopsmodules\tdmdownloads\Utility();
    //    $utilityTest = $utility;

    //    $downloadsfieldHandler = xoops_getModuleHandler('FieldHandler', $moduleDirName);
    $downloadsfieldHandler = new Xoopsmodules\tdmdownloads\FieldHandler();
    $obj                   = $downloadsfieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMHOMEPAGE);
    $obj->setVar('img', 'homepage.png');
    $obj->setVar('weight', 1);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $downloadsfieldHandler->insert($obj);
    $obj = $downloadsfieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMVERSION);
    $obj->setVar('img', 'version.png');
    $obj->setVar('weight', 2);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $downloadsfieldHandler->insert($obj);
    $obj = $downloadsfieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMSIZE);
    $obj->setVar('img', 'size.png');
    $obj->setVar('weight', 3);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $downloadsfieldHandler->insert($obj);
    $obj = $downloadsfieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMPLATFORM);
    $obj->setVar('img', 'platform.png');
    $obj->setVar('weight', 4);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $downloadsfieldHandler->insert($obj);

    $configurator = include __DIR__ . '/config.php';

    // default Permission Settings ----------------------
    global $xoopsModule;
    $moduleId = $xoopsModule->getVar('mid');
    //    $moduleId2    = $helper->getModule()->mid();
    $gpermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $gpermHandler->addRight($moduleDirName . '_approve', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utilityClass::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->blankFiles) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator->blankFiles) as $i) {
            $dest = $configurator->blankFiles[$i] . '/blank.png';
            $utilityClass::copyFile($file, $dest);
        }
    }

    //  ---  COPY UPLOAD FOLDERS ---------------
    if (count($configurator->copyFolders) > 0) {
        foreach (array_keys($configurator->copyFolders) as $i) {
            $source = $configurator->copyFolders[$i]['source'];
            $dest   = $configurator->copyFolders[$i]['dest'];
            $utilityClass::rcopy($source, $dest);
        }
    }

    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return true;
}
