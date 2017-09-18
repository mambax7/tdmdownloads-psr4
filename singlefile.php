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

//spl_autoload_extensions(".php");
//spl_autoload_register();

//use Xoops\Tdmdownloads as xoops2;
//use Xoops\Tdmdownloads\Tdmdownloads as xoopsObj;
//use Xoops\Tdmdownloads;
//use bingo\Tdmdownloads as Bingo;

require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
//file has to be loaded, either directly, via "xoops_load", or via Autoload.
//xoops_load("constants", $moduleDirName );

//require_once __DIR__ . '/class/autoloader.php';
//$loader = new  Xoops\Tdmdownloads\Loader;
//$loader->register();
//$loader->addPrefix('Xoops\Tdmdownloads', __DIR__ . '/class');

//$mydirname3 = xoops2\Tdmdownloads::mydirname();

//$mydirname3b = xoopsObj::mydirname();

//$broken = new Xoopsmodules\tdmdownloads\Broken();

//$tt = new xoops2\Tdmdownloads;
//$tt = new xoopsObj;

//OK
$tt         = new Xoopsmodules\tdmdownloads\Tdmdownloads();
$mydirname4 = $tt::$mydirname2; //set in constructor

//OK
//$mydirname4 = (new Xoops\Tdmdownloads\Tdmdownloads)->mydirname(); //calling a function

//OK
//use Xoops\Tdmdownloads\Tdmdownloads as xoopsObj;
//$mydirname4 = xoopsObj::mydirname();
//echo xoopsObj::myFunction2(); // displays 'this is a XOOPS class/function2.

//OK
//use Xoops\Tdmdownloads\Tdmdownloads;
//$mydirname4 = Xoops\Tdmdownloads\Tdmdownloads::mydirname();
//echo Xoops\Tdmdownloads\Tdmdownloads::myFunction().'<br>'; // displays 'this is a xoops class..
//echo Xoops\Tdmdownloads\Tdmdownloads::myFunction2(); // displays 'this is a XOOPS class/function2. STATIC

// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_singlefile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
$xoopsTpl->assign('mydirname', $mydirname4);
$lid = $utilities->cleanVars($_REQUEST, 'lid', 0, 'int');

//information du t�l�chargement
//$downloadsHandler             = new Xoopsmodules\tdmdownloads\DownloadsHandler($db);
$view_downloads = $downloadsHandler->get($lid);

// redirection si le t�l�chargement n'existe pas ou n'est pas activ�
if (0 === count($view_downloads) || 0 == $view_downloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

// pour les permissions
$categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}

//tableau des cat�gories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
//$categoryHandler          = new Xoopsmodules\tdmdownloads\CategoryHandler($db);
$downloadscat_arr = $categoryHandler->getAll($criteria);
$mytree           = new Tdmobjecttree($downloadscat_arr, 'cat_cid', 'cat_pid');

//navigation
$navigation = $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
$navigation = $navigation . ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ' . $view_downloads->getVar('title');
$xoopsTpl->assign('navigation', $navigation);

// sortie des informations
//Utilisation d'une copie d'�cran avec la largeur selon les pr�f�rences
if (1 == $xoopsModuleConfig['useshots']) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('img_float', $xoopsModuleConfig['img_float']);
}

if ('ltr' === $xoopsModuleConfig['download_float']) {
    $xoopsTpl->assign('textfloat', 'floatleft');
    $xoopsTpl->assign('infofloat', 'floatright');
} else {
    $xoopsTpl->assign('textfloat', 'floatright');
    $xoopsTpl->assign('infofloat', 'floatleft');
}

// sortie des informations
if ('blank.gif' === $view_downloads->getVar('logourl')) {
    $logourl = '';
} else {
    $logourl = $view_downloads->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
}
// D�fini si la personne est un admin
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
                 . '" /></a>';
}

$description = $view_downloads->getVar('description');
$xoopsTpl->assign('description', str_replace('[pagebreak]', '', $description));
$xoopsTpl->assign('lid', $lid);
$xoopsTpl->assign('cid', $view_downloads->getVar('cid'));
$xoopsTpl->assign('logourl', $logourl);
// pour les vignettes "new" et "mis � jour"
$new = $utilities->getStatusImage($view_downloads->getVar('date'), $view_downloads->getVar('status'));
$pop = $utilities->getPopularImage($view_downloads->getVar('hits'));
$xoopsTpl->assign('title', $view_downloads->getVar('title'));
$xoopsTpl->assign('new', $new);
$xoopsTpl->assign('pop', $pop);
$xoopsTpl->assign('adminlink', $adminlink);
$xoopsTpl->assign('date', formatTimestamp($view_downloads->getVar('date'), 's'));
$xoopsTpl->assign('author', XoopsUser::getUnameFromId($view_downloads->getVar('submitter')));
$xoopsTpl->assign('hits', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_NBTELECH, $view_downloads->getVar('hits')));
$xoopsTpl->assign('rating', number_format($view_downloads->getVar('rating'), 1));
$xoopsTpl->assign('votes', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_VOTES, $view_downloads->getVar('votes')));
$xoopsTpl->assign('nb_comments', sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_COMMENTS, $view_downloads->getVar('comments')));
$xoopsTpl->assign('show_bookmark', $xoopsModuleConfig['show_bookmark']);
$xoopsTpl->assign('show_social', $xoopsModuleConfig['show_social']);

//paypal
$paypal = false;
if (true === $xoopsModuleConfig['use_paypal'] && '' !== $view_downloads->getVar('paypal')) {
    $paypal = '<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="' . $view_downloads->getVar('paypal') . '">
    <input type="hidden" name="item_name" value="' . sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_PAYPAL, $view_downloads->getVar('title')) . ' (' . XoopsUser::getUnameFromId(!empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0) . ')">
    <input type="hidden" name="currency_code" value="' . $xoopsModuleConfig['currency_paypal'] . '">
    <input type="image" src="' . $xoopsModuleConfig['image_paypal'] . '" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
    </form>';
}
$xoopsTpl->assign('paypal', $paypal);

// pour les champs suppl�mentaires
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('status', 1));
//$downloadsfieldHandler        = new Xoopsmodules\tdmdownloads\FieldHandler($db);
$downloads_field = $downloadsfieldHandler->getAll($criteria);
$nb_champ        = count($downloads_field);
$champ_sup       = '';
$champ_sup_vide  = 0;
foreach (array_keys($downloads_field) as $i) {
    if (1 == $downloads_field[$i]->getVar('status_def')) {
        if (1 == $downloads_field[$i]->getVar('fid')) {
            //page d'accueil
            if ('' != $view_downloads->getVar('homepage')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . ':&nbsp;<a href="' . $view_downloads->getVar('homepage') . '">' . _MD_TDMDOWNLOADS_SINGLEFILE_ICI . '</a>';
                ++$champ_sup_vide;
            }
        }
        if (2 == $downloads_field[$i]->getVar('fid')) {
            //version
            if ('' != $view_downloads->getVar('version')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMVERSION . ':&nbsp;' . $view_downloads->getVar('version');
                ++$champ_sup_vide;
            }
        }
        if (3 == $downloads_field[$i]->getVar('fid')) {
            //taille du fichier
            $size_value_arr = explode(' ', $view_downloads->getVar('size'));
            if ('' != $size_value_arr[0]) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMSIZE . ':&nbsp;' . $view_downloads->getVar('size');
                ++$champ_sup_vide;
            }
        }
        if (4 == $downloads_field[$i]->getVar('fid')) {
            //plateforme
            if ('' != $view_downloads->getVar('platform')) {
                $champ_sup = '&nbsp;' . _AM_TDMDOWNLOADS_FORMPLATFORM . $view_downloads->getVar('platform');
                ++$champ_sup_vide;
            }
        }
    } else {
        //        $downloadsfielddataHandler    = new Xoopsmodules\tdmdownloads\FielddataHandler($db);
        $view_data = $downloadsfielddataHandler->get();
        $criteria  = new CriteriaCompo();
        $criteria->add(new Criteria('lid', $_REQUEST['lid']));
        $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
        $downloadsfielddata = $downloadsfielddataHandler->getAll($criteria);
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
            'data'  => $champ_sup
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
$categories = $utilities->getItemIds('tdmdownloads_download', $moduleDirName);
$item       = $utilities->getItemIds('tdmdownloads_download_item', $moduleDirName);
if (1 == $xoopsModuleConfig['permission_download']) {
    if (!in_array($view_downloads->getVar('cid'), $categories)) {
        $xoopsTpl->assign('perm_download', false);
    } else {
        $xoopsTpl->assign('perm_download', true);
    }
} else {
    if (!in_array($view_downloads->getVar('lid'), $item)) {
        $xoopsTpl->assign('perm_download', false);
    } else {
        $xoopsTpl->assign('perm_download', true);
    }
}

// pour utiliser tellafriend.
if ((1 == $xoopsModuleConfig['usetellafriend']) && is_dir('../tellafriend')) {
    $string  = sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename'] . ':  ' . XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?lid=' . $_REQUEST['lid']);
    $subject = sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_INTFILEFOUND, $xoopsConfig['sitename']);
    if (false !== stripos($subject, '%')) {
        $subject = rawurldecode($subject);
    }
    if (false !== stripos($string, '%3F')) {
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

// r�f�rencement
// tags
if ((1 == $xoopsModuleConfig['usetag']) && is_dir('../tag')) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($_REQUEST['lid'], 0));
} else {
    $xoopsTpl->assign('tags', false);
}

// titre de la page
$pagetitle = $view_downloads->getVar('title') . ' - ';
$pagetitle .= $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//version for title
$xoopsTpl->assign('version', $view_downloads->getVar('version'));
//description
if (false === strpos($description, '[pagebreak]')) {
    $description_short = substr($description, 0, 400);
} else {
    $description_short = substr($description, 0, strpos($description, '[pagebreak]'));
}
$xoTheme->addMeta('meta', 'description', strip_tags($description_short));
//keywords
/*$keywords = substr($keywords,0,-1);
$xoTheme->addMeta( 'meta', 'keywords', $keywords);*/
include XOOPS_ROOT_PATH . '/include/comment_view.php';
include XOOPS_ROOT_PATH . '/footer.php';
