<?php
/*
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 */

require_once("../includes/config/init_admin.php");

require_once ("admin/MenuAdmin.php");
require_once ("admin/AdminPage.php");
require_once ("classes/AUIBico.php");

// ($_SERVER["PATH_INFO"] == '/event')
    require_once ("admin/EventPropsView.php");
    $body = new EventPropsView(700);

// width=200 for menu ...Change it to your preferd width;
// 700 total table
$bico = new AUIBico(150, 800);
$bico->set("menu", new MenuAdmin());
$bico->set("body", $body);

$page = new AdminPage(600);
$page->setWidth(800);
$body->setWidth(600);
$page->setTitle("Administration");
$page->set("body", $bico);
$page->draw();

?>
