<?php
/**
 * WooCommerce Compatibility.
 *
 * @package zakra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function zakra_woocommerce_setup() {

	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

}
add_action( 'after_setup_theme', 'zakra_woocommerce_setup' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 *
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function zakra_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'zakra_woocommerce_active_body_class' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'zakra_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function zakra_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'zakra_woocommerce_wrapper_before' );

if ( ! function_exists( 'zakra_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function zakra_woocommerce_wrapper_after() {
		?>
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'zakra_woocommerce_wrapper_after' );

if ( ! function_exists( 'zakra_woocommerce_header_add_to_cart_fragment' ) ) {
	/**
	 * After Content.
	 *
	 * @param array $fragments Section to refresh via AJAX.
	 *
	 * WooCommerce shopping cart.
	 *
	 * @return array
	 */
	function zakra_woocommerce_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		?>

		<a class="cart-page-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>"
		   title="<?php esc_attr_e( 'View your shopping cart', 'zakra' ); ?>">
			<i class="tg-icon tg-icon-shopping-cart"></i>
			<span class="count">
		<?php
		printf(
			/* translators: number of items in the mini cart. */
			'%d',
			// @codingStandardsIgnoreStart
			WC()->cart->get_cart_contents_count()
			// @codingStandardsIgnoreEnd
		);
		?>
		</span>
		</a>

		<?php
		$fragments['.cart-page-link'] = ob_get_clean();

		return $fragments; // WPCS: CSRF ok.
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'zakra_woocommerce_header_add_to_cart_fragment' );

if ( ! function_exists( 'zakra_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return string
	 */
	function zakra_woocommerce_cart_link() {

		$output          = '';
		$output          .= '<a class="cart-page-link" href="' . esc_url( wc_get_cart_url() ) . '" title="' . __( 'View your shopping cart', 'zakra' ) . '">';
		$item_count_text = sprintf(
		/* translators: number of items in the mini cart. */
			'%d',
			WC()->cart->get_cart_contents_count()
		);
		$output          .= '<i class="tg-icon tg-icon-shopping-cart"></i>';
		$output          .= '<span class="count">' . esc_html( $item_count_text ) . '</span>';
		$output          .= '</a>';

		return $output;

	}
}

if ( ! function_exists( 'zakra_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return string
	 */
	function zakra_woocommerce_header_cart() {

		$output = '';

		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}

		$output .= '<li class="tg-menu-item tg-menu-item-cart ' . $class . '">';
		$output .= zakra_woocommerce_cart_link();
		$output .= '</li>';

		return $output;

	}
}
