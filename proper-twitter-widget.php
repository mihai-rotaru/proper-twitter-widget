<?php
/*
Plugin Name: Proper Twitter Widget
Plugin URI: https://github.com/mihai-rotaru/proper-twitter-widget
Description: Displays a Twitter widget
Author: Mihai Rotaru
Version: 1.2.0
Author URI: https://github.com/mihai-rotaru
*/
 
class ProperTwitterWidget extends WP_Widget {
	/**
	 * Default settings for Twitter widtgets
	 */
    protected static $twitter_defaults = array(
        'header'         => true,
        'footer'         => true,
        'borders'        => true,
        'scrollbar'      => true,
        'transparent_bg' => false,
        'opt_out'        => false,
    );

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'proper_twitter_widget', // Base ID
			'Proper Twitter Widget', // Name
			array( 'description' => __( 'Displays a Twitter widget', 'text_domain' ), ), // Args
            self::$twitter_defaults // Defaults
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

        // chrome options
        $header             = !empty( $instance['header']         ) ? '1' : '0';
        $footer             = !empty( $instance['footer']         ) ? '1' : '0';
        $borders            = !empty( $instance['borders']        ) ? '1' : '0';
        $scrollbar          = !empty( $instance['scrollbar']      ) ? '1' : '0';
        $transparent_bg     = !empty( $instance['transparent_bg'] ) ? '1' : '0';

        // opt-out of web intent related users
        $opt_out            = !empty( $instance['opt_out']        ) ? '1' : '0';

        // render widget
		echo $before_widget;
		if ( ! empty( $twitter_id ) && ! empty( $twitter_widget_id ) ) {
            $_out  = '<a class="twitter-timeline" ';
            if( !$header || !$footer || !$borders || !$scrollbar || $transparent_bg ) {
                $_out .= 'data-chrome="';
                if( !$header        ) $_out .= 'noheader ';
                if( !$footer        ) $_out .= 'nofooter ';
                if( !$borders       ) $_out .= 'noborders ';
                if( !$scrollbar     ) $_out .= 'noscrollbar ';
                if( $transparent_bg ) $_out .= 'transparent ';
                $_out .= '"';
            }
            if( $opt_out ) {
                $_out .= 'data-dnt="true" ';
            }
            $_out .= 'href="https://twitter.com/' . $twitter_id ;
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
		$instance = $old_instance;

		$instance['twitter_id']         = strip_tags( $new_instance['twitter_id'] );
		$instance['twitter_widget_id']  = strip_tags( $new_instance['twitter_widget_id'] );

        // chrome options
        $instance['header']             = $new_instance['header']           ? 1 : 0;
        $instance['footer']             = $new_instance['footer']           ? 1 : 0;
        $instance['borders']            = $new_instance['borders']          ? 1 : 0;
        $instance['scrollbar']          = $new_instance['scrollbar']        ? 1 : 0;
        $instance['transparent_bg']     = $new_instance['transparent_bg']   ? 1 : 0;

        // opt-out of web intent related users
        $instance['opt_out']            = $new_instance['opt_out']          ? 1 : 0;

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
        $instance = wp_parse_args( (array) $instance, self::$twitter_defaults );

        $twitter_id = isset($instance['twitter_id']) ? esc_attr($instance['twitter_id']) : '';
        $twitter_widget_id = isset($instance['twitter_widget_id']) ? esc_attr($instance['twitter_widget_id']) : '';

        // chrome options
        $header         = isset($instance['header'])            ? (bool) $instance['header']         : false;
        $footer         = isset($instance['footer'])            ? (bool) $instance['footer']         : false;
        $borders        = isset($instance['borders'])           ? (bool) $instance['borders']        : false;
        $scrollbar      = isset($instance['scrollbar'])         ? (bool) $instance['scrollbar']      : false;
        $transparent_bg = isset($instance['transparent_bg'])    ? (bool) $instance['transparent_bg'] : false;

        // opt-out of web intent related users
        $opt_out        = isset($instance['opt_out'])           ? (bool) $instance['opt_out']        : false;
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
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>"<?php checked( $header ); ?> />
            <label for="<?php echo $this->get_field_id('header'); ?>"><?php _e( 'Header ' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('footer'); ?>" name="<?php echo $this->get_field_name('footer'); ?>"<?php checked( $footer ); ?> />
            <label for="<?php echo $this->get_field_id('footer'); ?>"><?php _e( 'Footer ' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('borders'); ?>" name="<?php echo $this->get_field_name('borders'); ?>"<?php checked( $borders ); ?> />
            <label for="<?php echo $this->get_field_id('borders'); ?>"><?php _e( 'Borders ' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('scrollbar'); ?>" name="<?php echo $this->get_field_name('scrollbar'); ?>"<?php checked( $scrollbar ); ?> />
            <label for="<?php echo $this->get_field_id('scrollbar'); ?>"><?php _e( 'Scrollbar ' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('transparent_bg'); ?>" name="<?php echo $this->get_field_name('transparent_bg'); ?>"<?php checked( $transparent_bg ); ?> />
            <label for="<?php echo $this->get_field_id('transparent_bg'); ?>"><?php _e( 'Transparent background ' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('opt_out'); ?>" name="<?php echo $this->get_field_name('opt_out'); ?>"<?php checked( $opt_out ); ?> />
            <label for="<?php echo $this->get_field_id('opt_out'); ?>"><?php _e( 'Opt out of tailoring ' ); ?></label><br />
        </p>
		<?php 
	}

}

// register Twitter_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ProperTwitterWidget" );' ) );
