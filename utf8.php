<?php
/**
 * Logiciel : exemple d'utilisation de HTML2PDF
 *
 * Convertisseur HTML => PDF, utilise TCPDF
 * Distribué sous la licence LGPL.
 *
 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) n'est pas obligatoire
 * il permet juste d'afficher le résultat au format HTML
 * si le paramètre 'vuehtml' est passé en paramètre _GET
 */
 	// récupération du contenu HTML

define('ft_check','shop');
 if(function_exists("date_default_timezone_set") and
    function_exists("date_default_timezone_get")) {
   @date_default_timezone_set(@date_default_timezone_get());
 }

require_once ('includes/config/init_shop.php');

require_once ('includes/config/init.php');
require_once ('includes/classes/class.shopdb.php');
    $query="SELECT * FROM Template WHERE template_name='utf8_test'";
    if(!$data=ShopDB::query_one_row($query)){
        return FALSE; //no template
    }
  $content = $data['template_text'];
// 	$content = file_get_contents(dirname(__FILE__).'/../_tcpdf/cache/utf8test.txt');
 //	$content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>';

	// conversion HTML => PDF
	require_once(LIBS.'/html2pdf/html2pdf.class.php');
	$html2pdf = new HTML2PDF('P','A4','fr');
//	$html2pdf->pdf->SetDisplayMode('real');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
//  print_r($html2pdf);
	$html2pdf->Output('utf8.pdf');
  writeLog(print_r($pdf, true));