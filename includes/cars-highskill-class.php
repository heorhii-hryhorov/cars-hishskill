<?php

/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Cars_Highskill_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct() {
		parent::__construct(
			'cars_highskill', // Base ID
			esc_html__( 'Cars Highskill', 'ch_domain' ), // Name
			array( 'description' => esc_html__( 'Cars and Models Show', 'ch_domain' ), ) // Args
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array  An array of standard parameters for widgets in this theme
	 * @param array  An array of settings for this widget instance
	 * @return void Echoes it's output
	 */

	function widget( $args, $instance ) {
		echo $args['before_widget'];

		echo $args['before_title'];
		if (!empty($instance['title'])) {
			echo _e('<h1>'.$instance['title'].'</h1>', 'ch_domain');
		}
		echo $args['after_title'];
		?>
		<div id="accordion">
		<?php 

		 $cat = get_terms('models'); 
        foreach ($cat as $catVal) {
            echo _e('<h3>'.$catVal->name.'</h3>', 'ch_domain');
            $postArg = array('post_type'=>'cars','order'=>'desc',
                              'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'models',
                                                        'field' => 'term_id',
                                                        'terms' => $catVal->term_id
                                                    )
                            ));

            $getPost = new wp_query($postArg);
            global $post;

            if($getPost->have_posts()){
                echo '<div>';
                    while ( $getPost->have_posts()):$getPost->the_post();
                        echo _e("<p>".$post->post_title."</p>", 'ch_domain');
                    endwhile;
                echo '</div>';
            }

        }
        ?>
    </div>
        <?php
		echo $args['after_widget'];
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @param array  An array of new settings as submitted by the admin
	 * @param array  An array of the previous settings
	 * @return array The validated and (if necessary) amended settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance = array();
		
		$instance['title']  = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array  An array of the current settings for this widget
	 * @return void Echoes it's output
	 */
	function form( $instance ) {

		$title = !empty($instance['title']) ? $instance['title'] : __('Cars and Models', 'ch_domain');
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: '); ?></label>
		</p>
			<p>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
			</p>

			
			
			<?php 
		}
	}