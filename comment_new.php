<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <https://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include __DIR__ . '/header.php';
$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    // Get file title
    //-------------------------------
    //    $sql    = 'SELECT title, cid FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid=' . $com_itemid;
    //    $result = $xoopsDB->query($sql);
    //    if ($result) {
    //        $categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
    //        $row        = $xoopsDB->fetchArray($result);
    //        if (!in_array($row['cid'], $categories)) {
    //            redirect_header(XOOPS_URL, 2, _NOPERM);
    //        }
    //        $com_replytitle = $row['title'];
    //        include XOOPS_ROOT_PATH . '/include/comment_new.php';
    //    }
    //-------------------------------
    $sql = 'SELECT title, cid FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid=?';

    $conn = $xoopsDB->conn;
    $stmt = $conn->prepare($sql);
    if (false === $stmt) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
    }

    $lid = $com_itemid;

    /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
    $stmt->bind_param('is', $lid);

    /* Execute statement */
    $stmt->execute();

    /* Fetch result to array */
    $result = $stmt->get_result();  //$result = $xoopsDB->query($sql);

    $sql2    = 'SELECT title, cid FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid=' . $com_itemid;
    $result2 = $xoopsDB->query($sql2);
    $row2    = $xoopsDB->fetchArray($result);

    //    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    //        array_push($a_data, $row);
    //    }

    $categories = $utilities->getItemIds('tdmdownloads_view', $moduleDirName);
    $row        = $xoopsDB->fetchArray($result);
    if (!in_array($row['cid'], $categories)) {
        redirect_header(XOOPS_URL, 2, _NOPERM);
    }
    $com_replytitle = $row['title'];
    include XOOPS_ROOT_PATH . '/include/comment_new.php';
}
