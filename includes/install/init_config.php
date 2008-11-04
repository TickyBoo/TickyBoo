<?php
/**
%%%copyright%%%
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

  global $_SHOP;

  //directory where SHOP installed
  $_SHOP->install_dir="%install_dir_esc%";

  //url root, at least "/"
  $_SHOP->root="%root_url%";

  //SHOP database config
  $_SHOP->db_name="%DB_Database%";
  $_SHOP->db_uname="%DB_Username%";
  $_SHOP->db_host="%DB_Hostname%";
  $_SHOP->db_pass="%DB_Password%";
  
  // secured (https) shop root
  $_SHOP->root_secured = $_SHOP->root;
?>