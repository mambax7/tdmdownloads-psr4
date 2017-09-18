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

//require dirname(__DIR__) . '/include/setup.php';
require_once __DIR__ . '/admin_header.php';

//On recupere la valeur de l'argument op dans l'URL$
$op = $utilities->cleanVars($_REQUEST, 'op', 'list', 'string');
//$downloadsfieldHandler = new Xoopsmodules\tdmdownloads\FieldHandler($db);
//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $field_admin = \Xmf\Module\Admin::getInstance();
        echo $field_admin->displayNavigation(basename(__FILE__));
        $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
        $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
        echo $field_admin->displayButton('left');
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfieldHandler->getAll($criteria);
        $numrows         = count($downloads_field);
        //Affichage du tableau
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="left">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMIMAGE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMWEIGHT . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMAFFICHE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMAFFICHESEARCH . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($downloads_field) as $i) {
                $downloadsfield_fid = $downloads_field[$i]->getVar('fid');
                echo '<tr class="' . $class . '">';
                echo '<td align="left">' . $downloads_field[$i]->getVar('title') . '</a></td>';
                echo '<td align="center" width="10%">';
                echo '<img src="' . $uploadurl_field . $downloads_field[$i]->getVar('img') . '" alt="" title="" height="16">';
                echo '</td>';
                echo '<td align="center" width="10%">' . $downloads_field[$i]->getVar('weight') . '</td>';

                echo '<td align="center" width="10%"><a href="field.php?op=update_status&fid=' . $downloadsfield_fid . '&aff=' . (1 == $downloads_field[$i]->getVar('status') ? '0"><img src="../assets/images/icon/on.png"></a>' : '1"><img src="../assets/images/icon/off.png"></a>') . '</td>';
                echo '<td align="center" width="10%"><a href="field.php?op=update_search&fid=' . $downloadsfield_fid . '&aff=' . (1 == $downloads_field[$i]->getVar('search') ? '0"><img src="../assets/images/icon/on.png"></a>' : '1"><img src="../assets/images/icon/off.png"></a>') . '</td>';
                echo '<td align="center" width="10%">';
                echo '<a href="field.php?op=edit_field&fid=' . $downloadsfield_fid . '"><img src="../assets/images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT . '" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> ';
                if (0 == $downloads_field[$i]->getVar('status_def')) {
                    echo '<a href="field.php?op=del_field&fid=' . $downloadsfield_fid . '"><img src="../assets/images/icon/delete.png" alt="' . _AM_TDMDOWNLOADS_FORMDEL . '" title="' . _AM_TDMDOWNLOADS_FORMDEL . '"></a>';
                }
                echo '</td>';
                echo '</tr>';
                $class = ('even' === $class) ? 'odd' : 'even';
            }
            echo '</table>';
        }
        break;

    case 'update_status':
        $obj = $downloadsfieldHandler->get($_REQUEST['fid']);

        $obj->setVar('status', $_REQUEST['aff']);
        if ($downloadsfieldHandler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
        break;

    case 'update_search':
        $obj = $downloadsfieldHandler->get($_REQUEST['fid']);

        $obj->setVar('search', $_REQUEST['aff']);
        if ($downloadsfieldHandler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
        break;
    //

    // vue création
    case 'new_field':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $field_admin = \Xmf\Module\Admin::getInstance();
        echo $field_admin->displayNavigation(basename(__FILE__));
        $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
        echo $field_admin->displayButton('left');

        //Affichage du formulaire de création des champs
        $obj  = $downloadsfieldHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    // Pour éditer un champ
    case 'edit_field':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $field_admin = \Xmf\Module\Admin::getInstance();
        echo $field_admin->displayNavigation(basename(__FILE__));
        $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
        $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
        echo $field_admin->displayButton('left');

        //Affichage du formulaire de création des champs
        $obj  = $downloadsfieldHandler->get($_REQUEST['fid']);
        $form = $obj->getForm();
        $form->display();
        break;

    // Pour supprimer un champ
    case 'del_field':
        global $xoopsModule;
        $obj = $downloadsfieldHandler->get($_REQUEST['fid']);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des entrée du champ
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('fid', $_REQUEST['fid']));
            $downloads_arr = $downloadsfielddataHandler->getAll($criteria);
            foreach (array_keys($downloads_arr) as $i) {
                // supression de l'entrée
                $objdownloadsfielddata = $downloadsfielddataHandler->get($downloads_arr[$i]->getVar('iddata'));
                $downloadsfielddataHandler->delete($objdownloadsfielddata) || $objdownloads->getHtmlErrors();
            }
            if ($downloadsfieldHandler->delete($obj)) {
                redirect_header('field.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            $downloadsfield = $downloadsfieldHandler->get($_REQUEST['fid']);
            if (1 == $downloadsfield->getVar('status_def')) {
                redirect_header('field.php', 2, _AM_TDMDOWNLOADS_REDIRECT_NODELFIELD);
            }
            $message  = '';
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('fid', $_REQUEST['fid']));
            $downloads_arr = $downloadsfielddataHandler->getAll($criteria);
            if (count($downloads_arr) > 0) {
                $message .= _AM_TDMDOWNLOADS_DELDATA . '<br>';
                foreach (array_keys($downloads_arr) as $i) {
                    $message .= '<span style="color: Red;">' . $downloads_arr[$i]->getVar('data') . '</span><br>';
                }
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $field_admin = \Xmf\Module\Admin::getInstance();
            $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
            $field_admin->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
            echo $field_admin->displayButton('left');

            xoops_confirm(['ok' => 1, 'fid' => $_REQUEST['fid'], 'op' => 'del_field'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('title')) . '<br><br>' . $message);
        }

        break;

    // Pour sauver un champ
    case 'save_field':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['fid'])) {
            $obj = $downloadsfieldHandler->get($_REQUEST['fid']);
        } else {
            $obj = $downloadsfieldHandler->create();
        }
        $erreur         = false;
        $message_erreur = '';
        // Récupération des variables:
        // Pour l'image
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new XoopsMediaUploader($uploaddir_field, [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/x-png',
            'image/png'
        ], $xoopsModuleConfig['maxuploadsize'], 16, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('downloads_');
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('img', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('img', $_REQUEST['downloadsfield_img']);
        }
        // Pour les autres variables
        $obj->setVar('title', $_POST['title']);
        $obj->setVar('weight', $_POST['weight']);
        $obj->setVar('status', $_POST['status']);
        $obj->setVar('search', $_POST['search']);
        $obj->setVar('status_def', $_POST['status_def']);

        if (0 === (int)$_REQUEST['weight'] && '0' !== $_REQUEST['weight']) {
            $erreur         = true;
            $message_erreur = _AM_TDMDOWNLOADS_ERREUR_WEIGHT . '<br>';
        }
        if (true === $erreur) {
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
        } else {
            if ($downloadsfieldHandler->insert($obj)) {
                redirect_header('field.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form = $obj->getForm();
        $form->display();
        break;
}
//Affichage de la partie basse de l'administration de Xoops
require_once __DIR__ . '/admin_footer.php';
