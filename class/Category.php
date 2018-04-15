<?php namespace XoopsModules\Tdmdownloads;

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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
class Category extends \XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('cat_cid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('cat_pid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_imgurl', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_description_main', XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT, 0, false, 11);
    }

    /**
     * @param null $db
     * @return mixed
     */
    public function getNewEnreg($db = null)
    {
        /** @var \XoopsMySQLDatabase $db */
        $newEnreg = $db->getInsertId();

        return $newEnreg;
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        /** @var Tdmdownloads\Helper $helper */
        $helper = Tdmdownloads\Helper::getInstance();

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $moduleDirName = basename(dirname(__DIR__));
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? sprintf(_AM_TDMDOWNLOADS_FORMADD) : sprintf(_AM_TDMDOWNLOADS_FORMEDIT);

        //création du formulaire
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'cat_title', 50, 255, $this->getVar('cat_title')), true);
        //editeur
        $editor_configs           = [];
        $editor_configs['name']   = 'cat_description_main';
        $editor_configs['value']  = $this->getVar('cat_description_main', 'e');
        $editor_configs['rows']   = 20;
        $editor_configs['cols']   = 160;
        $editor_configs['width']  = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $helper->getConfig('editor');
        $form->addElement(new \XoopsFormEditor(_AM_TDMDOWNLOADS_FORMTEXT, 'cat_description_main', $editor_configs), false);
        //image
        $downloadscat_img = $this->getVar('cat_imgurl') ?: 'blank.gif';
        $uploadirectory   = '/uploads/' . $moduleDirName . '/images/cats';
        $imgtray          = new \XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG, '<br>');
        $imgpath          = sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory);
        $imageselect      = new \XoopsFormSelect($imgpath, 'downloadscat_img', $downloadscat_img);
        $topics_array     = \XoopsLists:: getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
        foreach ($topics_array as $image) {
            $imageselect->addOption((string)$image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"downloadscat_img\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $downloadscat_img . "' name='image3' id='image3' alt='' />"));
        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
        $fileseltray->addElement(new \XoopsFormLabel(''), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        // Pour faire une sous-catégorie
        $categoryHandler = Tdmdownloads\Helper::getInstance()->getHandler('Category');//  xoops_getModuleHandler('Category', $moduleDirName);
        $criteria        = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $downloadscatArray = $categoryHandler->getAll($criteria);
        $mytree           = new TdmObjectTree($downloadscatArray, 'cat_cid', 'cat_pid');
        //$form->addElement(new \XoopsFormLabel(_AM_TDMDOWNLOADS_FORMINCAT, $mytree->makeSelBox('cat_pid', 'cat_title', '--', $this->getVar('cat_pid'), true)));
        $form->addElement($mytree->makeSelectElement('cat_pid', 'cat_title', '--', $this->getVar('cat_pid'), true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);
        //poids de la catégorie
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMWEIGHT, 'cat_weight', 5, 5, $this->getVar('cat_weight', 'e')), false);

        //permissions
        $memberHandler = xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();
        $grouppermHandler  = xoops_getHandler('groupperm');
        $full_list     = array_keys($group_list);
        global $xoopsModule;
        if (!$this->isNew()) {
            $groups_ids_view                   = $grouppermHandler->getGroupIds('tdmdownloads_view', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_submit                 = $grouppermHandler->getGroupIds('tdmdownloads_submit', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_download               = $grouppermHandler->getGroupIds('tdmdownloads_download', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_view                   = array_values($groups_ids_view);
            $groups_news_can_view_checkbox     = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_VIEW_DSC, 'groups_view[]', $groups_ids_view);
            $groups_ids_submit                 = array_values($groups_ids_submit);
            $groups_news_can_submit_checkbox   = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC, 'groups_submit[]', $groups_ids_submit);
            $groups_ids_download               = array_values($groups_ids_download);
            $groups_news_can_download_checkbox = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC, 'groups_download[]', $groups_ids_download);
        } else {
            $groups_news_can_view_checkbox     = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_VIEW_DSC, 'groups_view[]', $full_list);
            $groups_news_can_submit_checkbox   = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC, 'groups_submit[]', $full_list);
            $groups_news_can_download_checkbox = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC, 'groups_download[]', $full_list);
        }
        // pour voir
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_view_checkbox);
        // pour editer
        $groups_news_can_submit_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_submit_checkbox);
        // pour télécharger
        if (1 == $helper->getConfig('permission_download')) {
            $groups_news_can_download_checkbox->addOptionArray($group_list);
            $form->addElement($groups_news_can_download_checkbox);
        }

        // pour passer "cid" si on modifie la catégorie
        if (!$this->isNew()) {
            $form->addElement(new \XoopsFormHidden('cat_cid', $this->getVar('cat_cid')));
            $form->addElement(new \XoopsFormHidden('categorie_modified', true));
        }
        //pour enregistrer le formulaire
        $form->addElement(new \XoopsFormHidden('op', 'save_cat'));
        //boutton d'envoi du formulaire
        $form->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

/**
 * Class TDMDownloadsCategoryHandler
 */
//class TDMDownloadsCategoryHandler extends \XoopsPersistableObjectHandler
//{
//    /**
//     * @param null|object $db
//     */
//    public function __construct(\XoopsDatabase $db)
//    {
//        parent::__construct($db, "tdmdownloads_cat", 'Category', 'cat_cid', 'cat_title');
//    }
//}
