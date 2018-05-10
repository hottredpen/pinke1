define([ 
     '../components/form_builder/icon/1.0.0/main',
     '../components/form_builder/image/1.0.0/main',
     '../components/form_builder/checkbox/1.0.0/main',
     '../components/form_builder/cover_config_by_category_id/1.0.0/main',
     '../components/form_builder/cpk_pictures/1.0.0/main',
     '../components/form_builder/datepicker/1.0.0/main',
     '../components/form_builder/daterangepicker/1.0.0/main',
     '../components/form_builder/filter_box/1.0.0/main',
     '../components/form_builder/imgbox/1.0.0/main',
     '../components/form_builder/menu_auth/1.0.0/main',
     '../components/form_builder/number/1.0.0/main',
     '../components/form_builder/password/1.0.0/main',
     '../components/form_builder/radio/1.0.0/main',
     '../components/form_builder/select/1.0.0/main',
     '../components/form_builder/switch/1.0.0/main',
     '../components/form_builder/text/1.0.0/main',
     '../components/form_builder/textarea/1.0.0/main',
     '../components/form_builder/ueditor/1.0.0/main',
     '../components/form_builder/user_msg_tpl_box/1.0.0/main',
     '../components/form_builder/weixin_card_color_picker/1.0.0/main',
     '../components/form_builder/weixin_card_preview/1.0.0/main',
     '../components/form_builder/weixin_keywords_str/1.0.0/main',
     '../components/form_builder/weixin_member_card_preview/1.0.0/main',
     '../components/form_builder/weixin_news_side_preview/1.0.0/main',
     '../components/form_builder/weixin_send_box/1.0.0/main',
     '../components/form_builder/store_stock_detail_list/1.0.0/main',
     '../components/form_builder/order_order_detail_list/1.0.0/main',
     '../components/form_builder/text_dialog_with_id/1.0.0/main'],
 function(
  icon,
  image,
  checkbox,
  cover_config_by_category_id,
  cpk_pictures,
  datepicker,
  daterangepicker,
  filter_box,
  imgbox,
  menu_auth,
  number,
  password,
  radio,
  select,
  pk_switch,
  text,
  textarea,
  ueditor,
  user_msg_tpl_box,
  weixin_card_color_picker,
  weixin_card_preview,
  weixin_keywords_str,
  weixin_member_card_preview,
  weixin_news_side_preview,
  weixin_send_box,
  store_stock_detail_list,
  order_order_detail_list,
  text_dialog_with_id){	        console.log(' test components');	        var components_arr = [];
 components_arr.push({'name' : icon.getName() , 'main' : icon});
 components_arr.push({'name' : image.getName() , 'main' : image});
 components_arr.push({'name' : checkbox.getName() , 'main' : checkbox});
 components_arr.push({'name' : cover_config_by_category_id.getName() , 'main' : cover_config_by_category_id});
 components_arr.push({'name' : cpk_pictures.getName() , 'main' : cpk_pictures});
 components_arr.push({'name' : datepicker.getName() , 'main' : datepicker});
 components_arr.push({'name' : daterangepicker.getName() , 'main' : daterangepicker});
 components_arr.push({'name' : filter_box.getName() , 'main' : filter_box});
 components_arr.push({'name' : imgbox.getName() , 'main' : imgbox});
 components_arr.push({'name' : menu_auth.getName() , 'main' : menu_auth});
 components_arr.push({'name' : number.getName() , 'main' : number});
 components_arr.push({'name' : password.getName() , 'main' : password});
 components_arr.push({'name' : radio.getName() , 'main' : radio});
 components_arr.push({'name' : select.getName() , 'main' : select});
 components_arr.push({'name' : pk_switch.getName() , 'main' : pk_switch});
 components_arr.push({'name' : text.getName() , 'main' : text});
 components_arr.push({'name' : textarea.getName() , 'main' : textarea});
 components_arr.push({'name' : ueditor.getName() , 'main' : ueditor});
 components_arr.push({'name' : user_msg_tpl_box.getName() , 'main' : user_msg_tpl_box});
 components_arr.push({'name' : weixin_card_color_picker.getName() , 'main' : weixin_card_color_picker});
 components_arr.push({'name' : weixin_card_preview.getName() , 'main' : weixin_card_preview});
 components_arr.push({'name' : weixin_keywords_str.getName() , 'main' : weixin_keywords_str});
 components_arr.push({'name' : weixin_member_card_preview.getName() , 'main' : weixin_member_card_preview});
 components_arr.push({'name' : weixin_news_side_preview.getName() , 'main' : weixin_news_side_preview});
 components_arr.push({'name' : weixin_send_box.getName() , 'main' : weixin_send_box});
 components_arr.push({'name' : store_stock_detail_list.getName() , 'main' : store_stock_detail_list});
 components_arr.push({'name' : order_order_detail_list.getName() , 'main' : order_order_detail_list});
 components_arr.push({'name' : text_dialog_with_id.getName() , 'main' : text_dialog_with_id});
 console.log(components_arr);
	        return components_arr;
	        });