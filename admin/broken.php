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

use XoopsModules\Tdmdownloads;

require __DIR__ . '/admin_header.php';

/** @var Tdmdownloads\Helper $helper */
$helper = Tdmdownloads\Helper::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = $utility->cleanVars($_REQUEST, 'op', 'list', 'string');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $broken_admin = \Xmf\Module\Admin::getInstance();
        echo $broken_admin->displayNavigation(basename(__FILE__));
        $criteria = new \CriteriaCompo();
        if (\Xmf\Request::hasVar('limit', 'REQUEST')) {
            $criteria->setLimit($_REQUEST['limit']);
            $limit = $_REQUEST['limit'];
        } else {
            $criteria->setLimit($helper->getConfig('perpageadmin'));
            $limit = $helper->getConfig('perpageadmin');
        }
        if (\Xmf\Request::hasVar('start', 'REQUEST')) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
            $start = 0;
        }
        $criteria->setSort('reportid');
        $criteria->setOrder('ASC');
        //pour faire une jointure de table
        //        $brokenHandler       = new \XoopsModules\Tdmdownloads\BrokenHandler($db);
        $brokenHandler->table_link   = $brokenHandler->db->prefix('tdmdownloads_downloads'); // Nom de la table en jointure
        $brokenHandler->field_link   = 'lid'; // champ de la table en jointure
        $brokenHandler->field_object = 'lid'; // champ de la table courante
        $downloadsbroken_arr         = $brokenHandler->getByLink($criteria);
        $numrows                     = $brokenHandler->getCount($criteria);
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&limit=' . $limit);
            $pagenav = $pagenav->renderNav(4);
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des téléchargements brisés
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMFILE . '</th>';
            echo '<th align="center">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMDOWNLOADS_BROKEN_SENDER . '</th>';
            echo '<th align="center" width="15%">' . _AM_TDMDOWNLOADS_FORMACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($downloadsbroken_arr) as $i) {
                $class               = ('even' === $class) ? 'odd' : 'even';
                $downloads_lid       = $downloadsbroken_arr[$i]->getVar('lid');
                $downloads_reportid  = $downloadsbroken_arr[$i]->getVar('reportid');
                $downloads_title     = $downloadsbroken_arr[$i]->getVar('title');
                $downloads_cid       = $downloadsbroken_arr[$i]->getVar('cid');
                $downloads_poster    = \XoopsUser::getUnameFromId($downloadsbroken_arr[$i]->getVar('sender'));
                $downloads_poster_ip = $downloadsbroken_arr[$i]->getVar('ip');
                echo '<tr class="' . $class . '">';
                echo '<td align="center">';
                echo '<a href="../visit.php?cid=' . $downloads_cid . '&lid=' . $downloads_lid . '" target="_blank"><img src="../assets/images/icon/download.png" alt="Download ' . $downloads_title . '" title="Download ' . $downloads_title . '"></a>';
                echo '</td>';
                echo '<td align="center">' . $downloads_title . '</td>';
                echo '<td align="center"><b>' . $downloads_poster . '</b> (' . $downloads_poster_ip . ')</td>';
                echo '<td align="center" width="15%">';
                echo '<a href="downloads.php?op=view_downloads&downloads_lid=' . $downloads_lid . '"><img src="../assets/images/icon/view_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '" title="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '"></a> ';
                echo '<a href="downloads.php?op=edit_downloads&downloads_lid=' . $downloads_lid . '"><img src="../assets/images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT . '" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> ';
                echo '<a href="broken.php?op=del_brokendownloads&broken_id=' . $downloads_reportid . '"><img src="../assets/images/icon/ignore_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMIGNORE . '" title="' . _AM_TDMDOWNLOADS_FORMIGNORE . '"></a>';
                echo '</td>';
            }
            echo '</table><br>';
            echo '<br><div align=right>' . $pagenav . '</div><br>';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMDOWNLOADS_ERREUR_NOBROKENDOWNLOADS . '</div><br>';
        }
        break;
    // permet de suprimmer le rapport de téléchargment brisé
    case 'del_brokendownloads':
        $obj = $brokenHandler->get($_REQUEST['broken_id']);
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($brokenHandler->delete($obj)) {
                redirect_header('broken.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            }
            echo $objvotedata->getHtmlErrors();
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $broken_admin = \Xmf\Module\Admin::getInstance();
            $broken_admin->addItemButton(_MI_TDMDOWNLOADS_ADMENU4, 'broken.php', 'list');
            echo $broken_admin->displayButton('left');
            xoops_confirm(['ok' => 1, 'broken_id' => $_REQUEST['broken_id'], 'op' => 'del_brokendownloads'], $_SERVER['REQUEST_URI'], _AM_TDMDOWNLOADS_BROKEN_SURDEL . '<br>');
        }
        break;
}
//Affichage de la partie basse de l'administration de Xoops
require_once __DIR__ . '/admin_footer.php';
