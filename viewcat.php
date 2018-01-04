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

use XoopsModules\Tdmdownloads\TdmObjectTree;

require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);

// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_viewcat.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
$xoopsTpl->assign('mydirname', $moduleDirName);
$cid = $utilities->cleanVars($_REQUEST, 'cid', 0, 'int');

// pour les permissions
$categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);

// redirection si la cat�gorie n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_cid', $cid));
if (0 === $cid || 0 === $categoryHandler->getCount($criteria)) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_CAT_NONEXISTENT);
}
// pour les permissions (si pas de droit, redirection)
if (!in_array((int)$cid, $categories)) {
    redirect_header('index.php', 2, _NOPERM);
}

//tableau des cat�gories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadscatArray = $categoryHandler->getAll($criteria);
$mytree           = new TdmObjectTree($downloadscatArray, 'cat_cid', 'cat_pid');

//tableau des t�l�chargements
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloads_arr = $downloadsHandler->getAll($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_INDEX_THEREARE, count($downloads_arr)));

//navigation
$nav_category = $utilities->getPathTreeUrl($mytree, $cid, $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC');
$xoopsTpl->assign('category_path', $nav_category);

// info cat�gorie
$xoopsTpl->assign('category_id', $cid);
$cat_info = $categoryHandler->get($cid);
$xoopsTpl->assign('cat_description', $cat_info->getVar('cat_description_main'));

//affichage des cat�gories
$xoopsTpl->assign('nb_catcol', $xoopsModuleConfig['nb_catcol']);
$count    = 1;
$keywords = '';
foreach (array_keys($downloadscatArray) as $i) {
    if ($downloadscatArray[$i]->getVar('cat_pid') === $cid) {
        $totaldownloads    = $utilities->getNumbersOfEntries($mytree, $categories, $downloads_arr, $downloadscatArray[$i]->getVar('cat_cid'));
        $subcategories_arr = $mytree->getFirstChild($downloadscatArray[$i]->getVar('cat_cid'));
        $chcount           = 0;
        $subcategories     = '';
        //pour les mots clef
        $keywords .= $downloadscatArray[$i]->getVar('cat_title') . ',';
        foreach (array_keys($subcategories_arr) as $j) {
            if ($chcount >= $xoopsModuleConfig['nbsouscat']) {
                $subcategories .= '<li>[<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $downloadscatArray[$i]->getVar('cat_cid') . '">+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $subcategories_arr[$j]->getVar('cat_cid') . '">' . $subcategories_arr[$j]->getVar('cat_title') . '</a></li>';
            $keywords      .= $downloadscatArray[$i]->getVar('cat_title') . ',';
            ++$chcount;
        }
        $xoopsTpl->append('subcategories', [
            'image'            => $uploadurl . $downloadscatArray[$i]->getVar('cat_imgurl'),
            'id'               => $downloadscatArray[$i]->getVar('cat_cid'),
            'title'            => $downloadscatArray[$i]->getVar('cat_title'),
            'description_main' => $downloadscatArray[$i]->getVar('cat_description_main'),
            'infercategories'  => $subcategories,
            'totaldownloads'   => $totaldownloads,
            'count'            => $count
        ]);
        ++$count;
    }
}

//pour afficher les r�sum�s
//----------------------------------------------------------------------------------------------------------------------------------------------------
//t�l�chargements r�cents
if (1 == $xoopsModuleConfig['bldate']) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)$_REQUEST['cid']));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, $xoopsModuleConfig['longbl']) . '...';
        }
        $date = formatTimestamp($downloads_arr[$i]->getVar('date'), 's');
        $xoopsTpl->append('bl_date', [
            'id'    => $downloads_arr[$i]->getVar('lid'),
            'cid'   => $downloads_arr[$i]->getVar('cid'),
            'date'  => $date,
            'title' => $title
        ]);
    }
}
//plus t�l�charg�s
if (1 == $xoopsModuleConfig['blpop']) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)$_REQUEST['cid']));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, $xoopsModuleConfig['longbl']) . '...';
        }
        $xoopsTpl->append('bl_pop', [
            'id'    => $downloads_arr[$i]->getVar('lid'),
            'cid'   => $downloads_arr[$i]->getVar('cid'),
            'hits'  => $downloads_arr[$i]->getVar('hits'),
            'title' => $title
        ]);
    }
}
//mieux not�s
if (1 == $xoopsModuleConfig['blrating']) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)$_REQUEST['cid']));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $title = $downloads_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, $xoopsModuleConfig['longbl']) . '...';
        }
        $rating = number_format($downloads_arr[$i]->getVar('rating'), 1);
        $xoopsTpl->append('bl_rating', [
            'id'     => $downloads_arr[$i]->getVar('lid'),
            'cid'    => $downloads_arr[$i]->getVar('cid'),
            'rating' => $rating,
            'title'  => $title
        ]);
    }
}
// affichage du r�sum�
$bl_affichage = 1;
if (0 == $xoopsModuleConfig['bldate'] && 0 == $xoopsModuleConfig['blpop'] && 0 == $xoopsModuleConfig['blrating']) {
    $bl_affichage = 0;
}
//----------------------------------------------------------------------------------------------------------------------------------------------------

// affichage des t�l�chargements
if ($xoopsModuleConfig['perpage'] > 0) {
    $xoopsTpl->assign('nb_dowcol', $xoopsModuleConfig['nb_dowcol']);
    //Utilisation d'une copie d'�cran avec la largeur selon les pr�f�rences
    if (1 == $xoopsModuleConfig['useshots']) {
        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('img_float', $xoopsModuleConfig['img_float']);
    }
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)$_REQUEST['cid']));
    $numrows = $downloadsHandler->getCount($criteria);
    $xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_CAT_THEREARE, $numrows));

    // Pour un affichage sur plusieurs pages
    if (isset($_REQUEST['limit'])) {
        $criteria->setLimit($_REQUEST['limit']);
        $limit = $_REQUEST['limit'];
    } else {
        $criteria->setLimit($xoopsModuleConfig['perpage']);
        $limit = $xoopsModuleConfig['perpage'];
    }
    if (isset($_REQUEST['start'])) {
        $criteria->setStart($_REQUEST['start']);
        $start = $_REQUEST['start'];
    } else {
        $criteria->setStart(0);
        $start = 0;
    }
    if (isset($_REQUEST['sort'])) {
        $criteria->setSort($_REQUEST['sort']);
        $sort = $_REQUEST['sort'];
    } else {
        $criteria->setSort('date');
        $sort = 'date';
    }
    if (isset($_REQUEST['order'])) {
        $criteria->setOrder($_REQUEST['order']);
        $order = $_REQUEST['order'];
    } else {
        $criteria->setOrder('DESC');
        $order = 'DESC';
    }

    $downloads_arr = $downloadsHandler->getAll($criteria);
    if ($numrows > $limit) {
        $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'limit=' . $limit . '&cid=' . (int)$_REQUEST['cid'] . '&sort=' . $sort . '&order=' . $order);
        $pagenav = $pagenav->renderNav(4);
    } else {
        $pagenav = '';
    }
    $xoopsTpl->assign('pagenav', $pagenav);
    $summary    = '';
    $cpt        = 0;
    $categories = $utilities->getItemIds('tdmdownloads_download', $moduleDirName);
    $item       = $utilities->getItemIds('tdmdownloads_download_item', $moduleDirName);
    foreach (array_keys($downloads_arr) as $i) {
        if ('blank.gif' === $downloads_arr[$i]->getVar('logourl')) {
            $logourl = '';
        } else {
            $logourl = $downloads_arr[$i]->getVar('logourl');
            $logourl = $uploadurl_shots . $logourl;
        }
        $datetime    = formatTimestamp($downloads_arr[$i]->getVar('date'), 's');
        $submitter   = XoopsUser::getUnameFromId($downloads_arr[$i]->getVar('submitter'));
        $description = $downloads_arr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (false === strpos($description, '[pagebreak]')) {
            $description_short = $description;
        } else {
            $description_short = substr($description, 0, strpos($description, '[pagebreak]'));
        }
        // pour les vignettes "new" et "mis � jour"
        $new = $utilities->getStatusImage($downloads_arr[$i]->getVar('date'), $downloads_arr[$i]->getVar('status'));
        $pop = $utilities->getPopularImage($downloads_arr[$i]->getVar('hits'));

        // D�fini si la personne est un admin
        $adminlink = '';
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $adminlink = '<a href="'
                         . XOOPS_URL
                         . '/modules/'
                         . $moduleDirName
                         . '/admin/downloads.php?op=view_downloads&amp;downloads_lid='
                         . $downloads_arr[$i]->getVar('lid')
                         . '" title="'
                         . _MD_TDMDOWNLOADS_EDITTHISDL
                         . '"><img src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $moduleDirName
                         . '/assets/images/icon/edit.png" width="16px" height="16px" border="0" alt="'
                         . _MD_TDMDOWNLOADS_EDITTHISDL
                         . '" /></a>';
        }
        //permission de t�l�charger
        $perm_download = true;
        if (1 === $xoopsModuleConfig['permission_download']) {
            if (!in_array($downloads_arr[$i]->getVar('cid'), $categories)) {
                $perm_download = false;
            }
        } else {
            if (!in_array($downloads_arr[$i]->getVar('lid'), $item)) {
                $perm_download = false;
            }
        }
        // utilisation du sommaire
        ++$cpt;
        $summary = $cpt . '- <a href="#l' . $cpt . '">' . $downloads_arr[$i]->getVar('title') . '</a><br>';
        $xoopsTpl->append('summary', ['title' => $summary, 'count' => $cpt]);

        $xoopsTpl->append('file', [
            'id'                => $downloads_arr[$i]->getVar('lid'),
            'cid'               => $downloads_arr[$i]->getVar('cid'),
            'title'             => $downloads_arr[$i]->getVar('title'),
            'new'               => $new,
            'pop'               => $pop,
            'logourl'           => $logourl,
            'updated'           => $datetime,
            'description_short' => $description_short,
            'adminlink'         => $adminlink,
            'submitter'         => $submitter,
            'perm_download'     => $perm_download,
            'count'             => $cpt
        ]);
        //pour les mots clef
        $keywords .= $downloads_arr[$i]->getVar('title') . ',';
    }

    if (0 == $numrows) {
        $bl_affichage = 0;
    }
    $xoopsTpl->assign('bl_affichage', $bl_affichage);

    // affichage du sommaire
    if ($xoopsModuleConfig['autosummary']) {
        if (0 == $numrows) {
            $xoopsTpl->assign('aff_summary', false);
        } else {
            $xoopsTpl->assign('aff_summary', true);
        }
    } else {
        $xoopsTpl->assign('aff_summary', false);
    }

    // affichage du menu de tri
    if ($numrows > 1) {
        $xoopsTpl->assign('navigation', true);
        $sortorder = $sort . $order;
        if ('hitsASC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_POPULARITYLTOM;
        }
        if ('hitsDESC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_POPULARITYMTOL;
        }
        if ('titleASC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_TITLEATOZ;
        }
        if ('titleDESC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_TITLEZTOA;
        }
        if ('dateASC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_DATEOLD;
        }
        if ('dateDESC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_DATENEW;
        }
        if ('ratingASC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_RATINGLTOH;
        }
        if ('ratingDESC' === $sortorder) {
            $affichage_tri = _MD_TDMDOWNLOADS_CAT_RATINGHTOL;
        }
        $xoopsTpl->assign('affichage_tri', sprintf(_MD_TDMDOWNLOADS_CAT_CURSORTBY, $affichage_tri));
    }
}
// r�f�rencement
// titre de la page
$pagetitle = $utilities->getPathTreeUrl($mytree, $cid, $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC');
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($downloadscatArray[$cid]->getVar('cat_description_main')));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

include XOOPS_ROOT_PATH . '/footer.php';
