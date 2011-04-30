<?php
/*
%%%copyright%%%
 */
?>
<?php
define('cart_empty','Din kundvagn &auml;r tom');
define('valid_tickets','Giltiga biljetter:');
define('next_expire','N&auml;sta upph&ouml;r om:');
define('minutes','min.');
define('expired_tickets','Upph&ouml;rda biljetter:');

define('weiter','Forts&auml;tt');
define('save','Spara'); //submit
define('res','&Aring;terst&auml;ll'); //reset
define('search','S&ouml;k'); //reset

//events.php
define('order_now','Best&auml;ll nu');


//categories.php
define('choice_cat_title','V&auml;lj kategory');
define('date','Datum');
define('open','Entr&eacute;n &ouml;ppnar');
define('start','Evenemanget b&ouml;rjar');
define('ort','Estrad');					
define('ort_phone','Tel');
define('ort_url','Hemsida');
define('categories_prices','kategorier och priser');
define('tickets','Biljetter');
define('prices','Pris');
define('prices_chf','Price in CHF');
define('choice_cat','V&auml;lj en kategori');
define('choice_qty','Antal platser');

//places.php
define('reservate','Reservera');
define('choice_place_title','V&auml;lj en plats');
define('info1','1. V&auml;lj dina platser');
define('info2','2. Klicka p&aring; &#146;Reservera&#146;');

//view_cart.php
define('ev_name','Evenemang');
define('ev_datum','Datum, Estrad');
define('ev_ticket','Biljetter');
define('cat_total','Totalt');
define('exp_rm','Upph&ouml;r om');
define('expired','UPPH&Ouml;RD');
define('warenkorb','Din kundvagn');
define('remove','Ta bort');
define('warenkorb_title','Din kundvagn');
define('total_price','Summa');
define('go_pay','Best&auml;ll');
define('by_post','Skicka med post');
define('back_shop','Best&auml;ll fler biljetter');
define('place_row','Rad');
define('place_seat','Plats');

//functions/user_func.php
define('mandatory','Detta f&auml;lt &auml;r obligatoriskt');
define('alreadyexist','Mailadressen finns redan i v&aring;r databas!');
define('choice','V&auml;lj');
define('lastname','Efternamn');
define('firstname','F&ouml;rnamn');
define('address','Adress');
define('address1','Adress 1');
define('city','Postadress');
define('country','Land');
define('phone','Tel');
define('fax','Fax');
define('email','E-mail');
define('conditions',' Jag har l&auml;st och accepterat reglerna f&ouml;r f&ouml;rs&auml;ljning.');
define('password','L&ouml;senord');
define('password2','Bekr&auml;fta l&ouml;senord');
define('regist_weiter','Registrera');
define('kreditkarte','Kreditkort');
define('type','Korttyp');
define('cc_number','Kortnummer');
define('valable_until','Kortet giltigt till');
define('cc_owner','Namn p&aring; kortet');
define('per_post','Med post');
define('nur_schweiz','Bara i Schweiz');
define('invalid','ogiltig');
define('outdated','utg&aring;ngen');
define('pay_methods','Betalning');
define('not_valid_email','Mailadressen &auml;r ogiltig');
define('forgot_password','Gl&ouml;mt ditt l&ouml;senord?');
define('passwd_changed','Ditt nya l&ouml;senord har skickats till dig via mail.');
define('user_dont_exist','Din mailadress finns inte i v&aring;r databas.');

//page_classes/KasseContent.php 
define('pay_error','V&auml;lj betalningss&auml;tt');
define('cc_error','Kreditkortsfel');
define('email_failed','Internt fel: Kan inte skicka mail. Ordernumret &auml;r:');
define('pdf_failed','Internt fel: Kan inte skapa PDF. Ordernumret &auml;r:');
define('save_failed','Intern fel: Kan inte spara ordern ');
define('reservate_failed','Internt fel: Kan inte reservera platser ');
define('error_loading_user','Ett fel uppstod n&auml;r anv&auml;ndardata sklulle h&auml;mtas ');
define('parzial_price','Totalt utan avgifter');
define('shipment_price','Avgift');
define('order_nr','Ordernummer ');
define('order_total','Ordersumma');

//check_login.php
define('welcome','V&auml;lkommen');
define('not_to_be','Om du inte &auml;r ');
define('not_to_be2',' ');
define('click_here','klicka h&auml;r.');

//add_cart.php
define('cart_updated','Kundvagnen &auml;r updaterad');
define('cart_not_updated','Kundvagnen &auml;r inte uppdaterad');
define('cart_try_again','F&ouml;rs&ouml;k igen');
define('places_empty','V&auml;lj en plats');
define('event_order_limit_exceeded','Antalet platser som du kan boka &auml;r begr&auml;nsat!');
define('places_occupied','De valda platserna var reserverade av en annan kund.');
define('places_toomuch','Detta antal platser kan inte reserveras');
define('places_remains',' &Aring;terst&aring;ende platser');
define('internal_error','Internt fel');

// forgot_password.php
define('forgot_pass','Gl&ouml;mt ditt l&ouml;senord?');
define('forgot_pass_note','Skriv din mailadress.
        Klicka p&aring; &#146;Skicka l&ouml;senord&#146; s&aring; kommer ett nytt l&ouml;senord till dig via mail');
define('send_password','Skicka l&ouml;senord');

//salepoint_web/shop.php

define('free_places','LEDIGA');
define('event_sold_out','Evenemanget uts&aring;lt');
define('cat_sold_out','Kategorin &auml;r sluts&aring;ld');
define('to_pay','Till kassan');
define('kasse','Till kassan');
define('logout','Logga ut');
define('shop','Best&auml;ll biljetter');
define('view_cart','Kundvagn');
define('discounts','Rabatt');
define('places','Platsnummer');

//salepoint_web/view_cart.php
define('kassecontent','Till kassan');
define('kasse_empty','Il n&#146;y a pas de commandes en suspens');
define('printing_wait','Biljetter skrivs ut, v&auml;nta...');
define('printing_manual','Skriv ut order');
define('category_sold_out','UTS&Aring;LD');
define('sure_to_cancel','Avbryt ?');  
define('sure_to_delete','Ta bort ?');

//salepoint
define('kasse_nickname','Logga in');
define('kasse_lang','Spr&aring;k');
define('user_prefs','Inst&auml;llningar');
define('user_prefs_pdf','&Ouml;ppna order i Acrobat');
define('user_prefs_pdt','Skicka order till skrivare');

//salepoint_web/kasse.php
define('spoint_confirm_print','Bekr&auml;fta och skriv ut');
define('spoint_cancel','Avbryt order');
define('spoint_order','Order');

//admin interface 
define('ort_admin','Estrader');
define('event_admin','Evenemang');
define('ort_title','Estrader');
define('view','Visa');
define('edit','&Auml;ndra');
define('add','L&auml;gg till');
define('ort_id','Estradnummer');
define('ort_name','Estradnamn');
define('ort_plan_nr','Number on plan');
define('ort_add_title','L&auml;gg till estrad');
define('ort_update_title','Redigera estrad');
define('admin_list','Lista');
define('event_id','Evenemangsnummer');
define('event_name','Evenenemangsnamn');
define('event_short_text','Kort evenemangsbeskrivning');
define('event_text','L&aring;ng evenemangsbeskrivning');
define('event_url','Hemsida');
define('event_date','Datum');
define('event_time','Evenemanget b&ouml;rjar');
define('event_open','Entr&eacute;n &ouml;ppnar');
define('event_ort_id','Estrad');
define('event_add_title','L&auml;gg till evenemang');
define('event_update_title','Redigera evenemang');
define('event_title','Alla evenemang');
define('ort_pm','Platsskiss');
define('pm_id','Platsskissnummer');
define('pm_name','Platsskissnamn');
define('pm_width','Skissbredd');
define('pm_height','Skissh&ouml;jd');
define('pm_first_row','F&ouml;rsta radens nummer');
define('pm_dir','Riktning');
define('pm_img_url','URL f&ouml;r bilden');
define('pm_dir_LR','V&auml;nster till h&ouml;ger');
define('pm_dir_RL','H&ouml;ger till v&auml;nster');
define('pm_ort_id','Estradnummer:');
define('pm_update_title','Redigera skiss');
define('pm_add_title','L&auml;gg till skiss');
define('pm_title','Alla skisser');
define('category_id','Kategorinummer');
define('category_name','Kategorinamn');
define('category_price','Kategoripris');
define('category_pm_id','Kategorins skissnummer');
define('category_event_id','Evenemangsnummer');
define('category_update_title','Redigera kategori');
define('category_add_title','L&auml;gg till kategori ');
define('category_title','Alla kategorier');
define('event_cats','Kategorier:');
define('already_pub','redan publicerad');
define('pub_begin_failed','Transaction begin failed');
define('pub_begin_trans','Transaction begin');
define('pub_places_failed','Fel - platserna &auml;r inte publicerade');
define('pub_places_published','Platserna publicerade');
define('pub_status_updated','Status uppdaterad');
define('pub_pm_locked','Platsskiss sp&auml;rrad');
define('pub_category_failed', 'Fel - kategorin &auml;r inte publicerad');
define('pub_success','Lyckades');
define('pub_failure','Fel');
define('pub_confirm_msg','Publicera detta evenemang?');
define('delete_confirm_msg','Radera detta evenemang?');
define('delete_subs_first','Radera underevenemang f&ouml;rst');
define('in_use','Detta objekt kan inte raderas');

define('user_lastname','Efternamn');
define('user_firstname','F&ouml;rnamn');
define('user_address','Adress');
define('user_address1','Adress 1');
define('user_zip','Postnummer');
define('user_city','Postadress');
define('user_country_name','Land');
define('user_country','Land');
define('user_state_name','Stat');
define('user_state','Stat');
define('user_email','E-Mail');
define('user_phone','Tel');
define('user_fax','Fax');
define('user_status','Status');
define('member','Medlem');
define('sale_point','F&ouml;rs&auml;ljningsst&auml;lle');
define('guest','G&auml;st');
define('tickets_nr','Antal');
define('orders','Order');
define('order_type_post','Post');
define('order_type_email','E-Mail');
define('order_type_ordered','Best&auml;lld');
define('order_type_sended','Skickad');
define('order_type_payed','Betald');
define('order_type_canceled','Borttagen');
define('order_type_reissued','&Aring;tertagen');
define('ordered','Best&auml;lld');
define('sended','Skickad');
define('payed','Betald');
define('canceled','Borttagen');
define('reissued','&Aring;tertagen');
define('order_status_email','Skickad via mail');
define('order_status_post','Skickad med posten');
define('order_price','Summa');
define('time_stamp','Tid');
define('organizer_admin','Arrang&ouml;r');
define('search_order','S&ouml;k');
define('order_admin','Order');
define('order_list_title','Alla order');
define('order_status_ord','Best&auml;lld');
define('order_status_reissue','&Aring;tertagen');
define('order_status_send','Skickad');
define('order_status_payed','betald');
define('order_status_cancel','Borttagen');
define('firstpage','F&ouml;rsta sidan');
define('lastpage','Sista sidan');
define('previouspage','<<<<<<<');
define('nextpage','>>>>>>>');
define('search_title_user','S&ouml;k kund');
define('search_title_place','S&ouml;k efter plats');
define('search_title_order','S&ouml;k efter order');
define('choice_please','V&auml;lj');
define('event_list','Evenemangslista');
define('place_row_nr','Radnummer');
define('seat_row_nr','Radnummer');
define('place_seat_nr','Platsnummer');
define('seat_nr','Platsnummer');
define('order_id','Ordernummer');
define('search_result','S&ouml;kresultat');
define('event','Evenemang');
define('category','Kat.');
define('place','Plats');
define('price','Pris');
define('user','Kund');
define('bs','Ord.');
define('status','Status');
define('organizer_title','Alla arrang&ouml;rer');
define('organizer_id','Arrang&ouml;rsnummer');
define('organizer_name','Arrang&ouml;rsnamn');
define('organizer_address','Adress');
define('organizer_phone','Tel');
define('organizer_fax','Fax');
define('organizer_plz','Postnummer');
define('organizer_ort','Postadress');
define('organizer_email','E-Mail');
define('organizer_place','Postadress');
define('organizer_nickname','Kortnamn');
define('organizer_update_title','Redigera arrang&ouml;r');
define('organizer_add_title','L&auml;gg till arrang&ouml;r');
define('event_image_url','URL till bild');
define('event_order_limit','Ordergr&auml;ns');
define('event_payment','Betalningss&auml;tt');
define('delete_item','Ta bort ?');
define('event_stats_title','Statistik');
define('stats','Statistik');
define('graph_stats','Grafisk statistik');
define('event_organizer','Arrang&ouml;r');
define('yes','JA');
define('pm_shift','F&ouml;rskjut rader');
define('order_tickets_nr','Antal biljetter');
define('order_total_price','Summa');
define('order_date','Orderdatum');
define('order_shipment_mode','Leveranss&auml;tt');
define('order_status','Status');
define('view_order_details','Visa order');
define('print_order','Skriv ut order');
define('send_order_post','&Auml;ndra status till &#146;Skickad&#146;?');
define('send_order_email','Skicka order via mail och &auml;ndra status till &#146;Skickad&#146;');
define('change_order_to_payed','&Auml;ndra status till &#146;Betald&#146;');
define('reissue_order_menu','&Aring;terta order med nytt ordernummer och med status &#146;Best&auml;lld&#146;. &Auml;ndra den h&auml;r orderns status till &#146;&Aring;tertagen&#146;');
define('cancel_order','Status &auml;ndrad till &#146;Borttagen&#146; och biljetterna raderade');
define('possible_actions','Funtioner');
define('legende','F&ouml;rklaring');
define('change_status_to_send','&Auml;ndra status till &#146;Skickad&#146;?');// und drucken?');
define('change_status_to_send_email','&Auml;ndra status till &#146;Skickad&#146; och skicka med mail?');
define('change_status_to_payed','&Auml;ndra status till &#146;Betald&#146;?');
define('reissue_order','&Aring;terskapa ordern med nytt ordernummer och ta bort denna order?');
define('reissue_ticket','&Aring;terskapa biljetten med ny streckkod? Biljettnummer:');
define('delete_ticket','Radera biljetten? Biljettnummer:');
define('reissue_order','&Aring;terskapa ordern med nytt ordernummer och ta bort denna order?');
define('cancel','Ta bort ordern?');
define('order_not_found','Kan inte hitta ordern');
define('order_cannot_change_to_send','Kan inte &auml;ndra status till &#146;Skickad&#146;');
define('pdf_failed','Fel i PDF');
define('email_failed','Fel: Kunde inte skicka mail');
define('change_order_status_failed','Kan inte &auml;ndra status');
define('order_cannot_change_to_payed','Kan inte &auml;ndra status till &#146;Betald&#146;');
define('order_cannot_reissue','Kan inte &aring;terskapa');
define('new_order_failed','Kan inte skapa ny order');
define('new_order_created','Ny order skapad');
define('tickets_update_failed','Kunde inte uppdatera biljetter');
define('tickets_update_done','Biljetter uppdaterade');
define('old_order_not_canceled','Den gamla ordern tog inte bort');
define('old_order_canceled','Den gamla ordern borttagen');
define('order_not_canceled','Ordern togs inte bort');
define('order_canceled','Ordern borttagen');
define('places_not_freed','Platserna inte &aring;ter till &#146;ledig&#146; status');
define('places_freed','Platserna &aring;ter till &#146;ledig&#146; status');
define('category_size','Kategoristorlek');
define('category_numbering','kategorinumrering');
define('category_numbering_none','Onumrerat');
define('category_numbering_rows','Bara raderna numrerade');
define('category_numbering_seat','Bara paltserna numrerade (inte raderna)');
define('category_numbering_both','rader och platser numrerade');
define('payment_cc','Kreditkort');
define('payment_post','Skicka');
define('filling_stats','Statistiktabell');
define('cat_stat_success','Ok - kategoristatistik uppdaterades');
define('cat_stat_error','Fel - kategoristatistik uppdaterades inte');
define('event_stat_success','Ok - evenemangsstatistik uppdaterades');
define('event_stat_error','Fel - evenemangsstatistik uppdaterades inte');
define('salepoint_admin','F&ouml;rs&auml;ljningsst&auml;llen');
define('template_admin','Mallar');
define('excel_admin','Excel');
define('spoint_title','Alla f&ouml;rs&auml;ljningsst&auml;llen');
define('spoint_add_title','L&auml;gg till f&ouml;rs&auml;ljningsst&auml;lle');
define('kasse_name','F&ouml;rs&auml;ljningsst&auml;llets namn');
define('user_nickname','Kortnamn');
define('user_id','ID');
define('spoint_update_title','Redigera f&ouml;rs&auml;ljningsst&auml;lle');
define('old_password','Gamalt l&ouml;senord');
define('new_password','Nytt l&ouml;senord');
define('template_title','Alla mallar');
define('template_add_title','L&auml;gg till titel');
define('template_update_title','Redigera mall');
define('template_id','Mallnummer');
define('template_name','Mallnamn');
define('template_type','Malltyp');
define('template_ts','Tidsst&auml;mpel');
define('template_status','Status');
define('comp','Kompilerad');
define('new','ny');
define('error','Fel');
define('template_type_email','email');
define('template_type_pdf','PDF');
define('compile_all','Kompilera alla');

define('export_admin','Exportera');
define('export_admin_title','Exportera data');

define('export_cc','Kreditkort');
define('export_cc_title','Exportera kreditkortsnummer');
define('export_xl','F&ouml;r&auml;ljningsdata');
define('export_xl_title','F&ouml;r&auml;ljningsdata');
define('export_cc_count','Antal kreditkort');
define('export_cc_submit','Exportera');

define('export_xml_event','Evenemang till XML');
define('export_xml_event_title','Exportera evenemang till XML');
define('export_xml_event_event','V&auml;lj ett evenemang');
define('export_xml_event_file','Filnamn');
define('export_xml_event_submit','Exportera!');


define('import_admin','Importera');
define('import_admin_title','Importera data');
define('import_xml','XML');
define('import_xml_title','Importera data fr&aring;n XML-format');
define('import_xml_file','XML-fil');
define('import_xml_submit','Importera!');



define('handling_text_payment','Betalningsm&auml;rkning');
define('handling_text_shipment','Leveransm&auml;rkning');
define('handling_pdf_ticket_template','PDF-biljettmall');
define('pdf_paper','PDF-sidformat');
define('page_format_default','Default');
define('page_format_std','Standard');
define('page_format_custom','Egen storlek');
define('page_orientation_portrait','St&aring;ende');
define('page_orientation_landscape','Liggande');



define('xl_view_title','F&ouml;rs&auml;ljningsdata');
define('xl_start','Startdatum');
define('xl_end','Slutdatum');
define('order_type_spoint','F&ouml;rs&auml;ljningsst&auml;lle');
define('order_status_sp','F&ouml;rs&auml;ljningsst&auml;lle');
define('compilation_succeed','Ok - mallen kompilerades');
define('sp','F&ouml;rs&auml;ljningsst&auml;lle');
define('event_discounts','Rabatter');
define('event_template','Biljettmall');
define('discount_id','Rabattnummer');
define('discount_name','Rabattnamn');
define('discount_type','Rabattyp');
define('discount_type_fixe','Fast rabatt');
define('discount_type_percent','Procentrabatt');
define('discount_value','Rabattniv&aring;');
define('discount_title','Alla rabatter');
define('login','Kortnamn');
define('already_exist','Ord upptaget. V&auml;lj annat ord.');
define('login_invalid','Felaktig inloggning');
define('pm_used','Platsskiss upptagen!');
define('category_template','Biljettmall');
define('category_color','Kategorif&auml;rg');
define('no_color','Ingen f&auml;rg');
define('generate_xl','Skapa f&ouml;rs&auml;ljningsdokument');
define('nosale_confirm_msg','Ta bort fr&aring;n f&ouml;rs&auml;ljning?');
define('unpub_begin_trans','Transaktion p&aring;b&ouml;rjad');
define('unpub_begin_failed','Transaktion inte p&aring;b&ouml;rjad');
define('nosale_status_updated','Status uppdaterad');
define('nosale_success','Lyckades');
define('nosale_failure','Misslyckades');
define('already_no_sale','Redan borttagen fr&aring;n f&ouml;rs&auml;ljning');
define('no_result','Inget resultat');
define('status_not_valid_for_repub','Status inte giltig f&ouml;r &aring;terpublicering');
define('event_image','Evenemangsbild');
define('new_event_image','Ny bild');
define('remove_event_image','Ta bort evenemangsbild');
define('ort_image','Estradbild');
define('new_ort_image','Ny bild');
define('remove_image','Ta bort bild');
define('administration','Administration');
define('file_admin','Filer');
define('event_group_admin','Evenemangsgrupper');
define('event_group_title','Alla evenemangsgrupper');
define('event_group_add_title','L&auml;gg till evenemangsgrupp');
define('event_group_id','Gruppnummer');
define('event_group_name','Namn');
define('event_group_start_date','Startdatum');
define('event_group_end_date','Slutdatum');
define('event_group_description','Beskrivning');
define('event_group_image','Bild');
define('start_date','Startdatum');
define('end_date','Slutdatum');
define('event_group_update_title','Redigera evenemangsgrupp');
define('file_name','Filnamn');
define('file_upload','Ladda upp filer');
define('file_mkdir','Mappar');
define('file_dir_name','Mappnamn');
define('organizer_not_found','Hittade inte arrang&ouml;ren');
define('file_not_found','Hittade inte filen');
define('create','Skapa');
define('upload','Ladda upp');
define('admin_select_organizer','V&auml;lj arrang&ouml;r');
define('categories','Kategorier');
define('place_maps','Platsskisser');
define('place_map','Platsskiss');

define('select','V&auml;lj');
define('pm_zones','Sektioner');
define('pm_zone','Sektion');

define('new_pm_zone','L&auml;gg till ny sektion');
define('pmz_color','F&auml;rg');
define('pmz_name','Namn');
define('pmz_short_name','Kortnamn');
define('new_pmz','Ny sektion');
define('redefine_pm_zone','Omdefiniera sektion');
define('redefine','Omdefiniera');
define('seat_numbering','Platsnumrering');
define('autonumber_pmz','Automatisk numrering');
define('first_row','F&ouml;rsta raden');
define('step_row','Radsteg');
define('inv_row','Invertera rader');
define('first_seat','F&ouml;rsta platsen');
define('step_seat','Platssteg');
define('inv_seat','Invertera platser');
define('flip','Flip');
define('map','Skiss');
define('payment_admin','Orderhanteringss&auml;tt');
define('payment_title','Alla betalningss&auml;tt');
define('invoice','Inbetalning');
define('post','Post');
define('payment_add_title','L&auml;gg till betalningss&auml;tt');
define('payment_mode','Betalningss&auml;tt');
define('payment_send','Biljettleverans');
define('payment_email_template','Mall f&ouml;r e-mail');
define('payment_fee_fixe','Fast avgift ');
define('payment_fee_percent','Procentavgift ');
define('payment_update_title','F&ouml;r&auml;ndra betalningss&auml;tt');
define('order_payment_mode','Betalningss&auml;tt');
define('order_fee','Avgift');
define('import','Importera');
define('shared_ort_title','Delade estrader');
define('ort_address','Adress');
define('ort_address1','Adress 1');
define('ort_zip','Postnummer');
define('ort_city','Stad');
define('ort_country_name','Land');
define('ort_country','Land');
define('ort_fax','Fax');
define('control_admin','Biljettkontrollant');
define('control_title','Alla biljettkontollanter');
define('control_login','Logga in');
define('control_event_ids','Evenemang');
define('control_add_title','L&auml;gg till biljettkontrollant');
define('control_update_title','Redigera biljettkontrollant');
define('control_view','Biljettkontrollant');
define('classics','Klassisk musik');
define('jazz','Jazz');
define('blues','Blues');
define('funk','Funk');
define('pop','Pop');
define('rock','Rock');
define('folklore','Folklore');
define('theater','Teater');
define('sacred','Kyrkomusik');
define('ballet','Ballett');
define('opera','Opera');
define('humour','Humor');
define('organizer_currency','Valuta');
define('event_pm_ort_id','Platsskiss');
define('event_rep','&Aring;terkommande');
define('event_type','Evenamangstyp');
define('event_mp3','MP3');
define('event_rep_main','Flera');
define('event_rep_unique','Eng&aring;ngs');
define('pm_parts','Platsskissdelar');
define('pmp_id','Delnummer');
define('pmp_name','Delnamn');
define('pm_part','Platsskissdel');
define('edit_pm','Redigera platsskiss');
define('pm_image','Bild till platsskiss');
define('remove_pm_image','Radera bild');
define('search_title_codebar','S&ouml;k streckkod');
define('codebar','Streckkod');
define('user_organizer_ids','Arrang&ouml;r');
define('category_data','Kategoridata');
define('pmp_height','Delh&ouml;jd');
define('pmp_width','Delbredd');
define('discount_add_title','L&auml;gg till rabatt');
define('remove_event_mp3','Ta bort MP3');
define('category_view_title','Kategori');
define('split_pm','Dela platsskiss');
define('pm_split','Dela platsskiss');
define('split_zones','Dela efter sektioner');
define('correct_errors_first','R&auml;tta till felen f&ouml;rst');
define('undefined','odefinierad');
define('category_pmp_id','Kategoriplatsskiss ID');

define('scene_h','images/scene_h_sv.png');
define('scene_v','images/scene_v_sv.png');

define('view_handling','Orderhantering');
define('handling_title','Orderhanteringar');
define('handling_add_title','L&auml;gg till orderhantering');
define('handling_id','ID');
define('handling_payment','Betalning');
define('handling_shipment','Leverans');
define('handling_email_template_ord','Mail-mall f&ouml;r order');
define('handling_email_template_send','Mail-mall f&ouml;r leverans');
define('handling_email_template_payed','Mail-mall f&ouml;r betalning');
define('handling_html_template','HTML-mall f&ouml;r f&ouml;rklarande text');
define('handling_pdf_template','PDF-mall f&ouml;r faktura/kvitto');
define('handling_sale_mode','F&ouml;rs&auml;ljnigss&auml;tt');
define('handling_fee_fix','Fast avgift');
define('handling_fee_percent','Procentavgift');

define('entrance','Vid entr&eacute;n');
define('cash','Kontant');
define('email','Via mail');
define('sp','F&ouml;rs&auml;ljningsst&auml;lle');

define('pmp_scene','Scenens placering');
define('pmp_scene_north','Norr');
define('pmp_scene_est','&Ouml;st');
define('pmp_scene_south','S&ouml;der');
define('pmp_scene_west','V&auml;ster');

define('pmp_shift','F&ouml;rskjut rader');
define('define','Definiera');
define('labels','Etiketter');
define('label_type','Typ');
define('label_text','Text');
define('label_type_text','Text');
define('label_type_row_east','Radnummer -&gt;');
define('label_type_row_west','&lt;- Radnummer');
define('label_type_seat_south','Platsnummer under');
define('label_type_seat_north','Platsnummer ovanf&ouml;r');
define('label_type_exit','Utg&aring;ng');
define('clear','Rensa skissen');

define('organizer_logo','Logga');
define('new_organizer_logo','Ny logga');
define('remove_logo','Ta bort logga');
define('copy','Kopiera');
define('agenda_only','Bara avisering-ingen bokning');
define('copy_main_pm','Kopiera huvudplatsskissen');

define('order_shipment_status','Leveransstatus');
define('order_payment_status','Betalningsstatus');
define('none','Inget');
define('send','Skickad');
define('ord','Best&auml;lld');
define('change_status_to_no_send','&Auml;ndra status till &#146;ej skickad&#146;?');
define('change_status_to_no_payed','&Auml;ndra status till &#146;obetald&#146;?');
define('change_status_to_ord','&Auml;ndra status till &#146;best&auml;lld&#146;?');
define('change_order_to_ord','&Auml;ndra status till \'best&auml;lld\'');
define('no_send_order_post','&Auml;ndra status till \'ej skickad\'');
define('change_order_to_no_payed','&Auml;ndra status till \'obetald\'');
define('payed_and_send','Betald och skickad');

define('pass_too_short','L&ouml;senordet &auml;r f&ouml;r kort');
define('music','Musik');
define('other','&Ouml;vrigt');
define('cinema','Bio');
define('exposition','Utst&auml;llning');
define('ballet','Opera/Balett');
define('party','Party');

define('also_sub','Underevenemang ocks&aring;');
define('restart_success','Omstart lyckades');
define('stop_success','Stopp lyckades');

//control 
define('check_success','Biljettkontroll OK');
define('place_without_nr','Onumrerad plats');
define('category','Kategori');
define('check','Riv biljett');
define('res','Avbryt');
define('not_valid_event','Evenemang inte giltigt');
define('ticket_not_found','Hittade inte biljetten i databasen');
define('ticket_already_checked','Biljetten &auml;r redan \'Riven\'');
define('place_not_commanded','Platsen &auml;r inte s&aring;ld');
define('place_only_reserved','Platsen &auml;r reserverad men inte s&aring;ld');
define('place_nr','Platsnummer');
define('rang_nr','Radnummer');
define('place_status_not_updated','Fel: Platsstatus &auml;r inte uppdaterad');
define('change_event','Byt evenemang');
define('control_events_list','Evenemang');
define('free','Ledig');
define('com','Betald');
define('reserved','Reserverad');
define('checked','\'Riven\'');

define('nickname','Kortnamn');
define('lang','Spr&aring;k');
define('img_loading_problem','Problem med att ladda bild');
define('enter_guest','Personuppgifter');

define('garbage','Skr&auml;p');
define('seat','Platser');
define('order','Order');
define('empty_trash','T&ouml;m');
define('purge','radera');

define('yp','Yellowpay');
define('authorize_aim','Authorize.net AIM');
define('authorize_sim','Authorize.net SIM');
define('paypal','PayPal');
define('cc','Kreditkort');


define('ticket_deleted','Ticket deleted');
define('cannot_find_seat','Hittade inte biljetten i databasen');
define('','');
define('','');

?>
