<?php
// Adds widget: Minimal Profile Widget
class Minimalprofilewidget_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'minimalprofilewidget_widget',
			esc_html__( 'Minimal Profile Widget', 'minimal' ),
			array( 'description' => esc_html__( 'A simple and easy minimal profile widget', 'minimal' ), ) // Args
		);
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'media_fields' ) );
	}

	private $widget_fields = array(
		array(
			'label' => 'Name',
			'id' => 'name',
			'default' => 'Jhon Doe',
			'type' => 'text',
		),
		array(
			'label' => 'Designation',
			'id' => 'designation',
			'default' => 'WordPress Developer',
			'type' => 'text',
		),
		array(
			'label' => 'Profile Photo',
			'id' => 'profile-photo',
			'type' => 'media',
		),
		array(
			'label' => 'Cover Photo',
			'id' => 'cover-photo',
			'type' => 'media',
		),
		array(
			'label' => 'Short Description',
			'id' => 'short_description',
			'default' => 'Describe yourself in a few sentences.',
			'type' => 'textarea',
		),
		array(
			'label' => 'Email',
			'id' => 'email',
			'default' => 'example@example.com',
			'type' => 'email',
		),
		array(
			'label' => 'Twitter Profile Link',
			'id' => 'twitter_link',
			'default' => 'http://twitter.com/',
			'type' => 'text',
		),
		array(
			'label' => 'LinkedIn Profile Link',
			'id' => 'linkedin_link',
			'default' => 'http://linkedin.com/',
			'type' => 'text',
		),
	);

	public function widget( $args, $instance ) {
		echo $args['before_widget'];


		$cover_image_src = wp_get_attachment_image_src( $instance['cover-photo'], 'medium' );
		$profile_image_src = wp_get_attachment_image_src( $instance['profile-photo'] );

        // Output generated fields
        $output = '
        <div class="minimal-profile">
            <div class="image-area">
                <img src="'. esc_attr( $cover_image_src[0] ) .'">
                <img class="thumb" src="'. esc_attr( $profile_image_src[0] ) .'">
            </div>
            <div class="profile-details">
                <h4>'. esc_html( $instance['name'] ) .' <span>'. esc_html( $instance['designation'] ) .'</span></h4>
				<p>
					'. esc_html( $instance['short_description'] ) .'
				</p>
                <div class="social-link">
                    <a href="mailto:'. esc_attr( $instance['email'] ) .'">Email</a>
                    <a target="_blank" href="'. esc_attr( $instance['twitter_link'] ) .'">Twitter</a>
                    <a target="_blank" href="'. esc_attr( $instance['linkedin_link'] ) .'">LinkedIn</a>
                </div>
            </div>
        </div>
		';
		
		echo $output;

		// echo '<p>'.$instance['name'].'</p>';
		// echo '<p>'.$instance['designation'].'</p>';
		// echo '<p>'.$instance['profile-photo'].'</p>';
		// echo '<p>'.$instance['cover-photo'].'</p>';
		// echo '<p>'.$instance['short_description'].'</p>';
		// echo '<p>'.$instance['email'].'</p>';
		// echo '<p>'.$instance['twitter_link'].'</p>';
		// echo '<p>'.$instance['linkedin_link'].'</p>';
		
		echo $args['after_widget'];
	}

	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$(document).on('click','.custommedia',function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$('input#'+id).val(attachment.id);
								$('span#preview'+id).css('background-image', 'url('+attachment.url+')');
								$('input#'+id).trigger('change');
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
					$(document).on('click', '.remove-media', function() {
						var parent = $(this).parents('p');
						parent.find('input[type="media"]').val('').trigger('change');
						parent.find('span').css('background-image', 'url()');
					});
				}
			});
		</script><?php
	}

	public function field_generator( $instance ) {
		$output = '';
		foreach ( $this->widget_fields as $widget_field ) {
			$default = '';
			if ( isset($widget_field['default']) ) {
				$default = $widget_field['default'];
			}
			$widget_value = ! empty( $instance[$widget_field['id']] ) ? $instance[$widget_field['id']] : esc_html__( $default, 'minimal' );
			switch ( $widget_field['type'] ) {
				case 'media':
					$media_url = '';
					if ($widget_value) {
						$media_url = wp_get_attachment_url($widget_value);
					}
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'minimal' ).':</label> ';
					$output .= '<input style="display:none;" class="widefat" id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" type="'.$widget_field['type'].'" value="'.$widget_value.'">';
					$output .= '<span id="preview'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" style="margin-right:10px;border:2px solid #eee;display:block;width: 100%;height:92px;background-image:url('.$media_url.');background-size:contain;background-repeat:no-repeat;"></span>';
					$output .= '<button id="'.$this->get_field_id( $widget_field['id'] ).'" class="button select-media custommedia">Add Media</button>';
					$output .= '<input style="width: 19%;" class="button remove-media" id="buttonremove" name="buttonremove" type="button" value="Clear" />';
					$output .= '</p>';
					break;
				case 'textarea':
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'minimal' ).':</label> ';
					$output .= '<textarea class="widefat" id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" rows="6" cols="6" value="'.esc_attr( $widget_value ).'">'.$widget_value.'</textarea>';
					$output .= '</p>';
					break;
				default:
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'minimal' ).':</label> ';
					$output .= '<input class="widefat" id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" type="'.$widget_field['type'].'" value="'.esc_attr( $widget_value ).'">';
					$output .= '</p>';
			}
		}
		echo $output;
	}

	public function form( $instance ) {
		$this->field_generator( $instance );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach ( $this->widget_fields as $widget_field ) {
			switch ( $widget_field['type'] ) {
				default:
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? strip_tags( $new_instance[$widget_field['id']] ) : '';
			}
		}
		return $instance;
	}
}
