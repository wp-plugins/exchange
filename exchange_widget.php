<?php
/*
Plugin Name: Exchange
Plugin URI: http://www.naira-wp.com
Version: 1.0
Short description of the plugin: this plugin executes currency convertion. 
Author: Naira Jorge
Author URI: http://www.naira-wp.com

Copyright 2012 Naira  (email : nairawordpress@gmail.com)
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}/* else {*/

/**
* Exchange widget converts currencies
*/
class exchange_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'exchange_widget', // Base ID
			'exchange_Widget', // Name
			array( 'description' => __( 'exchange_widget', 'text_domain' ), ) // Args
		);
	// Add the style sheet to exchange_widget
		//wp_enqueue_style('Exchange', plugins_url( '/Exchange/styleconver.css' ) ); 

		}

		/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$authorcredit	= isset($instance['author_credit']) ? $instance['author_credit'] : false ; // give plugin author credit
		// Before widget //
		echo $before_widget;
		
		// Title of widget //
		
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
		//if ( $title ) { echo $before_title . $title . $after_title; }
		
/**
* Get all the currency options
*
* @param $default
* Selected currency
*
* @return
* Echo the select options
*/
function getCurrencyOptions($default){

$currencies = getOpenExchangeRates('currencies.json');

foreach($currencies as $k => $v){
$selected = '';
if($k == $default){
$selected = "selected='selected'";
}	
echo '<option '.$selected.' value="'.$k.'">'.$v.'</option>';

}
}
//add_filter('the_content','getCurrencyOptions');
/**
* Make a call to the open exchange API
*
* @param $filename
* What data to collect
*
* @return
* JSON Data
*/
function getOpenExchangeRates($filename){

if($filename == ""){ return false; }

// Open CURL session:
$ch = curl_init('http://openexchangerates.org/' . $filename);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Get the data:
$json = curl_exec($ch);
curl_close($ch);

return json_decode($json);
}

//add_filter('the_content','getOpenExchangeRates');
		
?>

<?php $exchangeRates = getOpenExchangeRates('latest.json'); 
		//add_filter('the_content','$exchangeRates');?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<link rel="stylesheet" href="./styleconver.css">
	<script src="./core.js"></script>
	<script src="./money.js"></script>
	<script type="text/javascript">
fx.rates = <?php echo json_encode($exchangeRates->rates); ?>;
fx.base = "<?php echo $exchangeRates->base; ?>";
//fx.rates = {<?php echo json_encode($rates); ?>};
//fx.base = "<?php echo $base ?>";
    </script>
	<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
    </script>
	<style type="text/css">
<!--
.Estilo10 {font-size: 10px}
-->
    </style>
	<div class="fx" >
	
<div align="center">
<br />
  
  <select id="country_from"  class="fx" >

   <?php getCurrencyOptions("USD");  ?>" 
   </select>
 <input class="fx" name="money" type="text"  style="font-size:8px"  maxlength="40"  size="6"  height="4" id="money" value="1"  /> 
<p >
 
  
    <select id="country_to" class="fx" > 
<?php getCurrencyOptions("GBP");  ?>"
 </select>
 <input class="fx" type="text" name="exchange_total" id="exchange_total"  style="font-size:8px" maxlength="40"  size="6" height="4"/>
</p>
<p >
    <input class="fx" style="font-size:8px" size="7" height="4"  name="exchange" type="text"   id="exchange" value="Exchange" align="center" />
</p>

<?php 

if ($authorcredit) { ?>
			<p style="font-size:8px;">
			<span class="Estilo2 Estilo2 Estilo10">created by</span> <a href="http://www.naira-wp.com" title=" My Widget" class="Estilo2 Estilo10">Naira Jorge</a>
	</p>
</div>
</div>
<?php }

echo $after_widget;
		}
		// Update Settings //
 function update($new_instance, $old_instance) {
		$instance = array();
			$instance['title'] = strip_tags($new_instance['title']);
			
			$instance['author_credit'] = $new_instance['author_credit'];
			//$instance['no_results'] = $new_instance['no_results'];
			return $instance;
		}
		
	/*public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}*/
// Widget Control Panel //
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	 //function form($instance) {

		//$defaults = array( 'title' => 'Upcoming Posts', 'soup_number' => 3, 'post_type' => 'future', 'show_newsletter' => false, newsletter_url => '', author_credit => 'on' );
		//$instance = wp_parse_args( (array) $instance, $defaults ); 

	 function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		
		?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
			<label for="<?php echo $this->get_field_id('author_credit'); ?>"><?php _e('Give credit to plugins author?'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['author_credit'], 'on' ); ?> id="<?php echo $this->get_field_id('author_credit'); ?>" name="<?php echo $this->get_field_name('author_credit'); ?>" />
	</p>
        <?php 
		}
 
}


// End class soup_widget
// register exchange_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "exchange_widget" );' ) );
?>
