<?php
/**
 * Template for FAQ Builder
 *
 * @package a-faq-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $post;

$title_visibility = apply_filters( 'afq_title_show', __return_true(), $args['id'], $post );

$template = isset( $value['template'] ) && !empty( $value['template'] ) ? $value['template'] : false;
$selected_bullet_type = isset( $value['bullet_type'] ) && ! empty( $value['bullet_type'] ) ? $value['bullet_type'] : false;
$active_id = isset( $value['active'] ) && !empty( $value['active'] ) ? $value['active'] : false;
ob_start();
?>
<div class="a-faq-builder <?php echo $template ? esc_attr( 'temp' . $template ) : ''; ?>">
	<div class="afb-inner-wrapper">
		<?php
		if( $title_visibility ) :
		?>
		<h3 class="afb-title"><?php echo get_the_title($args['id']); ?></h3>
		<?php endif; ?>
		<ul class="afb-items">
			<?php
			if ( isset( $value['contents'] ) && ! empty( $value['contents'] ) && is_array( $value['contents'] ) && count( $value['contents'] ) > 0 ) :
				foreach( $value['contents'] as $key => $item ) :
					$item_title = isset( $item['title'] ) ? $item['title'] : '';
					$item_content = isset( $item['content'] ) ? $item['content'] : '';
					if ( ! empty( $item_title ) && ! empty( $item_content ) ) :
						?>
						<li id="afb-item-<?php echo esc_attr( $key ); ?>" class="afb-item afb-item-<?php echo esc_attr( $key ); ?> <?php echo $active_id == $key ? esc_attr( 'active' ) : ''; ?>">
							<div class="afb-item-inner">
								<div class="item-header">
										<a class="afb-item-title" href="#afb-item-<?php echo esc_attr( $key ); ?>">
											<?php if ( $selected_bullet_type && 'number' === $selected_bullet_type ) { ?>
											<span class="number"><?php echo esc_attr( ( $key + 1 ) . '.' ); ?></span>
											<?php } ?>
											<span class="afb-title-wrap"><?php echo esc_html( $item_title ); ?></span>
											<span class="right-icon">
												<i class="fa-solid fa-plus"></i>
												<i class="fa-solid fa-minus"></i>
											</span>
										</a>
								</div>
								<div class="afb-item-body">
									<div class="afb-item-content">
										<p><?php echo esc_html( $item_content ); ?></p>
									</div>
								</div>
							</div>
						</li>
						<?php
					endif;
				endforeach;
			endif;
			?>
		</ul>
	</div>
	</div>
<?php
$html = ob_get_clean();
