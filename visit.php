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

error_reporting(0);
include __DIR__ . '/header.php';

$lid = $utilities->cleanVars($_REQUEST, 'lid', 0, 'int');
$cid = $utilities->cleanVars($_REQUEST, 'cid', 0, 'int');
// redirection si le t�l�chargement n'existe pas
$view_downloads = $downloadsHandler->get($lid);
if (0 === count($view_downloads)) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}
//redirection si pas de permission (cat)
$categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}
//redirection si pas de permission (t�l�charger)
if (2 == $helper->getConfig('permission_download')) {
    $item = $utilities->getItemIds('tdmdownloads_download_item', $moduleDirName);
    if (!in_array($view_downloads->getVar('lid'), $item)) {
        redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
    }
} else {
    $categories = $utilities->getItemIds('tdmdownloads_download', $moduleDirName);
    if (!in_array($view_downloads->getVar('cid'), $categories)) {
        redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
    }
}
//check download limit option
if (1 == $helper->getConfig('downlimit')) {
    $limitlid    = $helper->getConfig('limitlid');
    $limitglobal = $helper->getConfig('limitglobal');
    $yesterday   = strtotime(formatTimestamp(time() - 86400));
    if ($limitlid > 0) {
        $criteria = new \CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new \Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new \Criteria('downlimit_hostname', getenv('REMOTE_ADDR'), '='));
        }
        $criteria->add(new \Criteria('downlimit_lid', $lid, '='));
        $criteria->add(new \Criteria('downlimit_date', $yesterday, '>'));
        $numrows = $downloadslimitHandler->getCount($criteria);
        if ($numrows >= $limitlid) {
            redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITLID, $numrows, $limitlid));
        }
    }
    if ($limitglobal > 0) {
        $criteria = new \CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new \Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new \Criteria('downlimit_hostname', getenv('REMOTE_ADDR'), '='));
        }
        $criteria->add(new \Criteria('downlimit_date', $yesterday, '>'));
        $numrows = $downloadslimitHandler->getCount($criteria);
        if ($numrows >= $limitglobal) {
            redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITGLOBAL, $numrows, $limitglobal));
        }
    }

    $obj = $downloadslimitHandler->create();
    $obj->setVar('downlimit_lid', $lid);
    $obj->setVar('downlimit_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
    $obj->setVar('downlimit_hostname', getenv('REMOTE_ADDR'));
    $obj->setVar('downlimit_date', strtotime(formatTimestamp(time())));
    $downloadslimitHandler->insert($obj) || $obj->getHtmlErrors();
    // purge
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('downlimit_date', time() - 172800, '<'));
    $numrows = $downloadslimitHandler->getCount($criteria);
    echo 'a d�truire: ' . $numrows . '<b>';
    $downloadslimitHandler->deleteAll($criteria);
}

@$xoopsLogger->activated = false;
error_reporting(0);
if ($helper->getConfig('check_host')) {
    $goodhost     = 0;
    $referer      = parse_url(xoops_getenv('HTTP_REFERER'));
    $referer_host = $referer['host'];
    foreach ($helper->getConfig('referers') as $ref) {
        if (!empty($ref) && preg_match('/' . $ref . '/i', $referer_host)) {
            $goodhost = '1';
            break;
        }
    }
    if (!$goodhost) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/singlefile.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMDOWNLOADS_NOPERMISETOLINK);
    }
}

// ajout +1 pour les hits
$sql = sprintf('UPDATE %s SET hits = hits+1 WHERE lid = %u AND STATUS > 0', $xoopsDB->prefix('tdmdownloads_downloads'), $lid);
$xoopsDB->queryF($sql);

$url = $view_downloads->getVar('url', 'n');
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    header("Location: $url");
}
echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $url . '"></meta></head><body></body></html>';
exit();
