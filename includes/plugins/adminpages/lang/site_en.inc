<?php
// Global Search and Edits Tab in 'Admin -> Search' :: Added by Lxsparks May 2011 //
 define('form_title_main','Main Search form');
 define('global_tab','Global Search/Edit');
 define('search_title_global','Search Customer records');
 define('search_notes_global','Leaving all fields empty will pull every customer record who are Members, Guests and/or POS customers');
 define('user_active','Customer has activated their account');
 define('user_ticket_status','Customer currently has tickets');
 define('user_login_last','Customer has not logged on since');
 define('?','?');
 define('N/A','N/A');
 define('Yes','Yes');
 define('No','No');
 define('global_customer__activated_both','Account activated: n/a');
 define('global_customer__activated_yes','Account activated: yes');
 define('global_customer__activated_no','Account activated: no');
 define('global_customer__tickets_both','Has tickets: n/a');
 define('global_customer__tickets_yes','Has tickets: yes');
 define('global_customer__tickets_none','Has tickets: no');
 define('global_customer_login_req','User has not logged on since');
 
 define('global_search_title_street','Search by Street');
 define('global_search_notes_street','The wildcard \'?\' can be used (e.g. ?street, or ? The High Street)');
 define('global_search_title_city','Search by Town/City');
 define('global_search_notes_city','The wildcard \'?\' can be used.');
 define('global_search_title_state','Search by County');
 define('global_search_notes_state','The wildcard \'?\' can be used.');
 define('global_search_title_zip','Search by Postcode');
 define('global_search_notes_zip','The wildcard \'?\' can be used (e.g. NW?, or GL? NN');
 define('global_search_title_zipRange','Search Postcodes range');
 define('global_search_notes_zipRange','Search for postcdes between two values - again the \'?\' wildcard can be used.');
 define('user_zip_start','Postcodes between:');
 define('user_zip_end','and:');
 define('global_search_title_ticketsBetween','Tickets bought between');
 define('global_search_notes_ticketsBetween','Seach for customers who have bought tickets between two dates.');
 define('user_tickets_between','Tickets between:');
 
 define('search_title_address','Search: Address');
 define('search_title_name','Search: Name');
 define('search_title_combine','Search: Duplicate customers');
 define('search_title_activity','Search: Customer activity');
 define('search_title_admin','Admin Section');
 
 define('','');
 define('','');
 define('','');
 define('','');
 define('','');
 
 define('global_table_id','ID');
 define('global_customer_address','Searched on street that matched');
 define('global_table_name','Name');
 define('global_table_city','City');
 define('global_customer_city','Searched on Town/City that matched');
 define('global_table_zip','Postcode');
 define('global_customer_zip','Customers that matched the postcode');
 define('global_customer_zipRange','Searched for Postcodes in the range');
 define('global_table_joined','Joined');
 define('global_table_last_visit','Last Visited');
 define('global_customer_tickets_between','Customers who bought tickets between');
 define('global_table_qty','QTY');
 define('global_table_status','Status');
 define('global_table_results_header','Search returned');
 define('global_table_customers','customers');
 define('search_title_global_posMerge','Duplicate customers search: Members, Guests & POS');
 define('search_notes_global_posMerge','Search for duplicate users who have entries as Members AND Guests or POS customers');
 define('posMerge_email','Check on emails');
 define('posMerge_select_user','Combine POS/Guest details into Member records?');
 define('search_title_global_posMerge_update','Duplicate customers (Members, Guests & POS)');
 define('search_notes_global_posMerge_update','Note: A customer may register using an email already used by a Guest and/or POS User but not the otherway round.<br /><br />
 Tickets of POS and Guest customers will be moved to Member records.  <br /><br /> ***WARNING***  Backup database before proceeding with merging of any records.  POS/Guest records will be deleted!');
 define('search_choice_one_fields','At least one option must be selected!');
 define('search_title_global_cleanCustomerData','Clean Customer & mailing information');
 define('search_notes_global_cleanCustomerData1','Search for Customer surnames that:');
 define('user_surname_start','are between:');
 define('user_surname_end','and:');
 define('search_notes_global_cleanCustomerData2','Search for Customers by surname - use \'?\' as a wildcard at one or both ends');
 define('user_surname','Surname:');
 
 define('global_table_results_header_edit','Editing');
 define('search_notes_global_edit_manual','Manually edit the data, be-warned once updated there is no \'undo\' button!');
 define('confirm_sure','Are you sure you want to update the Customer record\/s?');
 define('confirm_cancel','Are you sure you want to cancel?');
 define('btn_search','Search');
 define('btn_reset','Reset');
 define('btn_manual_edit','Manual Edit');
 define('btn_format_data','Format Data');
 define('btn_global_edit','Global Edit');
 define('btn_merge','Merge Data');
 define('btn_cancel','Cancel');
 define('global_search_title_AZ','Edit Data: A-Z Surname search');
 define('global_search_notes_AZ','Search for surnames which are between two letters');
 define('global_customer_surname','Surname which matches');
 define('global_customer_surname_between','Surnames that are between the letters');
 define('search_notes_edit_format','These are the results after automatic clean up - further editing can be done by using the manual edit function');
 define('global_search_title_activity','Edit Data: Search Customers by their Activity and/or Status');
 define('global_search_notes_activity','If no options are selected then ALL customer records will be pulled from the database');
 define('global_search_title_surname','Edit Data: Search by Customer Surname');
 define('global_search_notes_surname','Search for Customers by surname <br />Use \'?\' as a wildcard at one or both ends (e.g. \'?sm\', \'?mit?\' or \'th?\')');
 define('global_customer_login_notreq','Last login not selected');
 define('global_update_title_updated','Update success!');
 define('global_update_notes_updated','Customer records updated');
 define('global_update_title_notupdated','No records were updated this time');
 define('global_update_notes_notupdated','We\'re any records altered?');
 define('member_cust_rec','Member customer record:');
 define('pos_cust_rec','POS customer record:');
 define('guest_cust_rec','Guest customer record:');
 define('replaced_by','replaced by');
 define('search_notes_global_edit_global','Data entered will replace the information on ALL the customers currently selected only if the field is \'selected\' (if 
 the field is selected but left blank then this will effectively delete any information stored for the customers).<br /><br />Postcodes and phone numbers will be formated to the standard of the current 
 country that is listed even if it\'s not selected.');
 ?>