<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class wp_user_notification {

    public function __construct() {
        add_action( 'init', array($this,'wpun_notification_post') );
        add_shortcode( 'wpun_notification', array($this,'wpun_notification') );
        add_shortcode( 'wpun_view_notification', array($this,'wpun_view_notification') );
        add_shortcode( 'wpun_ajax_notify', array($this,'wpun_ajax_notify') );
		
		add_action( 'wp_ajax_wpun_notify', array($this,'wpun_notify_callback') );
		add_action( 'wp_ajax_nopriv_wpun_notify', array($this,'wpun_notify_callback') );
    }
	
	function wpun_notify_callback() {
		$request = esc_html($_POST['request']);
		$notify_id = intval($_POST['notify_id']);
		
		if($request == 'update_notify')
			echo do_shortcode('[wpun_view_notification]');
		else if($request == 'read_notify')
			update_post_meta($notify_id,'n_read','1');

		die();
	}

	// View Notifications
    public function wpun_view_notification($atts) { ?> <script type="text/javascript" >

            jQuery(document).ready(function() {

                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                var user_id = "<?php echo get_current_user_id()?>";

                var unread = jQuery('.wpun-notify #wpun-notifications .unread').length;
                if(unread > 0)
                    jQuery('#noti_Counter').html(unread);

                setInterval(function(){
                    var data = {
                        'action': 'wpun_notify',
                        'request': 'update_notify',
                        'user_id' : user_id,
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        var old_html = jQuery('.wpun-notify #wpun-notifications p').length;
                        var new_html = (response.match(/<p/g) || []).length;
                        var unread = jQuery('.wpun-notify #wpun-notifications .unread').length;

                        if(unread > 0)
                            jQuery('#noti_Counter').html(unread);

                        if(old_html != new_html) {
                            jQuery('.wpun-notify').html(response);
                            var unread = jQuery('.wpun-notify #wpun-notifications .unread').length;
                            jQuery('#noti_Counter').html(unread);
                        }

                    });
                },8000);

                jQuery('.wpun-notify .click-notify').on('click',function() {
                    jQuery(this).css('background-color','#FFF');
                    var notify_id = jQuery(this).attr('id');
                    var this_click = jQuery(this);
                    var data = {
                        'action': 'wpun_notify',
                        'request': 'read_notify',
                        'notify_id' : notify_id,
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        // RESPONSE
                    });
                });
            });
        </script> <?php

        $current_user = get_current_user_id();

        // Attributes
        $atts = shortcode_atts(
            array(
                'user_id' => $current_user,
            ),
            $atts
        );

        if(empty($atts['user_id']))
            return "<b style='color:gray'>missing parameter `user_id` in the shortcode</b>";

        $notify_ids = get_user_meta($atts['user_id'],'n_notify_ids',true);

        if(!empty($notify_ids)) {
            $notifications_list = get_posts( array(
                'post_type'      => 'Notifications',
                'posts_per_page' => -1,
                'post__in'       => $notify_ids,
            ) );
        }
        $html = '<div class="wpun-notify">
				<ul>
					<li id="noti_Container">
					<div id="noti_Counter"></div> <!--SHOW NOTIFICATIONS COUNT.-->

					<!--A CIRCLE LIKE BUTTON TO DISPLAY NOTIFICATION DROPDOWN.-->
                <div id="wpun_noti_Button"></div>

                <!--THE NOTIFICAIONS DROPDOWN BOX.-->
                <div id="wpun-notifications">
                    <h3>Notifications</h3>';
        if(!empty($notifications_list)) {
            foreach($notifications_list as $notification) {
                $read = get_post_meta($notification->ID,'n_read',true);
                $link = get_post_meta($notification->ID,'n_link',true);
                $css = ''; $read_notify = 'read';
                if($read == '0') {
                    $css = 'style="background-color:#c4e6ff"';
                    $read_notify = 'unread';
                }
                $html .= '<a href="'.$link.'"><p id="'.$notification->ID.'" class="click-notify '.$read_notify.'" '.$css.' link="'.$link.'">' . $notification->post_title . '</p></a>';
            }
        } else {
            $html .= '<a href=""><p id="" class="click-notify" ></p></a>';
        }
        $html .= '<div class="seeAll"><a href="#">See All</a></div>
					</div>
					</li>
				</ul>
				</div>';

        return $html;

    }

	// Send Notification
    public function wpun_notification($atts) {

        $send_by = get_current_user_id();

        // Attributes
        $atts = shortcode_atts(
            array(
                'title' => 'New Notification',
                'user_id' => '0',
                'message' => 'New Message',
                'type' => 'offer',
                'read' => '0',
                'link' => '#',
                'role' => '',
            ),
            $atts
        );

		// SEND TO ALL USERS
		if($atts['role'] == 'all') {
			$args = array(
				'role'	=> '',
			);			
			$users_list = get_users( $args );
			foreach($users_list as $users) {
				$this->wpun_send_notification($users->ID,$atts['title'],$atts['message'],$atts['type'],$atts['read'],$atts['link'],$send_by);
			}
			return;
		}
		
		// SEND NOTIFICATION BY USER ROLE
		if(!empty($atts['role'])) {
			$args = array(
				'role'	=> $atts['role'],
			);			
			$users_list = get_users( $args );
			
			foreach($users_list as $users) {			
				$this->wpun_send_notification($users->ID,$atts['title'],$atts['message'],$atts['type'],$atts['read'],$atts['link'],$send_by);
			}
			return;
		}
		
		// NOTIFICATION & USER ROLE EMPTY
        if(empty($atts['user_id']) && empty($atts['role']))
			return "<b style='color:gray'>missing parameter `user_id` or `role` in the shortcode</b>";		

		// SEND MULTIPLE NOTIFICATION BY USER ID
		if(strpos($atts['user_id'],',')) {
			$multi_users = explode(',',$atts['user_id']);
			foreach($multi_users as $user) {
				$this->wpun_send_notification($user,$atts['title'],$atts['message'],$atts['type'],$atts['read'],$atts['link'],$send_by);				
			}
			return;
		}
		
		// SEND NOTIFICATION TO SINGLE USER
		if(!empty($atts['user_id']))
			$this->wpun_send_notification($atts['user_id'],$atts['title'],$atts['message'],$atts['type'],$atts['read'],$atts['link'],$send_by);
    }
	
	// SEND NOTIFICATION FUNCTION
	public function wpun_send_notification($receiver_user_id, $title, $message, $type,$read,$link,$sender_id) {
		// Create post object
        $my_post = array(
            'post_title'    => wp_strip_all_tags( $title ),
            'post_content'  => $message,
            'post_status'   => 'publish',
            'post_author'   => $sender_id,
            'post_type'     => 'Notifications',
        );

        // Insert the post into the database
        $notification_id = wp_insert_post( $my_post );

        update_post_meta($notification_id,'n_type',$type);
        update_post_meta($notification_id,'n_read',$read);
        update_post_meta($notification_id,'n_link',$link);

        $status = $this->wpun_update_user_notification($receiver_user_id,$notification_id);

        return $status;
	}

	// ADD NEW NOTIFICATION
    public function wpun_update_user_notification($user_id, $notify_id) {
        $notify_ids = array();
        $notify_ids = get_user_meta($user_id, 'n_notify_ids', true);
        $notify_ids[] = $notify_id;

        update_user_meta($user_id,'n_notify_ids',$notify_ids);
    }

	// NOTIFICAION POST TYPE
    public function wpun_notification_post() {
        register_post_type( 'Notifications',
            array(
                'labels' => array(
                    'name' => __( 'Notifications' ),
                    'singular_name' => __( 'Notifications' )
                ),
                'public' => false,
                'has_archive' => true,
            )
        );
    }
}