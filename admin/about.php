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

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

// pour file protection
$xoopsUrl       = parse_url(XOOPS_URL);
$xoopsUrl       = str_replace('www.', '', $xoopsUrl['host']);
$fileProtection = _AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO1 . '<br /><br />' . XOOPS_ROOT_PATH . '/uploads/TDMDownloads/downloads/' . '<br /><br />' . _AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO2 . '<br /><br />';
$fileProtection .= 'RewriteEngine on' . '<br />' . 'RewriteCond %{HTTP_REFERER} !' . $xoopsUrl . "/.*$ [NC]<br />ReWriteRule \.*$ - [F]";

$adminObject = \Xmf\Module\Admin::getInstance();

$adminObject->addInfoBox(_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION);
$adminObject->addInfoBoxLine(sprintf($fileProtection, '', '', 'information'), '');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->setPaypal('gregory.mage@gmail.com');
$adminObject->displayAbout(false);

require_once __DIR__ . '/admin_footer.php';
