<?php
/*
%%%copyright%%%
 */
?>
<?php
define("cart_empty","Votre panier est vide.");
define("valid_tickets","Billets valides:");
define("next_expire","Temps restant:");
define("minutes","Minute(s)");
define("expired_tickets","Billets expir&eacute;s:");

define("weiter","Continuer");
define("save","Sauver"); //submit
define("res","Annuler"); //reset
define("search","Chercher"); //reset

//events.php
define("order_now","Commander maintenant");

//cat&eacute;gories.php
define("choice_cat_title","Choisir une cat&eacute;gorie");
define("date","Date");
define("open","Ouverture des portes");
define("start","D&eacute;but de l' &eacute;v&egrave;nement");
define("ort","Lieu");
define("ort_phone","T&eacute;l");
define("ort_url","Homepage");
define("categories_prices","Cat&eacute;gories et prix");
define("tickets","Billets");
define("prices","Prix");
define("prices_chf","Prix en CHF");
define("choice_cat","Choisir une cat&eacute;gorie");
define("choice_qty","Quantit&eacute; de places ");

//places.php
define("reservate","R&eacute;server");
define("choice_place_title","Choisir une place");
define("info1","1.SVP, choisissez ici votre place");
define("info2","2.Cliquez sur R&eacute;server");

//view_cart.php
define("ev_name","&Eacute;v&egrave;nement");
define("ev_datum","Date,Lieu");
define("ev_ticket","Billets");
define("cat_total","Total");
define("exp_rm","Expire dans");
define("expired","EXPIRE");
define("warenkorb","Votre panier");
define("remove","Effacer");
define("warenkorb_title","Votre panier");
define("total_price","Montant total");
define("go_pay","Commander");
define("by_post","Envoyer par la poste");
define("back_shop","Commander d'autres billets");
define("place_row","Rang");
define("place_seat","Place");

//functions/user_func.php
define("mandatory","Ce champ est obligatoire");
define("alreadyexist","Cette adresse email est d&eacute;j&agrave; registr&eacute;e dans notre base des donn&eacute;es!");
define("choice","choisir");
define("lastname","Nom");
define("firstname","Pr&eacute;nom");
define("address","Adresse");
define("address1","Adresse 1");
define("city","Lieu");
define("city","Lieu");
define("country","Pays");
define("phone","T&eacute;l");
define("fax","Fax");
define("email","Email");
define("conditions","J'ai lu les Conditions g&eacute;n&eacute;rales de vente et je les accepte.");
define("password","Mot de passe");
define("password2","Confirmer le mot de passe");
define("regist_weiter","Registrer");
define("kreditkarte","Carte de cr&eacute;dit");
define("type","Type de carte");
define("cc_number","Num&eacute;ro de carte");
define("valable_until","Date d'&eacute;ch&eacute;ance");
define("cc_owner"," Titulaire de la carte");
define("per_post","Par la  Poste");
define("nur_schweiz","Seulement pour la Suisse");
define("invalid","Erreur");
define("outdated","P&eacute;rim&eacute;");
define("pay_methods","Modalit&eacute;s de payement");
define("not_valid_email","Email invalide");
define("forgot_password","Avez-vous oubli&eacute; votre mot de passe?");
define("passwd_changed","Votre nouveau mot de passe a &eacute;t&eacute; envoy&eacute; &agrave; votre email adresse");
define("user_dont_exist","Votre adresse email n'est pas dans la base des donn&eacute;es");

//page_classes/KasseContent.php
define("pay_error","Choissisez, SVP, une modalit&eacute; de payement");
define("cc_error","Erreur dans les donn&eacute;es sur la carte de credit");
define("email_failed","Erreur interne: impossible d'envoyer l'email  - Commande Nr:");
define("pdf_failed","Erreur interne: impossible de produire le pdf - Commande Nr:");
define("save_failed","Erreur interne: impossible de sauver la commande");
define("reservate_failed","Erreur interne: impossible de r&eacute;server les places");
define("error_loading_user","Erreur dans le chargement de l'utilisateur");
define("parzial_price","Somme partielle");
define("shipment_price","Frais de port");
define("order_nr","Commande-Nr ");
define("order_total","Facture totale");

//check_login.php
define("welcome","Bienvenue");
define("not_to_be","Si vous n'&ecirc;tes pas");
define("not_to_be2"," ");
define("click_here","cliquez, SVP, ici");

//add_cart.php
define("cart_updated","Votre panier a &eacute;t&eacute; mis &agrave; jour");
define("cart_not_updated","Votre panier n'a  pas &eacute;t&eacute; mis &agrave; jour");
define("cart_try_again","Essayez, SVP, une autre fois");
define("places_empty","Choisissez, SVP, une place");
define("event_order_limit_exceeded","D&eacute;sol&eacute;! vous avez d&eacute;pass&eacute; la limite maximale de places dans une commande pour ce &eacute;v&egrave;nement");
define("places_occupied","D&eacute;sol&eacute;! Les places que vous d&eacute;sirez commander ont &eacute;t&eacute; entre temps
occup&eacute; par quelqu'un d'autre.");
define("places_toomuch","Ce nombre de places ne peut pas &ecirc;tre command&eacute;");
define("places_remains"," Places restants");
define("internal_error","Erreur interne");

// forgot_password.php
define("forgot_pass","Avez-vous oubli&eacute; votre mot de passe?");
define("forgot_pass_note","Indiquez, SVP, l'adresse email avec lequel vous avez ouvert votre compte.
        Cliquez sur &#146;Envoyer mot de passe&#146; et nous vous envoierons une email avec un nouveau mot de passe");
define("send_password","Envoyer mot de passe");

//salepoint_web/shop.php

define("free_places","Encore LIBRES");
define("event_sold_out","&Eacute;v&egrave;nement vendu");
define("cat_sold_out","Cat&eacute;gorie vendue");
define("to_pay","A la caisse");
define("kasse","Caisse");
define("logout","Se d&eacute;connecter");
define("shop","Commander");
define("view_cart","Panier");
define("discounts","Rabais");
define("places","Place Nr.");
//salepoint_web/view_cart.php
define("kassecontent","Caisse");
define("kasse_empty","Il n'y a pas de commandes en suspens");
define("printing_wait","Attendez, SVP: Les billets sont en train d'&ecirc;tre imprim&eacute;s");
define("printing_manual","Imprimer la commande");
define("category_sold_out","VENDU");
define("sure_to_cancel","Annuler?");
define("sure_to_delete","Effacer?");

//salepoint
define("kasse_nickname","Login");
define("kasse_lang","Langue");
define("user_prefs","Preferences");
define("user_prefs_pdf","Impression: Ouvrir Acrobat");
define("user_prefs_pdt","Impression: Envoyer sur l&#146;imprimante");

//salepoint_web/kasse.php
define("spoint_confirm_print","Confirmer et imprimer");
define("spoint_cancel","Annuler");
define("spoint_order","Commander");

//admin interface
define("ort_admin","Lieux");
define("event_admin","&Eacute;v&egrave;nements");
define("ort_title","Toutes les salles");
define("view","Voir");
define("edit","Modifier");
define("add","Ajouter");
define("ort_id","Lieu nr.");
define("ort_name","Nom du lieu");
define("ort_plan_nr","Plan nr");
define("ort_add_title","Ajouter un nouveau lieu");
define("ort_update_title","Modifier un lieu");
define("admin_list","Liste");
define("event_id","&Eacute;v&egrave;nement nr");
define("event_name","Nom de l' &eacute;v&egrave;nement");
define("event_short_text","Courte description de l' &eacute;v&egrave;nement");
define("event_text","Description de l' &eacute;v&egrave;nement");
define("event_url","Homepage");
define("event_date","Date");
define("event_time","D&eacute;but");
define("event_open","Ouverture des portes");
define("event_ort_id","Nom du lieu");
define("event_add_title","Ajouter un nouveau &eacute;v&egrave;nement ");
define("event_update_title","Modifier un &eacute;v&egrave;nement");
define("event_title","Tous les &eacute;v&egrave;nements");
define("ort_pm","Plan des places");
define("pm_id","Plan nr.");
define("pm_name","Nom du plan");
define("pm_width","Largeur du plan");
define("pm_height","Hauteur du plan");
define("pm_first_row","Num&eacute;ro du premier rang");
define("pm_dir","Direction");
define("pm_img_url","URL de l'image relative au plan");
define("pm_dir_LR","De gauche &agrave; droit");
define("pm_dir_RL","De droit &agrave; gauche");
define("pm_ort_id","Lieu Nr:");
define("pm_update_title","Modifier le plan");
define("pm_add_title","Ajouter un nouveau plan");
define("pm_title","Tous les plans");
define("category_id","cat&eacute;gorie Nr.");
define("category_name","Nom de la cat&eacute;gorie");
define("category_price","Prix de la cat&eacute;gorie");
define("category_pm_id","Plan de la cat&eacute;gorie");
define("category_event_id","&Eacute;v&egrave;nement nr.");
define("category_update_title","Modifier la cat&eacute;gorie");
define("category_add_title","Ajouter une nouvelle cat&eacute;gorie ");
define("category_title","Toutes les cat&eacute;gories");
define("event_cats","Cat&eacute;gories:");
define("already_pub","D&eacute;j&agrave; mis en vente");
define("pub_begin_failed","Transaction ne peut pas commencer");
define("pub_begin_trans","Transaction initialis&eacute;e");
define("pub_places_failed","Erreur- places pas publi&eacute;es");
define("pub_places_published","Succ&egrave;s - places pibli&eacute;es");
define("pub_statut_updated","Statut mis &agrave; jour");
define("pub_pm_locked","Plan de places bloqu&eacute;");
define("pub_category_failed", "Erreur: la cat&eacute;gorie n'a pas &eacute;t&eacute; mis en vente");
define("pub_success","Succ&egrave;s");
define("pub_failure","Erreur");
define("pub_confirm_msg","Voulez-vous mettre cet &eacute;v&egrave;nement en vente?");
define("delete_confirm_msg","Voulez-vous vraiment effacer cet &eacute;v&egrave;nement?");
define('delete_subs_first','Effacez d\'abord les sous-evenements');
define('in_use','Cet objet est utilis&eacute;');


define("user_lastname","Nom");
define("user_firstname","Pr&eacute;nom");
define("user_address","Adresse");
define("user_address1","Adresse");
define("user_zip","NPA");
define("user_city","Lieu");
define("user_country_name","Pays");
define("user_country","Pays");
define("user_state","Etat/Province");
define("user_state_name","Etat/Province");

define("user_email","E-Mail");
define("user_phone","T&eacute;l");
define("user_fax","Fax");
define("user_status","Statut");
define("member","Membre");
define("sale_point","Point de vente");
define("guest","Invit&eacute;");
define("tickets_nr","Quantit&eacute;");
define("orders","Les commandes");
define("order_type_post","Poste");
define("order_type_email","E-Mail");
define("order_type_ordered","Command&eacute;e");
define("order_type_sended","Envoy&eacute;e");
define("order_type_payed","Pay&eacute;e");
define("order_type_canceled","Annul&eacute;e");
define("order_type_reissued","Re&eacute;mise");
define("ordered","Command&eacute;");
define("sended","Envoy&eacute;");
define("payed","Pay&eacute;");
define("canceled","Annul&eacute;");
define("reissued","Re&eacute;mis");
define("order_status_email","Commandes E-Mail");
define("order_status_post","Commandes Post");
define("order_price","Prix total");
define("time_stamp","Temps");
define("organizer_admin","Organisateurs");
define("search_order","Chercher");
define("order_admin","Commandes");
define("order_list_title","Commandes");
define("order_status_ord","Command&eacute;");
define("order_status_reissue","Re&eacute;mis");
define("order_status_send","Envoy&eacute;");
define("order_status_payed","Pay&eacute;");
define("order_status_cancel","Annul&eacute;");
define("order_status","Status");
define("firstpage","D&eacute;but");
define("lastpage","Fin");
define("previouspage","<<<<<<<");
define("nextpage",">>>>>>>");
define("search_title_user","Chercher client");
define("search_title_place","Chercher place");
define("search_title_order","Chercher commandes");
define("choice_please","Faites un choix, SVP");
define("event_list","Liste des &eacute;v&egrave;nements");
define("place_row_nr","Num&eacute;ro de rang");
define("seat_row_nr","Num&eacute;ro de rang");
define("place_seat_nr","Num&eacute;ro de place");
define("seat_nr","Num&eacute;ro de place");
define("order_id","Num&eacute;ro de commande");
define("search_result","Trouv&eacute;");
define("event","&Eacute;v&egrave;nement");
define("category","Cat.");
define("place","Place");
define("price","Prix");
define("user","Client");
define("bs","Com.");
define("status","Statut");
define("organizer_title","Tous les organisateurs");
define("organizer_id","Organisateur Nr.");
define("organizer_name","Nom");
define("organizer_address","Adresse");
define("organizer_phone","T&eacute;l");
define("organizer_fax","Fax");
define("organizer_plz","NPA");
define("organizer_ort","Lieu");
define("organizer_email","E-Mail");
define("organizer_place","Salle");
define("organizer_nickname","Identifiant");
define("organizer_update_title","Modifier l'organisateur");
define("organizer_add_title","Ajouter un organisateur");
define("event_image_url","URL de l'image");
define("event_order_limit","Limite par Commande");
define("event_payment","Modalit&eacute;s de payement");
define("delete_item","Effacer?");
define("event_stats_title","Statistiques");
define("stats","Statistique");
define("graph_stats","Statistique graphique");
define("event_organizer","Organisateur");
define("yes","OUI");
define("pm_shift","Shifter les rangs");
define("order_tickets_nr","Quantit&eacute; de billets");
define("order_total_price","Prix total");
define("order_date","Date de la commande");
define("order_shipment_mode","Exp&eacute;dition");
define("order_Statut","Statut");
define("view_order_details","Details de la commande");
define("print_order","Imprimer la commande");
define("send_order_post","Changer le Statut en &#146;Envoy&eacute;&#146;");
define("send_order_email","Envoyer par e-mail et changer le Statut en &#146;Envoy&eacute;&#146;");
define("change_order_to_payed","Changer le Statut en &#146;Pay&eacute;&#146;");
define("reissue_order_menu","Re&eacute;mettre avec un nouveau num&eacute;ro de commande et Statut &#146;Commande&eacute;&#146;. Changer le Statut de la vieille commande en  &#146;Re&eacute;emis&#146;");
define("cancel_order","Changer le Statut en &#146;Annul&eacute;&#146; et remettre les places en vente");
define("possible_actions","Actions possibles");
define("legende","La legende");
define("change_statut_to_send","Changer le Statut en &#146;Envoy&eacute;&#146;");// und drucken?");
define("change_statut_to_send_email","Changer le Statut en &#146;Envoy&eacute;&#146; et envoyer par email?");
define("change_statut_to_payed","Changer le Statut en &#146;Pay&eacute;&#146;?");
define("reissue_order","Re&eacute;mettre la commande?");
define("cancel","Annuler la commande?");
define("order_not_found","Commande n'a pas &eacute;t&eacute; trouv&eacute;e");
define("order_cannot_change_to_send","Cette commande ne peut pas avoir le Statut Envoy&eacute;");
define("pdf_failed","Erreur dans le PDF");
define("email_failed","Erreur: l'email n'a pas &eacute;t&eacute; envoy&eacute;e");
define("change_order_Statut_failed","Le Statut n'a pas &eacute;t&eacute; chang&eacute;");
define("order_cannot_change_to_payed","Cette commande ne peut pas avoir le Statut Pay&eacutee;");
define("order_cannot_reissue","Cette commande ne peut pas &ecirc;tre re&eacute;mise;");
define("new_order_failed","La nouvelle commande n'a pas pu &ecirc;tre g&eacute;n&eacute;r&eacute;e");
define("new_order_created","Nouvelle commande cr&eacute;e");
define("tickets_update_failed","Erreur dans la mise &agrave; jour des billets");
define("tickets_update_done","Billets mis &agrave; jour");
define("old_order_not_canceled","Ancienne commande n'a pas &eacute;t&eacute; annul&eacute;e");
define("old_order_canceled","Ancienne commande annul&eacute;e");
define("order_not_canceled","Commande pas annul&eacute;e");
define("order_canceled","Commande annul&eacute;e");
define("places_not_freed","Places pas liber&eacute;es");
define("places_freed","Places liber&eacute;es");
define("category_size","Nombre de places de la cat&eacute;gorie");
define("category_numbering","Numerotation de places");
define("category_numbering_none","Sans num&eacute;ro");
define("category_numbering_rows","Seulement les rangs sont num&eacute;rot&eacute;s");
define("category_numbering_both","Places num&eacute;rot&eacute;es");
define("category_numbering_seat","Seulement les places (pas de rangs)");

define("payment_cc","Carte de cr&eacute;dit");
define("payment_post","Poste");
define("filling_stats","Table pour les statistiques");
define("cat_stat_Succ&egrave;ss","Statistiques de la cat&eacute;gorie mis &agrave; jour");
define("cat_stat_error","Erreur - Statistiques de la cat&eacute;gorie pas mis &agrave; jour");
define("event_stat_Succ&egrave;ss","Statistiques du &eacute;v&egrave;nement mis &agrave; jour");
define("event_stat_error","Erreur - les statistiques du &eacute;v&egrave;nement n'ont pas &eacute;t&eacute; mis &agrave; jour");
define("salepoint_admin","Points de vente");
define("template_admin","Templates");
define("excel_admin","Excel");
define("spoint_title","Tous les points de vente");
define("spoint_add_title","Ajouter un nouveau point de vente");
define("kasse_name","Nom du point de vente");
define("user_nickname","Identifiant");
define("user_id","ID");
define("spoint_update_title","Modifier le point de vente");
define("old_password","Ancien mot de passe");
define("new_password","Nouveau mot de passe");
define("template_title","Tous les templates");
define("template_add_title","Ajouter un template");
define("template_update_title","Modifier le template");
define("template_id","ID");
define("template_name","Nom");
define("template_type","Type");
define("template_ts","Timestamp");
define("template_Statut","Statut");
define("comp","Compil&eacute;");
define("new","Nouveau");
define("error","Erreur");
define("template_type_email","e-mail");
define("template_type_pdf","PDF");
define("compile_all","Tout compiler");

define('export_admin','Exporter');
define('export_admin_title','Exporter les donn&eacute;es');

define('export_cc','Cartes de cr&eacute;dit');
define('export_cc_title','Exporter les num&eacute;ros de cartes de cr&eacute;dit');
define('export_xl','Document Excel ');
define('export_xl_title','Fichier Excel');
define('export_cc_count','Nombre de cartes &agrave; exporter');
define('export_cc_submit','Exporter');

define('export_xml_event','Event en XML');
define('export_xml_event_title','Exporter un evenement en XML');
define('export_xml_event_event','Choisir l\'evenement');
define('export_xml_event_file','Nom du fichier');
define('export_xml_event_submit','Exporter');


define('import_admin','Importer');
define('import_admin_title','Importer les donn&eacute;es');

define('import_xml','XML');
define('import_xml_title','Importer les donn&eacute;es XML');
define('import_xml_file','Fichier XML');
define('import_xml_submit','Importer');


define('handling_text_payment','Description courte du moyen de payement');
define('handling_text_shipment','Description courte du moyen de livraison');


define("xl_view_title","Excel File");
define("xl_start","Du");
define("xl_end","Au");
define("order_type_spoint","Points de vente");
define("order_Statut_sp","Points de vente");
define("compilation_failed","Erreur dans le template");
define("compilation_succeed","Template compil&eacute; avec Succ&egrave;s");
define("sp","Point de vente");
define("event_discounts","Rabais");
define("event_template","Template de billet pour le &eacute;v&egrave;nement");
define("discount_id","ID");
define("discount_name","Nom du rabais");
define("discount_type","Type du rabais");
define("discount_type_fixe","Rabais fixe");
define("discount_type_percent","Rabais en pourcent");
define("discount_value","Valeur du rabais");
define("discount_title","Tous les rabais");
define("login","Identifiant");
define("already_exist","Cet identifiant existe d&eacute;j&agrave;. Choisissez un autre, SVP.");
define("login_invalid","Mot de passe ou identifiant invalide");
define("sp","Point de vente");
define("pm_used","Ce plan est d&eacute;j&agrave; utilis&eacute;!");
define("category_template","Template de billet pour la cat&eacute;gorie");
define("category_color","Couleur pour la cat&eacute;gorie");
define("no_color","Aucune couleur");
define("generate_xl","G&eacute;n&eacute;rer document Excel ");
define("nosale_confirm_msg","Voulez-vous retirer ce &eacute;v&egrave;nement de la veente?");
define("unpub_begin_trans","Transaction initialis&eacute;e");
define("unpub_begin_failed","Erreur d&eacute;but transaction");
define("nosale_Statut_updated","Statut mis &agrave; jour");
define("nosale_Succ&egrave;ss","Succ&egrave;s");
define("nosale_failure","Erreur");
define("already_no_sale","D&eacute;j&agrave; retir&eacute; de la vente");
define("no_result","Pas de resultat");
define("Statut_not_valid_for_repub","Impossible de remettre en vente");
define("event_image","Image pour &eacute;v&egrave;nement");
define("new_event_image","Changer image");
define("remove_event_image","Enlever image");
define("ort_image","Image de la salle");
define("new_ort_image","Changer image");
define("remove_image","Enlever image");
define("administration","Administration");
define("file_admin","Fichers");
define("event_group_admin","Groupes");
define("event_group_title","Tous les groupes d'&eacute;v&egrave;nements");
define("event_group_add_title","Ajouter un groupe d'&eacute;v&egrave;nements");
define("event_group_id","Groupe ID");
define("event_group_name","Nom");
define("event_group_start_date","D&eacute;but");
define("event_group_end_date","Fin");
define("event_group_description","Description");
define("event_group_image","Image");
define("start_date","D&eacute;but");
define("end_date","Fin");
define("event_group_update_title","Modifier  un groupe d'&eacute;v&egrave;nements");
define("file_name","Nom du ficher");
define("file_upload","T&eacute;l&eacute;charger le ficher");
define("file_mkdir","Repertoirs");
define("file_dir_name","Nom du repertoir");
define("organizer_not_found","Organisateur non trouv&eacute;");
define("file_not_found","Ficher non trouv&eacute; ");
define("create","Cr&eacute;er");
define("upload","Upload");
define("admin_select_organizer","Selectionner un organisateur");
define("categories","Categories");
define("place_maps","Cartes des places");
define("place_map","Carte des places");

define("select","Choisir");
define("pm_zones","Zones");
define("pm_zone","Zone");

define("new_pm_zone","Ajouter une nouvelle zone");
define("pmz_color","Couleur");
define("pmz_name","Nom");
define("pmz_short_name","Nickname");
define("new_pmz","Nouvelle Zone");
define("redefine_pm_zone","Red&eacute;finir la zone");
define("redefine","Red&eacute;finir");
define("seat_numbering","Num&eacute;rotation des places");
define("autonumber_pmz","Num&eacute;rotation automatique");
define("first_row","Premier rang");
define("step_row","Rang step");
define("inv_row","Inverser la num&eacute;rotation des rangs");
define("first_seat","Premi&egrave;re place");
define("step_seat","Place step");
define("inv_seat","Inverser la num&eacute;rotation des places");
define("flip","Flip");
define("map","Carte");
define("payment_admin","Traitements");
define("payment_title","Toutes les Modalit&eacute;s de payement");
define("invoice","Virement");
define("post","Poste");
define("payment_add_title","Ajouter une modalit&eacute; de payement");
define("payment_mode","Modalit&eacute; de payement");
define("payment_send","Envoi des billets");
define("payment_email_template","Email Template");
define("payment_fee_fixe","Frais de port fixe ");
define("payment_fee_percent","Frais de port en pourcent");
define("payment_update_title","Modifier la  modalit&eacute; de payement");
define("order_payment_mode","Payement");
define("order_fee","Frais de port");
define("import","Importer");
define("shared_ort_title","Lieux partag&eacute;s");
define("ort_address","Adresse");
define("ort_address1","Adresse 1");
define("ort_zip","NPA");
define("ort_city","Lieu");
define("ort_country_name","Pays");
define("ort_country","Pays");
define("ort_fax","Fax");
define("control_admin","Points  de v&eacute;rification");
define("control_title","Tous les points de v&eacute;rification");
define("control_login","Identifiant");
define("control_event_ids","Ev&egrave;nements");
define("control_add_title","Ajouter un point  de v&eacute;rification");
define("control_update_title","Modifier un point  de v&eacute;rification");
define("control_view","Point  de v&eacute;rification");
define("classics","Musique classique");
define("jazz","Jazz");
define("blues","Blues");
define("funk","Funk");
define("pop","Pop");
define("rock","Rock");
define("folklore","Folklore");
define("theater","Theatre");
define("sacred","Musique sacr&eacute;e");
define("ballet","Ballet");
define("opera","Opera");
define("humour","Humour");
define("organizer_currency","Monnaie");
define("event_pm_ort_id","Carte des places");
define("event_rep","R&eacute;p&eacute;tabilit&eacute;");
define("event_type","Type");
define("event_mp3","MP3");
define("event_rep_main","Multiple");
define("event_rep_unique","Unique");
define("pm_parts","Parties de la carte");
define("pmp_id","Part ID");
define("pmp_name","Part Nom");
define("pm_part","Part");
define("edit_pm","Modifier la carte des places");
define("pm_image","Image de la carte des places");
define("remove_pm_image","Eliminer l'immage");
define("search_title_codebar","Chercher codebar");
define("codebar","Codebar");
define("user_organizer_ids","Organizateur");
define("category_data","Donn&eacute;es de la categorie");
define("pmp_height","Hauteur de la partie");
define("pmp_width","Largeur de la partie");
define("discount_add_title","Ajouter un rabais");
define("remove_event_mp3","Eliminer MP3");
define("category_view_title","Categorie");
define("split_pm","S&eacute;parer la carte des places");
define("pm_split","S&eacute;parer la carte des places");
define("split_zones","S&eacute;parer par zones");
define("correct_errors_first","Corriger les erreurs avant de publier");
define("undefined","undefini");
define("category_pmp_id","Categorie partie ID");

define("scene_h","images/scene_h_fr.png");
define("scene_v","images/scene_v_fr.png");

define("view_handling","Modalit&eacute; de traitement  de la commande");
define("handling_title","Modalit&eacute;s de traitement  de la commande");
define("handling_add_title","Ajouter une modalit&eacute;; de traitement  de la commande");
define("handling_id","ID");
define("handling_payment","Payement");
define("handling_shipment","Envoi");
define("handling_email_template_ord","Email Template pour commande");
define("handling_email_template_send","Email Template pour envoi");
define("handling_email_template_payed","Email Template pour payement");
define("handling_html_template","HTML Template pour explications");
define("handling_pdf_template","PDF Template pour facture");
define("handling_sale_mode","Modalit&eacute;s de vente");
define("handling_fee_fix","Frais de port fixe");
define("handling_fee_percent","Frais de port en pourcent");

define("entrance","A l'entr&eacute;e");
define("cash","Cash");
define("email","Email");
define("sp","Point de vente");

define("pmp_scene","Position de la sc&egrave;ne");
define("pmp_scene_north","nord");
define("pmp_scene_est","est");
define("pmp_scene_south","sud");
define("pmp_scene_west","ouest");

define("pmp_shift","Shifter les rangs");
define("define","D&eacute;finir");
define("labels","Etiquettes");
define("label_type","Type");
define("label_text","Texte");
define("label_type_text","Texte");
define("label_type_row_east","Rang est");
define("label_type_row_west","Rang ouest");
define("label_type_seat_south","Place sud");
define("label_type_seat_north","Place nord");
define("label_type_exit","Sortie");
define("clear","Liberer");

define("organizer_logo","Logo");
define("new_organizer_logo","Nouveau Logo");
define("remove_logo","Eliminer le logo");
define("copy","Copier");
define("agenda_only","Seulement calendrier");
define('copy_main_pm','Copier principal');

define("order_shipment_status","Statut de l'envoie");
define("order_payment_status","Statut du payement");
define("none","Aucun");
define("send","Envoy&eacute;");
define("ord","Command&eacute;");
define("change_status_to_no_send","Changer le Statut en &#146;non Envoy&eacute;&#146;");
define("change_status_to_no_payed","Changer le Statut en &#146;non Pay&eacute;&#146;");
define("change_status_to_ord","Changer le Statut en &#146;Command&eacute;&#146;");
define("change_order_to_ord","Changer le Statut en 'Command&eacute;'");
define("no_send_order_post","Changer le Statut en 'Non-envoy&eacute;e'");
define("change_order_to_no_payed","Changer le Statut en 'Non-pay&eacute;e'");
define("payed_and_send","Pay&eacute;e et Envoy&eacute;e");

define("pass_too_short","Mot de passe trop court");
define("music","Musique");
define("other","Autre");
define("cinema","Cin&eacute;ma");
define("exposition","Exposition");
define("ballet","Ballet");
define("party","Party");

define("also_sub","Sous-&eacute;v&eacute;nements aussi");
define("restart_success","Repubblicazione con successo");
define("stop_success","Stop con successo");

//control
define("check_success","Verification r&eacute;ussie");
define("place_without_nr","Place sans num&eacute;ro");
define("category","Cat&eacute;gorie");
define("check","Verifier");
define("res","Annuler");
define("not_valid_event","Ce billet n'est pas pour ce &eacute;v&egrave;nement");
define("ticket_not_found","Ce billet n'est pas valable: <br>il n'a pas &eacute;t&eacute; trouv&eacute; dans la base de donn&eacute;es");
define("ticket_already_checked","Le billet a &eacute;t&eacute; d&eacute;j&agrave; control&eacute;");
define("place_not_commanded","Place pas command&eacute;e");
define("place_only_reserved","Place r&eacute;serv&eacute;e mais pas command&eacute;e");
define("place_nr","Place nr");
define("rang_nr","Rang nr");
define("place_Statut_not_updated","Erreur: le Statut de la place n'a pas &eacute;t&eacute; mis &agrave; jour en &#146;control&eacute;&#146;");
define("change_event","Changer d'&eacute;v&egrave;nement");
define("nickname","Nom court");
define("lang","Langue");
define("img_loading_problem","Probleme de charger l&#146;image");
define("enter_guest","Informations personelles");
define("control_events_list","Ev&egrave;nements");
define("free","Libre");
define("com","Command&eacute;e");
define("reserved","Reserv&eacute;e");
define("checked","V&eacute;rifi&eacute;e");

define("garbage","Poubelle");
define("seat","Place");
define("order","Ordre");
define("empty_trash","Jeter la poubelle");
define('purge','purger');

define("yp","Yellowpay");
define("authorize_aim","Authorize.net AIM");
define("authorize_sim","Authorize.net SIM");
define("paypal","PayPal");
define("cc","Carte de cr&eacute;dit");


define('ticket_deleted','Le billet a &eacute;t&eacute; annul&eacute;');
define('cannot_find_seat','Impossible de trouver la place');
define("","");
define("","");
define("","");

?>
