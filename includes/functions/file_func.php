<?php
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't
 * clear to you.

 */

/*
  * rm -r
  * $in param is for internal use
  */
function file_rmdirr ($dir, $in = true)
{
  if ($in){
    if (!is_dir($dir)){
      return unlink($dir);
    }
  }

  $d = dir($dir);

  while (false !== ($e = $d->read())){
    if ($e == '.' or $e == '..'){
      continue;
    }
    $sub = "$dir/$e";

    if (is_dir($sub)){
      if (!file_rmdirr($sub, false)){
        $d->close();
        return false;
      }
    }else if (!unlink($sub)){
      $d->close();
      return false;
    }
  }
  $d->close();
  if (!rmdir($dir)){
    return false;
  }
  return true;
}

/*
   * cp -r
   */
function file_cpr ($src, $dst)
{
  global $_SHOP;
  if (is_dir($src)){
    if (!file_exists($dst)){
      if (!mkdir($dst, $_SHOP->dir_mode)){
        return false;
      }
    }

    $d = dir($src);
    while (false !== ($e = $d->read())){
      if ($e == '.' or $e == '..'){
        continue;
      }
      if (!file_cpr("$src/$e", "$dst/$e")){
        return false;
      }
    }
  }else{
    if (!copy($src, $dst)){
      return false;
    }
  }
  return true;
}

/*
   * cp -r directories only
   */
function dir_cpr ($src, $dst)
{
  global $_SHOP;
  if (is_dir($src)){
    if (!file_exists($dst)){
      if (!mkdir($dst, $_SHOP->dir_mode)){
        return false;
      }
    }

    $d = dir($src);
    while (false !== ($e = $d->read())){
      if ($e == '.' or $e == '..'){
        continue;
      }
      if (!dir_cpr("$src/$e", "$dst/$e")){
        return false;
      }
    }
  }
  return true;
}

/*/
Download a file using fpassthru()
/*/
function file_download ($fileDir, $fileName)
{ 
  // $fileDir = "/home/pathto/myfiles"; // supply a path name.
  // $fileName = "myfile.zip"; // supply a file name.
  $fileString = $fileDir . '/' . $fileName; // combine the path and file   
  // translate file name properly for Internet Explorer.
  if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")){
    $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
  } 
  // make sure the file exists before sending headers
  if (!$fdl = @fopen($fileString, 'r')){
    die("Cannot Open File!");
  }else{
    header("Cache-Control: "); // leave blank to avoid IE errors
    header("Pragma: "); // leave blank to avoid IE errors
    header("Content-type: application/octet-stream");
    header("Content-Disposition:attachment; filename=\"" . $fileName . "\"");
    header("Content-length:" . (string)(filesize($fileString)));
    sleep(1);
    fpassthru($fdl);
  }
}

function file_is_sub ($par, $sub)
{
  $par = realpath($par) . "/";
  $sub = realpath($sub) . "/";
  return strcmp($par, substr($sub, 0, strlen($par))) == 0;
}

function user_file ($path)
{
  global $_SHOP;

  return realpath($_SHOP->user_dir . '/' . $_SHOP->organizer_data->organizer_nickname . '/' . $path);
}

function get_content_type ($file_name)
{
  $ext = strrchr($file_name, ".");
  switch($ext){
    case ".gif":
      return "image/gif";
    case ".jpg":
    case ".jpeg":
      return "image/jpg";
    case ".png":
      return "image/png";
    case ".css":
      return "text/css";
    case ".html":
    case ".htm":
      return "text/html";
    case ".php":
      return "text/plain";
    case ".tpl":
      return "text/plain";
  }
}

function file_view ($fileDir, $fileName)
{ 
  // $fileDir = "/home/pathto/myfiles"; // supply a path name.
  // $fileName = "myfile.zip"; // supply a file name.
  $fileString = $fileDir . '/' . $fileName; // combine the path and file   
  // translate file name properly for Internet Explorer.
  if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")){
    $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
  } 
  // make sure the file exists before sending headers
  if (!$fdl = @fopen($fileString, 'r')){
    die("Cannot Open File!");
  }else{
    header("Cache-Control: "); // leave blank to avoid IE errors
    header("Pragma: "); // leave blank to avoid IE errors
    if ($cc = get_content_type($fileName)){
      header("Content-type: $cc");
    } 
    // header("Content-Disposition:attachment; filename=\"".$fileName."\"");
    header("Content-length:" . (string)(filesize($fileString)));
    sleep(1);
    fpassthru($fdl);
  }
}

?>