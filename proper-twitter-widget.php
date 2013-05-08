<?php
/*
Plugin Name: Proper Twitter Widget
Plugin URI: https://github.com/mihai-rotaru/proper-twitter-widget
Description: Displays a Twitter widget
Author: Mihai Rotaru
Version: 1.1.0
Author URI: https://github.com/mihai-rotaru
*/
 
class ProperTwitterWidget extends WP_Widget {
	/**
	 * Default settings for Twitter widtgets
	 */
    protected static $twitter_defaults = array(
        'transparent_bg' => false,
    );

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'proper_twitter_widget', // Base ID
			'Proper Twitter Widget', // Name
			array( 'description' => __( 'Displays a Twitter widget', 'text_domain' ), ) // Args
		);
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
		$twitter_id         = $instance['twitter_id'];
		$twitter_widget_id  = $instance['twitter_widget_id'];
        $transparent_bg     = !empty( $instance['transparent_bg'] ) ? '1' : '0';

		echo $before_widget;
		if ( ! empty( $twitter_id ) && ! empty( $twitter_widget_id ) ) {
            $_out  = '<a class="twitter-timeline" ';
            if( $transparent_bg ) {
                $_out .= 'data-chrome="transparent"';
            }
            $_out .= 'data-dnt="true" href="https://twitter.com/' . $twitter_id ;
            $_out .= '"  data-widget-id="' . $twitter_widget_id . '">Tweets by @' . $twitter_id . '</a>';
            $_out .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?';
            $_out .= '\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
            echo __( $_out, 'text_domain' );
        }
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['twitter_id']         = strip_tags( $new_instance['twitter_id'] );
		$instance['twitter_widget_id']  = strip_tags( $new_instance['twitter_widget_id'] );
        $instance['transparent_bg']     = $new_instance['transparent_bg'] ? 1 : 0;

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, $twitter_defaults );
        $twitter_id = isset($instance['twitter_id']) ? esc_attr($instance['twitter_id']) : '';
        $twitter_widget_id = isset($instance['twitter_widget_id']) ? esc_attr($instance['twitter_widget_id']) : '';
        $transparent_bg = isset($instance['transparent_bg']) ? (bool) $instance['transparent_bg'] :false;
		?>
		<p>
            <label for="<?php echo $this->get_field_id( 'twitter_id' ); ?>"><?php _e( 'Twitter username:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitter_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_id' ); ?>" type="text" value="<?php echo esc_attr( $twitter_id ); ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'twitter_widget_id' ); ?>"><?php _e( 'Widget ID:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitter_widget_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_widget_id' ); ?>" type="text" value="<?php echo esc_attr( $twitter_widget_id ); ?>" />
		</p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('transparent_bg'); ?>" name="<?php echo $this->get_field_name('transparent_bg'); ?>"<?php checked( $transparent_bg ); ?> />
            <label for="<?php echo $this->get_field_id('transparent_bg'); ?>"><?php _e( 'Transparent background ' ); ?></label><br />
        </p>
		<?php 
	}

}

// register Twitter_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ProperTwitterWidget" );' ) );
