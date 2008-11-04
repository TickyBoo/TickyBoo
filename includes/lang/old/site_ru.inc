<?php
/*
%%%copyright%%%
 */
?>
<?php
define('weiter','Продолжить');
define('save','Сохранить'); //submit
define('res','Аннулировать'); //reset
define('search','Поиск'); //reset


//categories.php
define('choice_cat_title','Выбор категории');
define('date','Дата');
define('open','Вход');
define('start','Начало');
define('ort','Зал');
define('ort_phone','Телефон');
define('ort_url','WWW');
define('categories_prices','Категории и цены');
define('tickets','Билеты');
define('prices','Цена');
define('choice_cat','Выберите категорию');
define('choice_qty','Количество мест');

//places.php
define('reservate','Зарезервировать');

//view_cart.php
define('ev_name','Представление');
define('ev_datum','Дата, Зал');
define('ev_ticket','Билеты');
define('cat_total','Всего');
define('exp_rm','Годен ещё');
define('expired','EXPIRED');
define('warenkorb','Your cart');
define('remove','Аннулировать');
define('warenkorb_title','Your cart');
define('total_price','Всего');
define('go_pay','Order');
define('by_post','Send by post');
define('back_shop','Order more tickets');
define('place_row','Ряд');
define('place_seat','Место');

//functions/user_func.php
define('mandatory','Обязательно к заполнению');
define('alreadyexist','Этот е-мейл уже зарегистрирован!');
define('choice','Выбор');
define('lastname','Фамилия');
define('firstname','Имя');
define('address','Адрес');
define('address1','Адрес');
define('city','Нас. пункт');
define('country','Страна');
define('phone','Телефон');
define('fax','Факс');
define('email','Е-мейл');
define('conditions',' I have read and agree to the General Terms of Sale.');
define('password','Пароль');
define('password2','Повторить пароль');
define('regist_weiter','Зарегестрировать');
define('kreditkarte','Кредитная карта');
define('type','Тип карты');
define('cc_number','Номер карты');
define('valable_until','Срок действия карты');
define('cc_owner','Имя на карте');
define('per_post','По почте');
define('nur_schweiz','Only in Switzerland');
define('invalid','Неправильное значение');
define('outdated','Дата просроченна');
define('pay_methods','Оплата');
define('not_valid_email','Ошибка в е-мейле');
define('forgot_password','Forgotten your password?');
define('passwd_changed','Your new password was sent to your e-mail.');
define('user_dont_exist','Your e-mail is not in our database.');

//page_classes/KasseContent.php 
define('pay_error','Select the payment mode');
define('cc_error','Ошибка в при обработке кредитной карты');
define('email_failed','Ошибка в программе: impossible to send the e-mail. Order Number  is:');
define('pdf_failed','Ошибка в программе: Impossible to produce pdf - Order Nr:');
define('save_failed','Ошибка в программе: impossible to save the order ');
define('reservate_failed','Ошибка в программе: impossible to save seats ');
define('error_loading_user','Ошибка в программе: Error occured when loading user data ');
define('parzial_price','Total without charges');
define('shipment_price','Charges');
define('order_nr','Номер заказа');
define('order_total','Цена заказа');

//check_login.php
define('welcome','Welcome');
define('not_to_be','If you are not');
define('not_to_be2',' ');
define('click_here','click here, please');

//add_cart.php
define('cart_updated','Cart is updated');
define('cart_not_updated','Cart is not updated');
define('cart_try_again','Try again, please');
define('places_empty','Select a seat, please');
define('event_order_limit_exceeded','Sorry, The number of seats that you can order for this event is limited!');
define('places_occupied','Surry! The selected seats were taken by another customer.');
define('places_toomuch','This number of seats cannot be reservated');
define('places_remains',' Remain seats');
define('internal_error','Ошибка в программе:');

// forgot_password.php
define('forgot_pass','Forgotten your password?');
define('forgot_pass_note','Introduce here your e-mail address.
        Click on &#146;Send password&#146; and a new password will be send to you by e-mail');
define('send_password','Send password');

//salepoint_web/shop.php

define('free_places','FREE');
define('event_sold_out','Event sold');
define('cat_sold_out','Category sold');
define('to_pay','Check out');
define('kasse','Check out');
define('logout','Log out');
define('shop','Order tickets');
define('view_cart','Cart');
define('discounts','Discount');
define('places','Seat Nr.');
//salepoint_web/view_cart.php
define('kassecontent','Check out');
define('kasse_empty','Il n&#146;y a pas de commandes en suspens');
define('printing_wait','Tickets are printing, wait please');
define('printing_manual','Print order');
define('category_sold_out','SOLD');
define('sure_to_cancel','Cancel?');  
define('sure_to_delete','Delete?');

//salepoint
define('kasse_nickname','Login');
define('kasse_lang','Language');
define('user_prefs','Preferences');
define('user_prefs_pdf','Open Orders in Acrobat');
define('user_prefs_pdt','Send Orders to printer');

//salepoint_web/kasse.php
define('spoint_confirm_print','Confirm and Print');
define('spoint_cancel','Cancel order');
define('spoint_order','Order');

//admin interface 
define('ort_admin','Залы');
define('event_admin','Представления');
define('ort_title','Залы');
define('view','Смотреть');
define('edit','Редактировать');
define('add','Добавить');
define('ort_id','ID зала');
define('ort_name','Название зала');
define('ort_plan_nr','Номер на карте');
define('ort_add_title','Новый зал');
define('ort_update_title','Редактировать зал');
define('admin_list','Список');
define('event_id','ID представления');
define('event_name','Название');
define('event_short_text','Короткий текст');
define('event_text','Длинный текст');
define('event_url','WWW');
define('event_date','Дата');
define('event_time','Начало');
define('event_open','Вход');
define('event_ort_id','Зал');
define('event_add_title','Новое представление');
define('event_update_title','Редактировать');
define('event_title','Все представления');
define('ort_pm','План');
define('pm_id','ID');
define('pm_name','Название');
define('pm_width','Ширина');
define('pm_height','Высота');
define('pm_first_row','Number of the first row');
define('pm_dir','Direction');
define('pm_img_url','URL of the image');
define('pm_dir_LR','Left to right');
define('pm_dir_RL','Right to left');
define('pm_ort_id','Номер зала:');
define('pm_update_title','Редактировать план');
define('pm_add_title','Новый план');
define('pm_title','Все планы');
define('category_id','Номер категории');
define('category_name','Название');
define('category_price','Цена');
define('category_pm_id','Номер плана.');
define('category_event_id','Номер представления');
define('category_update_title','Редактировать категорию');
define('category_add_title','Новая категория');
define('category_title','Все категории');
define('event_cats','Категории:');
define('already_pub','Уже опубликованно');
define('pub_begin_failed','Transaction begin failed');
define('pub_begin_trans','Transaction begin');
define('pub_places_failed','Error - Seats not published');
define('pub_places_published','Seats published');
define('pub_status_updated','Статус изменён');
define('pub_pm_locked','План блокирован');
define('pub_category_failed', 'Error: Категория не опубликованна');
define('pub_success','Операция выполненна успешно');
define('pub_failure','Ошибка');
define('pub_confirm_msg','Опубликовать представление?');
define('delete_confirm_msg','Стереть представление?');
define('delete_subs_first','Сотите сначала суб-представления');
define('in_use','Обьект используется и поэтому не может быть стёрт');

define('user_lastname','Фамилия');
define('user_firstname','Имя');
define('user_address','Адрес');
define('user_address1','Адрес');
define('user_zip','Индекс');
define('user_city','Нас. пункт');
define('user_country_name','Страна');
define('user_country','Страна');
define('user_state_name','Штат/регион');
define('user_state','Штат/регион');
define('user_email','Е-мейл');
define('user_phone','Телефон');
define('user_fax','Телефакс');
define('user_status','Статус');
define('member','Постоянный пользователь');
define('sale_point','Касса');
define('guest','Гость');
define('tickets_nr','Количество');
define('orders','Заказы');
define('order_type_post','Почта');
define('order_type_email','Е-мейл');
define('order_type_ordered','Заказан');
define('order_type_sended','Отправлен');
define('order_type_payed','Оплачен');
define('order_type_canceled','Отменён');
define('order_type_reemited','Перезаказан');
define('ordered','Заказан');
define('sended','Отослан');
define('payed','Оплачен');
define('canceled','Отменён');
define('reemited','Перезаказан');
define('order_status_email','Отправлен по е-мейлу');
define('order_status_post','Отправлен по почте');
define('order_price','Цена заказа');
define('time_stamp','Время');
define('organizer_admin','Организатор');
define('search_order','Поиск');
define('order_admin','Заказы');
define('order_list_title','Все заказы');
define('order_status_ord','Заказан');
define('order_status_reemit','Перезаказан');
define('order_status_send','Отослан');
define('order_status_payed','Оплачен');
define('order_status_cancel','Отменён');
define('firstpage','В начало');
define('lastpage','В конец');
define('previouspage','<<<<<<<');
define('nextpage','>>>>>>>');
define('search_title_user','Поиск пользователя');
define('search_title_place','Поиск места');
define('search_title_order','Поиск заказа');
define('choice_please','Выберите');
define('event_list','Список представлений');
define('place_row_nr','Номер ряда');
define('seat_row_nr','Номер ряда');
define('place_seat_nr','Номер места');
define('seat_nr','Номер места');
define('order_id','Номер заказа');
define('search_result','Резултьтат поиска');
define('event','Представление');
define('category','Кат.');
define('place','Место');
define('price','Цена');
define('user','Пользователь');
define('bs','Заказ.');
define('status','Статус');
define('organizer_title','All Organizers');
define('organizer_id','Номер организатора');
define('organizer_name','Имя');
define('organizer_address','Адрес');
define('organizer_phone','Телефон');
define('organizer_fax','Факс');
define('organizer_plz','Индекс');
define('organizer_ort','Нас. пункт');
define('organizer_email','Е-мейл');
define('organizer_place','Нас. пункт');
define('organizer_nickname','Ник');
define('organizer_update_title','Редактировать');
define('organizer_add_title','Add organizer');
define('event_image_url','Picture URL');
define('event_order_limit','Лимит билетов');
define('event_payment','Payment mode');
define('delete_item','Стереть?');
define('event_stats_title','Статистика');
define('stats','Статистика');
define('graph_stats','Графическая статистика');
define('event_organizer','Организатор');
define('yes','ДА');
define('pm_shift','Сдвинуть ряды');
define('order_tickets_nr','Количество билетов');
define('order_total_price','Цена заказа');
define('order_date','Дата заказа');
define('order_shipment_mode','Тип отправки');
define('order_status','Статус');
define('view_order_details','Подробно');
define('print_order','Напечатать');
define('send_order_post','Изменить статус на "Отослан"?');
define('send_order_email','Send order by e-mail and change status to &#146;Sent&#146;');
define('change_order_to_payed','Изменить статус на "Оплачен"?s');
define('reemit_order_menu','Создать новый заказ с теми же билетами, отметить старый заказ как "Перезаказан"?');
define('cancel_order','Заказ стёрт, места освобожденны');
define('possible_actions','Действия');
define('legende','Обьяснения');
define('change_status_to_send','Изменить статус на "Отправлен"?');// und drucken?');
define('change_status_to_send_email','Change status to &#146;Sent&#146; and send by e-mail?');
define('change_status_to_payed','Изменить статус на "Оплачен"?');
define('reemit_order','Перезаказать?');
define('reemit_ticket','Перезаказать билет (изменить штирхкод)? Номер билета: ');
define('delete_ticket','Освободить место? Номер билета: ');
define('reemit_order','Перезаказать?');
define('cancel','Отменить заказ?');
define('order_not_found','Заказ не найден');
define('order_cannot_change_to_send','Ошибка, статус не изменён');
define('pdf_failed','Error in PDF');
define('email_failed','Error: e-mail is not sent');
define('change_order_status_failed','Ошибка, статус не изменён');
define('order_cannot_change_to_payed','Ошибка, статус не изменён');
define('order_cannot_reemit','Ошибка при перезаказе');
define('new_order_failed','Новый заказ не создан');
define('new_order_created','Новый заказ создан');
define('tickets_update_failed','Билеты не изменены');
define('tickets_update_done','Билеты изменены');
define('old_order_not_canceled','Старый заказ не отменён');
define('old_order_canceled','Старый заказ отменён');
define('order_not_canceled','Заказ НЕ отменён');
define('order_canceled','Заказ отменён');
define('places_not_freed','Места НЕ освобожденны');
define('places_freed','Места освобожденны');
define('category_size','Размер категории');
define('category_numbering','Нумерация');
define('category_numbering_none','Места без номера');
define('category_numbering_rows','Пронумерованны ряды');
define('category_numbering_seat','Пронумерованны места');
define('category_numbering_both','Пронумерованны ряды и места');
define('payment_cc','Кредитная карта');
define('payment_post','Почта');
define('filling_stats','Tabelle f&uuml;r Statistik');
define('cat_stat_success','Категория изменена');
define('cat_stat_error','Категория не изменена');
define('event_stat_success','Представление измененно');
define('event_stat_error','Представление не измененно');
define('salepoint_admin','Кассы');
define('template_admin','Шаблоны');
define('excel_admin','Excel');
define('spoint_title','Все кассы');
define('spoint_add_title','Новая касса');
define('kasse_name','Название кассы');
define('user_nickname','Ник');
define('user_id','ID');
define('spoint_update_title','Редактировать кассу');
define('old_password','Старый пароль');
define('new_password','Новый пароль');
define('template_title','Все шаблоны');
define('template_add_title','Новый шаблон');
define('template_update_title','Редактировать шаблон');
define('template_id','ID');
define('template_name','Название');
define('template_type','Тип');
define('template_ts','Timestamp');
define('template_status','Статус');
define('comp','Скомпилирован');
define('new','Новый');
define('error','Ошибка');
define('template_type_email','е-мейл');
define('template_type_pdf','PDF');
define('compile_all','Перекомпилировать все шаблоны');

define('export_admin','Экспорт');
define('export_admin_title','Экспорт данных');

define('export_cc','Кредитные карты');
define('export_cc_title','Скачать номера карт');
define('export_xl','Данные по продажам');
define('export_xl_title','Данные по продажам');
define('export_cc_count','Карт в базе данных');
define('export_cc_submit','Скачать');

define('export_xml_event','Представление в формате  XML');
define('export_xml_event_title','Скачать представление в формате XML');
define('export_xml_event_event','Выбор представления');
define('export_xml_event_file','Название файла');
define('export_xml_event_submit','Качать');


define('import_admin','Импорт');
define('import_admin_title','Импорт данных');

define('import_xml','XML');
define('import_xml_title','Закачать данные в формате format');
define('import_xml_file','XML файл');
define('import_xml_submit','Закачать');



define('handling_text_payment','Название оплаты');
define('handling_text_shipment','Название отправки');
define('handling_pdf_ticket_template','PDF Шаблон билетов');
define('pdf_paper','PDF Формат страницы');
define('page_format_default','По умолчанию');
define('page_format_std','Стандартный');
define('page_format_custom','Нестандартный');
define('page_orientation_portrait','Портрет');
define('page_orientation_landscape','Пейзаж');



define('xl_view_title','Продажи');
define('xl_start','от');
define('xl_end','до');
define('order_type_spoint','Касса');
define('order_status_sp','Кассы');
define('compilation_succeed','Шаблон скомпилирован успешно');
define('sp','Касса');
define('event_discounts','Скидки');
define('event_template','Шаблон для билетов');
define('discount_id','Номер скидки');
define('discount_name','Название скидки');
define('discount_type','Тип скидки');
define('discount_type_fixe','Фиксированная скидка');
define('discount_type_percent','Процентная скидка');
define('discount_value','Значение скидки');
define('discount_title','Все скидки');
define('login','ник');
define('already_exist','Выберите другой ник');
define('login_invalid','Неправилный ник');
define('pm_used','План используется, операция аннулированна!');
define('category_template','Шаблон для билетов');
define('category_color','Цвет категории');
define('no_color','Без цвета');
define('generate_xl','Составить отчёт');
define('nosale_confirm_msg','Остановить продажу (дезактивация)?');
define('unpub_begin_trans','Transaction started');
define('unpub_begin_failed','Transaction not started');
define('nosale_status_updated','Статус изменён');
define('nosale_success','Успех');
define('nosale_failure','Ошибка');
define('already_no_sale','Not on sale already');
define('no_result','Без результата');
define('status_not_valid_for_repub','Status not valid for re-publish');
define('event_image','Картинка');
define('new_event_image','Новая картинка');
define('remove_event_image','Стереть картинку');
define('ort_image','Картинка');
define('new_ort_image','Новая картинка');
define('remove_image','Стереть картинку');
define('administration','Админ');
define('file_admin','Files');
define('event_group_admin','Группы');
define('event_group_title','Все группы');
define('event_group_add_title','Новая группа');
define('event_group_id','ID');
define('event_group_name','Название');
define('event_group_start_date','Начало');
define('event_group_end_date','Конец');
define('event_group_description','Описание');
define('event_group_image','Картинка');
define('start_date','Начало');
define('end_date','Конец');
define('event_group_update_title','Изменить');
define('file_name','File Name');
define('file_upload','Upload files');
define('file_mkdir','Directories');
define('file_dir_name','Directory Name');
define('organizer_not_found','Organizer not found');
define('file_not_found','File not found');
define('create','Create');
define('upload','Upload');
define('admin_select_organizer','Select an organizer');
define('categories','Категории');
define('place_maps','Планы');
define('place_map','План');

define('select','Выбор');
define('pm_zones','Зоны');
define('pm_zone','Зона');

define('new_pm_zone','Новая зона');
define('pmz_color','Цвет');
define('pmz_name','Название');
define('pmz_short_name','Сокр. название');
define('new_pmz','Новая зона');
define('redefine_pm_zone','Zone redefine');
define('redefine','Redefine');
define('seat_numbering','Нумерация мест');
define('autonumber_pmz','Авто-нумерация');
define('first_row','Первый ряд');
define('step_row','Шаг');
define('inv_row','Нумерация рядов в обратном порядке');
define('first_seat','Первое место');
define('step_seat','Шаг');
define('inv_seat','Нумерация мест в обратном порядке');
define('flip','Повернуть по диагонали');
define('map','План');
define('payment_admin','Типы заказов');
define('payment_title','Все типы заказов');
define('invoice','Перевод');
define('post','Почта');
define('payment_add_title','Add a type of payment');
define('payment_mode','Payment type');
define('payment_send','Tickets Shipment');
define('payment_email_template','Email Template');
define('payment_fee_fixe','Фиксированная надбавка');
define('payment_fee_percent','Надбавка в процентах');
define('payment_update_title','Редактировать тип оплаты');
define('order_payment_mode','Тип оплаты');
define('order_fee','Надбавка');
define('import','Import');
define('shared_ort_title','Shared venues');
define('ort_address','Адрес');
define('ort_address1','Адрес 1');
define('ort_zip','Индекс');
define('ort_city','Нас пункт');
define('ort_country_name','Страна');
define('ort_country','Страна');
define('ort_fax','Факс');
define('control_admin','Пункты контроля');
define('control_title','Все пункты контроля');
define('control_login','Login');
define('control_event_ids','Представления');
define('control_add_title','Добавить пункт контроля');
define('control_update_title','Редактировать');
define('control_view','Контроль билетов');
define('classics','Классическая музыка');
define('jazz','Jazz');
define('blues','Blues');
define('funk','Funk');
define('pop','Поп');
define('rock','Рок');
define('folklore','Фольклор');
define('theater','Театр');
define('sacred','Духовная музыка');
define('ballet','Балет');
define('opera','Опера');
define('humour','Юмор');
define('organizer_currency','Валюта');
define('event_pm_ort_id','План мест');
define('event_rep','Повтор');
define('event_type','Тип');
define('event_mp3','MP3');
define('event_rep_main','Несколько представлений');
define('event_rep_unique','Одно представление');
define('pm_parts','Части');
define('pmp_id','ID');
define('pmp_name','Название');
define('pm_part','План');
define('edit_pm','Редактировать план');
define('pm_image','Картинка плана');
define('remove_pm_image','Стереть картинку');
define('search_title_codebar','Искать по штрихкоду');
define('codebar','Штрихкод');
define('user_organizer_ids','Организатор');
define('category_data','Дополнительные данные');
define('pmp_height','Высота');
define('pmp_width','Ширина');
define('discount_add_title','Новая скидка');
define('remove_event_mp3','Стереть  MP3');
define('category_view_title','Категория');
define('split_pm','Поделить категории по частям');
define('pm_split','Поделить категории по частям');
define('split_zones','Поделить также по зонам');
define('correct_errors_first','Исправьте ошибки');
define('undefined','Неопределенно');
define('category_pmp_id','Часть плана');

define('scene_h','images/scene_h_ru.png');
define('scene_v','images/scene_v_ru.png');

define('view_handling','Типы заказов');
define('handling_title','Типы заказов');
define('handling_add_title','Новый тип');
define('handling_id','ID');
define('handling_payment','Оплата');
define('handling_shipment','Отправка');
define('handling_email_template_ord','Е-мейл после заказа');
define('handling_email_template_send','Е-мейл после отправки');
define('handling_email_template_payed','Е-мейл после оплаты');
define('handling_html_template','Шаблон HTML для оплаты/oбяснений');
define('handling_pdf_template','Шаблон PDF для квитанции');
define('handling_sale_mode','Продажа');
define('handling_fee_fix','Надбавка фиксированная');
define('handling_fee_percent','Надбавка %');

define('entrance','На входе');
define('cash','Наличные');
define('email','Е-мейл');
define('sp','Касса');

define('pmp_scene','Где сцена на плане');
define('pmp_scene_north','Наверху');
define('pmp_scene_est','Справа');
define('pmp_scene_south','Внизу');
define('pmp_scene_west','Слева');

define('pmp_shift','Ряды "сдвинуты"');
define('define','Запомнить');
define('labels','Ярлыки');
define('label_type','Тип');
define('label_text','Текст');
define('label_type_text','Текст');
define('label_type_row_east','Номер ряда -&gt;');
define('label_type_row_west','&lt;- Номер ряда');
define('label_type_seat_south','Номер места внизу');
define('label_type_seat_north','Номер места сверху');
define('label_type_exit','Выход');
define('clear','Очистить');

define('organizer_logo','Логотип');
define('new_organizer_logo','Новый логотип');
define('remove_logo','Стереть логотип');
define('copy','Копия');
define('agenda_only','Только календарь (не продажа)');
define('copy_main_pm','Скопировать основной план');

define('order_shipment_status','отправка');
define('order_payment_status','оплата');
define('none','---');
define('send','Заказ отправлен');
define('ord','Заказан');
define('change_status_to_no_send','Записать что заказ не отправлен?');
define('change_status_to_no_payed','Записать что заказ не оплачен?');
define('change_status_to_ord','Записать что заказ заказан?');
define('change_order_to_ord','Записать что заказ заказан');
define('no_send_order_post','Записать что заказ не отправлен');
define('change_order_to_no_payed','Записать что заказ не оплачен');
define('payed_and_send','Оплачен и отправлен');

define('pass_too_short','Password too short');
define('music','Music');
define('other','Other');
define('cinema','Cinema');
define('exposition','Exposition');
define('ballet','Ballet');
define('party','Party');

define('also_sub','Subevents also');
define('restart_success','Restart success');
define('stop_success','Stop success');

//control 
define('check_success','Билет действителен');
define('place_without_nr','Место без номера');
define('category','Категория');
define('check','Проверить');
define('res','Очистить');
define('not_valid_event','Неправильное представление');
define('ticket_not_found','Неправильный билет');
define('ticket_already_checked','Билет уже проверен');
define('place_not_commanded','Место не заказанно');
define('place_only_reserved','Место не заказанно');
define('place_nr','Место');
define('rang_nr','Ряд');
define('place_status_not_updated','Ошибка, проверка билета не записанна');
define('change_event','Быбрать представление');
define('control_events_list','Представления');
define('free','Свободно');
define('com','Заказанно');
define('reserved','В корзине');
define('checked','Проверенно');

define('nickname','Ник');
define('lang','Язык');
define('img_loading_problem','Ошибка при загрузке картинки');
define('enter_guest','Информация о пользователе');

define('garbage','Свалка');
define('seat','Места');
define('order','Заказы');
define('empty_trash','Очистить');
define('purge','Стереть');

define('yp','Yellowpay');
define('authorize_aim','Authorize.net AIM');
define('authorize_sim','Authorize.net SIM');
define('paypal','PayPal');
define('cc','Кредитная карта (off-line)');

?>
