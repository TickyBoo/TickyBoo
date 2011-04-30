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
 
#calander.tpl

define("calendar","Calendar");

#cart_content.tpl

define("cart_cont_mess1","Please check your order, to change your tickets remove the ones you have reserved and click 'Order More Tickets'.");
define("cart_cont_mess2","You have 15 minutes before your reserved tickets are released back to sale, so please either order more tickets or continue with your order. Thank you.");
define("shopping_cart","Your shopping cart");
define("cart_empty","Your cart is empty.");
define("event","Event");
define("tickets","Tickets");
define("total","Total");
define("expires_in","Expires in");
define("total_price","Total price");

#cart_resume.tpl

define("shopcart","Shopping Cart");
define("valid_tickets","Valid tickets");
define("expired_tickets","Expired tickets");
define("tick_exp_in","Tickets expire in");
define("x_tick","x Tickets");
define("view_order","View order");
define("checkout","Check Out");
define("no_tick_res","You have no tickets reserved.");
define("Resume_min","min.");
define("tot_tick_price","Total Ticket Price:");

#cart_subcontent.tpl

define("row","Row");
define("normal","Normal");
define("remove","Remove");
define("expired","EXPIRED");

#cart_view.tpl

define("order_more_tickets","Order more tickets");

#cat_descrition.tpl

define("cat_description","Seating Sections and prices");
define("cat_ticketsection","Ticket Section");
define("cat_ticketprice","Ticket Price");
define("category_sold","Category sold");
define("prices_in","Prices in");
define("select_category","Select a category");
define("order_limit","Order limit");


#catagory.tpl

define("sign_in_first","sign_in_first");
define("select_seat","1.Select your seat(s) please");
define("click_on_reserve","2.Click on 'Reserve'");
define("number_seats","Number of seats");
define("only_rows_numbered","Only rows numbered");
define("reserve","Reserve");
define("Please_login","Please login or register to reserve seats.");

#cc_autorize_form.tpl

define("cc_name","Credit card owner name");
define("cc_number","Credit card number");
define("cc_exp","Expiry Date");
define("cc_code","Checknumber");
define("pay","Pay");

#cc_autorize_pay.tpl

define("pay_accept","Payment accepted");
define("pay_refused","Payment not accepted");
define("order_id","Order ID");
define("trx_id","Transation ID");

#discount.tpl

define("discount","Discount");

#event_discription.tpl

define("doors_open","Doors open");
define("dates_localities","Dates and localities");
define("various_dates","Various dates");
define("date","Date");
define("venue","Location");
define("time","Time");
define("indexpage","Index");

#event_groups.tpl

define("event_groups","Event Groups");

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
define("pwd_note","Introduce here your e-mail address. Click on 'Send password' and a new password will be send to you by e-mail");
define("pwd_send","Send password");

#help.tpl

define("helpme","Help information");

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
define("ordered","Ordered");
define("order_date","Order date");
define("status","Status");
define("cancelled","Cancelled");
define("reissued","Reissued");
define("sent","Sent");
define("notsent","Not Sent");
define("paid","Paid");
define("notpaid","Not Paid");
define("seat","Seat");
define("zone","Zone");
define("price","Price");
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
define("orders","Orders");
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




#programm.tpl

define("program","Program");
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


# --- unknown ---


define("total_without_charges","Total without charges");
define("charges","Charges");
define("preferences","Preferences");
define("shopping_cart_pos","Shopping cart");
define("cart_empty_pos","No orders to be finished");
define("homepage","Homepage");
define("event_start","Event start");
define("event_sold","Event sold out");
define("order_details","Order details");
define("print_order","Print order");
define("cancel_and_delete","Status changed to &#146;Canceled&#146; and tickets deleted");
define("date_venue","Date,Venue");
define("confirm_and_print","Confirm and Print");
define("cancel_order","Cancel order");
define("tickets_printing","Tickets are printing, wait please");
define("without_fee","Without fee");
define("save","Save");
define("send_orders_printer","Send Orders to printer");
define("open_with_acrobat","Open Orders in Acrobat");
define("cancel_ticket","Cancel ticket");
define("from","From");
define("to","To");
define("actions","Actions");
define("prev","Previous");
define("next","Next");
define("order_canceled","Order canceled");
define("pay_reg","Payment registered");
define("invalid_name","Invalid name");
define("invalid_date","Invalid Date");
define("invalid_number","Invalid credit card number");
define("cannot_store","Internal error: Cannot store credit card info");
define("cannot_seal","Internal error: Cannot seal credit card info");
define("discounts","Discounts");
define("place_nr","Place Nr.");
define("categories_and_prices","Categories and prices");
?>
