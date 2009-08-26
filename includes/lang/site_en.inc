<?php
//Order - Date/Time, Buttons/Links, Navigation, Passwords, Globals, Warnings, Admin Pages, Control Pages, Web Templates, POS

//DATE / TIME FORMATS
	// Euro Day/Date
	//	define("date_format","%A %e %B %Y");
	// US Day/Date
		define("date_format","%A - %B %e, %Y");
	// 24 hour
	//	define("time_format","%H:%M");
	// 12 hour
		define("time_format","%I:%M %p");

define("longdate_format","%Y-%m-%d %H:%M:%S");
define("shortdate_format","%e %b %Y");
define("shorterdate_format","%h %e");
define("date","Date"); 

//BUTTONS / LINKS
define("add","Add");
define('add_tickets','Add Ticket');
define("admin_list","List");
define("browse","Browse..."); //add to programs
define("cancel","Cancel Order?");
define("checkout","Check Out");
define('clear_selection','Clear Selection');
define("close","Close");
define("continue","Continue");
define("delete","Delete");
define("login_button","OK");
define("order_it","Order");
define("order_more_tickets","Order More Tickets");
define("publish", "Publish" );
define("pwd_send","Send Password");
define("res","Reset");
define("reserve","Reserve");
define("save","Save");
define("search","Search");
define('show_seats','Seating Chart');
define("unpublish","Unpublish");
define("view_order","View Order");

//NAVIGATION
define("nav_first","First");
define("nav_prev"," < ");
define("nav_next"," > ");
define("nav_last","Last");

	// Only used in Control
		define("firstpage","First page");
		define("previouspage","<<< ");
		define("nextpage"," >>>");
		define("lastpage","Last page");
		
//PASSWORDS
	define("password","Password");
	define('password1', "Choose Password");
	define("password2","Repeat Password"); 
	define("new_password","New Password");
	define("old_password","Old Password");
	define("confirmpassword","Confirm Password");

//Globals
define("remove_image","Remove picture");
define("yes"," YES");
define("no"," No");
define("home","Home");

define("user_firstname","First Name");
define("user_lastname","Last Name");
define("user_address","Address");
define("user_address1","Address 1");
define("user_address2","Address 2");
define("user_city","City");
define("user_state","State");
define("user_state_name","State");
define("user_zip","Zip");
define("user_country","Country");
define("user_country_name","Country");
define("user_phone","Phone");
define("user_fax","Fax");
define("user_email","E-Mail");
define("user_id","ID");
define("user_status","Status");

//Warnings
define("pass_not_egal","Passwords do not match");
define("already_exist","This name already exists. Choose another.");
define("in_use","This object is used and cannot be deleted");
define("delete_item","Delete?");
define("event_order_limit_exceeded","Sorry, The number of seats that you can order for this event is limited!");
define("undefined","Undefined");
define("mandatory"," Field Required");
define("stop_success","Successfully Stopped");
define("restart_success","Successfully Restarted");
define("nosale_confirm_msg","Remove from Sale?");
define("alreadyexist","This E-mail is already Registered in the Database!");
define("ticket_not_found","Ticket Not Found in Database");
define("order_not_found","Order Not Found");
define("no_result","No Result");
define("compilation_succeed","Success - Template Compiled");
define('act_error',   'Your account could not be activated. 
						Please re-check the link or contact the system administrator.');
define('act_uselink', 'Please use the link that has been sent to your email address.');
define('log_err_mailnotsend', 'The activation email could not be sent, please check you have the correct email address.');
define("act_err", "User not found or already activated.");
define('mand_all', "All input fields are required.");
define('pwd_second_missing', "Second Password missing.");
define('pwd_to_short', "Password too short.");
define('pwd_not_thesame', "The passwords you entered do not match.");
define('AutoCancel_order','Auto Cancel: Failed to order.');
define('AutoCancel_paying','Auto Cancel: Failed to pay for order.');
define('check_condition', "Please accept the terms");
define('system_halted','SYSTEM IS HALTED');
define('Status_is_pub','This event is published');
define('deleted_all_tickets','Deleted All Tickets');

//------------------------------------------------------
//ADMIN

//Administration Menu
define("welcome","Welcome");
define("index_admin","Home"); define("administration","Administration");
define("users_admin","System Managers"); 
define("event_admin","Events"); 
define("stats","Statistics"); 
define("order_admin","Order Overview");  
define("template_admin","Templates"); 
define("payment_admin","Order Handlings"); 
define("search_order","Search"); 
define("imp_export_admin", "Import /Export"); 
define("garbage","Garbage");
define("logout","Log Out");

//Home
//Information
define("index_admin_tab","Information"); //add tab
define("current_version",	"Version:");
define("InfoWebVersion",	"Server Software");
define("InfoPhpVersion",	"PHP Version");
define("InfoMysqlVersion", 	"MySQL Version");
define("InfoUserCount",    	"User count:");
define("InfoGroupCount",   	"Group count:");
define("InfoVenueCount",   	"Venue count:");
define("InfoEventCount",   	"Event count:");
define("InfoAdminCount",   	"Admin count:");
define("system_summary",	"System Summary");
define("index_admins_count","<b>Admins:</b> %d, <b>Organizers:</b> %d, <b>Controlers:</b> %d, <b>Total:</b> %d");
define("index_user_count","<b>POS:</b> %d, <b>Guests:</b> %d, <b>Members:</b> %d/%d/%d");
define("index_events_count","<b>Pub:</b> %d, <b>New:</b> %d, <b>Nosale:</b> %d, <b>Trash:</b> %d, <b>Total:</b> %d");
define('index_ort_count','%d');

//Owner
define("owner_tab","Owner"); //add tab
define("organizer_update_title","Modify Owner information");
define("organizer_name","Name");
define("organizer_address","Address");
define("organizer_plz","Zip");
define("organizer_ort","City");
define("organizer_state","State");
define("organizer_country","Country");
define("organizer_phone","Phone");
define("organizer_fax","Fax");
define("organizer_email","E-Mail");
define("organizer_currency","Currency");
define("organizer_logo","Owner's Logo");
define("organizer_logo_new","New Logo Image"); //add
define("organizer_remove_image","Remove Logo");

//Configuration
define("shopconfig_tab","Configuration"); //add tab
define("option_update_title","Config Shop Auto-Updates");
define("shopconfig_lastrun_int","Order check interval");
define("shopconfig_restime","Reservation time. (<10 = off)");
//define("shopconfig_restime_remind","When reminder email is sent");
define("shopconfig_check_pos","Don't check POS orders");
	define("confirm_yes","Yes");
	define("confirm_no","No");
define("shopconfig_delunpaid","Global Delete Unpaid Orders");
define("option_others_title","Other Options");
define("shopconfig_user_activate","Users Checkout Level");
	define ("act_restrict_cart","Restrict Cart to Members");
	define("act_restrict_all","Only activated members");
	define("act_restrict_later","New members are allowed");
	define("act_restrict_w_guest","New members and guests");
	define("act_restrict_quest_only","Only guest users");
define("shopconfig_maxres","Max Tickets Allowed");
define("shopconfig_posttocollect","Switch to Alt Paymment");
define("res_delay","Ticket Timeout");
define("cart_delay","Cart Timeout");

//System Managers
//Globals
	define("admin_login","Login Name");

define("admin_user_tab","Admins"); //add tab
define("admin_user_title","All System Administrators");
define("admin_user_update_title","Modify Administrator");
	define("admin_user_add_title","Add System Administrator");
	define("admin_user_login","Login Name"); //change to admin_login

//Organizers
define("organizer_tab","Organizers"); //add tab
define("organizer_user_title","All Event Organizers");
define("organizer_user_add_title","Add an Event Organizer");

define("organizer_user_update_title","Modify Event Organizer");

//Sale Points
define("spoint_tab","Sale Points"); //add tab
define("spoint_title","All Points of Sale");
define("spoint_add_title","Add a Point of Sale");
define("spoint_update_title","Modify Point of Sale");
		define("kasse_name","POS Name");
		define("user_prefs","Preferences");
			define("user_prefs_pdf","Open Orders in Acrobat");
			define("user_prefs_pdt","Send Orders to Printer");
		define("user_nickname","Nickname");

//Ticket Takers
define("control_tab","Ticket Takers"); //add tab
define("control_title","All Ticket Takers");
	define("control_view","Ticket Taker");
	define("control_add_title","Add Ticket Taker");
	define("control_update_title","Modify Ticket Taker");
		define("control_login","Login");
		define("control_event_ids","Authorized Events");
		
//Venues
//OrtView.php
define("ort_admin","Venues"); //tab
define("ort_title","All Venues");
	define("ort_add_title","Add Venue");
	define("ort_update_title","Modify Venue");
		define("ort_id","Venue ID");
		define("ort_name","Venue Name");
		define("ort_address","Address");
		define("ort_address1","Address 1");
		define("ort_zip","Zip");
		define("ort_city","City");
		define("ort_state","State");
		define("ort_country","Country");
		define("ort_phone","Phone");
		define("ort_fax","Fax");
		define("ort_url","Homepage");
		define("ort_pm","Googlemaps link");
		define("ort_image","Venue Logo");
		define("ort_remove_image","Remove Venue Logo"); //add

define("place_maps","Seat Charts");
	define("edit_pm","Modify Seat Chart");
	define("pm_id","Seat Chart ID");
	define("pm_name","Seat Chart Name");
	define("pm_image","Seat Chart Image");

define("categories","Categories");
	define("both","Rows/Seats");
	define("rows","Rows");
	define("seat","Seats");
	define("none","None");
	define("category_add_title","Add a Category"); //add
	define("category_update_title","Modify Category");
		define("category_id","Category Number");
		define("category_name","Category Name");
		define("category_price","Category Price");
		define("category_template","Template for Tickets");
		define("category_color","Color for Category");
		define("category_numbering","Category Numbering");
			define("category_numbering_both","Rows and Seats Numbered");
			define("category_numbering_rows","Only Rows Numbered");
			define("category_numbering_seat","Only Seats Numbered (No Rows)");
			define("category_numbering_none","Seats w/o Numbers (Open Seating)");
		define("category_size","Category Size");
		define("number_taken","Seats Sold");	 
		define("category_data","Category Data");

define("pm_zones","Zones");
	define("pm_zone","Zone");
	define("pm_add_title","Add a Zone"); //add
	define("pm_update_title","Modify Zone"); //add
		define("pmz_pm_id","Seat Chart ID #");
		define("pmz_color","Zone Color");
		define("pmz_name","Zone Name");
		define("pmz_short_name","Zone Nickname");
		define("pmz_id","Zone Record Number");
		define("pmz_ident","Zone Number");
	define("place_map","Seat Chart"); //link
	define("define","Define"); //button
	define("clear","Clear"); //button

define("pm_parts","Seat Chart Parts");
	define("pm_add_title","Add a Seat Chart Part");
	define("pm_part","Seat Chart Part");
		define("pmp_id","Seat Chart Part ID");
		define("pmp_name","Seat Chart Part Name");
		define("pmp_width","Seat Chart Part Width");
		define("pmp_height","Seat Chart Part Height");
		define("pmp_scene","Scene Location");
			define("pmp_scene_none","None");
			define("pmp_scene_north","North");
			define("pmp_scene_east","East");
			define("pmp_scene_south","South");
			define("pmp_scene_west","West");
		define("pmp_shift","Shift Rows");

	define("labels","Labels");  //add/fix in program
		define("label_type_text","Text");
		define("label_type_row_east","Row nr. right");
		define("label_type_row_west","Left row nr. ");
		define("label_type_seat_south","Nr of seat below");
		define("label_type_seat_north","Nr.of seat above");
		define("label_type_exit","Exit");

	define("seat_numbering","Seat Numbering");
		define("autonumber_pmz","Automatic numbering");
		define("first_row","First Row");
		define("step_row","Row Step");
		define("inv_row","Invert Rows");
		define("first_seat","First Seat");
		define("step_seat","Seat Step");
		define("inv_seat","Invert Seat");
		define("flip","Flip");
		define("keep","Keep");
		define("map","Chart"); //link

define("ort","Venue");
define("ort_plan_nr","Number on plan");



//Event Section

//Event Groups
define("event_group_tab","Groups"); //tab
define("event_group_title","All Event Groups");
define("event_group_add_title","Add an Event Group");
define("event_group_update_title","Modify Event Group");
	define("event_group_id","Group ID");
define("event_group_name","Group Name");
define("event_group_start_date","Start Date");
define("event_group_end_date","End Date");
define("event_group_type","Group Type");
	define("festival","Festival");
	define("tournee","Tournament");
	define("theatre","Theaterl");
define("event_group_description","Description");
define("event_group_image","Group Picture");

define("unpublish_event_group","Unpublish Event Group" );
define("publish_event_group", "Publish Event Group");


//Events
define("event_tab","Events"); //tab
define("history_tab","History"); //tab
define("event_title","All Events");
define("checkbox_all","Check/Uncheck All"); //add
define("also_sub","Subevents also");
define("event_add_title","Add a New Event");
define("event_edit_title","Modify Event");
	define("event_id","Event ID#");
	define("event_name","Event Name");
	define("event_pm_ort_id","Seat Chart");
		define("agenda_only","Agenda Only");
	define("event_ort_name","Venue Name");
	define("event_rep","Repeatability");
		define("event_rep_main","Multiple");
		define("event_rep_unique","Unique");
	define("event_short_text","Event Short Text");
	define("event_text","Event Long Text");
	define("event_url","Homepage");
	define("event_date","Date");
		define("event_recur_type","Re-Occurs");
			define("recure_nothing","No");
			define("recure_daily","Daily");
				define("event_recur_end","Event Stops");
				define("recure_days_selection","Days to Exclude");
				define("dts_day_arr", "Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday");
	define("event_time","Event Begins");
	define("event_open","Doors Open");
	define("event_end","Event Ends");
	define("event_order_limit","Order Limit");
	define("event_template","Template for Tickets");
	define("event_type","Event Type");
		define("ballet","Ballet");
		define("blues","Blues");
		define("sacred","Church music");
		define("cinema","Cinema");
		define("classics","Classical music");
		define("exposition","Exposition");
		define("folklore","Folklore");
		define("funk","Funk");
		define("humour","Humour");
		define("jazz","Jazz");
		define("music","Music");
		define("opera","Opera");
		define("other","Other");
		define("party","Party");
		define("pop","Pop");
		define("rock","Rock");
		define("theater","Theater");
	define("event_image","Event picture");
	define("event_mp3","MP3");

//Discounts
define("discount_title","All Discounts");
define("discount_add_title","Add a Discount");
define("discount_update_title","Modify Discount");
	define("discount_id","Discount ID");
	define("discount_name","Discount Name");
	define("discount_type","Discount Type");
		define("discount_type_fixe","Fixed Discount");
		define("discount_type_percent","Percent Discount");
	define("discount_value","Discount Value");

//Statistics
define("event_stats_title","Statistics");
define("show_text_stats","Text");
define("show_grafik_stats","Graph");

//Order Overview
define("handlings_admin_tab","Handlings"); //add tab
define("event_admin_tab","Events"); //add tab
define("order_list_title","Orders by Payment Handling");
define("order_event_list_title","Orders by Event");
	define("order_type_ordered","Ordered");
	define("order_type_pending","Pending");
	define("order_type_canceled","Cancelled");
	define("order_type_reemited","Re-issued");
	define("order_type_payed","Paid");
	define("order_type_sended","Sent");
	define("order_type_reserved","Reserved");

	//View Order
	define("order_nr","Order #: ");
	define("tickets","Tickets");
	define("user","User ");
	define("order_tickets_nr","Number of Tickets");
	define("tickets_nr","Quantity");
	define("order_fee","Fee");
	define("order_total_price","Total Price");
	define("order_date","Order Date");
	define("order_shipment_status","Shipment Status");
		define("send","Sent");
	define("order_payment_status","Payment Status");
	define("order_status","Status");
	define("processed","Processed");
	define("order_deleted_manual","Order Manually Canceled");
	define("order_responce","Cancellation Type");
	define("order_responce_date","Cancellation Date");

	//Action icons
	define("change_status_to_ord","Change Status to Ordered?");
	define("change_status_to_send","Change Status to Sent?");
	define("change_status_to_no_send","Change Status to Not Sent?");
	define("change_status_to_payed","Change Status to Paid?");
	define("change_status_to_no_payed","Change Status to Not Paid?");
	define("reemit_order","Cancel this order and re-issue with new tickets?");
	define("reemit_ticket","Re-issue the ticket (recalculate unique barcode)? Ticket Nr:");

	//Legend Menu
	define("legende","Explanation");
	define("reserved","Reserved");
	define("ordered","Ordered");
	define("pending","Pending");
	define("payed","Paid");
	define("sended","Sent");
	define("payed_and_send","Paid and Sent");
	define("canceled","Cancelled");
	define("reemited","Re-issued");

	//Action menu
	define("possible_actions","Possible Actions");
	define("view_order_details","View Details");
	define("print_order","Print Order");
	define("change_order_to_ord","Mark 'Ordered'");
	define("send_order_post","Mark 'Sent'?");
	define("no_send_order_post","Mark 'Not Sent'");
	define("change_order_to_payed","Mark 'Paid'");
	define("change_order_to_no_payed","Mark 'Not Paid'");
	define("reemit_order_menu","Cancel and Issue a New Order with New Order Number. Mark as 'Ordered' and 'Re-issued'");
	define("cancel_order","Mark 'Canceled' and Delete Tickets");

//Templates
define("templ_System","System Emails");
define("templ_email","Emails");  
define("templ_pdf2","Html2pdf's" );
define("templ_pdf","Old Pdf's" );
define("template_title","All Templates");
define("template_add_title","Add a Template");
define("template_update_title","Modify Template");
	define("lang","Language");
		define("lang_en","English");
	define("email_from","From:");
	define("email_to","To:");
	define("email_subject","Subject");
	define("email_text","Text Section");
	define("email_html","HTML Section");
	define("template_id","ID #");
	define("template_type","Type");
	define("template_name","Name");
	define("compile_all","Compile all");

//Order Handling
define("handling_title","All Order Handlings");
define("handling_add_title","Add an Order Handling");
define("payment_update_title","Modify the Payment Type");
	define("handling_payment","Payment");
		define("cash","Cash");
		define("entrance","At the Entrance");
		define("invoice","Invoice");
		define("paypal","PayPal");
		define("ideal","iDEAL");
		define("Google","Google");
		define("email","Email");
		define("post","Post");
		define("google","Google");
		
	define("handling_shipment","Shipment");
	define("handling_sale_mode","Selling Method");
		define("www", "Webshop");
		define("sp","POS");
	define("handling_alt","Alt Handling Method");
		define("handling_no_alt","None");
	define("handling_alt_only","Only Alt Handling Method");
	define("handling_expires_min","Minutes until Handling Expires"); 
	define("handling_fee_fix","Fee Fixed");
	define("handling_fee_percent","Fee Percent");
	define("handling_email_template_ord","Email Template for Order");
	define("handling_email_template_send","Email Template for Shipment");
	define("handling_email_template_payed","Email Template for Payment");
	define("handling_pdf_template","PDF Template for Invoice/Receipt");
	define("handling_pdf_ticket_template","PDF Ticket Template");
	define("handling_text_payment","Payment Label");
	define("handling_text_shipment","Shipment Label");
	define("handling_html_template","HTML Template for Explanations");
	
	//paypal
	define("pm_paypal_business","PayPal Account"); 
	define("pm_paypal_test","PayPal Sandbox"); 
	//ideal
	define("pm_ideal_merchantid","Merchant ID"); 
	define("pm_ideal_subid","Sub ID"); 
	define("pm_ideal_issuer","Issuing Bank "); 
	define("pm_ideal_test","Ideal Sandbox"); 
	define("pm_ideal_key","Key"); 
	define("pm_ideal_keypass","Key Password"); 
	define("pm_ideal_pcert","Pcert"); 
	define("pm_ideal_certficate","Certificate"); 
	//Google
	define("pm_google_merchant_id","Merchant ID"); 
	define("pm_google_merchant_key","Key"); 
	define("pm_google_sandbox","Sandbox"); 
	define("pm_google_callback_link","Call Back Link"); 
		define("pm_google_callback_message","Generated On Save"); //add
	define("pm_on_google_cancel_cancel_order","Google Cancellation"); 
	define("pm_on_user_cancel_cancel_order","User Cancellation"); 
	define("pm_on_refund_cancel_order","Refund"); 
//define("Handling_Delete_Unpaid","Delete Unpaid Payments");
//define("handling_id","ID");

//Search
define("patron_tab","Patron"); //add tab
define("search_title_user","Search for Patron");
	define("sale_point","POS");
	define("member","Member");
	define("guest","Guest");
	
define("search_result","Search Result");
define("seat_tab","Seat"); //add tab
	define("search_title_place","Search Seats");
		define("event_list","Event List");
		define("seat_row_nr","Row Number");
		define("seat_nr","Seat Number");
		define("choice_please","Choose Please...");
			define("event","Events");
			define("category","Category");
			define("place","Seat");
			define("price","Price");
			define("bs","Ord.#");
			define("status","Status");
define("order_tab","Order"); //add tab
	define("search_title_order","Search Orders");
	define("order_id","Order Number");
define("barcode_tab","Barcode"); //add tab
	define("search_title_codebar","Search Barcodes");
	define("codebar","Barcode");

//Import/Export
//Export
define("export_admin_title","Export Data");
define("export_xml_event_title","Export Data");
define("export_xml_event_submit","Export It!");
define("export_xml_event_file","Filename");
	define("export_xml_calendar","Calendar");
		define("export_xml_calendar_event","Calendar");
	define("export_templates","Templates");
		define("export_template_id","Choose Template ");
		define("export_template_file","Filename");	
		define("export_submit","Export It!");	
	define("export_xml_event","Event in XML");
		define("export_xml_event_event","Choose an Event");
	define("export_xl","Sales Data");
		define("xl_view_title","Sales Data");
		define("xl_start","Start Date");
		define("xl_end","End Date");
		define("generate_xl","Generate Sales Worksheet");
	define("export_cc","Credit Cards");
		define("export_cc_title","Export Credit Card Numbers");
		define("export_cc_count","Credit Card Count");
	define("export_entrant","Orders");
		define("export_entrant_title","Export Attendees");	
		define("export_entrant_event","Choose Event");	
		define("export_entrant_NotSended","'Not Sent' Orders only");

//Import
define("import_admin_title","Import Data");
define("import_xml_title","Import Files");
define("import_xml_submit","Import It!");
define("import_xml","Event in XML");
	define("import_xml_file","Import File");
define("import_template","Templates");
	define("template_name","Name");
define("import_langauge","Language Files");
	define("import_csv_file","Import File Name");
	define("langauge","New Language File Name");

//Garbage
define("empty_trash","Empty");
define("Record_Orphan_Test","Orphaned Database Records");
define("unused_guests","Unused Guests");
define("order","Orders");

//---------------------------------------------------------
//CONTROL

// Most Control defines are found in Admin or Globals.
define("change_event","Change Event");
define("check","Check");
define("checked","Checked");
define("check_success","Ticket Check OK");
define("com","Ordered");
define("control_events_list","Events");
define("free","Free");
define("not_valid_event","Event is Not Valid");
define("order_payment_mode","Payment Mode");
define("order_shipment_mode","Shipment Mode");
define("place_not_commanded","Seat is Not Ordered");
define("place_only_reserved","Seat Reserved, but Not Paid");
define("place_status_not_updated","Error: Seat Status Not Updated");
define("place_without_nr","Seat Without Number");
define("ticket_already_checked","Ticket Already Checked");

//----------------------------------------------

//WEB TEMPLATES

//user_activate.tpl - resend_activation.tpl - user_registred.tpl
define("act_name","Activate account"); //checkout_preview
define('act_enter_title','Place your activation code here:');
define('act_enter_code','You can complete your activation by filling the box below with the activation code sent to you by email. Or click on the link in the email.');
define('act_code','Activation code:');
define("act_send","Send");
define("act_notarr","Activation email still not arrived?");
define('resend_activation','Resend Activation Email');
define("act_is_sent","Your activation email has been sent to your e-mail");
define('act_sent','The activation email has been sent. Please check your in box for a link.');
define("act_mess_line","Please check you email to activate your account.<br><br>");
define("act_note","Enter your e-mail address. Click on 'Send'; and a new activation
        email will be sent to your e-mail address.");
define("act_norecived","If you have not recived an activation email within 
						a few hours, please click this <strong>link</strong>");
define('success_activate','You have successfully activated your account.<br /> You may now login.');

//agb.tpl
define("agb_name","General Terms of Sale");
define("agb_info","Put your sales conditions in 'agb.html'");

//calendar.tpl
define("calendar","Calendar");

//cart_content.tpl
define("cart_empty","Your cart is empty.");
define("event","Event"); //personal_order.tpl, personl_orders.tpl
define("tickets","Tickets"); //personal_order.tpl, personal_orders.tpl
define("total","Total");
define("expires_in","Expires in");
define("total_price","Total Price"); //personal_order.tpl, personal_orders.tpl

//cart_resume.tpl
define("shopcart","Shopping Cart");
define("no_tick_res","You have no tickets reserved.");
define("valid_tickets","Valid tickets:");
define("expired_tickets","Expired tickets:");
define("tick_exp_in","Tickets expire in");
define("x_tick","x Tickets");
define("tot_tick_price","Total Ticket Price:");

//cart_subcontent.tpl
define("row","Row"); //discount.tpl
define("normal","Normal"); //discount.tpl
define("expired","EXPIRED");
define("minutes","min.");
define("remove","Remove");

//cart_view.tpl
define("shopping_cart","Your shopping cart");
define("cart_cont_mess","Please check your order. You have 15 minutes before your reserved
        tickets are released back to sale, so please either order more tickets or continue
        with your order. <br><br>To change your tickets remove the ones you have reserved
        and click 'Order More Tickets'.");

//cat_description.tpl
define("cat_description","Seating Sections and Prices");
define("tickets_available","Available:");
define("category_sold","Category Sold");
define('Discount_for','With Discount:');
define("prices_in","Prices in");
define("Please_login","<center><b>Please login or register to reserve seats.</b></center>");
define("select_category","Select a Category");
define("order_limit","Order Limit");
define('old_event','<center><B>This event is expired and can not be ordered anymore.</b></center>');

//category.tpl
define("select_seat","1. Select your seat(s) please");
define("click_on_reserve","2.Click on 'Reserve'");
define("order_limit","Order limit"); //cat_description.tpl
define("number_seats","Number of seats");
define("only_rows_numbered","Only rows numbered");

//checkout_confirm.tpl
define("order_reg","Order registered");
define("tnx_order_mess","Thank you very much! Your order was registered. To pay for your
        order follow the proccess below.<br><br>To view your order click");
        
//checkout_preview.tpl
define("shopping_cart_check_out","Select Payment method");
define("Handling_cont_mess"," ");
define("handlings","Payments");
define("payment","Payment"); //personal_order.tpl
define("shipment","Shipment"); //personal_order.tpl
define("limit","Unfortunately, you have exceeded the amount of allowed reserved tickets. To check
        your orders click <a href='index.php?personal_page=orders'>here</a>.");
define("orclick","Or Click below to:");

//checkout_result.tpl
define("pay_accept","Payment accepted");
define("pay_refused","Payment not accepted");
define("pay_reg","Payment registered");
define("order_id","Order Number"); //personal_order.tpl, personal_orders.tpl
define("trx_id","Transation ID");

//discount.tpl
define("discounts","Discounts");
define("place_nr","Seat nr");

//event.tpl
define("shop","Order Tickets");
define("shop_info","Select the desired number of tickets. Click on 'Next', to
        check and confirm your order.");
define("shop_condition","Conditions: Information collected by the Organizers will
        be stored to handle shipping and payment.");
        
//event_description.tpl
define("doors_open","Doors Open");
define("dates_localities","Dates and Locations");
define("various_dates","Multiple Dates");
define("venue","Location");

//event_groups.tpl
define("event_groups","Event Groups");

//event_type.tpl and event_types.tpl
define("et_church","Church music");
define("et_cinema","Cinema");
define("et_classics","Classical Music");
define("et_expo","Expo");
define("et_folk","Folk");
define("et_humour","Humour");
define("et_jbf","Jazz/Blues/Funk");
define("et_music","Music");
define("et_opbal","Opera/Ballet");
define("et_other","Other");
define("et_party","Party");
define("et_pr","Pop/Rock");
define("et_theater","Theater");

//forgot_password.tpl
define("forgot_pwd","Forgot my password.");
define("pwd_forgot","Forgotten your Password?");
define("forgot_password","Forgotten your Password?");
define("pwd_err","User not found or other problem occured");
define("pwd_is_sent","Your new password was sent to your e-mail");
define("pwd_note","Enter your e-mail address here. Click on 'Send Password'
        and a new password will be emailed to you");
define("close","Close");

//help.tpl
define("helpme","Help information");
//file is not currently used.  words need to be defined.

//last_event_list.tpl
define("up_events","Upcoming Events");
define('eventlist_info', 'Please select an upcoming event from below.');

//personal_order.tpl
define("orders","Orders");
define("number_tickets","Number of tickets"); //personal_reorder.tpl
define("userid","User Id");
define("order_date","Order Date");
define("status","Status");
define("reserved","Reserved");
define("ordered","Ordered");
define("cancelled","Cancelled");
define("reemited","Re-issued");
define("buytimeleft","You have ~DAYS~ Days, ~HOURS~ Hours, ~MINS~ Mins, ~SECS~ Seconds left to confirm your order!");
define("autocancel","After this time your order is automaticaly canceled!");
define("ordertickets","To Order Tickets Click below:");
define("reserv_cancel","WARNING Reservation is CANCELLED on Ordering!");
define("notpaid","Not Paid");
define("paid","Paid");
define("payhere","<br><br>To Pay Now, Check Here:");
define("notsent","Not Sent");
define("sent","Sent");
define("go_back","Click Here to Go Back");
define("id","ID");
define("category","Cat.");
define("zone","Zone");
define("seat","Seat");
define("discount","Discount");
define("price","Price");
define("none","None");
define("paytimeleft","You Have ~MINS~ Mins and ~SECS~ Seconds to pay for your order!");

//personal_orders.tpl
define("pers_orders","Current / Previous Order");
define("options","Options");
define("pers_cancel","Cancelled");
define("pers_ord","Ordered/Unpaid");
define("pers_page","Personal Page"); //user_logon_block.tpl
define("pers_payed","Paid");
define("pers_reemit","Re-issued");
define("pers_res","Reserved");
define("pers_send","Sent & Paid");
define("pers_unknown","Unknown Status - Contact Admin");

//personal_page.tpl 
define("personal","Personal Details");
define("pers_mess","To change your details please click <a href='index.php?personal_page=details'>Here</a>");
define("pers_mess2","To change or see your orders in more detail please click
         <a href='index.php?personal_page=orders'>Here</a><br><br>Only the last 6 orders are shown here.");

//personal_reorder.tpl
define("user","User");
define("payment_status","Payment Status");
define("shipment_status","Shipment Status");

//programm.tpl
define("program","Program");
define("div_dates","Multiple dates");

//user_address/tpl
define("your_addr","Your Address");
define("edit","Edit");

//user_form.tpl
define("confirmemail","Confirm E-mail Address");

//user_login.tpl
define("login","Login name");
define("memberinfo","Please enter your details below. To buy tickets you are required to
        sign up, it is recommend that you sign up before you reserve tickets.");
define("email","E-mail Address"); //personal_page.tpl, forgot_password.tpl
define("register","Register Here");

//user_login_block.tpl
define("member","Member");
define("welcome","Welcome");
define("logout","Log out");

//user_register.tpl
define("becomemember","Become a Member");
define("pers_info","Personal Information");
define("user_notice","To order your tickets please complete the following fields and click on
        'Continue'. Fields with (*) are mandatory. This information will stay confidential.
        We will not give out your information or sell it to others. Yo can also choose to
        become a member and never fill this form again.");
define("becomememberorguest","Register as Member or Guest");
define("becomeguest","Register as Guest");
define('guest_info', "Fill your details here, so that the ticket can be sent to you.
					 <br> Already a regular customer? Fill at the right side, your email address and password.");
define('pwd_min', " (min 6 letters)");
define('user_nospam', "Security Code");
define('nospam_info', "<------ Enter This Code ------>.");
define("agrement","I have read and agree to the General Terms of Sale.");
define("mayuse","I agree that {\$organizer->organizer_name} may use my details to send
        me information about future events and other {\$organizer->organizer_name} related 
        matters, and that they will not pass on my details to any 3rd party.");

//Progressbar.tpl
define("prg_order","Order");
define("prg_discounts","Discounts");
define("prg_review","Review Order");
define("prg_signin","Sign In");
define("prg_payment","Payment");
define("prg_complete","Complete");

//---------------------------------------------------------

//POS

define("total_price","Total Price");
define('exst_user','Existing');
define('new_partron','New Patron');
define("without_fee","Without Fee");
define("ordertickets","To Order Tickets Click below:");

define("event_sold","Event sold out"); //POS/EVENT_GROUP.TPL
define("event_start","Event start"); //POS/EVENT.TPL
define('pos_calender_info','To Book/Reserve Tickets, select an event from the list below.');
define('pos_cart_info','Check the tickets below: to add more click "Order More Tickets"<br>'.
       'Check the Delivery method and Payment Method.<br>'.
       'To reserve the tickets click "Reserve Tickets Button".<br>');
define('select_seat_info', ', the seat name will appear if you hover your mouse over it.<br>'.
       'Free Seats are Green, Red Seats are Taken.');
define('pos_timestampt','Time stamp');
define('pos_deleteorder','Delete Order?');
define('pos_reorder_info','To Order Tickets Click below:<br><font color="red">WARNING old order is CANCELED on Re-Order! </font>');
define('pos_selectuser','Either Create a new user, or select an exsiting user if they have already purchased from us before.');
define('pos_freetickets','Free / Complimentary Tickets');
define('pos_exsistingusers','Exsisting Users');
define('pos_userlist','List of Box office users:');
define('pos_gobacktoorders','Click to go back to list of Processed Orders');
define('pos_ticketready','The tickets have there ID and Order ID on them and are ready to to be posted?');
define('pos_clicktosent','Click To Set to Sent!');
define('pos_nextorder','Next Processed Order');
define('pos_gotounprocessed','Click to go back to list of UnProcessed Orders');
define('pos_processorder','Processed Order? The Order ID and Ticket IDs been noted?');
define('pos_clicktoproc','Click To Finish Processing!');
define('pos_nextunprocessed','Next Unprocessed Paid Order');
define('pos_clicktoview','Click to View and Process');
define('pos_listpaidunprocessed','<p>List of <u>paid</u> <b>but</b> <u>Unprocessed</u> orders</p>');
define('pos_listpaidunsent','<p>List of <b>paid, processed</b> but <u>Unsent</u> orders</p>');
define('pos_listpaidsent','<p>List of <u>completed/sent</u> orders</p>');
define('pos_paidlist','Paid List');
define('pos_unsentlist','UnSent List');
define('pos_sentlist','Sent List');
define('pos_sentorders','Sent Orders');
define('pos_checkdetails' ,'Check the details, If cash or check Click "Confirm and Print"<br>If you have to pay with Credit/Debit Card click the "PayNow" button and Follow the Paypal instructions.');
define('pos_checkdetails2','Check the details, And Procced to "Confirm" or "Cancel" the order.');
define('pos_checkdate','First Check you have the right date, Then choose catergory of seats, this just determins the cost and area. Then click continue.');
define('pos_homepage','Home Page');
define('pos_currenttickets','Current Tickets');
define('pos_booktickets','Book Tickets');
define('pos_holdtickets','Hold Tickets');
define("order_status_changed","Order Status Changed Successfully"); //pos
define('dts_month_arr', 'January|February|March|April|May|June|July|August|September|October|November|December');
define('timestamp','timestamp');//pos
define('user_name','user_name');//POS USER.TPL
define('webshop','webshop');

//Added by Niels 8-22-09
define('event_end','event_end');
define('No_useraddress_selected','No useraddress selected.');
define('No_handling_selected','No handling method selected.');
define('noting_checkout','There is noting to checkout!!!');

// Change to user_* in pos and delete this section
define("firstname","First Name");
define("lastname","Last Name");
define("address","Address");
define("address2","Address 2");
define("city","City");
define("zip","Zip");
define("state","State");
define("phone","Phone Number");
define("fax","Fax Number");

define('error_eventnotfound','Event not found');
define('error_missingcategorytype','Missing category numbering type');
define('error_discountnotfound','Discount not found');
?>