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
require_once __DIR__ . '/header.php';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$moduleDirName = basename(__DIR__);
// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_singlefile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);

$lid = $utility->cleanVars($_REQUEST, 'lid', 0, 'int');

//information du téléchargement
$viewDownloads = $downloadsHandler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if ((is_array($viewDownloads) && 0 == count($viewDownloads)) || 0 == $viewDownloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

// pour les permissions
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($viewDownloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}

//tableau des catégories
$criteria = new \CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadscatArray = $categoryHandler->getAll($criteria);
$mytree            = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');

//navigation
$navigation = $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ', true, 'ASC', true);
$navigation = $navigation . ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ' . $viewDownloads->getVar('title');
$xoopsTpl->assign('navigation', $navigation);

// sortie des informations
//Utilisation d'une copie d'écran avec la largeur selon les préférences
if (1 == $helper->getConfig('useshots')) {
    $xoopsTpl->assign('shotwidth', $helper->getConfig('shotwidth'));
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('img_float', $helper->getConfig('img_float'));
}

if ('ltr' === $helper->getConfig('download_float')) {
    $xoopsTpl->assign('textfloat', 'floatleft');
    $xoopsTpl->assign('infofloat', 'floatright');
} else {
    $xoopsTpl->assign('textfloat', 'floatright');
    $xoopsTpl->assign('infofloat', 'floatleft');
}

// sortie des informations
if ('blank.gif' === $viewDownloads->getVar('logourl')) {
    $logourl = '';
} else {
    $logourl = $viewDownloads->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
}
// Défini si la personne est un admin
$adminlink = '';
if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $adminlink = '<a href="'
                 . XOOPS_URL
                 . '/modules/'
                 . $moduleDirName
                 . '/admin/downloads.php?op=view_downloads&amp;downloads_lid='
                 . $_REQUEST['lid']
                 . '" title="'
                 . _MD_TDMDOWNLOADS_EDITTHISDL
                 . '"><img src="'
                 . XOOPS_URL
                 . '/modules/'
                 . $moduleDirName
                 . '/assets/images/icon/edit.png" width="16px" height="16px" border="0" alt="'
                 . _MD_TDMDOWNLOADS_EDITTHISDL
                 . '"></a>';
}

$description = $viewDownloads->getVar('description');
$xoopsTpl->assign('description', str_replace('[pagebreak]', '', $description));
$xoopsTpl->assign('lid', $lid);
$xoopsTpl->assign('cid', $viewDownloads->getVar('cid'));
$xoopsTpl->assign('logourl', $logourl);
// pour les vignettes "new" et "mis à jour"
$new = $utility->getStatusImage($viewDownloads->getVar('date'), $viewDownloads->getVar('status'));
$pop = $utility->getPopularImage($viewDownloads->getVar('hits'));
$xoopsTpl->assign('title', $viewDownloads->getVar('title'));
$xoopsTpl->assign('new', $new);
$xoopsTpl->assign('pop', $pop);
$xoopsTpl->assign('adminlink', $adminlink);
$xoopsTpl->assign('date', formatTimestamp($viewDownloads->getVar('date'), 's'));
$xoopsTpl->assign('author', \XoopsUser::getUnameFromId($viewDownloads->getVar('submitter')));
$xoopsTpl->assign('hits', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_NBTELECH, $viewDownloads->getVar('hits')));
$xoopsTpl->assign('rating', number_format($viewDownloads->getVar('rating'), 1));
$xoopsTpl->assign('votes', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_VOTES, $viewDownloads->getVar('votes')));
$xoopsTpl->assign('nb_comments', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_COMMENTS, $viewDownloads->getVar('comments')));
$xoopsTpl->assign('show_bookmark', $helper->getConfig('show_bookmark'));
$xoopsTpl->assign('show_social', $helper->getConfig('show_social'));

//paypal
$paypal = false;
if (true === $helper->getConfig('use_paypal') && '' !== $viewDownloads->getVar('paypal')) {
    $paypal = '<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="' . $viewDownloads->getVar('paypal') . '">
    <input type="hidden" name="item_name" value="' . sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_PAYPAL, $viewDownloads->getVar('title')) . ' (' . \XoopsUser::getUnameFromId(!empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0) . ')">
    <input type="hidden" name="currency_code" value="' . $helper->getConfig('currency_paypal') . '">
    <input type="image" src="' . $helper->getConfig('image_paypal') . '" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
    </form>';
}
$xoopsTpl->assign('paypal', $paypal);

// pour les champs supplémentaires
$criteria = new \CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('status', 1));
$downloads_field = $fieldHandler->getAll($criteria);
$nb_champ        = count($downloads_field);
$champ_sup       = '';
$champ_sup_vide  = 0;
foreach (array_keys($downloads_field) as $i) {
    if (1 == $downloads_field[$i]->getVar('status_def')) {
        if (1 == $downloads_field[$i]->getVar('fid')) {
            //page d'accueil
            if ('' != $viewDownloads->getVar('homepage')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . ':&nbsp;<a href="' . $viewDownloads->getVar('homepage') . '">' . _MD_TDMDOWNLOADS_SINGLEFILE_ICI . '</a>';
                ++$champ_sup_vide;
            }
        }
        if (2 == $downloads_field[$i]->getVar('fid')) {
            //version
            if ('' != $viewDownloads->getVar('version')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMVERSION . ':&nbsp;' . $viewDownloads->getVar('version');
                ++$champ_sup_vide;
            }
        }
        if (3 == $downloads_field[$i]->getVar('fid')) {
            //taille du fichier
            $size_value_arr = explode(' ', $viewDownloads->getVar('size'));
            if ('' != $size_value_arr[0]) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMSIZE . ':&nbsp;' . $viewDownloads->getVar('size');
                ++$champ_sup_vide;
            }
        }
        if (4 == $downloads_field[$i]->getVar('fid')) {
            //plateforme
            if ('' != $viewDownloads->getVar('platform')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMPLATFORM . $viewDownloads->getVar('platform');
                ++$champ_sup_vide;
            }
        }
    } else {
        $view_data = $fielddataHandler->get();
        $criteria  = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $_REQUEST['lid']));
        $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
        $downloadsfielddata = $fielddataHandler->getAll($criteria);
        $contenu            = '';
        foreach (array_keys($downloadsfielddata) as $j) {
            $contenu = $downloadsfielddata[$j]->getVar('data', 'n');
        }
        if ('' != $contenu) {
            $champ_sup = '&nbsp;' . $downloads_field[$i]->getVar('title') . ':&nbsp;' . $contenu;
            ++$champ_sup_vide;
        }
    }
    if ('' != $champ_sup) {
        $xoopsTpl->append('champ_sup', [
            'image' => $uploadurl_field . $downloads_field[$i]->getVar('img'),
            'data'  => $champ_sup,
        ]);
    }
    $champ_sup = '';
}
if ($nb_champ > 0 && $champ_sup_vide > 0) {
    $xoopsTpl->assign('sup_aff', true);
} else {
    $xoopsTpl->assign('sup_aff', false);
}
//permission
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_modif', $perm_modif);
$categories = $utility->getItemIds('tdmdownloads_download', $moduleDirName);
$item       = $utility->getItemIds('tdmdownloads_download_item', $moduleDirName);
if (1 == $helper->getConfig('permission_download')) {
    if (!in_array($viewDownloads->getVar('cid'), $categories)) {
        $xoopsTpl->assign('perm_download', false);
    } else {
        $xoopsTpl->assign('perm_download', true);
    }
} else {
    if (!in_array($viewDownloads->getVar('lid'), $item)) {
        $xoopsTpl->assign('perm_download', false);
    } else {
        $xoopsTpl->assign('perm_download', true);
    }
}

// pour utiliser tellafriend.
if ((1 == $helper->getConfig('usetellafriend')) && is_dir('../tellafriend')) {
    $string  = sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename'] . ':  ' . XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?lid=' . $_REQUEST['lid']);
    $subject = sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename']);
    if (false !== mb_strpos($subject, '%')) {
        $subject = rawurldecode($subject);
    }
    if (false !== mb_stripos($string, '%3F')) {
        $string = rawurldecode($string);
    }
    if (preg_match('/(' . preg_quote(XOOPS_URL, '/') . '.*)$/i', $string, $matches)) {
        $target_uri = str_replace('&amp;', '&', $matches[1]);
    } else {
        $target_uri = XOOPS_URL . $_SERVER['REQUEST_URI'];
    }
    $tellafriend_texte = '<a target="_top" href="' . XOOPS_URL . '/modules/tellafriend/index.php?target_uri=' . rawurlencode($target_uri) . '&amp;subject=' . rawurlencode($subject) . '">' . _MD_TDMDOWNLOADS_SINGLEFILE_TELLAFRIEND . '</a>';
} else {
    $tellafriend_texte = '<a target="_top" href="mailto:?subject=' . rawurlencode(sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename'])) . '&amp;body=' . rawurlencode(sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename'])
                                                                                                                                                                                             . ':  '
                                                                                                                                                                                             . XOOPS_URL
                                                                                                                                                                                             . '/modules/'
                                                                                                                                                                                             . $moduleDirName
                                                                                                                                                                                             . '/singlefile.php?lid='
                                                                                                                                                                                             . $_REQUEST['lid']) . '">' . _MD_TDMDOWNLOADS_SINGLEFILE_TELLAFRIEND . '</a>';
}
$xoopsTpl->assign('tellafriend_texte', $tellafriend_texte);

// référencement
// tags
if ((1 == $helper->getConfig('usetag')) && is_dir('../tag')) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($_REQUEST['lid'], 0));
} else {
    $xoopsTpl->assign('tags', false);
}

// titre de la page
$pagetitle = $viewDownloads->getVar('title') . ' - ';
$pagetitle .= $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC', true);
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//version for title
$xoopsTpl->assign('version', $viewDownloads->getVar('version'));
//description
if (false === mb_strpos($description, '[pagebreak]')) {
    $description_short = mb_substr($description, 0, 400);
} else {
    $description_short = mb_substr($description, 0, mb_strpos($description, '[pagebreak]'));
}
$xoTheme->addMeta('meta', 'description', strip_tags($description_short));
//keywords
$keywords = \Xmf\Metagen::generateKeywords($viewDownloads->getVar('description'), 10);
$xoTheme->addMeta('meta', 'keywords', implode(', ', $keywords));
/*$keywords = substr($keywords,0,-1);
$xoTheme->addMeta( 'meta', 'keywords', $keywords);*/
require XOOPS_ROOT_PATH . '/include/comment_view.php';
require XOOPS_ROOT_PATH . '/footer.php';
