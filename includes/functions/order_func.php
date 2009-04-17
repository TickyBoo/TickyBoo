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
require_once("classes/MyCart.php");
require_once("classes/Order.php");
require_once("classes/Ticket.php");

/*
function command ($order,$sid,$user_id,$trx=TRUE){
  require_once "classes/Place.php";
  
  foreach($order->places as $ticket){
  
    $places[]=array(
      'place_id'=>$ticket->place_id,
      'event_id'=>$ticket->event_id,
      'category_id'=>$ticket->category_id);
  }
  
  return Place::command($sid,$places,$user_id,$trx);
}
*/


//loads tickets and apply templates
//21dec2004: templates are  optional

function print_order ($order_id,$bill_template='',$mode='file',$print=FALSE, $subj=3){ //print subj: 1=tickets, 2=invoice, 3=both
  require_once("classes/ShopDB.php");
	global $_SHOP;
	
  $query = 'SELECT * FROM Seat LEFT JOIN 
      Discount ON seat_discount_id=discount_id, 
      Event FORCE KEY ( PRIMARY ) , 
      Ort FORCE KEY ( PRIMARY ) ,
      User FORCE KEY ( PRIMARY ) ,
      Category FORCE KEY ( PRIMARY ) ,
      `Order` FORCE KEY ( PRIMARY ) ,
			Handling FORCE KEY ( PRIMARY ) '
  . " WHERE seat_order_id = ".ShopDB::quote($order_id)." AND 
      event_id = seat_event_id AND 
      ort_id = event_ort_id AND 
      user_id = seat_user_id AND 
      category_id = seat_category_id AND
			order_handling_id=handling_id AND
      order_id = ".ShopDB::quote($order_id);

  //echo $query;

  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return FALSE;
  } 
    
  require_once("classes/TemplateEngine.php");
  require_once("classes/class.ezpdf.php");
  require_once("admin/AdminView.php");
  require_once('classes/Handling.php');
  require_once("functions/barcode_func.php");

	$first_page=TRUE;
	
  while($data=shopDB::fetch_assoc($res)){

		if(!isset($te)){
		  $hand=Handling::load($data['order_handling_id']);
			
			if($hand->pdf_paper_size){
				$paper_size=$hand->pdf_paper_size;
				$paper_orientation=$hand->pdf_paper_orientation;
			}else{	
				$paper_size=$_SHOP->pdf_paper_size;
				$paper_orientation=$_SHOP->pdf_paper_orientation;
			}
			$te = new TemplateEngine();  
			$pdf =& new Cezpdf($paper_size,$paper_orientation); 
		}

    //foreach ticket - choose the template
    
		if($hand->handling_pdf_ticket_template){
      $tpl_id=$hand->handling_pdf_ticket_template;
		}else if($data['category_template']){
      $tpl_id=$data['category_template'];
    }else if($data['event_template']){
      $tpl_id=$data['event_template'];
    }else{
		  $tpl_id=false;
		}

  	$country= new AdminView();
		$data['user_country_name']=$country->getCountry($data['user_country']);
    $country->free;
    
		
    if($tpl_id and ($subj & 1)){
			//load the template
			if(!$tpl =& $te->getTemplate($tpl_id)){
				user_error(no_template." cat: {$data['category_id']}, event: {$data['event_id']}");        
				return FALSE;
			}
		
			if($data['category_numbering']=='none'){
				$data['seat_nr']='0';
				$data['seat_row_nr']='0';
			}else if($data['category_numbering']=='rows'){
				$data['seat_nr']='0';
			}else if($data['category_numbering']=='seat'){
				$data['seat_row_nr']='0';
			}
		
			//compute  barcode
			$data['barcode_text']=
				sprintf("%08d%s",
								$data['seat_id'],
					$data['seat_code']);
		
			if(!$first_page){
				$pdf->ezNewPage();
			}
			$first_page=FALSE;

			//print the ticket
			$tpl->write($pdf,$data);
		}
		
		$last_data=$data; 

    //save the data for the bill
    $key = "({$data['category_id']},{$data['discount_id']})";
    
    if(!isset($bill[$key])){
      $bill[$key]=array(
        'event_name'=>$data['event_name'],
				'qty'=>1,
				'category_name'=>$data['category_name'],
				'seat_price'=>$data['seat_price'],
				'discount_name'=>$data['discount_name']
      );
    }else{
      $bill[$key]['qty']++;
    } 
  }

  //calculating the sub-total
  foreach(array_keys($bill) as $key){
    $bill[$key]['total']=$bill[$key]['seat_price']*$bill[$key]['qty'];
    $total_0+=$bill[$key]['total'];
  }

   
  $last_data['bill_data']=$bill; 
  $last_data['total_0']=$total_0;
  
  $last_data['fee']=$last_data['order_fee'];
  $last_data['total']=$total_0+$last_data['fee'];

	if(!isset($te)){
		$hand=Handling::load($data['order_handling_id']);
		
		if($hand->pdf_paper_size){
			$paper_size=$hand->pdf_paper_size;
			$paper_orientation=$hand->pdf_paper_orientation;
		}else{	
			$paper_size=$_SHOP->pdf_paper_size;
			$paper_orientation=$_SHOP->pdf_paper_orientation;
		}
		$te = new TemplateEngine();  
		$pdf =& new Cezpdf($paper_size,$paper_orientation); 
	}


	if(!$bill_template){
		$bill_template=$hand->handling_pdf_template;
	}	
	
	if($bill_template and ($subj & 2)){

		//loading the template 
		if($tpl =& $te->getTemplate($bill_template)){

			if(!$first_page){
				$pdf->ezNewPage();
			}
			$first_page=FALSE;

			//applying the template
			$tpl->write($pdf,$last_data);
		}else{
			echo "<div class=err>".no_template." : $bill_template </div>";        
			return FALSE;
		}
	}  
 
  //composing filename without extension
  $order_file_name = "order_".$order_id;
 
  //producing the output
  if($mode=='file'){
    $order_file = fopen($_SHOP->ticket_dir."/".$order_file_name.".pdf","w");
    fwrite($order_file,$pdf_data=$pdf->ezOutput());
    fclose($order_file);
  }else if($mode=='stream'){
    if($print){
      $pdf_options=array("Content-type"=>"application/x-print-pdf",
                         "Content-Disposition"=>$order_file_name.".pdt");
    }else{
      $pdf_options = array("Content-Disposition"=>$order_file_name.".pdf");
    }
    $pdf->ezStream($pdf_options);
    

    
  }else if($mode=='data'){
    $pdf_data=$pdf->ezOutput();
  }

  //echo "<pre>{$pdf->messages}</pre>";
  
  //return array('data'=>$last_data,'pdf'=>$pdf_data);
  return $pdf_data;
}

function email_order ($email_data,$template_name){
  global $_SHOP;
  require_once("classes/htmlMimeMail.php");
  require_once("classes/TemplateEngine.php");
  
  $te=new TemplateEngine;
  
  if(!$tpl=&$te->getTemplate($template_name)){
    user_error(no_template." ".$template_name);        
    return FALSE;
  }
  
  $email = new htmlMimeMail();
  $tpl->build($email,$email_data,$_SHOP->lang);

  if(!$email->send($tpl->to)){
    echo $email->errors;
    return FALSE;
  }
  
  return TRUE;
}

function email_confirm ($email_data,$template_name){
  if(is_numeric($email_data)){
    //$email_data is in fact order_id
    $query="SELECT * FROM 
      User FORCE KEY ( PRIMARY ) ,
      `Order` FORCE KEY ( PRIMARY )
      WHERE order_id='$email_data' and 
      user_id=order_user_id"; 

    if(!$email_data=ShopDB::query_one_row($query)){
      user_error(shopDB::error());
      return FALSE;
    }
  }

  global $_SHOP;
  require_once("classes/htmlMimeMail.php");
  require_once("classes/TemplateEngine.php");
  
  $te=new TemplateEngine;
  $tpl=&$te->getTemplate($template_name);
  
  $email = new htmlMimeMail();
  $tpl->build($email,$email_data,$_SHOP->lang);

  if(!$email->send($tpl->to)){
    user_error($email->errors);
    return FALSE;
  }
  
  return TRUE;
}

function get_payment ($payment){
   global $_SHOP;
   $query="SELECT * FROM Payment WHERE payment_id='$payment'";

    if(!$res=ShopDB::query_one_row($query)){
      return FALSE;
    }
    
    return $res;

}
?>