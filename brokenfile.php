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

use Xoopsmodules\tdmdownloads\Tdmobjecttree;

require_once __DIR__ . '/header.php';
// template d'affichage
$moduleDirName = basename(__DIR__);

$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_brokenfile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$
$op  = $utilities->cleanVars($_REQUEST, 'op', 'liste', 'string');
$lid = $utilities->cleanVars($_REQUEST, 'lid', 0, 'int');

//redirection si pas de permission de vote
if (false === $perm_vote) {
    redirect_header('index.php', 2, _NOPERM);
}

$xoopsTpl->assign('mydirname', $moduleDirName);
$view_downloads = $downloadsHandler->get($lid);
// redirection si le t�l�chargement n'existe pas ou n'est pas activ�
if (0 == count($view_downloads) || 0 == $view_downloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

//redirection si pas de permission (cat)
$categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'liste':
        //tableau des cat�gories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $downloadscat_arr = $categoryHandler->getAll($criteria);
        $mytree           = new Tdmobjecttree($downloadscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> <a title="' . $view_downloads->getVar('title') . '" href="singlefile.php?lid=' . $view_downloads->getVar('lid') . '">' . $view_downloads->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN;
        $xoopsTpl->assign('navigation', $navigation);
        // r�f�rencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN . ' - ' . $view_downloads->getVar('title') . ' - ';
        $pagetitle .= $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN . ' (' . $view_downloads->getVar('title') . ')'));
        //Affichage du formulaire de fichier bris�*/
        $obj  = $downloadsbrokenHandler->create();
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save':
        $obj = $downloadsbrokenHandler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        if (0 !== $ratinguser) {
            // si c'est un membre on v�rifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $downloadsbroken_arr = $downloadsbrokenHandler->getAll($criteria);
            foreach (array_keys($downloadsbroken_arr) as $i) {
                if ($downloadsbroken_arr[$i]->getVar('sender') === $ratinguser) {
                    redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_ALREADYREPORTED);
                }
            }
        } else {
            // si c'est un utilisateur anonyme on v�rifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $criteria->add(new Criteria('sender', 0));
            $criteria->add(new Criteria('ip', getenv('REMOTE_ADDR')));
            if ($downloadsbrokenHandler->getCount($criteria) >= 1) {
                redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_ALREADYREPORTED);
            }
        }
        $erreur         = false;
        $message_erreur = '';
        // Test avant la validation
        xoops_load('captcha');
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur         = true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('sender', $ratinguser);
        $obj->setVar('ip', getenv('REMOTE_ADDR'));
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            if ($downloadsbrokenHandler->insert($obj)) {
                $tags                      = [];
                $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/broken.php';
                $notificationHandler       = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'file_broken', $tags);
                redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des t�l�chargements
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
