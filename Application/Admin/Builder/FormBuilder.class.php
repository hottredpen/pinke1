<?php

namespace Admin\Builder;
use Common\Builder\FormBuilder as FormBaseBuilder;
/**
 * 为了保证主组件的不断优化，同时满足对各个开发者升级及自定义组件需求
 * 做一下约定：
 * 以text组件为例
 * 如果您是开发者对text的内部进行修改,但官方没有及时采纳你的修改，则请在text/1.0.0/yourname/
 * 下面建立一个text的相关文件，在并在本文件的$this->_component_alias中以text@1.0.0~yourname进行指向
 * 如果你的text组件已经与text比较大的改动，建议以text-something作为一个组件命名
 * 并将组件提交至官网,获取组件的专属key（免费或者有偿使用该组件的一组加密串）
 */
class FormBuilder extends FormBaseBuilder {

	protected function _initialize() {
		parent::_initialize();

		$this->_builder_module  = 'Admin'; // 当前模块
		$this->_component_alias = array(
			'icon'                        => 'icon@1.0.0',
			'image'                       => 'image@1.0.0',
			'checkbox'                    => 'checkbox@1.0.0',
			'cover_config_by_category_id' => 'cover_config_by_category_id@1.0.0',
			'cpk_pictures'                => 'cpk_pictures@1.0.0',
			'datepicker'                  => 'datepicker@1.0.0',
			'daterangepicker'             => 'daterangepicker@1.0.0',
			'filter_box'                  => 'filter_box@1.0.0',
			'imgbox'                      => 'imgbox@1.0.0',
			'menu_auth'                   => 'menu_auth@1.0.0',
			'number'                      => 'number@1.0.0',
			'password'                    => 'password@1.0.0',
			'radio'                       => 'radio@1.0.0',
			'select'                      => 'select@1.0.0',
			'switch'                      => 'switch@1.0.0',
			'text'                        => 'text@1.0.0',
			'textarea'                    => 'textarea@1.0.0',
			'ueditor'                     => 'ueditor@1.0.0',
			'user_msg_tpl_box'            => 'user_msg_tpl_box@1.0.0',
			'weixin_card_color_picker'    => 'weixin_card_color_picker@1.0.0',
			'weixin_card_preview'         => 'weixin_card_preview@1.0.0',
			'weixin_keywords_str'         => 'weixin_keywords_str@1.0.0',
			'weixin_material_image_dialog'=> 'weixin_material_image_dialog@1.0.0',
			'weixin_member_card_preview'  => 'weixin_member_card_preview@1.0.0',
			'weixin_news_side_preview'    => 'weixin_news_side_preview@1.0.0',
			'weixin_send_box'             => 'weixin_send_box@1.0.0',
			'store_stock_detail_list'     => 'store_stock_detail_list@1.0.0',
			'order_order_detail_list'     => 'order_order_detail_list@1.0.0',
			'text_dialog_with_id'         => 'text_dialog_with_id@1.0.0'


		);
	}

}