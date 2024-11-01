/**
 * Created by Muhammad on 10/23/2016.
 */
jQuery(document).ready(function(jQuery){
    jQuery('body').on('click','#wpun_noti_Button',function () {
        // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
        jQuery('#wpun-notifications').fadeToggle('fast');
    });
});