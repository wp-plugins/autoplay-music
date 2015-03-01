<?php
/*
Plugin Name: Autoplay music with cookie
Plugin URI: http://wpexpand.com/autoplay-music-with-cookie
Description: This plugin adds a music autoplay while loads your website. The settings can be confugraded via its <a href="options-general.php?page=autoplay-music-options">option panel</a>.
Author: WP Expand
Author URI: http://wpexpand.com/
Version: 1.0
*/


/* Adding Latest jQuery from Wordpress */
function wp_expand_autoplay_music_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'wp_expand_autoplay_music_jquery');


function wp_expand_autoplay_music_all_files(){
    wp_enqueue_script( 'we-autoplay-music-cookie-js', plugins_url( '/js/jquery.cookie.js', __FILE__ ), array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'wp_expand_autoplay_music_all_files' );



/* Plugin option panel */

function add_wexpand_automusic_options_framwrork()  
{  
	add_options_page('Autoplay Music Settings', 'Autoplay Music Settings', 'manage_options', 'autoplay-music-options','wexpand_automusic_options_framwrork');  
}  
add_action('admin_menu', 'add_wexpand_automusic_options_framwrork');


// Default options values
$weautomusic_options = array(
	'time_type' => 'hours',
	'homepage_only' => 'all_page',	
	'automusic_time' => '1',	
	'mp3_music_url' => '',
	'ogg_music_url' => '',
	'wav_music_url' => ''
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function weautomusic_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'weautomusic_p_options', 'weautomusic_options', 'weautomusic_validate_options' );
}

add_action( 'admin_init', 'weautomusic_register_settings' );


// Store gallery type views in array
$time_type = array(
	'minutes' => array(
		'value' => 'minutes',
		'label' => 'Minutes'
	),
	'hours' => array(
		'value' => 'hours',
		'label' => 'Hours'
	),
	'days' => array(
		'value' => 'days',
		'label' => 'Days'
	)
);


// Store gallery type views in array
$homepage_only = array(
	'home_only_yes' => array(
		'value' => 'home_only',
		'label' => 'Homepage only'
	),
	'all_page' => array(
		'value' => 'all_page',
		'label' => 'All pages'
	),
);




// Function to generate options page
function wexpand_automusic_options_framwrork() {
	global $weautomusic_options, $time_type, $homepage_only;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>Autoplay Music Settings</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'weautomusic_options', $weautomusic_options ); ?>
	
	<?php settings_fields( 'weautomusic_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>
	
	<div class="warp">

<style>

.time-is-money {
    padding: 20px 30px;
    position: relative;
}
.time-is-money::before {
    background: none repeat scroll 0 0 #fff;
    content: "";
    height: 100%;
    left: 0;
    opacity: 0.6;
    position: absolute;
    top: 0;
    width: 100%;
}
.mone-needs-money {
    background: none repeat scroll 0 0 #0074a2;
    color: #fff;
    padding: 10px 50px;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
}
.mone-needs-money > h2 {
    color: #fff;
    font-weight: 300;
    margin: 0;
}
.mone-needs-money > a {
    background: none repeat scroll 0 0 #000;
    color: #fff;
    display: inline-block;
    margin-bottom: 10px;
    margin-top: 5px;
    padding: 10px 20px;
    position: relative;
    text-decoration: none;
}
        
</style>
				<div class="settings_field">
					<table class="form-table">
					    
					    
					    
						<tr valign="top">
							<th scope="row">
								Homepage Only
							</th>
							<td>
								<?php foreach( $homepage_only as $layout ) : ?>
								<input type="radio" id="type-<?php echo $layout['value']; ?>" name="weautomusic_options[homepage_only]" value="<?php esc_attr_e( $layout['value'] ); ?>" <?php checked( $settings['homepage_only'], $layout['value'] ); ?> />
								<label for="type-<?php echo $layout['value']; ?>"><?php echo $layout['label']; ?></label><br />
								<?php endforeach; ?>
								<p class="description">If you select <strong>Homepage only</strong>, The music will play only on homepage. If you select <strong>All pages</strong>, music will play in all page. With settings below:</p>
							</td>
						</tr>						
					
					</table>
				
					
					    <div class="time-is-money">
					        
				    <table class="form-table">
						<tr valign="top">
							<th scope="row">
								Time unit
							</th>
							<td>
								<?php foreach( $time_type as $layout ) : ?>
								<input type="radio" id="type-<?php echo $layout['value']; ?>" name="weautomusic_options[time_type]" value="<?php esc_attr_e( $layout['value'] ); ?>" <?php checked( $settings['time_type'], $layout['value'] ); ?> />
								<label for="type-<?php echo $layout['value']; ?>"><?php echo $layout['label']; ?></label><br />
								<?php endforeach; ?>
								<p class="description">Select time unit &amp; type value below:</p>
							</td>
						</tr>	
						
						
						<tr valign="top">
							<th scope="row">
								<label for="automusic_time">Time value</label>
							</th>
							<td>
								<input id="automusic_time" name="weautomusic_options[automusic_time]" value="<?php echo stripslashes($settings['automusic_time']); ?>" type="text" />
								<p class="description">Type time value here. If you type <strong>1</strong> &amp; selected hour above, your music will play once an hour.</p>
							</td>
						</tr>	
                    </table>	
                        <div class="mone-needs-money">
                            <h2>Unlock those features only for $2</h2>
                            <a target="_blank" href="http://wpexpand.com/plugins/premium-plugins/autoplay-music/">Purchase premium version</a>
                        </div>						
						
						</div>	

					<table class="form-table">	
						<tr valign="top">
							<th scope="row">
								<label for="mp3_music_url">MP3 music URL</label>
							</th>
							<td>
								<input size="50" id="mp3_music_url" name="weautomusic_options[mp3_music_url]" value="<?php echo stripslashes($settings['mp3_music_url']); ?>" type="text" />
								<p class="description">Enter music's mp3 format URL here.</p>
							</td>
						</tr>	
						
	
						
						<tr valign="top">
							<th scope="row">
								<label for="ogg_music_url">OGG music URL</label>
							</th>
							<td>
								<input size="50" id="ogg_music_url" name="weautomusic_options[ogg_music_url]" value="<?php echo stripslashes($settings['ogg_music_url']); ?>" type="text" />
								<p class="description">Enter music's ogg format URL here.</p>
							</td>
						</tr>	
						
	
						
						<tr valign="top">
							<th scope="row">
								<label for="wav_music_url">WAV music URL</label>
							</th>
							<td>
								<input size="50" id="wav_music_url" name="weautomusic_options[wav_music_url]" value="<?php echo stripslashes($settings['wav_music_url']); ?>" type="text" />
								<p class="description">Enter music's wav format URL here.</p>
							</td>
						</tr>	
						
	
						
					
					</table>
				</div>
			
				
				<div class="submit_area">
					<input class="button button-primary" type="submit" value="Save Options" />
				</div>	
	</div>
	</form>
	</div>
	<?php
}

function weautomusic_validate_options( $input ) {
	global $weautomusic_options, $time_type, $homepage_only;

	$settings = get_option( 'weautomusic_options', $weautomusic_options );
	
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['automusic_time'] = wp_filter_post_kses( $input['automusic_time'] ); 	
	$input['mp3_music_url'] = wp_filter_post_kses( $input['mp3_music_url'] );	
	$input['ogg_music_url'] = wp_filter_post_kses( $input['ogg_music_url'] );	
	$input['wav_music_url'] = wp_filter_post_kses( $input['wav_music_url'] );	

	
	
	return $input;
}

endif;  // EndIf is_admin()


function append_music_to_body(){?>
<?php   
    global $weautomusic_options;
    $weautomusic_settings = get_option( 'weautomusic_options', $weautomusic_options );
    $mp3_music_url = $weautomusic_settings['mp3_music_url'];    
    $ogg_music_url = $weautomusic_settings['ogg_music_url'];    
    $wav_music_url = $weautomusic_settings['wav_music_url'];    
    $automusic_time = $weautomusic_settings['automusic_time'];    
    $time_type = $weautomusic_settings['time_type'];    
    $homepage_only = $weautomusic_settings['homepage_only'];    
?> 
    
<script type="text/javascript">
//<![CDATA[

jQuery(document).ready(function($){
    
    
    
    <?php if($homepage_only == 'home_only') : ?>
    
        <?php if( is_home() || is_front_page() ) : ?>

            if ($.cookie('temporaryCookie')) {

                // Song will play again after 1 minute

            } else if ($.cookie('longerCookie')) {

                // Song is playing again!
                $("body").append('<audio style="display:none" controls autoplay> <?php if($mp3_music_url): ?><source src="<?php echo $mp3_music_url ?>" type="audio/mpeg"> <?php endif; ?> <?php if($ogg_music_url): ?><source src="<?php echo $ogg_music_url ?>" type="audio/ogg"> <?php endif; ?> <?php if($wav_music_url): ?><source src="<?php echo $wav_music_url ?>" type="audio/wav"> <?php endif; ?></audio>');

            } else {

                // Song is playing first time!
                $("body").append('<audio style="display:none" controls autoplay> <?php if($mp3_music_url): ?><source src="<?php echo $mp3_music_url ?>" type="audio/mpeg"> <?php endif; ?> <?php if($ogg_music_url): ?><source src="<?php echo $ogg_music_url ?>" type="audio/ogg"> <?php endif; ?> <?php if($wav_music_url): ?><source src="<?php echo $wav_music_url ?>" type="audio/wav"> <?php endif; ?></audio>');

            }

            var expiresAt = new Date();

            expiresAt.setTime(expiresAt.getTime() + 1*24*60*60*1000); // Days               
            

            $.cookie('longerCookie', new Date());
            $.cookie('temporaryCookie', true, { expires: expiresAt });  

        <?php endif; ?>    
    
    
    <?php else : ?>
    
        if ($.cookie('temporaryCookie')) {
            

            // Song will play again after 1 minute

        } else if ($.cookie('longerCookie')) {

            // Song is playing again!
            $("body").append('<audio style="display:none" controls autoplay> <?php if($mp3_music_url): ?><source src="<?php echo $mp3_music_url ?>" type="audio/mpeg"> <?php endif; ?> <?php if($ogg_music_url): ?><source src="<?php echo $ogg_music_url ?>" type="audio/ogg"> <?php endif; ?> <?php if($wav_music_url): ?><source src="<?php echo $wav_music_url ?>" type="audio/wav"> <?php endif; ?></audio>');

        } else {

            
            // Song is playing first time!
            $("body").append('<audio style="display:none" controls autoplay> <?php if($mp3_music_url): ?><source src="<?php echo $mp3_music_url ?>" type="audio/mpeg"> <?php endif; ?> <?php if($ogg_music_url): ?><source src="<?php echo $ogg_music_url ?>" type="audio/ogg"> <?php endif; ?> <?php if($wav_music_url): ?><source src="<?php echo $wav_music_url ?>" type="audio/wav"> <?php endif; ?></audio>');

        }

        var expiresAt = new Date();

        <?php if($time_type == 'minutes'): ?>

            expiresAt.setTime(expiresAt.getTime() + <?php echo $automusic_time; ?>*60*1000); // Minutes

        <?php elseif($time_type == 'days'): ?>

            expiresAt.setTime(expiresAt.getTime() + <?php echo $automusic_time; ?>*24*60*60*1000); // Days               

        <?php else : ?>

            expiresAt.setTime(expiresAt.getTime() + <?php echo $automusic_time; ?>*60*60*1000); // Hours

        <?php endif; ?>


        $.cookie('longerCookie', new Date());
        $.cookie('temporaryCookie', true, { expires: expiresAt });    
    
    
    <?php endif; ?>
    
    

});
    

//]]>
</script>
    
    
    

<?php
}
add_action('wp_footer', 'append_music_to_body');