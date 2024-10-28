<?php
/**
 * Register meta boxes for Accordion Post type
 *
 * @since      0.1
 *
 * @package    a-faq-builder
 */

namespace AFaqBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use \AFaqBuilder\Includes\Helper;
class Register_Meta_Boxes {

	private static $instance;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 0.1
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Setup necessary settings
	 *
	 * @since 0.1
	 */
	protected function setup() {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_afb_content_meta_box_data' ] );
		add_filter( 'manage_accordion_faq_posts_columns', function( $columns ) {
			return array_merge( $columns, ['shortcode' => __( 'Shortcode', 'a-faq-builder' )] );
		} );
		add_action( 'manage_accordion_faq_posts_custom_column', [ $this, 'shortcode_genarator' ], 10, 2 );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'afb-shortcode',
			__( 'Shortcode', 'a-faq-builder' ),
			[ $this, 'afb_shortcode_meta_box_callback' ],
			'accordion_faq'
		);
		add_meta_box(
			'afb-content',
			__( 'Add Accordion FAQ', 'a-faq-builder' ),
			[ $this, 'afb_content_meta_box_callback' ],
			'accordion_faq'
		);
	}

	public function afb_content_meta_box_callback( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'afb_content_nonce', 'afb_content_nonce' );

		$value = get_post_meta( $post->ID, '_afb_content', true );
		$type = isset( $value['type'] ) && ! empty( $value['type'] ) ? $value['type'] : Helper::$defaults['type'];
		$selected_template_id = isset( $value['template'] ) && ! empty( $value['template'] ) ? $value['template'] : Helper::$defaults['template'];
		$selected_bullet_type = isset( $value['bullet_type'] ) && ! empty( $value['bullet_type'] ) ? $value['bullet_type'] : Helper::$defaults['bullet_type'];
		$contents = isset( $value['contents'] ) && ! empty( $value['contents'] ) && is_array( $value['contents'] ) ? $value['contents'] : array(); 
		$active_id = isset( $value['active'] ) && ! empty( $value['active'] ) ? $value['active'] : false; 
		?>
		<div class="afb-content-wrapper">
			<div class="meta-box-controls">
				<div class="ctrl ctrl-accordion-type">
					<h3 class="section-title"><?php echo esc_html__( 'Accordion Type', 'a-faq-builder' ); ?></h3>
					<ul>
						<li>
							<input type="radio" name="afb_data[type]" id="afb-type-content" value="content" <?php echo $type && 'content' === $type ? esc_attr( 'checked' ) : ''; ?> >
							<label for="afb-type-content"><?php echo esc_html__( 'Content', 'a-faq-builder' ); ?></label>
						</li>
						<li>
							<input type="radio" name="afb_data[type]" id="afb-type-post" value="post" disabled="disabled">
							<label for="afb-type-post"><?php echo esc_html__( 'Posts (Coming Soon)', 'a-faq-builder' ); ?></label>
						</li>
					</ul>
				</div>
				<div class="ctrl ctrl-template">
					<h3 class="section-title"><?php echo esc_html__( 'Template', 'a-faq-builder' ); ?></h3>
					<select name="afb_data[template]" id="afaqbuilder_template">
						<?php
						foreach( Helper::get_all_templates() as $key => $template ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php echo $key == $selected_template_id ? esc_attr( 'selected' ) : ''; ?> <?php echo $key > 3 ? esc_attr( 'disabled' ) : ''; ?>><?php echo esc_html( $template ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div class="ctrl ctrl-bullet-type">
					<h3 class="section-title"><?php echo esc_html__( 'Bullet Type', 'a-faq-builder' ); ?></h3>
					<ul>
						<li>
							<input type="radio" name="afb_data[bullet_type]" id="afb-bullet-icon" value="icon" <?php echo $selected_bullet_type && 'icon' === $selected_bullet_type ? esc_attr( 'checked' ) : ''; ?> disabled>
							<label for="afb-bullet-icon"><?php echo esc_html__( 'Icon (Coming Soon)', 'a-faq-builder' ); ?></label>
						</li>
						<li>
							<input type="radio" name="afb_data[bullet_type]" id="afb-bullet-number" value="number" <?php echo $selected_bullet_type && 'number' === $selected_bullet_type ? esc_attr( 'checked' ) : ''; ?>>
							<label for="afb-bullet-number"><?php echo esc_html__( 'Number', 'a-faq-builder' ); ?></label>
						</li>
						<li>
							<input type="radio" name="afb_data[bullet_type]" id="afb-bullet-none" value="none" <?php echo $selected_bullet_type && 'none' === $selected_bullet_type ? esc_attr( 'checked' ) : ''; ?>>
							<label for="afb-bullet-none"><?php echo esc_html__( 'None', 'a-faq-builder' ); ?></label>
						</li>
					</ul>
				</div>
				<div class="ctrl ctrl-expand-collapse">
					<ul>
						<li><span class="button button-primary afb_data_expand_all"><?php echo esc_html__( 'Expand All', 'a-faq-builder' ); ?></span></li>
						<li><span class="button button-primary afb_data_collapse_all"><?php echo esc_html__( 'Collapse All', 'a-faq-builder' ); ?></span></li>
				</div>
			</div>
			<div class="content-area">
				<div class="clonable-content" style="display: none;">
					<li id="clonable-item" class="afb--item afb-clonable-item">
						<div class="afb--item-wrapper">
							<div class="item-header">
								<div class="afb--ls">
									<h3 class="item-counter"><?php echo esc_html__( 'Item #', 'a-faq-builder' ); ?></h3>
								</div>
								<div class="afb--rs">
									<span class="dashicons dashicons-move handle"></span>
									<!-- <span class="dashicons dashicons-arrow-up move-up"></span>
									<span class="dashicons dashicons-arrow-down move-down"></span>
									<span class="dashicons dashicons-editor-code expand-handle"></span> -->
								</div>
							</div>
							<div class="item-body">
								<div class="row">
									<label data-target="title-label"><?php echo esc_html__( 'Title', 'a-faq-builder' ); ?></label>
									<input type="text" data-target="title-input">
								</div>
								<div class="row">
									<label data-target="content-label"><?php echo esc_html__( 'Content', 'a-faq-builder' ); ?></label>
									<textarea style="width:100%" rows="5" data-target="content-input"></textarea>
								</div>
							</div>
						</div>
					</li>
				</div>
				<ul id="afbItems" class="afb--items">
					<?php
					if ( count( $contents ) > 0 ) {
						foreach( $contents as $key => $val ) {
							$title = isset( $val['title'] ) && ! empty( $val['title'] ) ? $val['title'] : '';
							$content = isset( $val['content'] ) ? $val['content'] : '';
							?>
							<li id="item-<?php echo esc_attr( $key ); ?>" class="afb--item afb--item-<?php echo esc_attr( $key ); ?>" data-id="<?php echo esc_attr( $key ); ?>">
								<div class="afb--item-wrapper">
									<div class="item-header">
										<div class="afb--ls">
											<div class="active-handle">
												<input type="radio" name="afb_data[active]" id="afb_data[contents][<?php echo esc_attr( $key ); ?>][active]" value="<?php echo esc_attr( $key ); ?>" <?php echo $active_id == $key ? esc_attr( 'checked' ) : ''; ?>>
												<label for="afb_data[contents][<?php echo esc_attr( $key ); ?>][active]">
													<span class="circle"></span>
													<span class="label-text"><?php echo esc_html__( 'Active', 'a-faq-builder' )?></span>
												</label>
											</div>
											<h3 class="item-counter"><?php echo empty( $title ) ? esc_html__( 'New Item', 'a-faq-builder' ) : esc_html( $title ); ?></h3>
										</div>
										<div class="afb--rs">
											<span class="hover-control">
												<span class="dashicons dashicons-admin-page clone" title="Clone"></span>
												<span class="dashicons dashicons-trash trash" title="Delete"></span>
											</span>
											<span class="dashicons dashicons-move handle" title="Drag"></span>
											<span class="dashicons dashicons-arrow-up move-up" title="Move Up"></span>
											<span class="dashicons dashicons-arrow-down move-down" title="Move Down"></span>
											<span class="dashicons dashicons-editor-code expand-handle" title="Expand/Collapse"></span>
										</div>
									</div>
									<div class="item-body">
										<div class="row">
											<label data-target="title-label" for="afb_data[contents][<?php echo esc_attr( $key ); ?>][title]"><?php echo esc_html__( 'Title', 'a-faq-builder' ); ?></label>
											<input type="text" data-target="title-input" id="afb_data[contents][<?php echo esc_attr( $key ); ?>][title]" name="afb_data[contents][<?php echo esc_attr( $key ); ?>][title]" value="<?php echo esc_attr( $title ); ?>">
										</div>
										<div class="row">
											<label data-target="content-label" for="afb_data[contents][<?php echo esc_attr( $key ); ?>][content]"><?php echo esc_html__( 'Content', 'a-faq-builder' ); ?></label>
											<textarea style="width:100%" rows="5" data-target="content-input" id="afb_data[contents][<?php echo esc_html( $key ); ?>][content]" name="afb_data[contents][<?php echo esc_attr( $key ); ?>][content]"><?php echo esc_textarea( $content ); ?></textarea>
										</div>
									</div>
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul>
				<a href="#" id="add-new-faq-item" class="button button-primary button-large" data-next="<?php echo esc_attr( count( $contents ) ); ?>"><?php echo esc_html__( 'Add new item', 'a-faq-builder' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	* When the post is saved, saves our custom data.
	*
	* @param int $post_id
	*/
	public function save_afb_content_meta_box_data( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['afb_content_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['afb_content_nonce'], 'afb_content_nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'accordion_faq' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

		}

		/* OK, it's safe for us to save the data now. */

		// Make sure that it is set.
		if ( ! isset( $_POST['afb_data'] ) ) {
			return;
		}
		if ( ! isset( $_POST['afb_data']['contents'] ) ) {
			return;
		}

		// Sorting faq items if they need to be sorted
		$sorted_values = array_map( function( $v ){ return $v; }, array_values( $_POST['afb_data']['contents'] ) );

		// Re-assigning sorted faq items into original array
		$_POST['afb_data']['contents'] = $sorted_values;

		// Sanitize user input.
		$afb_data = Helper::recursive_sanitize_text_field( $_POST['afb_data'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, '_afb_content', $afb_data );
	}

	public function shortcode_genarator( $column_key, $post_id ) {
		if ( $column_key == 'shortcode' ) {
				$output = '<code id="afb-shortcode-'. esc_attr( $post_id ) .'">[A_FAQ_Builder id="' . esc_attr( $post_id ) . '"]</code>';
				$output .= '<span id="afb-notify-'. esc_attr( $post_id ) .'" class="afb-notify">' . esc_html__( 'Copied to clipboard', 'a-faq-builder' ) . '</span>';
				echo wp_kses_post( $output );
		}
	}

	public function afb_shortcode_meta_box_callback( $post ) {
		ob_start();
		?>
		<div class="shortcode">
			<code id="afb-shortcode-<?php echo esc_attr( $post->ID ); ?>">[A_FAQ_Builder id="<?php echo esc_attr( $post->ID ); ?>"]</code>
			<span id="afb-notify-<?php echo esc_attr( $post->ID ); ?>" class="afb-notify"><?php echo esc_html__( 'Copied to clipboard', 'a-faq-builder' ); ?></span>
		</div>
		<?php
		$html = ob_get_clean();
		echo wp_kses_post( $html );
	}
}
