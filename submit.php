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
/** @var Tdmdownloads\Helper $helper */
$helper = Tdmdownloads\Helper::getInstance();

require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);

// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_submit.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
$xoopsTpl->assign('mydirname', $moduleDirName);
//On recupere la valeur de l'argument op dans l'URL$
$op = $utilities->cleanVars($_REQUEST, 'op', 'list', 'string');

// redirection si pas de droit pour poster
if (false === $perm_submit) {
    redirect_header('index.php', 2, _NOPERM);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //navigation
        $navigation = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER;
        $xoopsTpl->assign('navigation', $navigation);
        // r�f�rencement
        // titre de la page
        $titre = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SUBMIT_PROPOSER));

        //Affichage du formulaire de notation des t�l�chargements
        $obj  = $downloadsHandler->create();
        $form = $obj->getForm($donnee = [], false);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save_downloads':
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $obj            = $downloadsHandler->create();
        $erreur         = false;
        $message_erreur = '';
        $donnee         = [];
        $obj->setVar('title',  Request::getString('type_size','', 'POST')$_POST['title']);
        $donnee['title'] =  Request::getString('type_size','', 'POST')$_POST['title'];
        $obj->setVar('cid',  Request::getString('type_size','', 'POST')$_POST['cid']);
        $donnee['cid'] =  Request::getString('type_size','', 'POST')$_POST['cid'];
        $obj->setVar('homepage', formatURL( Request::getString('type_size','', 'POST')$_POST['homepage']));
        $obj->setVar('version',  Request::getString('type_size','', 'POST')$_POST['version']);
        $obj->setVar('size',  Request::getString('type_size','', 'POST')$_POST['size']);
        $donnee['type_size'] =  Request::getString('type_size','', 'POST')$_POST['type_size'];
        $obj->setVar('paypal',  Request::getString('type_size','', 'POST')$_POST['paypal']);
        if (isset($_POST['platform'])) {
            $obj->setVar('platform', implode('|',  Request::getString('type_size','', 'POST')$_POST['platform']));
        }
        $obj->setVar('description',  Request::getString('type_size','', 'POST')$_POST['description']);
        if (isset($_POST['submitter'])) {
            $obj->setVar('submitter',  Request::getString('type_size','', 'POST')$_POST['submitter']);
            $donnee['submitter'] =  Request::getString('type_size','', 'POST')$_POST['submitter'];
        } else {
            $obj->setVar('submitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
            $donnee['submitter'] = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        }
        $obj->setVar('date', time());
        if (true === $perm_autoapprove) {
            $obj->setVar('status', 1);
        } else {
            $obj->setVar('status', 0);
        }
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                if (isset($_POST['status'])) {
                    $obj->setVar('status', $_POST['status']);
                    $donnee['status'] = $_POST['status'];
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            }
        }
        $donnee['date_update'] = 0;
        // erreur si la taille du fichier n'est pas un nombre
        if (0 === (int)$_REQUEST['size']) {
            if ('0' === $_REQUEST['size'] || '' === $_REQUEST['size']) {
                $erreur = false;
            } else {
                $erreur         = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la cat�gorie est vide
        if (isset($_REQUEST['cid'])) {
            if (0 == $_REQUEST['cid']) {
                $erreur         = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load('captcha');
        $xoopsCaptcha = \XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur         = true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (0 === $downloads_field[$i]->getVar('status_def')) {
                $nom_champ          = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        // enregistrement temporaire des tags
        if ((1 === $helper->getConfig('usetag')) && is_dir('../tag')) {
            $donnee['TAG'] = $_POST['tag'];
        }
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);
            // Pour le fichier
            if (isset($_POST['xoops_upload_file'][0])) {
                $uploader = new \XoopsMediaUploader($uploaddir_downloads, explode('|', $helper->getConfig('mimetype')), $helper->getConfig('maxuploadsize'), null, null);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($helper->getConfig('newnamedownload')) {
                        $uploader->setPrefix($helper->getConfig('prefixdownloads'));
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
                $uploader_2 = new \XoopsMediaUploader($uploaddir_shots, [
                    'image/gif',
                    'image/jpeg',
                    'image/pjpeg',
                    'image/x-png',
                    'image/png'
                ], $helper->getConfig('maxuploadsize'), null, null);
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

            if ($downloadsHandler->insert($obj)) {
                $lidDownloads = $obj->getNewEnreg($db);
                //tags
                if ((1 === $helper->getConfig('usetag')) && is_dir('../tag')) {
                    $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
                    $tagHandler->updateByItem($_POST['tag'], $lidDownloads, $moduleDirName, 0);
                }
                // R�cup�ration des champs suppl�mentaires:
                $criteria = new \CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $fieldHandler->getAll($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if (0 === $downloads_field[$i]->getVar('status_def')) {
                        $objdata   = $fielddataHandler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('data', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lidDownloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $fielddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                    }
                }
                if ($xoopsUser) {
                    if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                        //permission pour t�l�charger
                        if (1 == $helper->getConfig('permission_download')) {
                            $gpermHandler = xoops_getHandler('groupperm');
                            $criteria     = new \CriteriaCompo();
                            $criteria->add(new \Criteria('gperm_itemid', $lidDownloads, '='));
                            $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                            $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_download_item', '='));
                            $gpermHandler->deleteAll($criteria);
                            if (isset($_POST['item_download'])) {
                                foreach ($_POST['item_download'] as $onegroup_id) {
                                    $gpermHandler->addRight('tdmdownloads_download_item', $lidDownloads, $onegroup_id, $xoopsModule->getVar('mid'));
                                }
                            }
                        }
                    }
                }
                $notificationHandler   = xoops_getHandler('notification');
                $tags                  = [];
                $tags['FILE_NAME']     = $donnee['title'];
                $tags['FILE_URL']      = XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . $donnee['cid'] . '&lid=' . $lidDownloads;
                $downloadscat_cat      = $categoryHandler->get($donnee['cid']);
                $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
                $tags['CATEGORY_URL']  = XOOPS_URL . "/modules/$moduleDirName/viewcat.php?cid=" . $donnee['cid'];

                if (true === $perm_autoapprove) {
                    $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
                    $notificationHandler->triggerEvent('category', $donnee['cid'], 'new_file', $tags);
                    redirect_header('index.php', 2, _MD_TDMDOWNLOADS_SUBMIT_RECEIVED . '<br>' . _MD_TDMDOWNLOADS_SUBMIT_ISAPPROVED . '');
                    exit;
                } else {
                    $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/index.php?op=listNewDownloads';
                    $notificationHandler->triggerEvent('global', 0, 'file_submit', $tags);
                    $notificationHandler->triggerEvent('category', $donnee['cid'], 'file_submit', $tags);
                    redirect_header('index.php', 2, _MD_TDMDOWNLOADS_SUBMIT_RECEIVED);
                    exit;
                }
            }
            echo $obj->getHtmlErrors();
        }
        $form = $obj->getForm($donnee, true);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
