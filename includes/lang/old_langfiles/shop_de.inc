#time_format="%I:%M %p"
#time in 24 hours

define("time_format","%H:%M");
define("date_format","%A %e %B %Y");
define("longdate_format","%Y-%m-%d %H:%M:%S");
define("shortdate_format","%e %b %Y");
define("home","Home");



#activate.tpl

define("act_name","Activate account");
define("act_mess_line1","Please check you email to activate your account.");
define("act_mess_line2","99.9% of the time you will recive your email straight away, if do not recive something please check your spam folder or deleted folder. The email contains no images or files.");
define("act_mess_line3","Any problems or if you havent recived an activation email within a few hours. Please follow the following <strong>link</strong>");

#agb.tpl

define("agb_name","Put your sales conditions in 'agb.html'");
define("calendar","Kalender");

#cart_content.tpl

define("cart_cont_mess1","Please check your order, to change your tickets remove the ones you have reserved and click 'Order More Tickets'.");
define("cart_cont_mess2","l"You have 15 minutes before your reserved tickets are released back to sale, so please either order more tickets or continue with your order. Thank you.");
define("shopping_cart","Ihr Einkaufskorb");
define("cart_empty","Ihr Einkaufskorb ist leer");
define("event","Anlass");
define("tickets","Tickets");
define("total","Total");
define("expires_in","L&auml;uft ab in");
define("total_price","Gesamtpreis");

#cart_resume.tpl

define("shopcart","Shopping Cart");
define("valid_tickets","G&uuml;ltige Tickets");
define("expired_tickets","Abgelaufen Tickets");
define("tick_exp_in","Tickets expire in");
define("x_tick","x Tickets");
define("view_order","View order");
define("checkout","zur Kasse");
define("no_tick_res","You have no tickets reserved.");
define("Resume_min","min.");
define("tot_tick_price","Total Ticket Price:");
define("row","Reihe");
define("normal","Normal");
define("remove","Entfernen");
define("expired","ABGELAUFEN");
define("order_more_tickets","Weitere Tickets bestellen");

#cat_descrition.tpl

define("cat_description","Seating Sections and prices");
define("cat_ticketsection","Ticket Section");
define("cat_ticketprice","Ticket Price");
define("category_sold","Kategorie ausverkauft");
define("prices_in","Preise in ");
define("select_category","W&auml;hlen Sie eine Kategorie");
define("order_limit","Limit pro Bestellung");


#catagory.tpl

define("sign_in_first","sign_in_first");
define("select_seat","1. Bitte w&auml;hlen Sie hier Ihren Sitzplatz");
define("click_on_reserve","2. Klicken Sie auf 'Bestellen'");
define("number_seats","Platz Menge");
define("only_rows_numbered","Reihennummerierung");
define("reserve","Bestellen");
define("Please_login","Please login or register to reserve seats.");
define("cc_name","Kreditkarteninhaber");
define("cc_number","Kreditkarten-Nr.");
define("cc_exp","G&uuml;ltig bis");
define("cc_code","Karten-Pr&uuml;fnummer");
define("pay","Bezahlen");

#cc_autorize_pay.tpl

define("pay_accept","Payment accepted");
define("pay_refused","Die Bezahlung war nicht akzeptiert");
define("order_id","Order ID");
define("trx_id","Transation ID");
define("discount","Rabatt");
define("doors_open","Einlass");
define("dates_localities","Daten und Orte");
define("various_dates","verschiedene Daten");
define("date","Datum");
define("venue","Ort");
define("time","Time");
define("indexpage","Index");
define("event_groups","Veranstaltungen");

#event_type.tpl

define("et_classics","Classical music");
define("et_jbf","Jazz/Blues/Funk");
define("et_pr","Pop/Rock");
define("et_folk","Folk");
define("et_theater","Theater");
define("et_humour","Humour");
define("et_church","Church music");
define("et_opbal","Opera/Ballet");
define("et_music","Music");
define("et_other","Other");
define("et_cinema","Cinema");
define("et_expo","Expo");
define("et_party","Party");

#event_types.tpl

define("styles","Styles");

#forgot_password.tpl

define("pwd_sent","Your new password is sent to your e-mail");
define("pwd_err","User not found or other problem occured");
define("pwd_forgot","Forgotten your password?");
define("pwd_note","http://subnant.berlios.de ");
define("pwd_send","Send password");

#help.tpl

define("helpme","http://subnant.berlios.de ");

#inscription.tpl

define("becomemamber","Become a member");
define("memberinfo","Please enter your details below. To buy tickets you are required to sign up, it is recommend that you sign up before you go to reserve tickets.");
define("password","Password");
define("confirmpassword","Confirm password");
define("agrement","I have read and agree to the General Terms of Sale.");
define("mayuse","I agree that '{$organizer->organizer_name}' may use my details to send me information about future events and other pepper related matters, and that they will not pass on my details to any 3rd party.");
define("signup","Sign up");

#just_registre

define("bcm_mbr","Become member");
define("tnx_mbr","Thank you!");
define("note_mbr","Check your email");

#loginerror.tpl

define("log_error","Login Error");
define("log_err_wrong_usr","Wrong username or password! Please try again, remembering that the username and password are case sensitive.<br />If you have forgoten your username or password please click <a href='forgot_password.php'><strong>here</strong></a>");
define("log_err_not_act","<p>This account hasnt been activated yet.<br />Please activate your account!</p><p>Please check your email for the activation email. Also check your spam folder. Then follow the link to activate your account</p><p>If you still haven't recieved anything after a hour please click <a href='resend_activation.php'<b>here</b></a> to resend activation email.</p>");

#last_event_list.tpl

define("next_events","Next events");
define("up_events","Upcoming Events");


#order_confirm

define("order_reg","Order registered");
define("tnx_order","Thank you very much! Your order was registered.");
define("tnx_mess","To pay for your order follow the proccess below.<br><br>To view your order click");
define("tnx_here","here");

#order_preview

define("handlings","Payments");
define("payment","Payment");
define("shipment","Shipment");
define("order_it","Order");
define("limit","Unfortunatly you have exceeded amount of alowed reserved tickets. To check your orders click <a href='index.php?personal_page=orders'>here</a>.");
define("orclick","Or Click below to:");

#paypal_cancel

define("pay_cancel","Payment canceled");

#personal_order.tpl

define("pers_orders","Current / Previous Order");
define("go_back","Click Here to go back");
define("ordered","Bestellt");
define("order_date","Bestelldatum");
define("status","Status");
define("cancelled","Annulliert");
define("reissued","Wieder ausgestellt");
define("sent","Gesendet");
define("notsent","Not Sent");
define("paid","Bezahlt");
define("notpaid","Not Paid");
define("seat","Seat");
define("zone","Zone");
define("price","Preis");
define("autocancel","After this time your order is automaticaly canceled!");
define("buytimeleft","You have ~DAYS~ Days, ~HOURS~ Hours, ~MINS~ Mins, ~SECS~ Seconds left to buy your ticket(s)!");
define("payhere","<br><br>To Pay Now, Check Here:");
define("paytimeleft","You Have ~MINS~ Mins and ~SECS~ Seconds to pay for your order!");
define("number_tickets","Number of tickets");
define("userid","User id");
define("ordertickets","To Order Tickets Click below:");
define("reserv_cancel","WARNING Reservation is CANCELED on Ordering!");
define("options","Options");


#personal_page.tpl

define("personal","Personal Details");
define("categry","Category");
define("orders","Bestellungen");
define("id","ID");
define("reserved","Reserved");
define("user","User");
define("pers_mess","To change your details please click <a href="index.php?personal_page=details">here</a>");
define("pers_mess2","To change or see your orders in more detail please click <a href="index.php?personal_page=orders">here</a><br><br>Only the last 6 orders are showed here.");
define("ordernumber","Order Number");
define("Orderdate","Date Ordered");
define("pers_cancel","Canceled");
define("pers_reissue","Remade");
define("pers_res","Reserved");
define("pers_send","Sent & Payed");
define("pers_payed","Payed");
define("pers_ord","Ordered/Unpaid");
define("pers_unknown","Unknown Status - Contact Admin");
define("program","Programm");
define("div_dates","diverses dates");

#process_list.tpl

define("unprocessed_paid","Unprocessed Paid Orders");
define("processed","Processed"");
define("processed_unsent","Paid and Processed Orders Ready to be Sent");

#resend_activation.tpl

define("act_sent","Your activation email has been sent to your e-mail");
define("act_err","User not found or allready activated check error code");
define("act_notarr","Activation email still not arrived?");
define("act_note","Put your e-mail address in. Click on 'Send' and a new activation email will be send to your e-mail address.");
define("act_send","Send");

#Shop.tpl

define("order_status_changed","Order Status Changed Successfully");

#user.tpl

define("pers_info","Personal information");
define("user_notice","To order your tickets please the following fields and click on CONTINUE. Fields with (*) are mandatory. This information will stay confidential. We will not give out your information or sell it to others. Yo can also choose to become member and not to fill this form another time you order tickets.</i>Members entrance is at the bottom of this page.</i>"""");
define("check_cond","I have read and agree to the General Terms of Sale.");
define("continue","Continue");
define("member","Member");
define("pleaselogin","Please login or register.<br /> To signup please follow the following link: <a href='index.php?register_user=on'>Register</a>");

user_address.tpl

define("your_addr","Your address");

#user_form.tpl

define("lastname","Last name");
define("firstname","First name");
define("address","Address");
define("zip","ZIP");
define("city","City");
define("state","State");
define("country","Country");
define("phone","Phone Number");
define("fax","Fax Number");

#user_login.tpl

define("welcome","Welcome");
define("logout","Logout");
define("pers_page","Personal Page");
define("email","E-mail Address");
define("register","Register Here");
define("forgot_pwd","Forgot me password.");
define("total_without_charges","Zwischensumme");
define("charges","Bearbeitungsgeb&uuml;hr");



#pos


define("preferences","Pr&auml;ferenzen");
define("shopping_cart_pos","Warenkorb");
define("cart_empty_pos","Keine Bestellung in Vorbereitung");
define("homepage","Homepage");
define("event_start","Beginn");
define("event_sold","Anlass ausverkauft");
define("order_details","Bestelldetails");
define("print_order","Bestellung drucken");
define("cancel_and_delete","Status wechseln in &#146;Anulliert&#146; und Tickets wieder freigeben");
define("date_venue","Datum,Ort");
define("confirm_and_print","Best&auml;tigen und Drucken");
define("cancel_order","Bestellung zur&uuml;ckstellen");
define("tickets_printing","Bitte warten: die Tickets werden augedruckt");
define("without_fee","Ohne Bearbeitungsgeb&uuml;hr");
define("save","Speichern");
define("send_orders_printer","Drucken: Abgesetzt zum Drucken");
define("open_with_acrobat","Drucken: &ouml;ffnen mit Acrobat");
define("cancel_ticket","Annulieren Ticket");
define("from","Von");
define("to","Bis");
define("actions","T&auml;tigkeiten");
define("prev","Previous");
define("next","Next");
define("order_canceled","Bestellung annulliert");
define("pay_reg","Bezahlung registriert");
define("invalid_name","Ung&uuml;ltiger Name");
define("invalid_date","Ung&uuml;ltiges Datum");
define("invalid_number","Ung&uuml;ltige Kredit Kart Nummer");
define("cannot_store","Internal error: Cannot store credit card info");
define("cannot_seal","Internal error: Cannot seal credit card info");
define("discounts","Rabatte");
define("place_nr","Platz Nr.");
define("categories_and_prices","Kategorien und Preise");
?>
