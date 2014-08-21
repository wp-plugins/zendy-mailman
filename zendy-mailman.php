<?php
/*
Plugin Name: Zendy Mailman
Version: 1.0
Plugin URI: https://hq.zendy.net/wordpress/plugins/mailman/
Author: Zendy Labs
Author URI: https://hq.zendy.net/
Description: A simple SMTP mailer for Wordpress
TODO: internationalize
*/

// Add Zendy Mailman to Wordpress Settings
if ( ! function_exists( 'zendy_mailman_admin_default_setup' ) ) {

	// Function to add Zendy Mailman menu in Wordpress Settings
	// Gets hooked to 'admin_menu' action
	function zendy_mailman_admin_default_setup() {		
	
		// Wordpress fn to add options page
		add_options_page(
		
			// Page Title
			__( 
				'Zendy Mailman', 
				'zendy_mailman'
			), 
			
			// Menu Title
			__( 
				'Zendy Mailman', 
				'zendy_mailman'
			), 
			
			// Capability (access rights required)
			'manage_options', 
			
			// Menu slug
			'zendy_mailman_settings', 
			
			//  The function to be called to output the content for this page.
			'zendy_mailman_settings'
		
		);
	
	}

}

// Plugin init on admin side
if ( ! function_exists ( 'zendy_mailman_admin_init' ) ) {

	// Function to add Zendy Mailman menu in Wordpress Settings
	// Gets hooked to 'admin_init' action
	// admin_init is triggered before any other hook when a user accesses the admin area. This hook doesn't provide any parameters, so it can only be used to callback a specified function.
	function zendy_mailman_admin_init() {
		
		// ?????
		if ( isset( $_REQUEST['page'] ) && 'zendy_mailman_settings' == $_REQUEST['page'] ) {
			
			// Register settings
			$zendy_mailman_options_default = array(
			
				// Email address displayed in emails sent
				'from_email_field' 	=> '',
				
				// Name displayed in emails sent
				'from_name_field'   => '',
				
				// SMTP settings
				'smtp_settings'     => array( 
				
					// SMTP server
					'host'              => '',
					
					// Encryption Type
					'encryption_type'	=> 'none',
					
					// Port
					'port'              => '',
					
					// Use authentication? yes / no
					'autentication'		=> 'yes',
					
					// Username for authentication
					'username'			=> '',
					
					// Password for authentication
					'password'          => ''
					
				)
				
			);

			// Install the default plugin options
            if ( ! get_option( 'zendy_mailman_options' ) ){
            
                add_option( 
                	
                	// Name of option
                	'zendy_mailman_options', 
                	
                	// Value of option
                	$zendy_mailman_options_default, 
                	
                	// Leave empty - deprecated since WP 2.3
                	'', 
                	
                	// autoload - should this option be automatically loaded by the function wp_load_alloptions() (puts options into object cache on each page load)?
                	'yes' 
                	
                );
            
            }
		
		}
		
	}
	
}

// Plugin page links
// Action links (links under name of plugin)
if ( ! function_exists ( 'zendy_mailman_register_plugin_action_links' ) ) {

	// Function to add action links (links under name of plugin)
	// Gets hooked on plugin_action_links_
	function zendy_mailman_register_plugin_action_links( $links ) {
	
		// Add all new links into an array
		$new_links = array(
			'<a href="'. get_admin_url(null, 'options-general.php?page=zendy_mailman_settings') .'">Settings</a>',
			'<a href="https://hq.zendy.net/wordpress/plugins/mailman/premium-support/" target="_blank">Premium Support</a>'
		);

		// Merge new links into main action links array
		$links = array_merge( $links, $new_links );
		
		return $links;
	
	}

}


// Plugin page links
// Row meta links (links under description of plugin)
// Add action links on plugin page in to Plugin Description block
if ( ! function_exists ( 'zendy_mailman_register_plugin_row_meta_links' ) ) {
	
	// Add row meta links (links under description of plugin)
	// Gets hooked on plugin_row_meta
	function zendy_mailman_register_plugin_row_meta_links( $links, $file ) {
		
		// If our plugin name is in the file name, let's do stuff
		if ( strpos( $file, 'zendy-mailman.php' ) !== false ) {
		
			// Add all new links into an array
			$new_links = array(	
				'<a href="https://hq.zendy.net/wordpress/plugins/" target="_blank">More plugins by Zendy Labs</a>',
				'<a href="https://hq.zendy.net/wordpress/plugins/mailman/donate/" target="_blank">Donate</a>'
			);
		
			// Merge new links into main row meta links array
			$links = array_merge( $links, $new_links );
		}
	
		return $links;	      
	
	}

}



// Admin head: scripts & styles
if ( ! function_exists ( 'zendy_mailman_admin_head' ) ) {

	// Add admin styles
	// Gets hooked on admin_enqueue_scripts
	function zendy_mailman_admin_head() {
	
		// Add admin stylesheet
		wp_enqueue_style( 
		
			// Handle
			'zendy_mailman_stylesheet', 
			
			// Source
			plugins_url( 
			
				// Path relative to plugin
				'css/style.css', 
				
				// Plugin directory
				__FILE__ 
				
			) 
			
		);
	
	}

}

// Add SMTP to Wordpress PHP mailer
if ( ! function_exists ( 'zendy_mailman_init_smtp' ) ) {

	// Function to configure SMTP mailer
	// Gets hooked on phpmailer_init
	function zendy_mailman_init_smtp( $phpmailer ) {

		// Get plugin option values
		$zendy_mailman_options = get_option( 'zendy_mailman_options' );
		
		// Set the mailer type
		$phpmailer->IsSMTP();  
		
		// Set FROM header
		$from_email = $zendy_mailman_options['from_email_field'];
        $from_name  = $zendy_mailman_options['from_name_field'];
        $phpmailer->SetFrom( $from_email, $from_name );
		
		// Set the SMTPSecure value, if option has been set
		if ( $zendy_mailman_options['smtp_settings']['encryption_type'] !== 'none' ) {
			$phpmailer->SMTPSecure = $zendy_mailman_options['smtp_settings']['encryption_type'];
		}
		
		// Set the SMTP server
		$phpmailer->Host = $zendy_mailman_options['smtp_settings']['host'];
		
		// Set the port
		$phpmailer->Port = $zendy_mailman_options['smtp_settings']['port']; 

		// Set the SMTPAuth values, if option has been set
		if( 'yes' == $zendy_mailman_options['smtp_settings']['autentication'] ){
			
			// Turn authentication on
			$phpmailer->SMTPAuth = true;
			
			// Username for authentication
			$phpmailer->Username = $zendy_mailman_options['smtp_settings']['username'];
			
			// Password for authentication
			$phpmailer->Password = $zendy_mailman_options['smtp_settings']['password'];
			
		}
		
	}
	
}

// Settings page
if ( ! function_exists( 'zendy_mailman_settings' ) ) {

	// Settings page
	// Callback function for add_options_page
	function zendy_mailman_settings() {
		
		$display_add_options = '';
		$message = '';
		$error = '';
		$result = '';

		// Get plugin saved options
		$zendy_mailman_options = get_option( 'zendy_mailman_options' );
        
        // If SETTINGS form has been submitted && nonce security checks out        
		if ( isset( $_POST['zendy_mailman_form_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'zendy_mailman_nonce_name' ) ) {
			
			// Update from_name_field
			$zendy_mailman_options['from_name_field'] = isset( $_POST['zendy_mailman_from_name'] ) ? $_POST['zendy_mailman_from_name'] : '';
			
			// Update zendy_mailman_from_email
			if( isset( $_POST['zendy_mailman_from_email'] ) ){
			
				// Check if zendy_mailman_from_email is indeed an email address
				if( is_email( $_POST['zendy_mailman_from_email'] ) ){
					
					// All good, store the value
					$zendy_mailman_options['from_email_field'] = $_POST['zendy_mailman_from_email'];
				
				// Not an email!
				}else{
					
					// Spit out error
					$error .= " " . __( "Please enter a valid email address in the 'FROM' field.", 'zendy_mailman' );
					
				}
				
			}
			
			// Update smtp_settings host
			$zendy_mailman_options['smtp_settings']['host'] = $_POST['zendy_mailman_smtp_host'];

			// Update smtp_settings encryption_type
			$zendy_mailman_options['smtp_settings']['encryption_type'] = ( isset( $_POST['zendy_mailman_smtp_encryption_type'] ) ) ? $_POST['zendy_mailman_smtp_encryption_type'] : 'none' ;

			// Update smtp_settings autentication
			$zendy_mailman_options['smtp_settings']['autentication'] = ( isset( $_POST['zendy_mailman_smtp_autentication'] ) ) ? $_POST['zendy_mailman_smtp_autentication'] : 'yes' ;

			// Update smtp_settings username
			$zendy_mailman_options['smtp_settings']['username'] = $_POST['zendy_mailman_smtp_username'];

			// Update smtp_settings password
			$zendy_mailman_options['smtp_settings']['password'] = $_POST['zendy_mailman_smtp_password'];

			// Update smtp_settings port
			// If port was submitted
			if ( isset( $_POST['zendy_mailman_smtp_port'] ) ) {
				
				// Check that port value is a number
				// If port isn't a number
				if ( 
				
					// Check for empty string
					empty( $_POST['zendy_mailman_smtp_port'] ) 
					
					|| 
					
					// Cast as int and check for valid value
					1 > intval( $_POST['zendy_mailman_smtp_port'] ) 
					
					|| 
					
					// Preg match for numberic value only
					( ! preg_match( '/^\d+$/', $_POST['zendy_mailman_smtp_port'] ) ) 
					
				) {
					
					// set port to default (465)
					$zendy_mailman_options['smtp_settings']['port'] = '465';
					
					// And spit out error
					$error .= " " . __( "Please enter a valid port in the 'SMTP Port' field.", 'zendy_mailman' );
				
				// Port is a number. Carry on...
				} else {
					
					// Save port
					$zendy_mailman_options['smtp_settings']['port'] = $_POST['zendy_mailman_smtp_port'];
				
				}
			
			}

			// No error! Yay!
			if ( empty( $error ) ) {
			
				// Save data to DB
				update_option( 'zendy_mailman_options', $zendy_mailman_options );
				
				// Store success message
				$message .= __( 'Settings saved. <a href="options-general.php?page=zendy_mailman_settings&tab=troubleshooting">Send a test email to make sure everything works &rarr;</a>', 'zendy_mailman' );	
			
			// Oh boy! Errors were found	
			}else{
			
				// Display error notice
				$error .= " " . __( "Settings were not saved.", 'zendy_mailman' );
		
			}
		
		} // End : settings form has been submitted && nonce security checks out
		
		
		// TEST mail form has been submitted && nonce security checks out   
		if ( isset( $_POST['zendy_mailman_test_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'zendy_mailman_nonce_name' ) ) {	
		
			// Check that 'To' field has been set
			if( isset( $_POST['zendy_mailman_to'] ) ){
				
				// Check that 'To' field is an email address
				if( is_email( $_POST['zendy_mailman_to'] ) ){

					// Store 'to' field
					$zendy_mailman_to = $_POST['zendy_mailman_to'];
				
				// Spit out error message
				}else{
					$error .= " " . __( "Please enter a valid email address in the 'FROM' field.", 'zendy_mailman' );
				}
			}
			
			// Store subject
			$zendy_mailman_subject = isset( $_POST['zendy_mailman_subject'] ) ? $_POST['zendy_mailman_subject'] : '';
			
			// Store message
			$zendy_mailman_message = isset( $_POST['zendy_mailman_message'] ) ? $_POST['zendy_mailman_message'] : '';
			
			// If 'to' field has been set
			if( ! empty( $zendy_mailman_to ) ){
			
				// Send test mail
				$result = zendy_mailman_test_mail( $zendy_mailman_to, $zendy_mailman_subject, $zendy_mailman_message );
				
			}
			
		} 
		


		
		?>
		<!-- Settings confirmation messages -->
		<div class="zendy-updated" <?= empty( $message ) ? 'style="display:none"' : '' ?>>
			<?php echo $message; ?>
		</div>

		<!-- Test email confirmation messages -->
		<div class="zendy-updated" <?= empty( $result ) ? 'style="display:none"' : '' ?>>
			<?php echo $result; ?>
		</div>
		
		<!-- Error messages -->
		<div class="zendy-error" <?= empty( $error ) ? 'style="display:none"' : '' ?>>
			<?php echo $error; ?>
		</div>
			
		<!-- Plugin HTML wrap -->		
		<div id="zendy-mailman-wrap" class="zendy-mailman-wrap wrap">
		
			<!-- Header -->
			<div class="zendy-mailman-header">
			
				<!-- Logo -->
				<img class="zendy-mailman-logo" src="<?= plugin_dir_url( __FILE__ ) . '/images/zendy-mailman-logo-250x250.png' ?>" alt="Zendy Mailman Logo" />
				
				<!-- Description -->
				<h1 class="zendy-mailman-title">Zendy Mailman</h1>
				<h3 class="zendy-mailman-title">The friendly email delivery system for Wordpress</h3>
				<p class="zendy-mailman-title">Zendy Mailman makes sure all the email messages sent by your Wordpress site are delivered successfully.</p>

				<?
				
				// Tabs list
				$tabs = array( 'settings' => 'Settings', 'troubleshooting' => 'Troubleshooting', 'faq' => 'FAQ' );
				
				// Set current tab or default to settings tab
				$current = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
				
				// Output tabs
				echo '<h2 class="nav-tab-wrapper zendy-mailman-tabs">';
				foreach( $tabs as $tab => $name ){
					$class = ( $tab == $current || ( !isset( $_GET['tab'] ) && $tab == 'settings' ) ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab$class' href='?page=zendy_mailman_settings&tab=$tab'>$name</a>";			
				}
				echo '</h2>';	
	
				?>
				
			</div> <!-- /.zendy-mailman-header -->
			
			<div class="zendy-mailman-form-wrapper">
			
			
				
				<?
				
				// ############# START: SETTINGS TAB ##############
				if( $current == 'settings' ){
				
					?>
			
					<!-- Settings section -->
			
					<form id="zendy_mailman_settings_form" method="post" action="" class="zendy-mailman-form">	
			
						<h2 class="zendy-mailman-form-title">Enter your SMTP settings below<br><small><a href="options-general.php?page=zendy_mailman_settings&tab=faq">Where do I find this?</a></small>	</h2>	
							
							<table class="form-table">
				
								<!-- FROM email -->
								<tr valign="top">
									<th scope="row"><?php _e( "From Email Address", 'zendy_mailman' ); ?></th>
									<td>
										<input type="text" name="zendy_mailman_from_email" value="<?php echo stripslashes( $zendy_mailman_options['from_email_field'] ); ?>"/><br />
									</td>
								</tr>
					
								<!-- FROM name -->
								<tr valign="top">
									<th scope="row"><?php _e( "From Name", 'zendy_mailman' ); ?></th>
									<td>
										<input type="text" name="zendy_mailman_from_name" value="<?php echo $zendy_mailman_options['from_name_field']; ?>"/><br />
									</td>
								</tr>	
								
								<!-- SMTP HOST -->		
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'SMTP Host', 'zendy_mailman' ); ?></th>
									<td>
										<input type='text' name='zendy_mailman_smtp_host' value='<?php echo $zendy_mailman_options['smtp_settings']['host']; ?>' /><br />
									</td>
								</tr>
								
								<!-- ENCRYPTION TYPE -->
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'Type of Encription', 'zendy_mailman' ); ?></th>
									<td>
										<select name="zendy_mailman_smtp_encryption_type">
											<option value='ssl' <?php if( 'none' == $zendy_mailman_options['smtp_settings']['encryption_type'] ) echo 'selected="selected"'; ?>><?php _e( 'SSL', 'zendy_mailman' ); ?></option>
											<option value='tls' <?php if( 'none' == $zendy_mailman_options['smtp_settings']['encryption_type'] ) echo 'selected="selected"'; ?>><?php _e( 'TLS', 'zendy_mailman' ); ?></option>
											<option value='none' <?php if( 'none' == $zendy_mailman_options['smtp_settings']['encryption_type'] ) echo 'selected="selected"'; ?>><?php _e( 'None', 'zendy_mailman' ); ?></option>
										</select>
									</td>
								</tr>
								
								<!-- PORT -->
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'SMTP Port', 'zendy_mailman' ); ?></th>
									<td>
										<input type='text' name='zendy_mailman_smtp_port' value='<?php echo $zendy_mailman_options['smtp_settings']['port']; ?>' /><br />
									</td>
								</tr>
								
								<!-- AUTH -->
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'SMTP Autentication', 'zendy_mailman' ); ?></th>
									<td>
										<select name="zendy_mailman_smtp_autentication">
											<option value='yes' <?php if( 'yes' == $zendy_mailman_options['smtp_settings']['autentication'] ) echo 'selected="selected"'; ?>><?php _e( 'Yes', 'zendy_mailman' ); ?></option>
											<option value='no' <?php if( 'no' == $zendy_mailman_options['smtp_settings']['autentication'] ) echo 'selected="selected"'; ?>><?php _e( 'No', 'zendy_mailman' ); ?></option>
										</select>
									</td>
								</tr>
					
								<!-- AUTH USERNAME -->
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'SMTP username', 'zendy_mailman' ); ?></th>
									<td>
										<input type='text' name='zendy_mailman_smtp_username' value='<?php echo $zendy_mailman_options['smtp_settings']['username']; ?>' /><br />
									</td>
								</tr>
								
								<!-- AUTH PASSWORD -->
								<tr class="ad_opt zendy_mailman_smtp_options">
									<th><?php _e( 'SMTP Password', 'zendy_mailman' ); ?></th>
									<td>
										<input type='password' name='zendy_mailman_smtp_password' value='<?php echo $zendy_mailman_options['smtp_settings']['password']; ?>' /><br />
									</td>
								</tr>
					
							</table>
				
							<!-- SUBMIT BUTTON & NONCE SECURITY -->
							<p class="submit">
								<input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Save Changes', 'zendy_mailman' ) ?>" />
								<input type="hidden" name="zendy_mailman_form_submit" value="submit" />
								<?php wp_nonce_field( plugin_basename( __FILE__ ), 'zendy_mailman_nonce_name' ); ?>
							</p>	
							
					</form>
			
					<?
				// ############# END: SETTINGS TAB ##############
	
				// ############# START: TROUBLESHOOTING TAB ##############		
				}elseif( $current == 'troubleshooting' ){
	
					?>
		
					<!-- TESTING & TROUBLESHOOTING SECTION -->	
							
					<!-- Test mail form -->
					<form id="zendy_mailman_settings_form" method="post" action="" class="zendy-mailman-form">		
		
						<h2 class="zendy-mailman-form-title">Test your settings<br><small>Send a test email via Zendy Mailman</small></h2>	
		
								
						<table class="form-table">
						
							<!-- TO -->
							<tr valign="top">
								<th scope="row"><?php _e( "To Email", 'zendy_mailman' ); ?>:</th>
								<td>
									<input type="text" name="zendy_mailman_to" value=""/><br />
								</td>
							</tr>
							
							<!-- SUBJECT -->
							<tr valign="top">
								<th scope="row"><?php _e( "Subject", 'zendy_mailman' ); ?>:</th>
								<td>
									<input type="text" name="zendy_mailman_subject" value=""/><br />
								</td>
							</tr>
							
							<!-- MESSAGE -->
							<tr valign="top">
								<th scope="row"><?php _e( "Message", 'zendy_mailman' ); ?>:</th>
								<td>
									<textarea name="zendy_mailman_message" rows="5"></textarea><br />
								</td>
							</tr>	
										
						</table>
						
						<!-- SUBMIT BUTTON & NONCE SECURITY -->
						<p class="submit">
							<input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Send Test Email', 'zendy_mailman' ) ?>" />
							<input type="hidden" name="zendy_mailman_test_submit" value="submit" />
							<?php wp_nonce_field( plugin_basename( __FILE__ ), 'zendy_mailman_nonce_name' ); ?>
						</p>	
									
					</form>
		
					<?
		
				// ############# END: SETTINGS TAB ##############
	
				// ############# START: TROUBLESHOOTING TAB ##############		
		
				}elseif( $current = 'faq' ){
			
					?>
			
					<h2 class="zendy-mailman-form-title">FAQ<br><small>For more information about Zendy Mailman <a href="https://hq.zendy.net/wordpress/plugins/mailman/" target="_blank">visit our website</a>.</small>	</h2>	
					
					<div class="faq">
						<h3>Where do I find my SMTP settings?</h3>
						<p>Your web host (or email provider) will provide that for you. However the most popular settings are listed below.</p>
				
						<h3>SMTP settings for Gmail</h3>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<th>From Email Address</th>
								<td>Any email address</td>
							</tr>
							<tr>
								<th>From Name</th>
								<td>Any name - your business name or your personal name</td>
							</tr>
							<tr>
								<th>SMTP Host</th>
								<td>smtp.gmail.com</td>
							</tr>
							<tr>
								<th>Type of Encryption</th>
								<td>SSL</td>
							</tr>
							<tr>
								<th>SMTP Port</th>
								<td>465</td>
							</tr>
							<tr>
								<th>SMTP Authentication</th>
								<td>Yes</td>
							</tr>
							<tr>
								<th>SMTP Username</th>
								<td>your Gmail or Google email address</td>
							</tr>
							<tr>
								<th>SMTP Username</th>
								<td>The password for your Gmail or Google email address</td>
							</tr>
						</table>
						
						<h3>SMTP settings for Rackspace</h3>
						<table class="faq" cellpadding="0" cellspacing="0" border="1">
							<tr>
								<th>From Email Address</th>
								<td>Any email address</td>
							</tr>
							<tr>
								<th>From Name</th>
								<td>Any name - your business name or your personal name</td>
							</tr>
							<tr>
								<th>SMTP Host</th>
								<td>secure.emailsrvr.com</td>
							</tr>
							<tr>
								<th>Type of Encryption</th>
								<td>SSL</td>
							</tr>
							<tr>
								<th>SMTP Port</th>
								<td>465</td>
							</tr>
							<tr>
								<th>SMTP Authentication</th>
								<td>Yes</td>
							</tr>
							<tr>
								<th>SMTP Username</th>
								<td>your Gmail or Google email address</td>
							</tr>
							<tr>
								<th>SMTP Username</th>
								<td>The password for your Gmail or Google email address</td>
							</tr>
						</table>
						
						<h3>What does Zendy Mailman do?</h3>
						<p>Zendy Mailman increases the delivery rate of emails sent by your Wordpress site (like contact form notifications and new subscriber alerts). In other words, the emails sent by your Wordpress website are less likely to be flagged as spam.</p>
						
						<h3>How does Zendy Mailman work?</h3>
						<p>On shared hosting servers (most Wordpress sites are on shared hosting servers) the Wordpress mailer often gets flagged as spam or even blacklisted. Zendy Mailman uses SMTP mail instead of relying on the Wordpress mailer.</p>
						
					</div>
					<?
				
				}
				// ############# END: SETTINGS TAB ##############
			
				?>
			
			</div>

		</div><!--  #zendy-mailman-mail .zendy-mailman-mail -->
		
		<? 
			
	} // zendy_mailman_settings()

} // if !zendy_mailman_settings()
	
// Test mailer
if ( ! function_exists( 'zendy_mailman_test_mail' ) ) {

	// Function to send test email
	function zendy_mailman_test_mail( $to_email, $subject, $message ) {
	
		// Placeholder for errors
		$errors = '';

		// Get plugin options
		$zendy_mailman_options = get_option( 'zendy_mailman_options' );

		// Include PHP mailer
		require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
		$mail = new PHPMailer();
		
		// Get FROM info
		$from_name  = $zendy_mailman_options['from_name_field'];
		$from_email = $zendy_mailman_options['from_email_field']; 
		
		// Set mail type to SMTP
		$mail->IsSMTP();
		
		// Set Auth settings
		if( 'yes' == $zendy_mailman_options['smtp_settings']['autentication'] ){
			$mail->SMTPAuth = true;
			$mail->Username = $zendy_mailman_options['smtp_settings']['username'];
			$mail->Password = $zendy_mailman_options['smtp_settings']['password'];
		}
		
		// Set encryption settinsg
		if ( $zendy_mailman_options['smtp_settings']['encryption_type'] !== 'none' ) {
			$mail->SMTPSecure = $zendy_mailman_options['smtp_settings']['encryption_type'];
		}
		
		// Set host
		$mail->Host = $zendy_mailman_options['smtp_settings']['host'];
		
		// Set port
		$mail->Port = $zendy_mailman_options['smtp_settings']['port']; 
		
		// From
		$mail->SetFrom( $from_email, $from_name );
		
		// HTML = yes
		$mail->isHTML( true );
		
		// Subject
		$mail->Subject = $subject;
		
		// Message
		$mail->MsgHTML( $message );
		
		// To
		$mail->AddAddress( $to_email );
		
		// Turn debug off
		$mail->SMTPDebug = 0;

		// Send
		if ( ! $mail->Send() ){
		
			// If send failed, store errors
			$errors = $mail->ErrorInfo;
		
		}
		
		// Reset
		$mail->ClearAddresses();
		$mail->ClearAllRecipients();
		
		// Return errors	
		if ( ! empty( $errors ) ) {
			return $errors;
		
		// ... Or if everything went well, return confirmation message
		}else{
			return 'Test email was sent successfully. Check your inbox.';
		}
	}
}

// Uninstall
if ( ! function_exists( 'zendy_mailman_send_uninstall' ) ) {

	// Function to remove options on uninstall
	function zendy_mailman_send_uninstall() {
		
		// Delete options
		delete_option( 'zendy_mailman_options' );

	}

}

// ############# HOOK IT ALL UP!!! ################################

// Add action links on plugin page (links under plugin name)
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zendy_mailman_register_plugin_action_links' );

// Add row meta links on plugin page (links under plugin description)
add_filter( 'plugin_row_meta', 'zendy_mailman_register_plugin_row_meta_links', 10, 2 );

// Overwrite PHP Mailer
add_action( 'phpmailer_init','zendy_mailman_init_smtp');

// Admin menu link
add_action( 'admin_menu', 'zendy_mailman_admin_default_setup' );

// Admin init - plugin setup
add_action( 'admin_init', 'zendy_mailman_admin_init' );

// Admin enqueue scripts - add styles and scripts for admin
add_action( 'admin_enqueue_scripts', 'zendy_mailman_admin_head' );

// Uninstall hook
register_uninstall_hook( plugin_basename( __FILE__ ), 'zendy_mailman_send_uninstall' );