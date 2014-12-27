<?php

class AIOWPSecurity_Dashboard_Menu extends AIOWPSecurity_Admin_Menu
{
    var $dashboard_menu_page_slug = AIOWPSEC_MAIN_MENU_SLUG;
    
     var $menu_tabs;

    var $menu_tabs_handler = array(
        'tab1' => 'render_tab1', 
        'tab2' => 'render_tab2',
        'tab3' => 'render_tab3',
        );
    
    function __construct() 
    {
        $this->render_menu_page();
    }

    function set_menu_tabs() 
    {
        $this->menu_tabs = array(
        'tab1' => __('Dashboard','aiowpsecurity'), 
        'tab2' => __('System Info','aiowpsecurity'),
        'tab3' => __('Locked IP Addresses','aiowpsecurity'),
        );
    }

    function get_current_tab() 
    {
        $tab_keys = array_keys($this->menu_tabs);
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $tab_keys[0];
        return $tab;
    }

    /*
     * Renders our tabs of this menu as nav items
     */
    function render_menu_tabs() 
    {
        $current_tab = $this->get_current_tab();

        echo '<h2 class="nav-tab-wrapper">';
        foreach ( $this->menu_tabs as $tab_key => $tab_caption ) 
        {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->dashboard_menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
        }
        echo '</h2>';
    }
    
    /*
     * The menu rendering goes here
     */
    function render_menu_page() 
    {
        $this->set_menu_tabs();
        $tab = $this->get_current_tab();
        ?>
        <div class="wrap">
        <div id="poststuff"><div id="post-body">
        <?php 
        $this->render_menu_tabs();
        //$tab_keys = array_keys($this->menu_tabs);
        call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
        ?>
        </div></div>
        </div><!-- end of wrap -->
        <?php
    }
    
    function render_tab1()
    {
        echo '<div class="aio_grey_box">';
 	echo '<p>'.__('For information, updates and documentation, please visit the','aiowpsecurity').' <a href="https://www.tipsandtricks-hq.com/wordpress-security-and-firewall-plugin" target="_blank">'.__('AIO WP Security & Firewall Plugin','aiowpsecurity').'</a> '.__('Page','aiowpsecurity').'</p>';
        echo '<p><a href="https://www.tipsandtricks-hq.com/development-center" target="_blank">'.__('Follow us','aiowpsecurity').'</a> on '.__('Twitter, Google+ or via Email to stay up to date about the new security features of this plugin.','aiowpsecurity').'</p>';
        echo '</div>';

        echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";//Include the google chart library
        
        global $aiowps_feature_mgr;
        global $aio_wp_security;
        $feature_mgr = $aiowps_feature_mgr;
        $total_site_security_points = $feature_mgr->get_total_site_points();
        $total_security_points_achievable = $feature_mgr->get_total_achievable_points();
        
        ?>
        <div id="aiowps_dashboard_widget_content">
            
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Security Strength Meter', 'aiowpsecurity');?></label></h3>
        <div class="inside">

        <script type='text/javascript'>
          google.load('visualization', '1', {packages:['gauge']});
          google.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Label', 'Value'],
              ['Strength', <?php echo $total_site_security_points; ?>]
            ]);

            var options = {
              width: 320, height: 200, max: <?php echo $total_security_points_achievable; ?>,
              greenColor: '8EFA9B', yellowColor: 'F5EE90', redColor: 'FA7373',
              redFrom: 0, redTo: 10,
              yellowFrom:10, yellowTo: 50,
              greenFrom:50, greenTo: <?php echo $total_security_points_achievable; ?>,
              minorTicks: 5
            };

            var chart = new google.visualization.Gauge(document.getElementById('security_strength_chart_div'));
            chart.draw(data, options);
          }
        </script>
        <div id='security_strength_chart_div'></div>

        <div class="aiowps_dashboard_widget_footer">
        <?php 
        _e('Total Achievable Points: ','aiowpsecurity');
        echo '<strong>'.$total_security_points_achievable.'</strong><br />'; 
        _e('Current Score of Your Site: ','aiowpsecurity');
        echo '<strong>'.$total_site_security_points.'</strong>';
        ?>
        </div>
        
        </div></div>
        </div><!-- aiowps_dashboard_box -->
        
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Security Points Breakdown', 'aiowpsecurity');?></label></h3>
        <div class="inside">
        
        <?php
        $feature_items = $feature_mgr->feature_items;
        $pt_src_chart_data = "";
        $pt_src_chart_data .= "['Feature Name', 'Points'],";
        foreach($feature_items as $item)
        {
            if($item->feature_status == $feature_mgr->feature_active)
            {
                $pt_src_chart_data .= "['".$item->feature_name."', ".$item->item_points."],";
            }
        }
        
        ?>
        <script type="text/javascript">
              google.load("visualization", "1", {packages:["corechart"]});
              google.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                  <?php echo $pt_src_chart_data; ?>
                ]);

                var options = {
                  height: '250',
                  width: '320',
                  backgroundColor: 'F6F6F6'
                };

                var chart = new google.visualization.PieChart(document.getElementById('points_source_breakdown_chart_div'));
                chart.draw(data, options);
              }
        </script>
        <div id='points_source_breakdown_chart_div'></div>

        </div></div>
        </div><!-- aiowps_dashboard_box -->
        
        <div class="aiowps_dashboard_box_small aiowps_spread_the_word_widget">
        <div class="postbox">
        <h3><label for="title"><?php _e('Spread the Word', 'aiowpsecurity');?></label></h3>
        <div class="inside">
        
        <p><?php _e('We are working hard to make your WordPress site more secure. Please support us, here is how:', 'aiowpsecurity');?></p>
        <p>
            <a href="https://plus.google.com/+Tipsandtricks-hq/" target="_blank">Follow us on Google+</a>
        </p>
        <p>
            <a href="http://twitter.com/intent/tweet?url=https://www.tipsandtricks-hq.com/wordpress-security-and-firewall-plugin&text=I love the All In One WP Security and Firewall plugin!" target="_blank" class="aio_tweet_link">Post to Twitter</a>
        </p>
        <p>
            <a href="http://wordpress.org/support/view/plugin-reviews/all-in-one-wp-security-and-firewall/" target="_blank" class="aio_rate_us_link">Give us a Good Rating</a>
        </p>
        
        </div></div>
        </div><!-- aiowps_dashboard_box -->   
        
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Critical Feature Status', 'aiowpsecurity');?></label></h3>
        <div class="inside">

        <?php 
        _e('Below is the current status of the critical features that you should activate on your site to achieve a minimum level of recommended security','aiowpsecurity'); 
        $feature_items = $feature_mgr->feature_items;
        $username_admin_feature = $feature_mgr->get_feature_item_by_id("user-accounts-change-admin-user");
        echo '<div class="aiowps_feature_status_container">';
        echo '<div class="aiowps_feature_status_name">'.__('Admin Username','aiowpsecurity').'</div>';
        echo '<a href="admin.php?page='.AIOWPSEC_USER_ACCOUNTS_MENU_SLUG.'">';
        echo '<div class="aiowps_feature_status_bar">';
        if($username_admin_feature->feature_status == $feature_mgr->feature_active){
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_on">On</div>';
            echo '<div class="aiowps_feature_status_label">Off</div>';
        }else{
            echo '<div class="aiowps_feature_status_label">On</div>';
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_off">Off</div>';
        }
        echo '</div></div></a>';
        echo '<div class="aio_clear_float"></div>';
        
        $login_lockdown_feature = $feature_mgr->get_feature_item_by_id("user-login-login-lockdown");
        echo '<div class="aiowps_feature_status_container">';
        echo '<div class="aiowps_feature_status_name">'.__('Login Lockdown','aiowpsecurity').'</div>';
        echo '<a href="admin.php?page='.AIOWPSEC_USER_LOGIN_MENU_SLUG.'">';
        echo '<div class="aiowps_feature_status_bar">';
        if($login_lockdown_feature->feature_status == $feature_mgr->feature_active){
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_on">On</div>';
            echo '<div class="aiowps_feature_status_label">Off</div>';
        }else{
            echo '<div class="aiowps_feature_status_label">On</div>';
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_off">Off</div>';
        }
        echo '</div></div></a>';
        echo '<div class="aio_clear_float"></div>';
            
        $filesystem_feature = $feature_mgr->get_feature_item_by_id("filesystem-file-permissions");
        echo '<div class="aiowps_feature_status_container">';
        echo '<div class="aiowps_feature_status_name">'.__('File Permission','aiowpsecurity').'</div>';
        echo '<a href="admin.php?page='.AIOWPSEC_FILESYSTEM_MENU_SLUG.'">';
        echo '<div class="aiowps_feature_status_bar">';
        if($filesystem_feature->feature_status == $feature_mgr->feature_active){
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_on">On</div>';
            echo '<div class="aiowps_feature_status_label">Off</div>';
        }else{
            echo '<div class="aiowps_feature_status_label">On</div>';
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_off">Off</div>';
        }
        echo '</div></div></a>';
        echo '<div class="aio_clear_float"></div>';
        
        $basic_firewall_feature = $feature_mgr->get_feature_item_by_id("firewall-basic-rules");
        echo '<div class="aiowps_feature_status_container">';
        echo '<div class="aiowps_feature_status_name">'.__('Basic Firewall','aiowpsecurity').'</div>';
        echo '<a href="admin.php?page='.AIOWPSEC_FIREWALL_MENU_SLUG.'">';
        echo '<div class="aiowps_feature_status_bar">';
        if($basic_firewall_feature->feature_status == $feature_mgr->feature_active){
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_on">On</div>';
            echo '<div class="aiowps_feature_status_label">Off</div>';
        }else{
            echo '<div class="aiowps_feature_status_label">On</div>';
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_off">Off</div>';
        }
        echo '</div></div></a>';
        echo '<div class="aio_clear_float"></div>';        
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->        

        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Last 5 Logins', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php 
    	global $wpdb;
        $login_activity_table = AIOWPSEC_TBL_USER_LOGIN_ACTIVITY;

	/* -- Ordering parameters -- */
	//Parameters that are going to be used to order the result
        isset($_GET["orderby"]) ? $orderby = strip_tags($_GET["orderby"]): $orderby = '';
        isset($_GET["order"]) ? $order = strip_tags($_GET["order"]): $order = '';

	$orderby = !empty($orderby) ? $orderby : 'login_date';
	$order = !empty($order) ? $order : 'DESC';

	$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $login_activity_table ORDER BY %s %s LIMIT %d", $orderby, $order, 5), ARRAY_A); //Get the last 5 records
        
        if ($data == NULL){
            echo '<p>'.__('No data found!','aiowpsecurity').'</p>';
            
        }else{
            $login_summary_table = '';
            echo '<p>'.__('Last 5 logins summary:','aiowpsecurity').'</p>';
            $login_summary_table .= '<table class="widefat">';
            $login_summary_table .= '<thead>';
            $login_summary_table .= '<tr>';
            $login_summary_table .= '<th>'.__('User','aiowpsecurity').'</th>';
            $login_summary_table .= '<th>'.__('Date','aiowpsecurity').'</th>';
            $login_summary_table .= '<th>'.__('IP','aiowpsecurity').'</th>';
            $login_summary_table .= '</tr>';
            $login_summary_table .= '</thead>';
            foreach ($data as $entry) {
                $login_summary_table .= '<tr>';
                $login_summary_table .= '<td>'.$entry['user_login'].'</td>';
                $login_summary_table .= '<td>'.$entry['login_date'].'</td>';
                $login_summary_table .= '<td>'.$entry['login_ip'].'</td>';
                $login_summary_table .= '</tr>';
            }
            $login_summary_table .= '</table>';
            echo $login_summary_table;
        }

        echo '<div class="aio_clear_float"></div>';       
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->
        
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Maintenance Mode Status', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php 
        if($aio_wp_security->configs->get_value('aiowps_site_lockout') == '1'){
            echo '<p>'.__('Maintenance mode is currently enabled. Remember to turn it off when you are done','aiowpsecurity').'</p>';
        }
        else{
            echo '<p>'.__('Maintenance mode is currently off.','aiowpsecurity').'</p>';
        }
        
        echo '<div class="aiowps_feature_status_container">';
        echo '<div class="aiowps_feature_status_name">'.__('Maintenance Mode','aiowpsecurity').'</div>';
        echo '<a href="admin.php?page='.AIOWPSEC_MAINTENANCE_MENU_SLUG.'">';
        echo '<div class="aiowps_feature_status_bar">';
        if($aio_wp_security->configs->get_value('aiowps_site_lockout') == '1'){//Maintenance mode is enabled
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_off">On</div>';//If enabled show red by usign the "off" class
            echo '<div class="aiowps_feature_status_label">Off</div>';
        }else{
            echo '<div class="aiowps_feature_status_label">On</div>';
            echo '<div class="aiowps_feature_status_label aiowps_feature_status_on">Off</div>';
        }
        echo '</div></div></a>';
        echo '<div class="aio_clear_float"></div>';       
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->

        <?php
        //Insert Cookie Based Brute Force feature box if this feature is active
        if($aio_wp_security->configs->get_value('aiowps_enable_brute_force_attack_prevention')=='1'){
        ?>
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Cookie Based Brute Prevention', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php 
        $brute_force_login_feature_link = '<a href="admin.php?page='.AIOWPSEC_BRUTE_FORCE_MENU_SLUG.'&tab=tab2" target="_blank">'.__('Cookie-Based Brute Force','aiowpsecurity').'</a>';
        $brute_force_feature_secret_word = $aio_wp_security->configs->get_value('aiowps_brute_force_secret_word');
        echo '<div class="aio_yellow_box">';

        echo '<p>'.sprintf( __('The %s feature is currently active.', 'aiowpsecurity'), $brute_force_login_feature_link).'</p>';
        echo '<p>'.__('Your new WordPress login URL is now:','aiowpsecurity').'</p>';
        echo '<p><strong>'.AIOWPSEC_WP_URL.'/?'.$brute_force_feature_secret_word.'=1</strong></p>';
        echo '</div>'; //yellow box div
        echo '<div class="aio_clear_float"></div>';       
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->
        <?php
        }//End if statement for Cookie Based Brute Prevention box
        
        //Insert Rename Login Page feature box if this feature is active
        if($aio_wp_security->configs->get_value('aiowps_enable_rename_login_page')=='1'){
        ?>
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Rename Login Page', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php 
        if (get_option('permalink_structure')){
            $home_url = trailingslashit(home_url());
        }else{
            $home_url = trailingslashit(home_url()) . '?';
        }
        
        $rename_login_feature_link = '<a href="admin.php?page='.AIOWPSEC_BRUTE_FORCE_MENU_SLUG.'&tab=tab1" target="_blank">'.__('Rename Login Page','aiowpsecurity').'</a>';
        echo '<div class="aio_yellow_box">';

        echo '<p>'.sprintf( __('The %s feature is currently active.', 'aiowpsecurity'), $rename_login_feature_link).'</p>';
        echo '<p>'.__('Your new WordPress login URL is now:','aiowpsecurity').'</p>';
        echo '<p><strong>'.$home_url.$aio_wp_security->configs->get_value('aiowps_login_page_slug').'</strong></p>';
        echo '</div>'; //yellow box div
        echo '<div class="aio_clear_float"></div>';       
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->
        <?php
        }//End if statement for Rename Login box

        if($aio_wp_security->configs->get_value('aiowps_enable_automated_fcd_scan') == '1'){
      
        echo '<div class="aiowps_dashboard_box_small">';
        echo '<div class="postbox">';
        echo '<h3><label for="title">File Change Detection</label></h3>';
        echo '<div class="inside">';
        
        if($aio_wp_security->configs->get_value('aiowps_fcds_change_detected')){
            echo '<div class="aio_red_box aio_padding_10">File change detected!</div>';
            echo '<p>Please review the changes from the <a href="admin.php?page='.AIOWPSEC_FILESCAN_MENU_SLUG.'">scanner menu</a></p>';
        }
        else{
            echo '<div class="aio_green_box aio_padding_10">No recent file changes detected.</div>';
        }
        
        echo '</div></div>';
        echo '</div>';//<!-- aiowps_dashboard_box -->     
        }//End if statement for automated scan box
        ?>
        
        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Logged In Users', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php
        $users_online_link = '<a href="admin.php?page='.AIOWPSEC_USER_LOGIN_MENU_SLUG.'&tab=tab5">Logged In Users</a>';
        if (AIOWPSecurity_Utility::is_multisite_install())
        {
            $logged_in_users = get_site_transient('users_online');
            $num_users = count($logged_in_users);
            if($num_users > 1)
            {
                echo '<div class="aio_red_box"><p>'.__('Number of users currently logged in site-wide is:','aiowpsecurity').' <strong>'.$num_users.'</strong></p>';
                $info_msg = '<p>'.sprintf( __('Go to the %s menu to see more details', 'aiowpsecurity'), $users_online_link).'</p>';
                echo $info_msg.'</div>';
            }
            else
            {
                echo '<div class="aio_green_box"><p>'.__('There are no other site-wide users currently logged in.','aiowpsecurity').'</p></div>';
            }
        }
        else
        {
            $logged_in_users = get_transient('users_online');
            if ($logged_in_users === false || $logged_in_users == NULL)
            {
                $num_users = 0;
            }
            else
            {
                $num_users = count($logged_in_users);
            }
            if($num_users > 1)
            {
                echo '<div class="aio_red_box"><p>'.__('Number of users currently logged into your site (including you) is:','aiowpsecurity').' <strong>'.$num_users.'</strong></p>';
                $info_msg = '<p>'.sprintf( __('Go to the %s menu to see more details', 'aiowpsecurity'), $users_online_link).'</p>';
                echo $info_msg.'</div>';
            }
            else
            {
                echo '<div class="aio_green_box"><p>'.__('There are no other users currently logged in.','aiowpsecurity').'</p></div>';
            }
        }
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->

        <div class="aiowps_dashboard_box_small">
        <div class="postbox">
        <h3><label for="title"><?php _e('Locked IP Addresses', 'aiowpsecurity');?></label></h3>
        <div class="inside">        
        <?php
        $locked_ips_link = '<a href="admin.php?page='.AIOWPSEC_MAIN_MENU_SLUG.'&tab=tab3">Locked IP Addresses</a>';
        
        $locked_ips = AIOWPSecurity_Utility::get_locked_ips();
        if($locked_ips === FALSE)
        {
            echo '<div class="aio_green_box"><p>'.__('There are no IP addresses currently locked out.','aiowpsecurity').'</p></div>';
        }
        else
        {
            $num_ips = count($locked_ips);
            echo '<div class="aio_red_box"><p>'.__('Number of temporarily locked out IP addresses: ','aiowpsecurity').' <strong>'.$num_ips.'</strong></p>';
            $info_msg = '<p>'.sprintf( __('Go to the %s menu to see more details', 'aiowpsecurity'), $locked_ips_link).'</p>';
            echo $info_msg.'</div>';
        }
        
        ?>
        </div></div>
        </div><!-- aiowps_dashboard_box -->        

        <div class="aio_clear_float"></div>
        
        </div>
<!-- Masonry stuff -->
<?php
//wp_enqueue_script('masonry');
echo '<script type="text/javascript" src="'.AIO_WP_SECURITY_URL.'/js/masonry.pkgd.min.js?ver='.AIO_WP_SECURITY_VERSION.'"></script>';
?>
<style>
.aiowps_dashboard_box_small { 
    width: 350px;
}
</style>
<script type="text/javascript">
window.onload = function(){
var container = document.querySelector('#aiowps_dashboard_widget_content');
var msnry = new Masonry( container, {
  // options
  columnWidth: 100,
  itemSelector: '.aiowps_dashboard_box_small'
});
}
</script>
<!-- End Masonry stuff -->
        
        <?php
    }
   
    
    function render_tab2()
    {
        global $wpdb;
        ?>
        <div class="postbox">
        <h3><label for="title"><?php _e('Site Info', 'aiowpsecurity');?></label></h3>
        <div class="inside">
	<strong><?php _e('Plugin Version', 'aiowpsecurity');?>: </strong><code><?php echo AIO_WP_SECURITY_VERSION;?></code><br />
	<strong><?php _e('WP Version', 'aiowpsecurity');?>: </strong><code><?php echo get_bloginfo("version"); ?></code><br />
	<strong>WPMU: </strong><code><?php echo (!defined('MULTISITE') || !MULTISITE) ? "No" : "Yes";  ?></code><br />
	<strong>MySQL <?php _e('Version', 'aiowpsecurity');?>: </strong><code><?php echo $wpdb->db_version();?></code><br />
	<strong>WP <?php _e('Table Prefix', 'aiowpsecurity');?>: </strong><code><?php echo $wpdb->prefix; ?></code><br />
	<strong>PHP <?php _e('Version', 'aiowpsecurity');?>: </strong><code><?php echo phpversion(); ?></code><br />
	<strong><?php _e('Session Save Path', 'aiowpsecurity');?>: </strong><code><?php echo ini_get("session.save_path"); ?></code><br />
	<strong>WP URL: </strong><code><?php echo get_bloginfo('wpurl'); ?></code><br />
	<strong><?php _e('Server Name', 'aiowpsecurity');?>: </strong><code><?php echo $_SERVER['SERVER_NAME']; ?></code><br />
	<strong><?php _e('Cookie Domain', 'aiowpsecurity');?>: </strong><code><?php $cookieDomain = parse_url( strtolower( get_bloginfo('wpurl') ) ); echo $cookieDomain['host']; ?></code><br />
	<strong>CURL <?php _e('Library Present', 'aiowpsecurity');?>: </strong><code><?php echo (function_exists('curl_init')) ? "Yes" : "No"; ?></code><br />
	<strong><?php _e('Debug File Write Permissions', 'aiowpsecurity');?>: </strong><code><?php echo (is_writable(AIO_WP_SECURITY_PATH)) ? "Writable" : "Not Writable"; ?></code><br />
        </div></div><!-- End of Site Info -->
        
        <div class="postbox">
        <h3><label for="title"><?php _e('PHP Info', 'aiowpsecurity');?></label></h3>
        <div class="inside">
        <strong><?php _e('PHP Version', 'aiowpsecurity'); ?>: </strong><code><?php echo PHP_VERSION; ?></code><br />
        <strong><?php _e('PHP Memory Usage', 'aiowpsecurity'); ?>:
        </strong><code><?php echo round(memory_get_usage() / 1024 / 1024, 2) . __(' MB', 'aiowpsecurity'); ?></code>
        <br />
        <?php
        if (ini_get('memory_limit')) {
            $memory_limit = filter_var(ini_get('memory_limit'), FILTER_SANITIZE_STRING);
        } else {
            $memory_limit = __('N/A', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Memory Limit', 'aiowpsecurity'); ?>: </strong><code><?php echo $memory_limit; ?></code><br />
        <?php
        if (ini_get('upload_max_filesize')) {
            $upload_max = filter_var(ini_get('upload_max_filesize'), FILTER_SANITIZE_STRING);
        } else {
            $upload_max = __('N/A', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Max Upload Size', 'aiowpsecurity'); ?>: </strong><code><?php echo $upload_max; ?></code><br />
        <?php
        if (ini_get('post_max_size')) {
            $post_max = filter_var(ini_get('post_max_size'), FILTER_SANITIZE_STRING);
        } else {
            $post_max = __('N/A', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Max Post Size', 'aiowpsecurity'); ?>: </strong><code><?php echo $post_max; ?></code><br />
        <?php
        if (ini_get('safe_mode')) {
            $safe_mode = __('On', 'aiowpsecurity');
        } else {
            $safe_mode = __('Off', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Safe Mode', 'aiowpsecurity'); ?>: </strong><code><?php echo $safe_mode; ?></code><br />
        <?php
        if (ini_get('allow_url_fopen')) {
            $allow_url_fopen = __('On', 'aiowpsecurity');
        } else {
            $allow_url_fopen = __('Off', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Allow URL fopen', 'aiowpsecurity'); ?>: </strong><code><?php echo $allow_url_fopen; ?></code>
        <br />
        <?php
        if (ini_get('allow_url_include')) {
            $allow_url_include = __('On', 'aiowpsecurity');
        } else {
            $allow_url_include = __('Off', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Allow URL Include'); ?>: </strong><code><?php echo $allow_url_include; ?></code><br />
        <?php
        if (ini_get('display_errors')) {
            $display_errors = __('On', 'aiowpsecurity');
        } else {
            $display_errors = __('Off', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Display Errors', 'aiowpsecurity'); ?>: </strong><code><?php echo $display_errors; ?></code>
        <br />
        <?php
        if (ini_get('max_execution_time')) {
            $max_execute = filter_var(ini_get('max_execution_time'));
        } else {
            $max_execute = __('N/A', 'aiowpsecurity');
        }
        ?>
        <strong><?php _e('PHP Max Script Execution Time', 'aiowpsecurity'); ?>: </strong><code><?php echo $max_execute; ?> <?php _e('Seconds'); ?></code><br />  
        </div></div><!-- End of PHP Info -->
        
        <div class="postbox">
        <h3><label for="title"><?php _e('Active Plugins', 'aiowpsecurity');?></label></h3>
        <div class="inside">
        <?php
        $all_plugins = get_plugins();
        $active_plugins = get_option('active_plugins');
        //var_dump($all_plugins);
        ?>
        <table class="widefat aio_spacer_10_tb">
        <thead>
            <tr>
                <th><?php _e('Name', 'aiowpsecurity') ?></th>
                <th><?php _e('Version', 'aiowpsecurity') ?></th>
                <th><?php _e('Plugin URL', 'aiowpsecurity') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($active_plugins as $plugin_key)
        {
            $plugin_details = $all_plugins[$plugin_key];
            echo '<tr><td>'.$plugin_details['Name'].'</td><td>'.$plugin_details['Version'].'</td><td>'.$plugin_details['PluginURI'].'</td></tr>';
        }
        ?>
        </tbody>
        </table>
        </div></div><!-- End of Active Plugins -->
        <?php
    }

    function render_tab3()
    {
        global $wpdb;
        include_once 'wp-security-list-locked-ip.php'; //For rendering the AIOWPSecurity_List_Table in tab1
        $locked_ip_list = new AIOWPSecurity_List_Locked_IP(); //For rendering the AIOWPSecurity_List_Table in tab1

        if(isset($_REQUEST['action'])) //Do list table form row action tasks
        {
            if($_REQUEST['action'] == 'delete_blocked_ip'){ //Delete link was clicked for a row in list table
                $locked_ip_list->delete_lockdown_records(strip_tags($_REQUEST['lockdown_id']));
            }
            
            if($_REQUEST['action'] == 'unlock_ip'){ //Unlock link was clicked for a row in list table
                $locked_ip_list->unlock_ip_range(strip_tags($_REQUEST['lockdown_id']));
            }
        }
        
        ?>
        <div class="postbox">
        <h3><label for="title"><?php _e('Currently Locked Out IP Addresses and Ranges', 'aiowpsecurity');?></label></h3>
        <div class="inside">
            <?php 
            //Fetch, prepare, sort, and filter our data...
            $locked_ip_list->prepare_items();
            //echo "put table of locked entries here"; 
            ?>
            <form id="tables-filter" method="get" onSubmit="return confirm('Are you sure you want to perform this bulk operation on the selected entries?');">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <?php
            if(isset($_REQUEST["tab"]))
            {
                echo '<input type="hidden" name="tab" value="'.$_REQUEST["tab"].'" />';
            }
            ?>
            <!-- Now we can render the completed list table -->
            <?php $locked_ip_list->display(); ?>
            </form>
        </div></div>
        
        <?php
    }
    
} //end class