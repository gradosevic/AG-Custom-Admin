<?php
/*
Plugin Name: AG Custom Admin
Plugin URI: http://wordpress.org/extend/plugins/ag-custom-admin
Description: Hide or change items in admin panel.
Author: Argonius
Version: 1.2
Author URI: http://wordpress.argonius.com/ag-custom-admin

	Copyright 2011. Argonius (email : info@argonius.com)
 
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



$agca = new AGCA();

class AGCA{
	
	public function __construct()
	{		
				
		add_filter('plugin_row_meta', array(&$this,'jk_filter_plugin_links'), 10, 2);
		add_action('admin_init', array(&$this,'agca_register_settings'));
		add_action('admin_head', array(&$this,'print_admin_css'));		
		add_action('login_head', array(&$this,'print_login_head'));	
		add_action('admin_menu', array(&$this,'agca_create_menu'));		
		register_deactivation_hook(__FILE__, array(&$this,'agca_deactivate'));	
		
		/*Styles*/
	//	add_action('admin_menu', array(&$this,'agca_get_styles'));
	//	add_action('login_head', array(&$this,'agca_get_styles'));
	}
	// Add donate and support information
	function jk_filter_plugin_links($links, $file)
	{
		if ( $file == plugin_basename(__FILE__) )
		{
		$links[] = '<a href="tools.php?page=ag-custom-admin/plugin.php">' . __('Settings') . '</a>';
		$links[] = '<a href="http://wordpress.argonius.com/ag-custom-admin">' . __('Support') . '</a>';
		$links[] = '<a href="http://wordpress.argonius.com/donate">' . __('Donate') . '</a>';
		}
		return $links;
	}
	function agca_get_includes() {
		?>			
			<link rel="stylesheet" type="text/css" href="<?php echo trailingslashit(plugins_url(basename(dirname(__FILE__)))); ?>style/ag_style.css" />
			<script type="text/javascript" src="<?php echo trailingslashit(plugins_url(basename(dirname(__FILE__)))); ?>script/ag_script.js"></script>	
		<?php
	}
	
	function reloadScript(){
			?>
			<script type="text/javascript" src="<?php echo get_settings('home'); ?>/wp-includes/js/jquery/jquery.js"></script>	
			<?php
			
	}
	
	function agca_register_settings() {
		register_setting( 'agca-options-group', 'agca_screen_options_menu' );
		register_setting( 'agca-options-group', 'agca_help_menu' );
		register_setting( 'agca-options-group', 'agca_logout' );
		register_setting( 'agca-options-group', 'agca_logout_only' );
		register_setting( 'agca-options-group', 'agca_options_menu' );
		register_setting( 'agca-options-group', 'agca_howdy' );
		register_setting( 'agca-options-group', 'agca_header' );
		register_setting( 'agca-options-group', 'agca_header_show_logout' );		
		register_setting( 'agca-options-group', 'agca_footer' );
		register_setting( 'agca-options-group', 'agca_privacy_options' );
		register_setting( 'agca-options-group', 'agca_header_logo' );
		register_setting( 'agca-options-group', 'agca_site_heading' );
		register_setting( 'agca-options-group', 'agca_custom_site_heading' );
		register_setting( 'agca-options-group', 'agca_update_bar' );
		
		register_setting( 'agca-options-group', 'agca_footer_left' );
		register_setting( 'agca-options-group', 'agca_footer_left_hide' );		
		register_setting( 'agca-options-group', 'agca_footer_right' );
		register_setting( 'agca-options-group', 'agca_footer_right_hide' );
		
		register_setting( 'agca-options-group', 'agca_login_banner' );
		register_setting( 'agca-options-group', 'agca_login_banner_text' );
		register_setting( 'agca-options-group', 'agca_login_photo_remove' );
		register_setting( 'agca-options-group', 'agca_login_photo_url' );
		register_setting( 'agca-options-group', 'agca_login_photo_href' );
		
		//register_setting( 'agca-options-group', 'agca_menu_dashboard' ); DEPRECATED 1.2
		register_setting( 'agca-options-group', 'agca_dashboard_icon' );
		register_setting( 'agca-options-group', 'agca_dashboard_text' );
		register_setting( 'agca-options-group', 'agca_dashboard_text_paragraph' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_rc' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_il' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_plugins' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_qp' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_rn' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_rd' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_primary' );	
		register_setting( 'agca-options-group', 'agca_dashboard_widget_secondary' );			

		/*Admin menu*/
		register_setting( 'agca-options-group', 'agca_admin_menu_turnonoff' );	
		register_setting( 'agca-options-group', 'agca_admin_menu_agca_button_only' );	
		register_setting( 'agca-options-group', 'agca_admin_menu_separator_first' );	
		register_setting( 'agca-options-group', 'agca_admin_menu_separator_second' );		
		register_setting( 'agca-options-group', 'ag_edit_adminmenu_json' );
		register_setting( 'agca-options-group', 'ag_add_adminmenu_json' );	
		
	}

	function agca_deactivate() {
		delete_option( 'agca_screen_options_menu' );
		delete_option(  'agca_help_menu' );
		delete_option(  'agca_logout' );
		delete_option(  'agca_logout_only' );
		delete_option(  'agca_options_menu' );
		delete_option(  'agca_howdy' );
		delete_option(  'agca_header' );
		delete_option(  'agca_header_show_logout' );
		delete_option(  'agca_footer' );
		delete_option(  'agca_privacy_options' );
		delete_option(  'agca_header_logo' );
		delete_option(  'agca_site_heading' );
		delete_option(  'agca_custom_site_heading' );
		delete_option(  'agca_update_bar' );
		
		delete_option(  'agca_footer_left' );
		delete_option(  'agca_footer_left_hide' );
		delete_option(  'agca_footer_right' );
		delete_option(  'agca_footer_right_hide' );
		
		delete_option( 'agca_login_banner' );
		delete_option( 'agca_login_banner_text' );
		delete_option( 'agca_login_photo_remove' );
		delete_option( 'agca_login_photo_url' );
		delete_option( 'agca_login_photo_href' );		
		
		//delete_option(  'agca_menu_dashboard' ); DEPRECATED 1.2
		delete_option(  'agca_dashboard_icon' );
		delete_option(  'agca_dashboard_text' );
		delete_option(  'agca_dashboard_text_paragraph' );	
		delete_option(  'agca_dashboard_widget_rc' );	
		delete_option(  'agca_dashboard_widget_il' );	
		delete_option(  'agca_dashboard_widget_plugins' );	
		delete_option(  'agca_dashboard_widget_qp' );	
		delete_option(  'agca_dashboard_widget_rn' );	
		delete_option(  'agca_dashboard_widget_rd' );	
		delete_option(  'agca_dashboard_widget_primary' );	
		delete_option(  'agca_dashboard_widget_secondary' );

		/*Admin menu*/
		delete_option(  'agca_admin_menu_turnonoff' );
		delete_option(  'agca_admin_menu_agca_button_only' );
		delete_option(  'agca_admin_menu_separator_first' );
		delete_option(  'agca_admin_menu_separator_second' );
		delete_option(  'ag_edit_adminmenu_json' );
		delete_option(  'ag_add_adminmenu_json' );	
	
	}   
	function agca_create_menu() {
	//create new top-level menu		
		add_management_page( 'AG Custom Admin', 'AG Custom Admin', 'administrator', __FILE__, array(&$this,'agca_admin_page') );
	}
	
	function agca_create_admin_button($name,$href) {
		$class="";
		if($name == 'AG Custom Admin'){
			$class="agca_button_only";
		}
		$button ="";
		$button .= '<li id="menu-'.$name.'" class="ag-custom-button menu-top menu-top-first '.$class.' menu-top-last">';
			/*<div class="wp-menu-image">
				<a href="edit-comments.php"><br></a>
			</div>*/
			$button .= '<div class="wp-menu-toggle" style="display: none;"><br></div>';
			$button .=  '<a tabindex="1" class="menu-top menu-top-last" href="'.$href.'">'.$name.'<a>';
		$button .=  '</li>';
		
		return $button;		
	}	
	function agca_decode($code){
		$code = str_replace("{","",$code);
		$code = str_replace("}","",$code);
		$elements = explode(", ",$code);
		
		return $elements;
	}
	
	function jsonMenuArray($json,$type){
		$arr = explode("|",$json);
		$elements = "";
		$array ="";
		$first = true;
		//print_r($json);
		if($type == "buttons"){
			$elements = json_decode($arr[0],true);
			if($elements !=""){
				foreach($elements as $k => $v){		
					$array.=$this->agca_create_admin_button($k,$v);			
				}	
			}
		}else if($type == "buttonsJq"){
			$elements = json_decode($arr[0],true);
			if($elements !=""){
				foreach($elements as $k => $v){	
					$array.='<tr><td colspan="2"><button title="'.$v.'" type="button">'.$k.'</button>&nbsp;(<a style="cursor:pointer" class="button_edit">edit</a>)&nbsp;(<a style="cursor:pointer" class="button_remove">remove</a>)</td><td></td></tr>';							
				}	
			}
		}else{
			//$elements = json_decode($arr[$type],true);			
			$elements = $this->agca_decode($arr[$type]);
			if($elements !=""){
				foreach($elements as $element){
					if(!$first){
						$array .=",";
					}
					$parts = explode(" : ",$element);
					$array.="[".$parts[0].", ".$parts[1]."]";					
					$first=false;
				}	
			}	
		}
			
		return $array;			
	}
	

 
	function remove_dashboard_widget($widget,$side)	
	{
		//side can be 'normal' or 'side'
		global $wp_meta_boxes;
		remove_meta_box($widget, 'dashboard', $side); 
	}

	function print_admin_css()
	{
		$this->agca_get_includes();
		
		get_currentuserinfo() ;
		global $user_level;		
	?>		
	      <script type="text/javascript">
        /* <![CDATA[ */
            jQuery(document).ready(function() {
			

					<?php if(get_option('agca_screen_options_menu')==true){ ?>
							jQuery("#screen-options-link-wrap").css("display","none");
					<?php } ?>	
					<?php if(get_option('agca_help_menu')==true){ ?>
							jQuery("#contextual-help-link-wrap").css("display","none");
							jQuery("#contextual-help-link").css("display","none");							
					<?php } ?>	
					<?php if(get_option('agca_options_menu')==true){ ?>
							jQuery("#favorite-actions").css("display","none");
					<?php } ?>	
					<?php if(get_option('agca_privacy_options')==true){ ?>
							jQuery("#privacy-on-link").css("display","none");
					<?php } ?>	
					<?php if(get_option('agca_header_logo')==true){ ?>
							jQuery("#wphead #header-logo").css("display","none");
					<?php } ?>
					<?php if(get_option('agca_site_heading')==true){ ?>
							jQuery("#wphead #site-heading").css("display","none");
					<?php } ?>
					<?php if(get_option('agca_custom_site_heading')!=""){ ?>	
							jQuery("#wphead #site-heading").after('<h1><?php echo get_option('agca_custom_site_heading'); ?></h1>');
					<?php } ?>	
					<?php if(get_option('agca_update_bar')==true){ ?>
							jQuery("#update-nag").css("display","none");
					<?php } ?>
					<?php if(get_option('agca_header')==true){ ?>
							jQuery("#wphead").css("display","none");
					<?php } ?>	
					<?php if((get_option('agca_header')==true)&&(get_option('agca_header_show_logout')==true)){ ?>
							var clon ="";
							jQuery("div#user_info a").each(function(){
								if(jQuery(this).text() =="Log Out"){
									clon = jQuery(this).clone();
								}								
							});
							if(clon !=""){
								jQuery(clon).attr('style','float:right;padding:15px');	
								jQuery(clon).html('<?php echo ((get_option('agca_logout')=="")?"Log Out":get_option('agca_logout')); ?>');	
							}													
							jQuery("#wphead").after(clon);
							
					<?php } ?>	
					<?php if(get_option('agca_footer')==true){ ?>
							jQuery("#footer").css("display","none");
					<?php } ?>											
					<?php if(get_option('agca_howdy')!=""){ ?>
							var howdyText = jQuery("#user_info").html();
							jQuery("#user_info").html("<p>"+"<?php echo get_option('agca_howdy'); ?>"+howdyText.substr(9));	
					<?php } ?>
					<?php if(get_option('agca_logout')!=""){ ?>							
							jQuery("#user_info a:eq(1)").text("<?php echo get_option('agca_logout'); ?>");
					<?php } ?>
					<?php if(get_option('agca_logout_only')==true){ ?>						
							var logoutText = jQuery("#user_info a:nth-child(2)").text();
							var logoutLink = jQuery("#user_info a:nth-child(2)").attr("href");						
							jQuery("#user_info").html("<a href=\""+logoutLink+"\" title=\"Log Out\">"+logoutText+"</a>");
					<?php } ?>	

					
					<?php if(get_option('agca_footer_left')!=""){ ?>												
								jQuery("#footer-left").html('<?php echo get_option('agca_footer_left'); ?>');
					<?php } ?>	
					<?php if(get_option('agca_footer_left_hide')==true){ ?>											
								jQuery("#footer-left").css("display","none");
					<?php } ?>
					<?php if(get_option('agca_footer_right')!=""){ ?>												
								jQuery("#footer-upgrade").html('<?php echo get_option('agca_footer_right'); ?>');
					<?php } ?>
					<?php if(get_option('agca_footer_right_hide')==true){ ?>											
								jQuery("#footer-upgrade").css("display","none");
					<?php } ?>
					
					<?php if(get_option('agca_language_bar')==true){ ?>
							jQuery("#user_info p").append('<?php include("language_bar/language_bar.php"); ?>');
					<?php } ?>
					<?php //DEPRECATED 1.2
					/*if(get_option('agca_menu_dashboard')==true){ 
							jQuery("#adminmenu #menu-dashboard").css("display","none");
					 } */?>
					<?php if(get_option('agca_dashboard_icon')==true){ ?>
							var className = jQuery("#icon-index").attr("class");
							if(className=='icon32'){
								jQuery("#icon-index").attr("id","icon-index-removed");
							}
					<?php } ?>
					<?php if(get_option('agca_dashboard_text')!=""){ ?>							
							jQuery("#dashboard-widgets-wrap").parent().find("h2").text("<?php echo get_option('agca_dashboard_text'); ?>");
					<?php } ?>
					<?php if(get_option('agca_dashboard_text_paragraph')!=""){ ?>	
							jQuery("#wpbody-content #dashboard-widgets-wrap").before('<br /><p style=\"text-indent:45px;\"><?php echo get_option('agca_dashboard_text_paragraph'); ?></p>');
					<?php } ?>
					
					<?php /*Remove Dashboard widgets*/ ?>
					<?php			

 
						if(get_option('agca_dashboard_widget_rc')==true){
							$this->remove_dashboard_widget('dashboard_recent_comments','normal');
						}else{
							?>jQuery("#dashboard_recent_comments").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_il')==true){
							$this->remove_dashboard_widget('dashboard_incoming_links','normal');
						}else{
							?>jQuery("#dashboard_incoming_links").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_plugins')==true){
							$this->remove_dashboard_widget('dashboard_plugins','normal');
						}else{
							?>jQuery("#dashboard_plugins").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_qp')==true){
							$this->remove_dashboard_widget('dashboard_quick_press','side');
						}else{
							?>jQuery("#dashboard_quick_press").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_rn')==true){
							$this->remove_dashboard_widget('dashboard_right_now','normal');
						}else{
							?>jQuery("#dashboard_right_now").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_rd')==true){
							$this->remove_dashboard_widget('dashboard_recent_drafts','side');
						}else{
							?>jQuery("#dashboard_recent_drafts").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_primary')==true){
							$this->remove_dashboard_widget('dashboard_primary','side');
						}else{
							?>jQuery("#dashboard_primary").css("display","block");<?php
						}
						if(get_option('agca_dashboard_widget_secondary')==true){
							$this->remove_dashboard_widget('dashboard_secondary','side');
						}else{
							?>jQuery("#dashboard_secondary").css("display","block");<?php
						}
					?>
					
					
					<?php /*ADMIN MENU*/ ?>	

					//saved user menu configuration	
					<?php	$checkboxes = $this->jsonMenuArray(get_option('ag_edit_adminmenu_json'),'0');	?>
					var checkboxes = <?php echo "[".$checkboxes."]"; ?>;

					<?php	$textboxes = $this->jsonMenuArray(get_option('ag_edit_adminmenu_json'),'1');	?>
					var textboxes = <?php echo "[".$textboxes."]"; ?>;
					
					<?php	$buttons = $this->jsonMenuArray(get_option('ag_add_adminmenu_json'),'buttons');	?>
					var buttons = '<?php echo $buttons; ?>';	
					
					<?php	$buttonsJq = $this->jsonMenuArray(get_option('ag_add_adminmenu_json'),'buttonsJq');	?>
					var buttonsJq = '<?php echo $buttonsJq; ?>';	
				
					
					createEditMenuPage(checkboxes,textboxes);
					
					<?php if(get_option('agca_admin_menu_turnonoff') == 'on'){ ?>
					
					<?php /*If Turned on*/ ?>
					
					<?php /*Only admin see button*/
							if ($user_level > 9){ ?>								
								jQuery('#adminmenu').append('<?php echo $this->agca_create_admin_button('AG Custom Admin','tools.php?page=ag-custom-admin/plugin.php'); ?>');
							<?php } ?>
							
							<?php if(get_option('agca_admin_menu_agca_button_only') == true){ ?>											
								jQuery('#adminmenu > li').each(function(){
									if(!jQuery(this).hasClass('agca_button_only')){
										jQuery(this).addClass('noclass');
									}
								});
							 <?php } ?>					
							<?php if(get_option('agca_admin_menu_separator_first')==true){ ?>											
								jQuery("li.wp-menu-separator").eq(0).css("display","none");
							<?php } ?>
							<?php if(get_option('agca_admin_menu_separator_second')==true){ ?>											
								jQuery("li.wp-menu-separator").eq(1).css("display","none");
							<?php } ?>							
							<?php /*EDIT MENU ITEMS*/?>
							<?php if(get_option('ag_edit_adminmenu_json')!=""){ 											
									
									?>			
										var checkboxes_counter = 0;
										var createAGCAbutton = false;
									//console.log(checkboxes);							
									//console.log(textboxes);
									<?php //loop through original menu and hide and change elements according to user setttngs ?>																		

										var topmenuitem;
										jQuery('ul#adminmenu > li').each(function(){											
											
											if(!jQuery(this).hasClass("wp-menu-separator") && !jQuery(this).hasClass("wp-menu-separator-last")){
												//alert(checkboxes[checkboxes_counter]);
												
												topmenuitem = jQuery(this).attr('id');
												//console.log(jQuery(this));										
												
												var matchFound = false;
												var subMenus = "";
												
												for(i=0; i< checkboxes.length ; i++){
												
													if(checkboxes[i][0].indexOf("<-TOP->") >=0){ //if it is top item													
														if(checkboxes[i][0].indexOf(topmenuitem) >0){//if found match in menu, with top item in array															
															matchFound = true;		
															//console.log(checkboxes[i][0]);															
															jQuery(this).find('a').eq(1).html(textboxes[i][1]);
															if(checkboxes[i][1] == "true"){
																jQuery(this).addClass('noclass');
															}
															
															i++;
															var selector = '#' + topmenuitem + ' ul li';
															//console.log(i+" "+checkboxes);													
																while((i<checkboxes.length) && (checkboxes[i][0].indexOf("<-TOP->") < 0)){															
																	jQuery(selector).each(function(){ //loop through all submenus																	
																		if(checkboxes[i][0] == jQuery(this).text()){
																			if(checkboxes[i][1] == "true"){
																				jQuery(this).addClass('noclass');
																			}
																			jQuery(this).find('a').text(textboxes[i][1]);																			
																		}
																	});
																	i++;
																}						
														};
													}else{
														//i++;
													}												
												}
												//console.log(subMenus);					
												//checkboxes_counter++;
											}
										});								
									<?php
							 } ?>
							
							
							/*Add user buttons*/					
							jQuery('#adminmenu').append(buttons);							
							
							
					<?php /*END If Turned on*/ ?>
					<?php } else{ ?>
							jQuery("#adminmenu").removeClass("noclass");
					<?php } ?>
					
					
					
					/*Add user buttons*/	
					jQuery('#ag_add_adminmenu').append(buttonsJq);
					reloadRemoveButtonEvents();	 
										
            });
        /* ]]> */
        </script>
		<style type="text/css">
			.underline_text{
				text-decoration:underline;
			}
			.form-table th{
				width:300px;
			}
		</style>
	<?php 	
	}
	
	function print_login_head(){
		
		$this->reloadScript();
		$this->agca_get_includes();
	?>	
	     <script type="text/javascript">
        /* <![CDATA[ */
            jQuery(document).ready(function() {

					<?php if(get_option('agca_login_banner')==true){ ?>
							jQuery("#backtoblog").css("display","none");
					<?php } ?>	
					<?php if(get_option('agca_login_banner_text')==true){ ?>
							jQuery("#backtoblog").html('<?php echo get_option('agca_login_banner_text'); ?>');
					<?php } ?>
					<?php if(get_option('agca_login_photo_url')==true){ ?>						
							var $url = "url(" + "<?php echo get_option('agca_login_photo_url'); ?>" + ")";
							jQuery("#login h1 a").css("background-image",$url);							
					<?php } ?>
					<?php if(get_option('agca_login_photo_href')==true){ ?>						
							var $href = "<?php echo get_option('agca_login_photo_href'); ?>";
							jQuery("#login h1 a").attr("href",$href);							
					<?php } ?>
					<?php if(get_option('agca_login_photo_remove')==true){ ?>
							jQuery("#login h1 a").css("display","none");
					<?php } ?>	
									
						jQuery("#login h1 a").attr("title","");		
            });
        /* ]]> */
        </script>
	<?php 	
	}
	
	function agca_admin_page() {
		?>
					<div class="wrap">
			<h1 style="color:green">AG Custom Admin Settings</h1>						
										<br />					
			<ul id="ag_main_menu">
				<li class="selected" title="Options for Admin Bar">Admin Bar</li>
				<li class="normal">Admin Footer</li>
				<li class="normal">Dashboard Page</li>
				<li class="normal">Login Page</li>
				<li class="normal">Admin Menu</li>
				<a id="agca_donate_button" style="margin-left:8px" title="Do You like this plugin? You can support its future development by providing small donation" href="http://wordpress.argonius.com/donate"><img alt="Donate" src="<?php echo trailingslashit(plugins_url(basename(dirname(__FILE__)))); ?>images/btn_donate_LG.gif" /></a>
			</ul>
			<form method="post" id="agca_form" action="options.php">
				<?php settings_fields( 'agca-options-group' ); ?>				
				<div id="section_admin_bar" class="ag_section">
				<br />
					<p style="font-style:italic"><strong>Info: </strong>Roll over option labels for more information about option.</p>
				<br />
				<table class="form-table" width="500px">							
							<tr valign="center" class="ag_table_major_options" >
								<td >
									<label title="Hide admin bar with all elements in top of admin page" for="agca_header"><strong>Hide admin bar completely</strong></label>
								</td>
								<td>					
									<input type="checkbox" name="agca_header" value="true" <?php if (get_option('agca_header')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center" class="ag_table_major_options" >
								<td >
									<label title='Check this if You want to show "Log Out" button in top right corner of admin page' for="agca_header_show_logout"><strong>(but show 'Log Out' button)</strong></label>
								</td>
								<td>					
									<input type="checkbox" name="agca_header_show_logout" value="true" <?php if ((get_option('agca_header')==true) && (get_option('agca_header_show_logout')==true)) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">								
								<td colspan="2">
									<div class="ag_table_heading"><h3>Elements on Left</h3></div>
								</td>
								<td></td>
							</tr>
							<tr valign="center">
								<th >
									<label title="This is link next to heading in admin bar" for="agca_privacy_options">Hide Privacy link</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_privacy_options" value="true" <?php if (get_option('agca_privacy_options')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<th >
									<label title="Small Wordpress logo in admin top bar" for="agca_header_logo">Hide WordPress logo</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_header_logo" value="true" <?php if (get_option('agca_header_logo')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Adds custom text in admin top bar. Default Wordpress heading stays intact." for="agca_custom_site_heading">Custom blog heading</label>
								</th>
								<td>
								<textarea rows="5" name="agca_custom_site_heading" cols="40"><?php echo htmlspecialchars(get_option('agca_custom_site_heading')); ?></textarea><p><em><strong>Info: </strong>You can use HTML tags like 'h1' and/or 'a' tag</em></p>
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Hides yellow bar with notifications of new Wordpress release" for="agca_update_bar">Hide WordPress update notification bar</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_update_bar" value="true" <?php if (get_option('agca_update_bar')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label for="agca_site_heading">Hide default blog heading</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_site_heading" value="true" <?php if (get_option('agca_site_heading')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Elements on Right</h3></div>
								</td>
								<td>									
								</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label for="agca_screen_options_menu-options">Hide Screen Options menu</label>
								</th>
								<td>						
									<input type="checkbox" name="agca_screen_options_menu" value="true" <?php if (get_option('agca_screen_options_menu')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label for="agca_help_menu">Hide Help menu</label>
								</th>
								<td>						
									<input type="checkbox" name="agca_help_menu" value="true" <?php if (get_option('agca_help_menu')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label for="agca_options_menu">Hide Favorite Actions</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_options_menu" value="true" <?php if (get_option('agca_options_menu')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 	
							<tr valign="center">
								<th scope="row">
									<label for="agca_howdy">Change Howdy text</label>
								</th>
								<td><input type="text" size="47" name="agca_howdy" value="<?php echo get_option('agca_howdy'); ?>" /></td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Put 'Exit', for example" for="agca_logout">Change Log out text</label>
								</th>
								<td><input type="text" size="47" name="agca_logout" value="<?php echo get_option('agca_logout'); ?>" /></td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="If selected, hides all elements in top right corner, except 'Log Out' button" for="agca_logout_only">Log out only</label>
								</th>
								<td>
									<input type="checkbox" name="agca_logout_only" value="true" <?php if (get_option('agca_logout_only')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>							
							</table>
						</div>
						
						<div id="section_admin_footer" style="display:none" class="ag_section">	
							<br /><br />
							<table class="form-table" width="500px">		
							<tr valign="center" class="ag_table_major_options">
								<td>
									<label title="Hides footer with all elements" for="agca_footer"><strong>Hide footer completely</strong></label>
								</td>
								<td>					
									<input type="checkbox" id="agca_footer" name="agca_footer" value="true" <?php if (get_option('agca_footer')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Footer Options</h3></div>
								</td>
								<td>									
								</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label title="Hides default text in footer" for="agca_footer_left_hide">Hide footer text</label>
								</th>
								<td><input type="checkbox" name="agca_footer_left_hide" value="true" <?php if (get_option('agca_footer_left_hide')==true) echo 'checked="checked" '; ?> />								
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Replaces text 'Thank you for creating with WordPress. | Documentation | Feedback' with custom text" for="agca_footer_left">Change footer text</label>
								</th>
								<td>
									<textarea rows="5" name="agca_footer_left" cols="40"><?php echo htmlspecialchars(get_option('agca_footer_left')); ?></textarea>
								</td>						
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Hides text 'Get Version ...' on right" for="agca_footer_right_hide">Hide version text</label>
								</th>
								<td><input type="checkbox" name="agca_footer_right_hide" value="true" <?php if (get_option('agca_footer_right_hide')==true) echo 'checked="checked" '; ?> />								
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Replaces text 'Get Version ...' with custom" for="agca_footer_right">Change version text</label>
								</th>
								<td>
									<textarea rows="5" name="agca_footer_right" cols="40"><?php echo htmlspecialchars(get_option('agca_footer_right')); ?></textarea>
								</td>
							</tr> 	
							</table>
						</div>
						
						<div id="section_dashboard_page" style="display:none" class="ag_section">														
							<table class="form-table" width="500px">	
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Dashboard Page Options</h3></div>
								</td>
								<td></td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label title="This is small 'house' icon next to main heading ('Dashboard' text by default) on Dashboard page" for="agca_dashboard_icon">Hide Dashboard heading icon</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_icon" value="true" <?php if (get_option('agca_dashboard_icon')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
							
							<tr valign="center">
								<th scope="row">
									<label title="Main heading ('Dashboard') on Dashboard page" for="agca_dashboard_text">Change Dashboard heading text</label>
								</th>
								<td><input type="text" size="47" name="agca_dashboard_text" value="<?php echo get_option('agca_dashboard_text'); ?>" /></td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label title="Adds custom text (or HTML) between heading and widgets area on Dashboard page" for="agca_dashboard_text_paragraph">Add custom Dashboard content<br> <em>(text or HTML content)</em></label>
								</th>
								<td>
								<textarea rows="5" name="agca_dashboard_text_paragraph" cols="40"><?php echo htmlspecialchars(get_option('agca_dashboard_text_paragraph')); ?></textarea>
								</td>
							</tr>
							<?php /* DEPRECATED 1.2
							<tr valign="center">
								<th scope="row">
									<label for="agca_menu_dashboard">Hide Dashboard button from main menu</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_menu_dashboard" value="true" <php if (get_option('agca_menu_dashboard')==true) echo 'checked="checked" '; > />
								</td>
							</tr> */ ?>
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Dashboard widgets Options</h3></div>
								</td>
								<td></td>
							</tr>
							<tr><td>
							<p><i><strong>Info:</strong> These settings override settings in Screen options on Dashboard page.</i></p>							
							</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_rc">Hide "Recent Comments"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_rc" value="true" <?php if (get_option('agca_dashboard_widget_rc')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_il">Hide "Incoming Links"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_il" value="true" <?php if (get_option('agca_dashboard_widget_il')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
								<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_plugins">Hide "Plugins"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_plugins" value="true" <?php if (get_option('agca_dashboard_widget_plugins')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>											
							<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_qp">Hide "Quick Press"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_qp" value="true" <?php if (get_option('agca_dashboard_widget_qp')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_rn">Hide "Right Now"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_rn" value="true" <?php if (get_option('agca_dashboard_widget_rn')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							<tr valign="center">
								<th scope="row">
									<label for="agca_dashboard_widget_rd">Hide "Recent Drafts"</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_rd" value="true" <?php if (get_option('agca_dashboard_widget_rd')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							<tr valign="center">
								<th scope="row">
									<label title="This is 'WordPress Development Blog' widget by default" for="agca_dashboard_widget_primary">Hide primary widget area</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_primary" value="true" <?php if (get_option('agca_dashboard_widget_primary')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							<tr valign="center">
								<th scope="row">
									<label title="This is 'Other WordPress News' widget by default"  for="agca_dashboard_widget_secondary">Hide secondary widget area</label>
								</th>
								<td>					
									<input type="checkbox" name="agca_dashboard_widget_secondary" value="true" <?php if (get_option('agca_dashboard_widget_secondary')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>	
							</table>
						</div>
						<div id="section_login_page" style="display:none" class="ag_section">
							<br /><br />					
							<table class="form-table" width="500px">						
							<tr valign="center" class="ag_table_major_options">
								<td>
									<label for="agca_login_banner"><strong>Hide Login top bar completely</strong></label>
								</td>
								<td>					
									<input type="checkbox" name="agca_login_banner" value="true" <?php if (get_option('agca_login_banner')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr>
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Login Page Options</h3></div>
								</td>
								<td>									
								</td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label title="Changes '<- Back to ...' text in top bar on Login page" for="agca_login_banner_text">Change Login top bar text</label>
								</th>
								<td>
									<textarea rows="5" name="agca_login_banner_text" cols="40"><?php echo htmlspecialchars(get_option('agca_login_banner_text')); ?></textarea>&nbsp;<p><i>You should surround it with anchor tag &lt;a&gt;&lt;/a&gt;.</i></p>
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="If this field is not empty, image from provided url will be visible on Login page" for="agca_login_photo_url">Change Login header image</label>
								</th>
								<td>
									<input type="text" size="47" name="agca_login_photo_url" value="<?php echo get_option('agca_login_photo_url'); ?>" />																
									&nbsp;<p><i>Put here link of new login photo (e.g http://www.photo.com/myphoto.jpg). Original photo dimensions are: 310px x 70px</i>.</p>
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Put here custom link to a web location, that will be triggered on image click" for="agca_login_photo_href">Change hyperlink on Login image</label>
								</th>
								<td>
									<input type="text" size="47" name="agca_login_photo_href" value="<?php echo get_option('agca_login_photo_href'); ?>" />								
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="Remove login image completely" for="agca_login_photo_remove">Hide Login header image</label>
								</th>
								<td>
									<input type="checkbox" name="agca_login_photo_remove" value="true" <?php if (get_option('agca_login_photo_remove')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
						</table>
						</div>
						<?php
							/*ADMIN MENU*/
						?>
						<div id="section_admin_menu" style="display:none" class="ag_section">
						<br />
						<p style="font-style:italic"><strong>Important: </strong>Please Turn off menu configuration before activating or disabling other plugins (or making any other changes to main menu). Use "Reset Settings" button to restore default values if anything went wrong.</p>					
						<br />
							<table class="form-table" width="500px">	
							<tr valign="center" class="ag_table_major_options">
								<td><label for="agca_admin_menu_turnonoff"><strong>Turn on/off admin menu configuration</strong></label></td>
								<td><strong><input type="radio" name="agca_admin_menu_turnonoff" value="on" <?php if(get_option('agca_admin_menu_turnonoff') == 'on') echo 'checked="checked" '; ?> /><span style="color:green">ON</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="agca_admin_menu_turnonoff" value="off" <?php if(get_option('agca_admin_menu_turnonoff') != 'on') echo 'checked="checked"'; ?> /><span style="color:red">OFF</span></strong></td>
							</tr>
							<tr valign="center" class="ag_table_major_options">
								<td><label for="agca_admin_menu_agca_button_only"><strong>Hide admin menu completly (administrator can see 'AG custom admin' button)</strong></label></td>
								<td><input type="checkbox" name="agca_admin_menu_agca_button_only" value="true" <?php if (get_option('agca_admin_menu_agca_button_only')==true) echo 'checked="checked" '; ?> /></td>
							</tr>
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Edit/Remove Menu Items</h3></div>
								</td>
								<td>									
								</td>
							</tr>
							<tr>
								<td colspan="2">
								Reset to default values
											<button type="button" id="ag_edit_adminmenu_reset_button" name="ag_edit_adminmenu_reset_button">Reset Settings</button><br />
											<p><em>(click on menu item to show/hide submenus)</em></p>
									<table id="ag_edit_adminmenu">									
										<tr style="background-color:#999;">
											<td width="300px"><div style="float:left;color:#fff;"><h3>Item</h3></div><div style="float:right;color:#fff;"><h3>Remove?</h3></div></td><td width="300px" style="color:#fff;" ><h3>Change Text</h3>													
											</td>
										</tr>
									</table>
									<input type="hidden" size="47" id="ag_edit_adminmenu_json" name="ag_edit_adminmenu_json" value="<?php echo htmlspecialchars(get_option('ag_edit_adminmenu_json')); ?>" />
								</td>
								<td></td>
							</tr>
							<tr valign="center">
								<th scope="row">
									<label title="This is arrow like separator between Dashboard and Posts button (by default)" for="agca_admin_menu_separator_first">Remove first items separator</label>
								</th>
								<td>
									<input type="checkbox" name="agca_admin_menu_separator_first" value="true" <?php if (get_option('agca_admin_menu_separator_first')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<th scope="row">
									<label title="This is arrow like separator between Comments and Appearance button (by default)" for="agca_admin_menu_separator_second">Remove second items separator</label>
								</th>
								<td>
									<input type="checkbox" name="agca_admin_menu_separator_second" value="true" <?php if (get_option('agca_admin_menu_separator_second')==true) echo 'checked="checked" '; ?> />
								</td>
							</tr> 
							<tr valign="center">
								<td colspan="2">
										<div class="ag_table_heading"><h3>Add New Menu Items</h3></div>
								</td>
								<td>									
								</td>
							</tr> 
							<tr>
								<td colspan="2">
									
									<table id="ag_add_adminmenu">									
										<tr>
											<td colspan="2">
												name:<input type="text" size="47"  id="ag_add_adminmenu_name" name="ag_add_adminmenu_name" />
												url:<input type="text" size="47" id="ag_add_adminmenu_url" name="ag_add_adminmenu_url" />
												<button type="button" id="ag_add_adminmenu_button" name="ag_add_adminmenu_button">Add new item</button>	
											</td><td></td>	
										</tr>
									</table>
								<input type="hidden" size="47" id="ag_add_adminmenu_json" name="ag_add_adminmenu_json" value="<?php echo htmlspecialchars(get_option('ag_add_adminmenu_json')); ?>" />									
								</td>						
								<td>									
								</td>								
							</tr>
							</table>
						</div>
						
				<br /><br /><br />
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>

			</form>
			</div>
							<p><i><strong>Info:</strong> You can use HTML tags in text areas, e.g. &lt;a href=&quot;http://www.mywebsite.com&quot;&gt;Visit Us&lt;/a&gt;</i></p>
										<br />
			<br /><br /><br /><p id="agca_footer_support_info">Wordpress 'AG Custom Admin' plugin by Argonius. If You have any questions, ideas for future development or if You found a bug or having any issues regarding this plugin, please visit my <a href="http://wordpress.argonius.com/ag-custom-admin">SUPPORT</a> page. <br />You can also participate in development of this plugin if You <a href="http://wordpress.argonius.com/donate">BUY ME a DRINK</a> to refresh my energy for programming. Thanks!<br /><br />Have a nice blogging!</p><br />
		<?php
	}
}
?>