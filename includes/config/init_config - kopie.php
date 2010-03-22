<?php
/**
%%%copyright%%%
 *                            %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *    Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *    phpMyTicket - ticket reservation system
 *    Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the "GNU General
 * Public License" version 3 as published by the Free Software Foundation and appearing
 * in the file LICENSE included in the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING THE WARRANTY
 * OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
 *
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence
 * does NOT remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't clear to you.
*/

// The following settings are automatic filled by the installation procedure:

global $_SHOP;

define("CURRENT_VERSION","Pre-Beta 6.2");

$_SHOP->db_host = 'localhost';
$_SHOP->db_name = 'thempie';
$_SHOP->db_uname = 'root';
$_SHOP->db_pass = '';
$_SHOP->mail_smtp_host = 'smpt.xs4all.nl';
$_SHOP->mail_smtp_port = '25';
$_SHOP->mail_smtp_user = 'lumensof';
$_SHOP->mail_smtp_pass = 'Np1215At';
$_SHOP->secure_id = '53100ab5da4b9eeafcde315eed08c0b794223783';
$_SHOP->secure_site = '1';
$_SHOP->mail_smtp_security = '';
$_SHOP->root = 'http://localhost/beta6/';
$_SHOP->root_secured = 'http://localhost/beta6/';
$_SHOP->software_updater_enabled = True;
$_SHOP->test['*EUR'] = '&euro;';

?>