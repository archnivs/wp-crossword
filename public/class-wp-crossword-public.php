<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_Crossword
 * @subpackage Wp_Crossword/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Crossword
 * @subpackage Wp_Crossword/public
 * @author     Nivs <alvin@writerscentre.com.au>
 */
class Wp_Crossword_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        
        add_shortcode( 'crossword', array( $this, '_crossword_shortcode_func' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Crossword_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Crossword_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-crossword-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Crossword_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Crossword_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
         
        wp_enqueue_script( 'crossword', WP_CROSSWORD_PLUGIN_URL . 'assets/js/crossword.js' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-crossword-public.js', array( 'jquery' ), $this->version, false );
        
        wp_localize_script( $this->plugin_name, 'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}
    
    public function _crossword_shortcode_func($atts){
        
        extract(shortcode_atts(array(
			  'id' => null
		   ), $atts));
               
        if( empty( $id ) ) return '<div class="cw-error-msg"><p>There is no <strong>id</strong> in the crossword shortcode.</p></div>';
         
         ob_start();
         
        ?>
        
            <div data-crossword-id="<?php echo $id; ?>" id="xw">
                
            </div>
            <div style="margin:auto;display: inline-block;"><div class="loader-12"></div></div>
        <?php
        
        return ob_get_clean();
        
    }
    
    public function _get_crossword_ajax(){
        
        $data = $_POST['data'];
        
        $cw_id =  $data['cw_id'];
        
        $cw_words_clues = get_post_meta($cw_id, 'cw_words_clues', true);
        $cw_grid_json = get_post_meta($cw_id, 'cw_grid_json', true);
        $cw_grid_decode_json = json_decode($cw_grid_json) ;
        $return = array(
            'words_clues' => $cw_words_clues,
            'grid' => $cw_grid_decode_json
        );
        
        wp_send_json_success( $return );
    }
        
}
