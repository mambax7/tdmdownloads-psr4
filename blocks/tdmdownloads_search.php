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

//use Xoopsmodules\tdmdownloads;
use Xoopsmodules\tdmdownloads\Tdmobjecttree;

/**
 * @return array
 */
function b_tdmdownloads_search_show()
{
    $moduleHandler = xoops_getHandler('module');
    // get the name of the file's directory to get the "owner" of the block, i.e. its module, and not the "user", where it is currently
    //$mydir          = basename(dirname(__DIR__));
    $moduleDirName = basename(dirname(__DIR__));
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $db            = $helper = null;
    //    require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/setup.php';
    //    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/class/tree.php';
    //appel des class
    //    $categoryHandler       = xoops_getModuleHandler('Category', $moduleDirName);
    //    $downloadsHandler          = xoops_getModuleHandler('tdmdownloads_downloads', $moduleDirName);
    //    $downloadsfieldHandler     = xoops_getModuleHandler('Field', $moduleDirName);
    //    $downloadsfielddataHandler = xoops_getModuleHandler('Fielddata', $moduleDirName);
    //appel des fichiers de langues
    xoops_loadLanguage('main', $moduleDirName);
    xoops_loadLanguage('admin', $moduleDirName);
    $categoryHandler  = new Xoopsmodules\tdmdownloads\CategoryHandler(null);
    $utilities        = new Xoopsmodules\tdmdownloads\Utilities($db, $helper);
    $downloadsHandler = new Xoopsmodules\tdmdownloads\DownloadsHandler($db);
    $categories       = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);

    $block = [];

    //formulaire de recherche
    $form = new XoopsThemeForm(_MD_TDMDOWNLOADS_SEARCH, 'search', XOOPS_URL . '/modules/' . $moduleDirName . '/search.php', 'post');
    $form->setExtra('enctype="multipart/form-data"');
    //recherche par titre
    $form->addElement(new XoopsFormText(_MD_TDMDOWNLOADS_SEARCH_TITLE, 'title', 25, 255, ''));
    //recherche par cat�gorie
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
    $downloadscat_arr = $categoryHandler->getAll($criteria);
    $mytree           = new Tdmobjecttree($downloadscat_arr, 'cat_cid', 'cat_pid');
    //$form->addElement(new XoopsFormLabel(_AM_TDMDOWNLOADS_FORMINCAT, $mytree->makeSelBox('cat', 'cat_title', '--', '', true)));
    $form->addElement($mytree->makeSelectElement('cat', 'cat_title', '--', '', true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);
    //recherche champ sup.
    //    $downloadsfieldHandler = xoops_getModuleHandler('Field', $moduleDirName);
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('search', 1));
    $criteria->add(new Criteria('status', 1));
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $downloadsfieldHandler = new Xoopsmodules\tdmdownloads\fieldHandler();
    $downloads_field       = $downloadsfieldHandler->getAll($criteria);
    foreach (array_keys($downloads_field) as $i) {
        $title_sup                                          = '';
        $contenu_arr                                        = [];
        $lid_arr                                            = [];
        $nom_champ                                          = 'champ' . $downloads_field[$i]->getVar('fid');
        $criteria                                           = new CriteriaCompo();
        $champ_contenu[$downloads_field[$i]->getVar('fid')] = 999;
        if (1 == $downloads_field[$i]->getVar('status_def')) {
            $criteria->add(new Criteria('status', 0, '!='));
            if (1 == $downloads_field[$i]->getVar('fid')) {
                //page d'accueil
                $title_sup = _AM_TDMDOWNLOADS_FORMHOMEPAGE;
                $criteria->setSort('homepage');
                $nom_champ_base = 'homepage';
            }
            if (2 == $downloads_field[$i]->getVar('fid')) {
                //version
                $title_sup = _AM_TDMDOWNLOADS_FORMVERSION;
                $criteria->setSort('version');
                $nom_champ_base = 'version';
            }
            if (3 == $downloads_field[$i]->getVar('fid')) {
                //taille du fichier
                $title_sup = _AM_TDMDOWNLOADS_FORMSIZE;
                $criteria->setSort('size');
                $nom_champ_base = 'size';
            }
            if (4 == $downloads_field[$i]->getVar('fid')) {
                //platform
                $title_sup = _AM_TDMDOWNLOADS_FORMPLATFORM;
                //                $platform_array = explode('|', $GLOBALS['xoopsModuleConfig']['platform']);
                $moduleHandler = xoops_getHandler('module');
                $module        = $moduleHandler->getByDirname($moduleDirName);
                $configHandler = xoops_getHandler('config');
                $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
                //                echo 'The  current value of \'foo\' is: ' . $moduleConfig['platform'];
                $platform_array = explode('|', $moduleConfig['platform']);
                foreach ($platform_array as $platform) {
                    $contenu_arr[$platform] = $platform;
                }
            } else {
                $criteria->setOrder('ASC');
                $tdmdownloads_arr = $downloadsHandler->getAll($criteria);
                foreach (array_keys($tdmdownloads_arr) as $j) {
                    $contenu_arr[$tdmdownloads_arr[$j]->getVar($nom_champ_base)] = $tdmdownloads_arr[$j]->getVar($nom_champ_base);
                }
            }
        } else {
            $title_sup = $downloads_field[$i]->getVar('title');
            $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
            $criteria->setSort('data');
            $criteria->setOrder('ASC');
            $downloadsfielddataHandler = new Xoopsmodules\tdmdownloads\fielddataHandler();
            $tdmdownloads_arr          = $downloadsfielddataHandler->getAll($criteria);
            foreach (array_keys($tdmdownloads_arr) as $j) {
                $contenu_arr[$tdmdownloads_arr[$j]->getVar('data', 'n')] = $tdmdownloads_arr[$j]->getVar('data');
            }
            if ('' !== $champ_contenu[$downloads_field[$i]->getVar('fid')]) {
                $criteria_1 = new CriteriaCompo();
                $criteria_1->add(new Criteria('data', $champ_contenu[$downloads_field[$i]->getVar('fid')]));
                $data_arr = $downloadsfielddataHandler->getAll($criteria_1);
                foreach (array_keys($data_arr) as $k) {
                    $lid_arr[] = $data_arr[$k]->getVar('lid');
                }
            }
            $form->addElement($select_sup);
        }
        $select_sup = new XoopsFormSelect($title_sup, $nom_champ, $champ_contenu[$downloads_field[$i]->getVar('fid')]);
        $select_sup->addOption(999, _MD_TDMDOWNLOADS_SEARCH_ALL1);
        $select_sup->addOptionArray($contenu_arr);
        $form->addElement($select_sup);
        unset($select_sup);
    }
    //bouton validation
    $button_tray = new XoopsFormElementTray('', '');
    $button_tray->addElement(new XoopsFormButton('', 'submit', _MD_TDMDOWNLOADS_SEARCH_BT, 'submit'));
    $form->addElement($button_tray);
    $block['form'] = $form->render();

    return $block;
}
