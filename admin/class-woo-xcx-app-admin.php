<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://js.djkz.top
 * @since      1.0.0
 *
 * @package    Woo_Xcx_App
 * @subpackage Woo_Xcx_App/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Xcx_App
 * @subpackage Woo_Xcx_App/admin
 * @author     kzgzs <djkzcjgl@qq.com>
 */
class Woo_Xcx_App_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Xcx_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Xcx_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-xcx-app-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Xcx_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Xcx_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-xcx-app-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * 添加后台主菜单和子菜单
	 */
	public function add_admin_menus() {
		add_menu_page(
			'Woo小程序',
			'Woo小程序',
			'manage_options',
			'woo_xcx_app_settings',
			array($this, 'display_platform_settings'),
			'dashicons-admin-generic',
			6
		);
		add_submenu_page('woo_xcx_app_settings', '平台设置', '平台设置', 'manage_options', 'woo_xcx_app_settings', array($this, 'display_platform_settings'));
		add_submenu_page('woo_xcx_app_settings', '页面列表', '页面列表', 'manage_options', 'woo_xcx_app_pages', array($this, 'display_page_list'));
		add_submenu_page('woo_xcx_app_settings', '页面设计', '页面设计', 'manage_options', 'woo_xcx_app_design', array($this, 'display_page_design'));
		add_submenu_page('woo_xcx_app_settings', '页面分类', '页面分类', 'manage_options', 'woo_xcx_app_categories', array($this, 'display_page_categories'));
		add_submenu_page('woo_xcx_app_settings', '底部导航', '底部导航', 'manage_options', 'woo_xcx_app_nav', array($this, 'display_bottom_nav'));
		add_submenu_page('woo_xcx_app_settings', '商品详情', '商品详情', 'manage_options', 'woo_xcx_app_product_detail', array($this, 'display_product_detail'));
		add_submenu_page('woo_xcx_app_settings', '登录页面', '登录页面', 'manage_options', 'woo_xcx_app_login', array($this, 'display_login_page'));
		add_submenu_page('woo_xcx_app_settings', '链接地址', '链接地址', 'manage_options', 'woo_xcx_app_links', array($this, 'display_links'));
		add_submenu_page('woo_xcx_app_settings', '媒体库', '媒体库', 'manage_options', 'woo_xcx_app_media', array($this, 'display_media_library'));
	}

public function display_platform_settings() {
	// 处理表单保存（微信公众号和微信小程序，后续可扩展）
	$platforms = [
		'wechat_mp' => '微信公众号',
		'wechat_xcx' => '微信小程序',
		'douyin_xcx' => '抖音小程序',
		'alipay_xcx' => '支付宝小程序',
		'qq_xcx' => 'QQ小程序',
		'baidu_xcx' => '百度小程序',
		'kuaishou_xcx' => '快手小程序',
		'jd_xcx' => '京东小程序',
		'xiaohongshu_xcx' => '小红书小程序',
		'h5' => '手机H5',
		'native_app' => '原生 APP',
		'harmony_app' => '鸿蒙 App'
	];
	$current = isset($_POST['woo_xcx_app_platform_type']) ? $_POST['woo_xcx_app_platform_type'] : (isset($_GET['platform']) ? $_GET['platform'] : 'wechat_mp');

	if (isset($_POST['woo_xcx_app_save_platform'])) {
		check_admin_referer('woo_xcx_app_save_platform');
		if ($current === 'wechat_mp') {
			$fields = [
				'appid','appsecret','name','type','avatar','qrcode','ip_whitelist','domain','js_domain','auth_domain','server_url','server_token','server_aeskey','server_encrypt_type','server_safe_mode','server_check_file'
			];
			foreach($fields as $f) {
				if(isset($_POST['woo_xcx_app_wechat_mp_'.$f])) {
					update_option('woo_xcx_app_wechat_mp_'.$f, sanitize_text_field($_POST['woo_xcx_app_wechat_mp_'.$f]));
				}
			}
			echo '<div class="updated notice"><p>微信公众号设置已保存！</p></div>';
		} elseif ($current === 'wechat_xcx') {
			$fields = ['appid','appsecret','name'];
			foreach($fields as $f) {
				if(isset($_POST['woo_xcx_app_wechat_xcx_'.$f])) {
					update_option('woo_xcx_app_wechat_xcx_'.$f, sanitize_text_field($_POST['woo_xcx_app_wechat_xcx_'.$f]));
				}
			}
			echo '<div class="updated notice"><p>微信小程序设置已保存！</p></div>';
		}
		// 其它平台保存逻辑可后续扩展
	}

	// 获取已保存数据
	$data_mp = [];
	foreach(['appid','appsecret','name','type','avatar','qrcode','ip_whitelist','domain','js_domain','auth_domain','server_url','server_token','server_aeskey','server_encrypt_type','server_safe_mode','server_check_file'] as $f) {
		$data_mp[$f] = get_option('woo_xcx_app_wechat_mp_'.$f, '');
	}
	$data_xcx = [];
	foreach(['appid','appsecret','name'] as $f) {
		$data_xcx[$f] = get_option('woo_xcx_app_wechat_xcx_'.$f, '');
	}

	echo '<div class="wrap">';
	echo '<h1>平台设置</h1>';
	echo '<p>欢迎使用 Woo小程序/H5页面设计器！请选择需要对接的平台类型：</p>';
	echo '<form method="post" action="" enctype="multipart/form-data">';
	wp_nonce_field('woo_xcx_app_save_platform');
	echo '<label for="woo_xcx_app_platform_type"><strong>平台类型：</strong></label> ';
	echo '<select id="woo_xcx_app_platform_type" name="woo_xcx_app_platform_type" onchange="this.form.submit()">';
	foreach($platforms as $k=>$v) {
		echo '<option value="'.$k.'"'.($current==$k?' selected':'').'>'.$v.'</option>';
	}
	echo '</select>';
	echo '<hr>';
	if ($current === 'wechat_mp') {
		// 微信公众号表单
		echo '<h2>一、填写公众号信息</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>AppID：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_appid" value="'.esc_attr($data_mp['appid']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>AppSecret：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_appsecret" value="'.esc_attr($data_mp['appsecret']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>公众号名称：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_name" value="'.esc_attr($data_mp['name']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>公众号类型：</label></th><td>';
		$types = ['认证服务号','认证订阅号','普通服务号','平台订阅号'];
		foreach($types as $t) {
			echo '<label><input type="radio" name="woo_xcx_app_wechat_mp_type" value="'.$t.'"'.($data_mp['type']==$t?' checked':'').'>'.$t.'</label> ';
		}
		echo '</td></tr>';
		echo '<tr><th><label>公众号头像：</label></th><td>';
		echo '<input type="text" name="woo_xcx_app_wechat_mp_avatar" id="woo_xcx_app_wechat_mp_avatar" value="'.esc_attr($data_mp['avatar']).'" class="regular-text">';
		echo '<button type="button" class="button" id="woo_xcx_app_avatar_upload">上传</button>';
		if($data_mp['avatar']) echo '<br><img src="'.esc_url($data_mp['avatar']).'" style="max-width:100px;">';
		echo '</td></tr>';
		echo '<tr><th><label>公众号二维码：</label></th><td>';
		echo '<input type="text" name="woo_xcx_app_wechat_mp_qrcode" id="woo_xcx_app_wechat_mp_qrcode" value="'.esc_attr($data_mp['qrcode']).'" class="regular-text">';
		echo '<button type="button" class="button" id="woo_xcx_app_qrcode_upload">上传</button>';
		if($data_mp['qrcode']) echo '<br><img src="'.esc_url($data_mp['qrcode']).'" style="max-width:100px;">';
		echo '</td></tr>';
		echo '</table>';

		echo '<h2>二、设置IP白名单及域名</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>IP白名单：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_ip_whitelist" value="'.esc_attr($data_mp['ip_whitelist']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>业务域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_domain" value="'.esc_attr($data_mp['domain']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>JS接口安全域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_js_domain" value="'.esc_attr($data_mp['js_domain']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>网页授权域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_auth_domain" value="'.esc_attr($data_mp['auth_domain']).'" class="regular-text"></td></tr>';
		echo '</table>';

		echo '<h2>三、配置服务器域名</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>URL(服务器地址)：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_server_url" value="'.esc_attr($data_mp['server_url']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>Token(令牌)：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_server_token" value="'.esc_attr($data_mp['server_token']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>EncodingAESKey(消息加密密钥)：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_server_aeskey" value="'.esc_attr($data_mp['server_aeskey']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>消息加解密方式：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_server_encrypt_type" value="'.esc_attr($data_mp['server_encrypt_type']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>安全模式：</label></th><td><input type="text" name="woo_xcx_app_wechat_mp_server_safe_mode" value="'.esc_attr($data_mp['server_safe_mode']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>接口校验文件：</label></th><td>';
		echo '<input type="file" name="woo_xcx_app_wechat_mp_server_check_file_upload" id="woo_xcx_app_wechat_mp_server_check_file_upload">';
		if($data_mp['server_check_file']) echo '<br><span>已上传：'.esc_html($data_mp['server_check_file']).'</span>';
		echo '</td></tr>';
		echo '</table>';

		echo '<p><input type="submit" name="woo_xcx_app_save_platform" class="button button-primary" value="保存设置"></p>';
	}
	if ($current === 'wechat_xcx') {
		// 微信小程序表单（详细字段）
		echo '<h2>一、填写小程序信息</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>AppID：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_appid" value="'.esc_attr($data_xcx['appid']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>AppSecret：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_appsecret" value="'.esc_attr($data_xcx['appsecret']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>小程序名称：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_name" value="'.esc_attr($data_xcx['name']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>小程序头像：</label></th><td>';
		echo '<input type="text" name="woo_xcx_app_wechat_xcx_avatar" id="woo_xcx_app_wechat_xcx_avatar" value="'.esc_attr($data_xcx['avatar']).'" class="regular-text">';
		echo '<button type="button" class="button" id="woo_xcx_app_xcx_avatar_upload">上传</button>';
		if(!empty($data_xcx['avatar'])) echo '<br><img src="'.esc_url($data_xcx['avatar']).'" style="max-width:100px;">';
		echo '</td></tr>';
		echo '<tr><th><label>小程序码：</label></th><td>';
		echo '<input type="text" name="woo_xcx_app_wechat_xcx_qrcode" id="woo_xcx_app_wechat_xcx_qrcode" value="'.esc_attr($data_xcx['qrcode']).'" class="regular-text">';
		echo '<button type="button" class="button" id="woo_xcx_app_xcx_qrcode_upload">上传</button>';
		if(!empty($data_xcx['qrcode'])) echo '<br><img src="'.esc_url($data_xcx['qrcode']).'" style="max-width:100px;">';
		echo '</td></tr>';
		echo '</table>';

		echo '<h2>二、配置服务器域名</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>request合法域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_request_domain" value="'.esc_attr($data_xcx['request_domain']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>socket合法域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_socket_domain" value="'.esc_attr($data_xcx['socket_domain']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>uploadFile合法域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_upload_domain" value="'.esc_attr($data_xcx['upload_domain']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>downloadFile合法域名：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_download_domain" value="'.esc_attr($data_xcx['download_domain']).'" class="regular-text"></td></tr>';
		echo '</table>';

		echo '<h2>三、配置消息推送</h2>';
		echo '<table class="form-table">';
		echo '<tr><th><label>URL(服务器地址)：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_url" value="'.esc_attr($data_xcx['server_url']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>Token(令牌)：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_token" value="'.esc_attr($data_xcx['server_token']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>EncodingAESKey(消息加密密钥)：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_aeskey" value="'.esc_attr($data_xcx['server_aeskey']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>消息加密方式：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_encrypt_type" value="'.esc_attr($data_xcx['server_encrypt_type']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>安全模式：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_safe_mode" value="'.esc_attr($data_xcx['server_safe_mode']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>数据格式：</label></th><td><input type="text" name="woo_xcx_app_wechat_xcx_server_data_format" value="'.esc_attr($data_xcx['server_data_format']).'" class="regular-text"></td></tr>';
		echo '<tr><th><label>上传域名校验文件：</label></th><td><input type="file" name="woo_xcx_app_wechat_xcx_server_check_file_upload" id="woo_xcx_app_wechat_xcx_server_check_file_upload">';
		if(!empty($data_xcx['server_check_file'])) echo '<br><span>已上传：'.esc_html($data_xcx['server_check_file']).'</span>';
		echo '</td></tr>';
		echo '</table>';

		echo '<p><input type="submit" name="woo_xcx_app_save_platform" class="button button-primary" value="保存设置"></p>';
		// 小程序图片上传JS
		echo '<script>
		jQuery(document).ready(function($){
			var xcx_avatar_uploader, xcx_qrcode_uploader;
			$("#woo_xcx_app_xcx_avatar_upload").click(function(e){
				e.preventDefault();
				if(xcx_avatar_uploader){xcx_avatar_uploader.open();return;}
				xcx_avatar_uploader = wp.media({title:"选择小程序头像",button:{text:"选择"},multiple:false});
				xcx_avatar_uploader.on("select",function(){var attachment=xcx_avatar_uploader.state().get("selection").first().toJSON();$("#woo_xcx_app_wechat_xcx_avatar").val(attachment.url);});
				xcx_avatar_uploader.open();
			});
			$("#woo_xcx_app_xcx_qrcode_upload").click(function(e){
				e.preventDefault();
				if(xcx_qrcode_uploader){xcx_qrcode_uploader.open();return;}
				xcx_qrcode_uploader = wp.media({title:"选择小程序码",button:{text:"选择"},multiple:false});
				xcx_qrcode_uploader.on("select",function(){var attachment=xcx_qrcode_uploader.state().get("selection").first().toJSON();$("#woo_xcx_app_wechat_xcx_qrcode").val(attachment.url);});
				xcx_qrcode_uploader.open();
			});
		});
		</script>';
	echo '</form>';
	// 图片上传JS（WP官方方式）
	echo '<script>
	jQuery(document).ready(function($){
		var avatar_uploader, qrcode_uploader;
		$("#woo_xcx_app_avatar_upload").click(function(e){
			e.preventDefault();
			if(avatar_uploader){avatar_uploader.open();return;}
			avatar_uploader = wp.media({title:"选择公众号头像",button:{text:"选择"},multiple:false});
			avatar_uploader.on("select",function(){var attachment=avatar_uploader.state().get("selection").first().toJSON();$("#woo_xcx_app_wechat_mp_avatar").val(attachment.url);});
			avatar_uploader.open();
		});
		$("#woo_xcx_app_qrcode_upload").click(function(e){
			e.preventDefault();
			if(qrcode_uploader){qrcode_uploader.open();return;}
			qrcode_uploader = wp.media({title:"选择公众号二维码",button:{text:"选择"},multiple:false});
			qrcode_uploader.on("select",function(){var attachment=qrcode_uploader.state().get("selection").first().toJSON();$("#woo_xcx_app_wechat_mp_qrcode").val(attachment.url);});
			qrcode_uploader.open();
		});
	});
	</script>';
	echo '</div>';
}
	public function display_page_list() { echo '<h1>页面列表</h1>'; }
	public function display_page_design() { echo '<h1>页面设计</h1>'; }
	public function display_page_categories() { echo '<h1>页面分类</h1>'; }
	public function display_bottom_nav() { echo '<h1>底部导航</h1>'; }
	public function display_product_detail() { echo '<h1>商品详情</h1>'; }
	public function display_login_page() { echo '<h1>登录页面</h1>'; }
	public function display_links() { echo '<h1>链接地址</h1>'; }
	public function display_media_library() { echo '<h1>媒体库</h1>'; }
}
