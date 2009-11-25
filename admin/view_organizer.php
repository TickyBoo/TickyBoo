<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 */



define('ft_check','admin');
require_once("../includes/config/init_admin.php");
require_once ("admin/MenuAdmin.php");
require_once ("admin/AdminPage.php");
require_once ("classes/AUIBico.php");
require_once ("admin/OrganizerView.php");



//print cart update
$body=new OrganizerView();
// width=200 for menu ...Change it to your preferd width;
// 700 total table
$page=new AdminPage(800);
$page->setTitle("Administration");

$bico=new AUIBico(200,800);

$page->set("body",$bico);

$bico->setmenu(new MenuAdmin());
$bico->setbody($body);

$page->draw();
?>