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
//require_once dirname(__DIR__) . '/include/setup.php';
//require_once  dirname(__DIR__) . '/autoloader.php';
require_once __DIR__ . '/admin_header.php';

//require_once dirname(__DIR__) . '/include/functions.folders.php';

xoops_cp_header();

// compte le nombre de catégories
$criteria      = new \CriteriaCompo();
$nb_categories = $categoryHandler->getCount($criteria);
// compte le nombre de téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$nb_downloads = $downloadsHandler->getCount($criteria);
// compte le nombre de téléchargements en attente de validation
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0));
$nb_downloads_waiting = $downloadsHandler->getCount($criteria);
// compte le nombre de rapport de téléchargements brisés
$nb_broken = $brokenHandler->getCount();
// compte le nombre de demande de modifications
$nb_modified = $modifiedHandler->getCount();

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = strtoupper($moduleDirName);

$adminObject = \Xmf\Module\Admin::getInstance();
/*
foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
    $utilities->prepareFolder($uploadFolders[$i]);
    $adminObject->addConfigBoxLine($uploadFolders[$i], 'folder');
    //    $adminObject->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}
*/

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU2);
$adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_CATEGORIES, $nb_categories), '');

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU3);
$adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADS, $nb_downloads), '');

if (0 == $nb_downloads_waiting) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, $nb_downloads_waiting), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, $nb_downloads_waiting), '', 'Red');
}

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU4);
if (0 === $nb_broken) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_BROKEN, $nb_broken), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_BROKEN, $nb_broken), '', 'Red');
}

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU5);
if (0 == $nb_modified) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_MODIFIED, $nb_modified), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_MODIFIED, $nb_modified), '', 'Red');
}

$adminObject->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];
//    global $xoopsModuleConfig, $modversion;
//    $className = $xoopsModule->modinfo['dirname'].'Folders';

$languageConstants = [
    _AM_TDMDOWNLOADS_AVAILABLE,
    _AM_TDMDOWNLOADS_NOTAVAILABLE,
    _AM_TDMDOWNLOADS_CREATETHEDIR,
    _AM_TDMDOWNLOADS_NOTWRITABLE,
    _AM_TDMDOWNLOADS_SETMPERM,
    _AM_TDMDOWNLOADS_DIRCREATED,
    _AM_TDMDOWNLOADS_DIRNOTCREATED,
    _AM_TDMDOWNLOADS_PERMSET,
    _AM_TDMDOWNLOADS_PERMNOTSET
];

/*
foreach (array_keys($folders) as $i) {
    $path = $folders[$i];

    //set the folder that you're checking
    //        $pathStatus = FoldersChecker::getDirectoryStatus($path,0777,$folderWords,$redirectFile);
    //        $pathStatus = call_user_func($className .'::getDirectoryStatus',$path,0777,$folderWords,$redirectFile);
    //        $adminObject->addConfigBoxLine($pathStatus);

    //        $utilities->createFolder($path, $mode = 0777, $fileSource, $fileTarget = null);

    $adminObject->addConfigBoxLine(FoldersChecker::getDirectoryStatus($path, 0777, $languageConstants, $redirectFile));
}
*/

//$helper = \XoopsModules\AboutHelper::getInstance();
$helper->loadLanguage('common');

//xoops_loadLanguage('common', $moduleDirName);

$configurator = require_once dirname(__DIR__) . '/include/config.php';
foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);

    if (is_dir($configurator->uploadFolders[$i])) {
        $adminObject->addConfigBoxLine('<img src="' . $pathIcon16 . '/1.png"><span class="Green">' . sprintf(constant('CO_' . $moduleDirNameUpper . '_FOLDER_YES'), $configurator->uploadFolders[$i]) . '</span>', '', '');
    } else {
        $adminObject->addConfigBoxLine('<label><img src="' . $pathIcon16 . '/0.png"><span class="Red">' . sprintf(constant('CO_' . $moduleDirNameUpper . '_FOLDER_NO'), $configurator->uploadFolders[$i]) . '</span></label>', '', '');
    }
}

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

echo $utility::getServerStats();

require_once __DIR__ . '/admin_footer.php';
