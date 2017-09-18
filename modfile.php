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

use Xmf\Request;

require_once __DIR__ . '/header.php';
// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_modfile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$moduleDirName = basename(__DIR__);

$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
$xoopsTpl->assign('mydirname', $moduleDirName);
//On recupere la valeur de l'argument op dans l'URL$
$op = $utilities->cleanVars($_REQUEST, 'op', 'list', 'string');

// redirection si pas de droit pour poster
if (false === $perm_modif) {
    redirect_header('index.php', 2, _NOPERM);
}

$lid = $utilities->cleanVars($_REQUEST, 'lid', 0, 'int');

//information du téléchargement
$view_downloads = $downloadsHandler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if (0 === count($view_downloads) || 0 == $view_downloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //navigation
        $view_categorie = $categoryHandler->get($view_downloads->getVar('cid'));
        $categories     = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
        if (!in_array($view_downloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
        }
        //tableau des catégories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $downloadscat_arr = $categoryHandler->getAll($criteria);
        $mytree           = new Xoopsmodules\tdmdownloads\TDMObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> <a title="' . $view_downloads->getVar('title') . '" href="singlefile.php?lid=' . $view_downloads->getVar('lid') . '">' . $view_downloads->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' - ' . $view_downloads->getVar('title') . ' - ';
        $pagetitle .= $utilities->getPathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' (' . $view_downloads->getVar('title') . ')'));

        //Affichage du formulaire de notation des téléchargements
        $obj  = $downloadsmodHandler->create();
        $form = $obj->getForm($lid, false, $donnee = []);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save':
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $obj            = $downloadsmodHandler->create();
        $erreur         = false;
        $message_erreur = '';
        $donnee         = [];
        $obj->setVar('title', Request::getString('title', '', 'POST')); //$_POST['title']);
        $donnee['title'] = Request::getString('title', '', 'POST'); //$_POST['title'];
        $obj->setVar('cid', Request::getInt('cid', 0, 'POST')); //$_POST['cid']);
        $donnee['cid'] = Request::getInt('cid', 0, 'POST'); //$_POST['cid'];
        $obj->setVar('lid', Request::getInt('lid', 0, 'POST')); //$_POST['lid']);
        $obj->setVar('homepage', Request::getString('homepage', '', 'POST')); //formatURL($_POST["homepage"]));
        $donnee['homepage'] = Request::getString('homepage', '', 'POST'); //formatURL($_POST["homepage"]);
        $obj->setVar('version', Request::getString('version', '', 'POST')); //$_POST["version"]);
        $donnee['version'] = Request::getString('version', '', 'POST'); //$_POST["version"];
        $obj->setVar('size', Request::getString('size', '', 'POST')); //$_POST["size"]);
        $donnee['size']      = Request::getString('size', '', 'POST'); //$_POST["size"];
        $donnee['type_size'] = Request::getString('type_size', '', 'POST'); //$_POST['type_size'];
        if (Request::getString('platform', '', 'POST')) {
            $obj->setVar('platform', implode('|', Request::getString('platform', '', 'POST'))); //$_POST['platform']));
            $donnee['platform'] = implode('|', Request::getString('platform', '', 'POST')); //$_POST["platform"]);
        } else {
            $donnee['platform'] = '';
        }
        $obj->setVar('description', Request::getString('description', '', 'POST')); //$_POST["description"]);
        $donnee['description'] = Request::getString('description', '', 'POST'); //$_POST["description"];
        $obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

        // erreur si la taille du fichier n'est pas un nombre
        if (0 == Request::getString('size', '', 'POST')) {
            if ('0' == Request::getString('size', '', 'POST')
                || '' === Request::getString('size', '', 'POST')) {
                $erreur = false;
            } else {
                $erreur         = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (0 == Request::getInt('cid', 0, 'POST')) {
            $erreur         = true;
            $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
        }

        // erreur si le captcha est faux
        xoops_load('captcha');
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur         = true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (0 == $downloads_field[$i]->getVar('status_def')) {
                $nom_champ          = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);
            // Pour le fichier
            if (isset($_POST['xoops_upload_file'][0])) {
                $uploader = new XoopsMediaUploader($uploaddir_downloads, explode('|', $xoopsModuleConfig['mimetype']), $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($xoopsModuleConfig['newnamedownload']) {
                        $uploader->setPrefix($xoopsModuleConfig['prefixdownloads']);
                    }
                    $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                    if (!$uploader->upload()) {
                        $errors = $uploader->getErrors();
                        redirect_header('javascript:history.go(-1)', 3, $errors);
                    } else {
                        $obj->setVar('url', $uploadurl_downloads . $uploader->getSavedFileName());
                    }
                } else {
                    $obj->setVar('url', $_REQUEST['url']);
                }
            }
            // Pour l'image
            if (isset($_POST['xoops_upload_file'][1])) {
                $uploader_2 = new XoopsMediaUploader($uploaddir_shots, [
                    'image/gif',
                    'image/jpeg',
                    'image/pjpeg',
                    'image/x-png',
                    'image/png'
                ], $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader_2->fetchMedia($_POST['xoops_upload_file'][1])) {
                    $uploader_2->setPrefix('downloads_');
                    $uploader_2->fetchMedia($_POST['xoops_upload_file'][1]);
                    if (!$uploader_2->upload()) {
                        $errors = $uploader_2->getErrors();
                        redirect_header('javascript:history.go(-1)', 3, $errors);
                    } else {
                        $obj->setVar('logourl', $uploader_2->getSavedFileName());
                    }
                } else {
                    $obj->setVar('logourl', $_REQUEST['logo_img']);
                }
            }

            if ($downloadsmodHandler->insert($obj)) {
                $lidDownloads = $obj->getNewEnreg($db);
                // Récupération des champs supplémentaires:
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $downloadsfieldHandler->getAll($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if (0 == $downloads_field[$i]->getVar('status_def')) {
                        $objdata   = $downloadsfieldmoddataHandler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('moddata', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lidDownloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $downloadsfieldmoddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                    }
                }
                $tags                      = [];
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/modified.php';
                $notificationHandler       = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('singlefile.php?lid=' . (int)$_REQUEST['lid'], 1, _MD_TDMDOWNLOADS_MODFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form = $obj->getForm((int)$_REQUEST['lid'], true, $donnee);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
