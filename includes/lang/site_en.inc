<?php
//These defines are located at the beginning of this file and are used throughout Fusion Ticket.
//Use caution when changing and try not to be page specific with the definition.   
	//Date & Time Formats, Buttons & Links, Navigation, Passwords, Globals, and Warnings.

//These defines are next and mostly page specific.
	//Admin Pages, Control Pages, Web Templates, and POS Templates

//DATE & TIME FORMATS
//comment "//" out the format not used.
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
define("dd_mm_yyyy","(dd-mm-yyyy)");
define("yyyy_mm_dd","(yyyy-mm-dd)");
define("date_from","Date From");
define("date_to","Date To");


//BUTTONS & LINKS
//for continuity all Buttons & Links should be defined the same if initiating the same action
define("add","Add");
define("add_tickets","Add Ticket(s)");
define("admin_list","List");
define("cancel","Cancel Order?");
define("checkout","Check Out");
define("clear","Clear");
define("clear_selection","Clear Selection");
define("close","Close");
define("continue","Continue");
define("define","Define");
define("edit","Edit");
define("delete","Delete");
define("login_button","OK");
define("order_it","Order");
define("order_more_tickets","Order More Tickets");
define("publish", "Publish" );
define("pwd_send","Send Password");
define("remove","Remove");
define("res","Reset");
define("reserve","Reserve");
define("save","Save");
define("search","Search");
define("show_seats","Seating Chart");
define("submit","Submit");
define("unpublish","Unpublish");
define("view","View");
define("view_order","View Order");
define("icon_unpublish","Click to UnPublish");
define("icon_unpublish_alt","Published");
define("icon_publish","Click to Publish");
define("icon_publish_alt","New Event");
define("icon_nosal","Click to RePublish");
define("icon_nosal_alt","UnPublished");

//NAVIGATION  
//should remain the same throughout all pages.
define("nav_first","First");
define("nav_prev"," < ");
define("nav_next"," > ");
define("nav_last","Last");

//PASSWORDS
define("password","Password");
define("password1", "Choose Password");
define("password2","Repeat Password"); 
define("new_password","New Password");
define("old_password","Old Password");
define("confirmpassword","Confirm Password");

//Globals
//basic words used throughout the program
define("event","Event");
define("home","Home");
define("logout","Log out");
define("member","Member");
define("no"," No");
define("order_id","Order Number");
define("remove_image","Remove Image");
define("tickets","Tickets");
define("welcome","Welcome");
define("yes"," YES");

//User Globals
//basic user information not page specific.
define("user_firstname","First Name");
define("user_lastname","Last Name");
define("user_name","Name");
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
define("user_id","User ID");
define("user_status","Status");

//Warnings and Flags
//many of these are page specific but are more easily controlled in one spot.
define("act_err", "User Not Found or Already Activated!");
define("act_error","Account Could Not be Activated! Please re-check the link or contact the system administrator.");
define("act_uselink", "Please use the link that has been sent to your email address.");
define("already_exist","This Name Already Exists. Choose Another!");
define("alreadyexist","This E-mail is Already Registered in the Database!");
define("AutoCancel_order","Auto Cancel: Failed to Order!");
define("AutoCancel_paying","Auto Cancel: Failed to Pay for Order!");
define("cannot_find_seat","Cannot Find Seat!");
define("cannot_seal","Internal Error: Cannot Seal Credit Card Info!");
define("cannot_store","Internal Error: Cannot Store Credit Card Info!");
define("check_condition","Please Accept the Terms!");
define("compilation_succeed","Success - Template Compiled!");
define("correct_errors_first","Correct Errors First!");
define("delete_confirm_msg","Delete This Event?");
define("delete_item","Delete?");
define("deleted_all_tickets","Deleted All Tickets!");
define("delete_subs_first","Delete Sub-Events First!");
define("email_valid","Please Enter a Valid Email Address!");
define("email_match","The Emails Do Not Match!");
define("event_order_limit_exceeded","Sorry, The number of seats that you can order for this event is limited!");
define("fax_short","The Fax Number is too Short!");
define("fax_long","The Fax Number is too Long!");
define("img_loading_problem","Problem Loading Image!");
define("in_use","This Object is in use and <b>cannot</b> be Deleted!");
define("internal_error","Internal Error!");
define("invalid_date","Invalid Date!");
define("invalid_number","Invalid Credit Card Number!");
define("log_err_mailnotsend", "The Activation Email <b>could not</b> be sent, please check you have entered the correct email address.");
define("log_err_not_act","You are Not Activated!");
define("log_err_not_act_info","<br />Please activate your account!
	<p>Please check your email for the activation email. Also check your spam folder. Then follow the link to activate your account</p>
	<p>If you still haven't recieved anything after an hour please click <a href='resend_activation.php'HERE</a> to resend activation email.</p>");
define("log_err_wrong_usr","Wrong Username or Password!");
define("log_err_wrong_usr_info","<p>Please try again, remembering that the username and password are case sensitive.<br />
		If you have forgoten your username or password please click <a href='forgot_password.php'>here</a></p>");
define("mand_all", "All Input Fields are Required!");
define("mandatory"," Field Required!");
define("MP3_loading_problem","Problem Loading MP3 File!");
define("nosale_confirm_msg","Remove from Sale?");
define("new_order_created","New Order Created");
define("not_number","Enter a Number Without -()!");
define("not_valid_email","E-mail is Invalid!");
define("no_result","No Result!");
define("old_order_canceled","Old Order Cancelled!");
define("order_allready_cancelled","Order Allready Cancelled!");
define("order_canceled","Order Cancelled!");
define("order_cannot_reemit","Can Not Re-Issue!");
define("order_not_found","Order Not Found!");
define("order_not_canceled","Order Not Cancelled!");
define("pass_not_egal","Passwords Do Not Match!");
define("pass_match","Passwords Do Not Match!");
define("pass_too_short","Password too Short!");
define("phone_long","The Phone Number is too Long!");
define("phone_short","The Phone Number is too Short!");
define("places_empty","Select a Seat, Please!");
define("places_occupied","Sorry! The selected seats were taken by another customer.");
define("places_remains"," Remaining seats");
define("places_toomuch","This Number of Seats can Not be Reserved!");
define("pwd_not_thesame", "The Passwords Entered Do Not Match!");
define("pwd_second_missing", "Second Password is Missing!");
define("pwd_to_short", "Password Too Short!");
define("pub_confirm_msg","Publish Event?");
define("reservate_failed","Internal Error: Impossible to Save Seats ");
define("restart_success","Successfully Restarted!");
define("save_failed","Internal Error: Impossible to Save the Order ");
define("send_order_email","Send Order by E-mail and Change Status to 'Sent'");
define("split_pm","Split Seat Chart");
define("Status_is_pub","This Event is Published!");
define("stop_success","Successfully Stopped!");
define("system_halted","<b>SYSTEM IS HALTED!<b>");
define("ticket_deleted","Ticket Deleted!");
define("ticket_not_found","Ticket Not Found in Database!");
define("tickets_printing","Tickets are Printing. Please Wait!");
define("too_low","Please Enter a Number Larger than 0 !");
define("undefined","Undefined!");

//------------------------------------------------------
//ADMIN
//these defines are seperated by page and are page specific. 
//indents indicate the define is a sub-page or a drop down list.

//Administration Menu - the menu on the left side
define("index_admin","Home"); 
define("administration","Administration");
define("users_admin","System Managers"); 
define("event_admin","Events"); 
define("stats","Statistics"); 
define("order_admin","Order Overview");  
define("template_admin","Templates"); 
define("payment_admin","Order Handlings"); 
define("search_order","Search"); 
define("imp_export_admin", "Import /Export"); 
define("garbage","Garbage");

//Home
//System Information
define("index_admin_tab","Information");
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
define("index_ort_count","%d");

//Owner
define("owner_tab","Owner");
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
define("organizer_remove_image","Remove Logo");

//Configuration
define("shopconfig_tab","Configuration");
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

define("admin_login","Login Name"); //used in admin, organizer, and spoint

define("admin_user_tab","Admins");
define("admin_user_title","All System Administrators");
define("admin_user_update_title","Modify Administrator");
	define("admin_user_add_title","Add System Administrator");

//Organizers
define("organizer_tab","Organizers");
define("organizer_user_title","All Event Organizers");
define("organizer_user_add_title","Add an Event Organizer");

define("organizer_user_update_title","Modify Event Organizer");

//Sale Points
define("spoint_tab","Sale Points");
define("spoint_title","All Points of Sale");
define("spoint_add_title","Add a Point of Sale");
define("spoint_update_title","Modify Point of Sale");
		define("kasse_name","POS Name");
		define("user_prefs","Preferences");
			define("user_prefs_pdf","Open Orders in Acrobat");
			define("user_prefs_pdt","Send Orders to Printer");
		define("user_nickname","Nickname");

//Ticket Takers
define("control_tab","Ticket Takers");
define("control_title","All Ticket Takers");
	define("control_view","Ticket Taker");
	define("control_add_title","Add Ticket Taker");
	define("control_update_title","Modify Ticket Taker");
		define("control_login","Login Name");
		define("control_event_ids","Authorized Events");
		
//Event Section
		
//Venues
define("ort_admin_tab","Venues");
define("ort_admin","Venues");
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
	define("cat_at","@");
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
		define("pmz_pm_id","Seat Chart ID #");
		define("pmz_color","Zone Color");
		define("pmz_name","Zone Name");
		define("pmz_short_name","Zone Nickname");
		define("pmz_id","Zone Record Number");
		define("pmz_ident","Zone Number");
	define("place_map","Seat Chart"); //link

define("pm_parts","Seat Chart Parts");
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
			//do not edit.  Places stage image 
				define("scene_h","images/scene_h_en.png");
				define("scene_v","images/scene_v_en.png");

	define("label","Label:");
		define("label_type_text","Text");
		define("label_type_row_east","Row# on Right");
		define("label_type_row_west","Row# on Left");
		define("label_type_seat_south","Seat# Below");
		define("label_type_seat_north","Seat# Above");
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

//Event Groups
define("event_group_tab","Groups");
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
	define("theatre","Theater");
define("event_group_description","Description");
define("event_group_image","Event Group Image");

define("unpublish_event_group","Unpublish Event Group" );
define("publish_event_group", "Publish Event Group");

//Events
define("event_tab","Events");
define("history_tab","History");
define("event_title","All Events");
define("checkbox_all","Check/Uncheck All");
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
	define("event_image","Event Image");
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
define("orders_handlings_tab","Handlings");
define("orders_event_tab","Events");
define("order_list_title","Orders by Payment Handling");
define("order_event_list_title","Orders by Event");
	define("order_type_ordered","Ordered");
	define("order_type_pending","Pending");
	define("order_type_canceled","Cancelled");
	define("order_type_reemited","Re-issued");
	define("order_type_payed","Paid");
	define("order_type_sended","Sent");
	define("order_type_reserved","Reserved");
	define("purge","Purge");

	//View Order
	define("order_nr","Order #: ");
	define("order_tickets_nr","Number of Tickets");
	define("tickets_nr","Quantity");
	define("order_fee","Fee");
	define("order_total_price","Total Price");
	define("order_date","Order Date");
	define("order_shipment_status","Shipment Status");
		define("send","Sent");
	define("order_payment_status","Payment Status");
	define("order_payment_id","Payment ID");
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
	define("pm_on_google_cancel_cancel_order","Google Cancellation"); 
	define("pm_on_user_cancel_cancel_order","User Cancellation"); 
	define("pm_on_refund_cancel_order","Refund"); 

//Search
define("patron_tab","Patron");
define("search_title_user","Search for Patron");
	define("sale_point","POS");
	define("guest","Guest");
	
define("search_result","Search Result");
define("seat_tab","Seat");
	define("search_title_place","Search Seats");
		define("event_list","Event List");
		define("seat_row_nr","Row Number");
		define("seat_nr","Seat Number");
		define("choice_please","Choose Please...");
			define("category","Category");
			define("place","Seat");
			define("price","Price");
			define("bs","Ord.#");
			define("status","Status");
define("order_tab","Order");
	define("search_title_order","Search Orders");
define("barcode_tab","Barcode");
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
//define("import_template_name","Name"); see templates
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
define("check_success","Ticket Check OK!");
define("com","Ordered");
define("control_events_list","Events");
define("free","Free");
define("not_valid_event","Event is Not Valid");
define("place_not_commanded","Seat is Not Ordered");
define("place_only_reserved","Seat Reserved, but Not Paid");
define("place_status_not_updated","Error: Seat Status Not Updated");
define("place_without_nr","Seat Without Number");
define("ticket_already_checked","Ticket Already Checked");
define("header_ticket_taker","Ticket Takers");
define("no_event_sets","No Events Scheduled");

//----------------------------------------------

//WEB TEMPLATES (tpl)
//these defines are seperated by tpl file.
//if you can't find the define you are looking for check in Globals.
//if a define is followed by //file.tpl it is also used in that file. 

//user_activate.tpl - resend_activation.tpl - user_registred.tpl
define("act_name","Activate Account"); //checkout_preview
define("act_enter_title","Place your Activation Code here:");

define("act_enter_code","Complete your activation by entering the activation code, sent to you by Email, in the box below. Or click on the link in the Email.");
define("act_code","Activation code:");
define("act_send","Send");
define("act_notarr","Activation Email still not arrived?");
define("resend_activation","Resend Activation Email");
define("act_is_sent","Your Activation Email has been sent to your Inbox!");
define("act_sent","The activation Email has been sent. Please check your Email for a link.");
define("act_mess_line","Please check your email to activate your account.<br><br>");
define("act_note","Enter your e-mail address. Click on 'Send'; and a new activation email will be sent to your e-mail address.");
define("act_norecived","If you have not recived an activation email within a few hours, please click this <strong>link</strong>");
define("success_activate","You have successfully activated your account.<br /> You may now login.");

//agb.tpl
define("agb_name","General Terms of Sale");
define("agb_info","Put your sales conditions in 'agb.html'");

//calendar.tpl
define("calendar","Calendar");

//cart_content.tpl
define("cart_empty","Your Cart is Empty.");
define("total","Total");
define("expires_in","Expires in");
define("total_price","Total Price"); //personal_order.tpl, personal_orders.tpl

//cart_resume.tpl
define("shopcart","Shopping Cart");
define("no_tick_res","You have No Tickets Reserved.");
define("valid_tickets","Valid Tickets:");
define("expired_tickets","Expired Tickets:");
define("tick_exp_in","Tickets expire in ");
define("x_tick","x Tickets");
define("tot_tick_price","Total Ticket Price:");

//cart_subcontent.tpl
define("row","Row"); //discount.tpl
define("normal","Normal"); //discount.tpl
define("expired","EXPIRED");
define("minutes","min.");

//cart_view.tpl
define("shopping_cart","Your shopping cart");
define("cart_cont_mess","Please check your order. You have 15 minutes before your reserved tickets are released back to sale. Please, either order more tickets or continue with your order. 
	<br><br>To change your tickets 'Remove' the ones you have reserved and click 'Order More Tickets'.");

//cat_description.tpl
define("cat_description","Seating Sections and Prices");
define("tickets_available","Available:");
define("category_sold","Category Sold");
define("Discount_for","With Discount:");
define("prices_in","Prices in");
define("Please_login","<center><b>Please Login or Register to Reserve Seats.</b></center>");
define("select_category","Select a Category");
define("order_limit","Order Limit"); //category.tpl
define("old_event","<center><B>This event is expired and can not be ordered anymore.</b></center>");

//category.tpl
define("select_seat","1. Select your seat(s) please");
define("click_on_reserve","2.Click on 'Reserve'");
define("number_seats","Number of seats");
define("only_rows_numbered","Only rows numbered");

//checkout_confirm.tpl
define("order_reg","Order Registered");
define("tnx_order_mess","Thank you very much! Your order was registered. To pay for your order follow the proccess below.
	<br><br>To view your order click ");
        
//checkout_preview.tpl
define("shopping_cart_check_out","Select Payment Method");
define("Handling_cont_mess"," ");
define("handlings","Payments");
define("payment","Payment"); //personal_order.tpl
define("shipment","Shipment"); //personal_order.tpl
define("limit","Unfortunately, you have exceeded the amount of allowed reserved tickets. To check your orders click <a href='index.php?personal_page=orders'>here</a>.");
define("orclick","Or Click below to:");

//checkout_result.tpl
define("pay_accept","Payment Method Accepted");
define("pay_refused","Payment Method Not Accepted");
define("pay_reg","Payment Registered");
define("trx_id","Transation ID");
define("orderdeleted","Order Deleted");
define("printinvoice","Print Invoice");
define("printtickets","Print Tickets");

//discount.tpl
define("discounts","Discounts");
define("place_nr","Seat nr");

//event.tpl
define("shop","Order Tickets");
define("shop_info","Select the desired number of tickets. Click on 'Next', to check and confirm your order.");
define("shop_condition","<br>Conditions: Information collected will only be stored to handle shipping and payment.");
        
//event_description.tpl
define("doors_open","Doors Open");
define("dates_localities","Dates and Locations");
define("various_dates","Multiple Dates");
define("venue","Location");

//event_groups.tpl
define("event_groups","Event Groups");

//event_type.tpl and event_types.tpl
define("et_church","Church Music");
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
define("pwd_note","Enter your e-mail address here. Click on 'Send Password'and a new password will be emailed to you");

//help.tpl
define("helpme","Help information");
//file is not currently used.  Words need to be defined.

//last_event_list.tpl
define("up_events","Upcoming Events");
define("eventlist_info", "Please select an upcoming event from below.");

//personal_order.tpl
define("orders","Orders");
define("number_tickets","Number of tickets"); //personal_reorder.tpl
define("userid","User Id");
define("cancelled","Cancelled");
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
define("zone","Zone");
define("discount","Discount");
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
define("pers_mess2","To change or see your orders in more detail please click <a href='index.php?personal_page=orders'>Here</a><br><br>Only the last 6 orders are shown here.");

//personal_reorder.tpl
define("payment_status","Payment Status");
define("shipment_status","Shipment Status");

//programm.tpl
define("program","Program");
define("div_dates","Multiple Dates");

//user_address/tpl
define("your_addr","Your Address");


//user_form.tpl
define("confirmemail","Confirm E-mail Address");

//user_login.tpl
define("login","Login name");
define("memberinfo","Please enter your details below. To buy tickets you are required to sign up, it is recommend that you sign up before you reserve tickets.");
define("register","Register Here");

//user_login_block.tpl - no unique defines

//user_register.tpl
define("becomemember","Become a Member");
define("pers_info","Personal Information");
define("user_notice","To order your tickets please complete the following fields and click on 'Continue'. Fields with (*) are mandatory. This information will stay confidential. We will not give out your information or sell it to others. You can also choose to become a member and never fill this form again.");
define("becomememberorguest","Register as Member or Guest");
define("becomeguest","Register as Guest");
define("guest_info", "Fill your details here, so that the ticket can be sent to you.
					 <br> Already a regular customer? Fill at the right side, your email address and password.");
define("pwd_min", " (min 6 letters)");
define("user_nospam", "Security Code");
define("nospam_info", "<------ Enter This Code ------>.");
define("agrement","I have read and agree to the General Terms of Sale.");
define("mayuse","I agree that {\$organizer->organizer_name} may use my details to send me information about future events and other {\$organizer->organizer_name} related matters, and that they will not pass on my details to any 3rd party.");
define("member_info","Please enter your email address and password");

//Progressbar.tpl
define("prg_order","Order");
define("prg_discounts","Discounts");
define("prg_review","Review Order");
define("prg_signin","Sign In");
define("prg_payment","Payment");
define("prg_complete","Complete");

//---------------------------------------------------------

//POS

//checkout.php
define("No_handling_selected","No handling method selected.");
define("noting_checkout","There is nothing to checkout!!!");
define("No_useraddress_selected","No user address selected.");

//Errors
define("error_missingcategorytype","Missing category numbering type");
define("error_discountnotfound","Discount not found");

//event_group.tpl
define("event_sold","Event sold out");

//datetimeselect.php
define("dts_month_arr", "January|February|March|April|May|June|July|August|September|October|November|December");

//handlings.tpl
define("without_fee","Without Fee");

//header.tpl
//define("box_office","Box Office");
//define("ajax_beta","<i>[AJAX Beta]</i>");
define("box_office","<h2>Box Office<span style='color:red; font-size:14px;'><i>[AJAX Beta]</i></span></h2>");


//order.tpl
define("alert","Alert:");
define("pos_booktickets","Book Tickets");//header.tpl
define("seat_count","Seat Count:");
define("pos_order_page","Order checkout");

//process_list.tpl
define("actions","Actions");
define("timestamp","Timestamp");

//process.menu.tpl
define("cancel_and_delete","Status Changed to 'Cancelled' and Tickets Deleted");
define("order_details","Order details");

//process_select.tpl
define("order_status_changed","Order Status Changed Successfully");
define("pos_currenttickets","Current Tickets"); //header.tpl
define("pos_paidlist","Paid List");
define("pos_unsentlist","UnSent List");

define("pos_unpaidlist","UnPaid List");
define("pos_reservedlist","Reserved List");

//process_view.tpl
define("paymentstatus","Payment Status");
define("pos_clicktoproc","Click To Finish Processing!");
define("pos_clicktosent","Click To Set to Sent!");
define("pos_deleteorder","Delete Order?");
define("pos_enternote",'Notes about order and tickets:<br><b>Remember to save after any changes!!</b>');
define("pos_goback","Click to go back");
define("pos_nextorder","Next Processed Order");
define("pos_processorder","Processed Order? The Order ID and Ticket IDs been noted?");
define("pos_reorder_info","To Order Tickets Click below:<br>".
		"WARNING old order is CANCELED on Re-Order!");
define("pos_ticketready","The tickets have there ID and Order ID on them and are ready to to be posted?");
define("shipmentstatus","Shipment Status");
define("next_order_id","Next Order ID");
//process_view.tpl; view.tpl; view_reorder.tpl
define("cancel_ticket","Cancel Ticket");
define("reemitted","Re-issued");

//user.tpl
define("exist_user","Existing");
define("new_partron","New Patron");

//view_options.tpl
define("open_with_acrobat","Open Orders in Acrobat");
define("preferences","Preferences");
define("send_orders_printer","Send Orders to printer");

define("select_country","Select Country");
define("eph_cash_confirm","The order is created, press '".con('pay')."' to continue.");

//------------------------------------------------------
//Not quite sure about these.  8/26/09 Lou

//AdminView.php - These are in the file but I can not find where they display
define("page_format_default","Default");
define("page_format_std","Standard");
define("page_format_custom","Custom");
define("page_orientation_landscape","Landscape");
define("page_orientation_portrait","Portrait");

//function.placemap.php
define("place_row","Row");
define("place_seat","Seat");

//EventViewCommon.php
define("payment_cc","Credit Card");
define("payment_post","Post");

//eph_paypal.php
define("paypal_pay","Make payments with PayPal - it's fast, free and secure!");

//OrtView.php
define("pm_title","All charts");
//--------------------------------------------------

//Html2pdf Templates
define("tmp_receipt","Receipt");
define("tmp_invoice","Invoice");
define("tmp_sold_to","Sold To:");
define("tmp_order_date","Order Date");
define("tmp_order_number","Order Number");
define("tmp_order_method","Order Method");
define("tmp_shipping_method","Shipping Method ");
define("tmp_payment_method","Payment Method");
define("tmp_description","Description");
define("temp_quantity","Qty.");
define("tmp_category","Category ");
define("tmp_discounts","Discounts");
define("tmp_price","Price");
define("tmp_total","Total ");
define("tmp_subtotal","SubTotal:");
define("tmp_fee","Handling Fee:");
define("tmp_order_total","Total:");
define("tmp_thank_you","Thank You!");
define("tmp_inv_message","Your seats have been reserved. Please pay this invoice within 10 days. ");

//Tickets
define("tmp_flourish"," Presents");
define("tmp_venue","Location Details");
define("tmp_date_time","Date and Time");
define("tmp_event_date","Event Date:");
define("tmp_start_time","Start Time: ");
define("tmp_open_time","Doors Open: ");
define("tmp_section","Seating Section");
define("tmp_zone","Zone:");
define("tmp_row","Row #: ");
define("tmp_seat","Seat#: ");
define("tmp_details","Ticket Details");
define("tmp_type","Type: ");
define("tmp_discount","Discount: ");

define("tmp_conditions","<b>NOTE TO PURCHASER: TREAT THESE TICKETS AS YOU WOULD ANY OTHER VALUABLE OR CASH.</b>
Unauthorized duplication, alteration, or sale of this ticket may prevent admittance to the event. 
Present this page at the time of admission to be scanned. 
The unique bar code on this ticket allows only one entry per ticket.");

?>