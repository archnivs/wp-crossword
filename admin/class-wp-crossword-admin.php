<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_Crossword
 * @subpackage Wp_Crossword/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Crossword
 * @subpackage Wp_Crossword/admin
 * @author     Nivs <alvin@writerscentre.com.au>
 */
class Wp_Crossword_Admin {

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
		 * defined in Wp_Crossword_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Crossword_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-crossword-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Crossword_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Crossword_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_enqueue_script( 'crossword', WP_CROSSWORD_PLUGIN_URL . 'assets/js/crossword.js' );
        wp_enqueue_script( 'clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-crossword-admin.js', array( 'jquery' ), $this->version, false );
		

	}
    
    /**
	 * Register the wp_crossword Post Type
	 *
	 * @since    1.0.0
	 */
    public function register_crossword_post_type() {
        if ( ! post_type_exists( 'wp_crossword' ) ) {
            $labels = array(
                'name'               => _x( 'Crosswords', 'post type general name', 'wp-crossword' ),
                'singular_name'      => _x( 'Crossword', 'post type singular name', 'wp-crossword' ),
                'menu_name'          => _x( 'Crosswords', 'admin menu', 'wp-crossword' ),
                'name_admin_bar'     => _x( 'Crossword', 'add new on admin bar', 'wp-crossword' ),
                'add_new'            => _x( 'Add New', 'crossword', 'wp-crossword' ),
                'add_new_item'       => __( 'Add New Crossword', 'wp-crossword' ),
                'new_item'           => __( 'New Crossword', 'wp-crossword' ),
                'edit_item'          => __( 'Edit Crossword', 'wp-crossword' ),
                'view_item'          => __( 'View Crossword', 'wp-crossword' ),
                'all_items'          => __( 'All Crosswords', 'wp-crossword' ),
                'search_items'       => __( 'Search Crosswords', 'wp-crossword' ),
                'parent_item_colon'  => __( 'Parent Crosswords:', 'wp-crossword' ),
                'not_found'          => __( 'No crosswords found.', 'wp-crossword' ),
                'not_found_in_trash' => __( 'No crosswords found in Trash.', 'wp-crossword' )
            );

            $args = array(
                'labels'             => $labels,
                'description'        => __( 'A', 'wp-crossword' ),
                'menu_icon'     => 'dashicons-forms',
                'public'             => true,
                'exclude_from_search' => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => false,
                'rewrite'            => array( 'slug' => 'crossword' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 98,
                'supports'           => array( 'title' ),
                'register_meta_box_cb' => array($this, 'cw_add_metaboxes'),
            );

            register_post_type( 'wp_crossword', $args );
        }
    }
    
     /**
	 * Adding the necessary metaboxes to handle the words, clues and crossword topology
	 *
	 * @since    1.0.0
	 */
    public function cw_add_metaboxes($post) {
        
        add_meta_box(
            'cw-shortcode-clipboard-manager',
            __( 'Shortcode', 'wp-crossword' ),
            array( $this, 'render_shortcode_clipboard_metabox' ),
            'wp_crossword',
            'advanced',
            'high'
        );
        
        add_meta_box(
            'cw-words-clue-manager',
            __( 'Words and its Clue', 'wp-crossword' ),
            array( $this, 'render_word_clue_manager_metabox' ),
            'wp_crossword',
            'advanced',
            'high'
        );
        
        add_meta_box(
            'cw-topology-manager',
            __( 'Crossword Topology', 'wp-crossword' ),
            array( $this, 'render_topology_manager_metabox' ),
            'wp_crossword',
            'advanced',
            'high'
        );
        
    }
    
    public function render_shortcode_clipboard_metabox($post) {
        ?>
            <div class="widefat">
                <div id="shortcode-clipboard">
                    [crossword id="<?php echo $post->ID; ?>"]
                </div>
                <p>
                <button id="copy-shortcode-clipboard" class="button-secondary" data-clipboard-action="copy" data-clipboard-target="#shortcode-clipboard">Copy shortcode</button>
                Then paste it to any page or post you like.
                </p>
            </div>
        <?php
    }
    
    /**
	 * Render the Words and its clue manager
	 *
	 * @since    1.0.0
	 */
    public function render_word_clue_manager_metabox($post) {
        
        wp_nonce_field( 'word_clue_manager_metabox', 'word_clue_manager_metabox_nonce' );
        
        $words_clues = get_post_meta( $post->ID, 'cw_words_clues', true );
        
        //echo '<pre>' . print_r($words_clues, true) . '</pre>';
        
        ?>
            <div  class="widefat">
                <table id="words-clue">
                    <thead>
                        <tr>
                            <th class="index-col row-title">Index</th>
                            <th class="word-col">Word</th>
                            <th class="clue-col">Clue</th>
                            <th class="remove-col">&nbsp;</th>
                        </tr>
                    </thead>
                    
                   <tbody>
                   
                        <?php if(! empty($words_clues) ) : ?>
                        
                        <?php foreach($words_clues as $k => $wc) : ?>
                        <tr class="word-clue-row">
                            <td class="index-col row-title"><?php echo $_k = ($k + 1); ?></td>
                            <td class="word-col"><input type="text" name="cw_word[<?php echo $k; ?>]" class="cw-word all-options" value="<?php echo $wc['word']; ?>" /></td>
                            <td class="clue-col"><input type="text" name="cw_clue[<?php echo $k; ?>]" class="cw-clue large-text" value="<?php echo $wc['clue']; ?>" /></td>
                            <td class="remove-col"><a class="button-secondary" href="#"><span class="dashicons dashicons-dismiss"></span> Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php else: ?>
                        
                        <tr class="word-clue-row">
                            <td class="index-col row-title">1</td>
                            <td class="word-col"><input type="text" name="cw_word[0]" class="cw-word all-options" /></td>
                            <td class="clue-col"><input type="text" name="cw_clue[0]" class="cw-clue large-text" /></td>
                            <td class="remove-col"><a class="button-secondary" href="#"><span class="dashicons dashicons-dismiss"></span> Remove</a></td>
                        </tr>
                        
                        <?php endif; ?>
                        
                    </tbody>  
                    
                    <tfoot>
                        <tr>
                            <th class="last-row" colspan="4"><a class="button-primary" id="adding-cw-word" href="#"><span class="dashicons dashicons-plus-alt"></span> Add</a></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php
        
    }    
    
    /**
	 * Render the Crosswod Topology manager
	 *
	 * @since    1.0.0
	 */
    public function render_topology_manager_metabox($post) {
        
        $cw_grid_json = get_post_meta($post->ID, 'cw_grid_json', true);
        $cw_grid_decode_json = json_decode($cw_grid_json) ;
       // echo '<pre>' . print_r( $cw_grid_decode_json, true) . '</pre>';
        ?>
        
            <div id="crossword-topology-wrap">
                <h3 style="text-align: center;"><a class="button-primary" id="generate-crossword" href="#"><span class="dashicons dashicons-forms"></span> Generate New Crossword</a></h3>
                <div id="xw">
                </div>
                 <textarea id="grid-json" style="display:none;opacity:0;"  name="cw_grid_json" cols="60"><?php echo ($cw_grid_json) ? esc_js($cw_grid_json) : ''; ?></textarea>
            </div>
            
           
        <?php
    }
    
    /**<input type="hidden" id="grid-json" name="cw_grid_json" value="<?php echo ($cw_grid_json) ? $cw_grid_json : ''; ?>" />style="display:none;opacity:0;" 
	 * Saving all the metaboxes
	 *
	 * @since    1.0.0
	 */
    public function save_crossword_metaboxes($post_id) {
        
        
         /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['word_clue_manager_metabox_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['word_clue_manager_metabox_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'word_clue_manager_metabox' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }*/
 
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        
 
        /* OK, it's safe for us to save the data now. sanitize_text_field*/
        
        $old_words_clues = get_post_meta($post_id, 'cw_words_clues', true);
        $old_cw_grid_json = get_post_meta($post_id, 'cw_grid_json', true);
        
        $new_words_clues = array();
        
        $words = $_POST['cw_word'];
        $clues = $_POST['cw_clue'];
        $new_cw_grid_json = $_POST['cw_grid_json'];
        /* 
        echo '<pre>' . print_r( $new_cw_grid_json , true) . '</pre>';
        echo '<pre>' . print_r( $_POST['cw_grid_json'] , true) . '</pre>';
         */
        //exit();
        
        $count = count($words);
        
        for ( $i = 0; $i < $count; $i++ ) {
            if ( $words[$i] != '' && $clues[$i] != '' ) {
                $new_words_clues[$i] = array(
                    'word' => $words[$i],
                    'clue' => $clues[$i],
                );
            }
        }
        $new_words_clues = array_values($new_words_clues);
        // Update the meta field.
        update_post_meta( $post_id, 'cw_words_clues', $new_words_clues );
        update_post_meta( $post_id, 'cw_grid_json', $new_cw_grid_json );
        
    }
       
    
    /**
	 * Move metaboxes after the Title textbox
	 *
	 * @since    1.0.0
	 */
    function move_metabox_after_title () {
        global $post, $wp_meta_boxes;
        
        if($post->post_type == 'wp_crossword') {
            do_meta_boxes( get_current_screen(), 'advanced', $post );
            unset( $wp_meta_boxes[get_post_type( $post )]['advanced'] );
        }
        
    }
    
    /**
	 * Set default dashboard layout to one-column
	 *
	 * @since    1.0.0
	 */
    public function cw_dashboard_layout() {
        return true;
    }
    
    /**
	 * Remove the discussion metabox
	 *
	 * @since    1.0.0
	 */
    public function cw_remove_discussion_box() {
        remove_meta_box('commentstatusdiv', 'wp_crossword', 'normal');        
        remove_meta_box('commentsdiv', 'wp_crossword', 'normal');        
        remove_meta_box('postexcerpt_wp_crossword', 'wp_crossword', 'normal');        
        remove_meta_box('group_access_wp_crossword', 'wp_crossword', 'sidebar');         
    }

}
