<?php
/**
 * Setup Wizard Agent — full-page chat entry point for first-time onboarding.
 *
 * @since      1.0.277
 * @package    RankMath
 * @subpackage RankMath
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Agent;

use RankMath\Helper;
use RankMath\KB;
use RankMath\Helpers\Param;
use RankMath\Traits\Hooker;
use RankMath\Traits\Grnd_Provider;
use RankMath\Traits\Connect_Cta;
use GroupOne\WapClient\ChatWidget;

defined( 'ABSPATH' ) || exit;

/**
 * Setup_Wizard_Agent class.
 */
class Setup_Wizard_Agent {

	use Hooker;
	use Grnd_Provider;
	use Connect_Cta;

	/**
	 * Admin page slug.
	 */
	const PAGE_SLUG = 'rank-math-wizard-agent';

	/**
	 * Product slug. Must match a role mapped on the WAP backend.
	 */
	const PRODUCT = Agent::PRODUCT;

	/**
	 * WAP backend base URL the browser talks to directly.
	 */
	const SERVER_URL = Agent::SERVER_URL;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->action( 'init', 'register_chat_page' );
		$this->action( 'admin_enqueue_scripts', 'enqueue_assets' );
	}

	/**
	 * Enqueue the wizard stylesheet, only on this page.
	 */
	public function enqueue_assets() {
		if ( self::PAGE_SLUG !== Param::get( 'page' ) ) {
			return;
		}

		wp_enqueue_style( 'rank-math-wizard', rank_math()->plugin_url() . 'assets/admin/css/setup-wizard.css', [ 'wp-admin', 'buttons', 'wp-components', 'rank-math-common' ], rank_math()->version );
	}

	/**
	 * Register the Setup Wizard Agent chat page: hidden menu, bare full-page render.
	 */
	public function register_chat_page() {
		if ( ! class_exists( '\WapClient' ) ) {
			return;
		}

		\WapClient::register_chat_page(
			[
				'menu_slug'            => self::PAGE_SLUG,
				'page_title'           => esc_html__( 'Setup Wizard', 'seo-by-rank-math' ),
				'menu_title'           => esc_html__( 'Setup Wizard', 'seo-by-rank-math' ),
				'hidden_admin_menu'    => true,
				'render_mode'          => 'standalone',
				'standalone_shell_css' => false,
				'render'               => [ $this, 'render_page' ],
				'product'              => self::PRODUCT,
				'server_url'           => self::SERVER_URL,
				'page_context'         => 'setup-assistant',
				'grnd_provider'        => [ $this, 'acquire_grnd' ],
				'terms_url'            => KB::get( 'terms-and-conditions', 'Setup Wizard Agent Terms Link' ),
				'layout'               => [
					'width'  => 'boxed',
					'chrome' => 'card',
				],
			]
		);
	}

	/**
	 * Render the page body: classic wizard header, chat widget (or Connect CTA), classic-wizard link.
	 *
	 * @param array $config Credential-free page config from ChatWidget.
	 */
	public function render_page( $config ) {
		rank_math()->tracking->track_event( 'Setup Wizard Chat Accessed', [], 'manage_options' );

		?>
		<div class="rank-math-wizard-agent-wrapper">
			<?php $this->render_header(); ?>
			<div class="rank-math-wizard-agent-chat">
				<?php
				if ( Helper::is_site_connected() ) {
					ChatWidget::render_chat_root( $config['menu_slug'] );
				} else {
					$this->render_connect_cta(
						__( 'Connect your Rank Math account for free to start the AI-powered Setup Wizard.', 'seo-by-rank-math' ),
						self::PAGE_SLUG
					);
				}
				$this->render_footer();
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * GRND provider callback: mints, seals, and exchanges the credential for a GRND.
	 *
	 * @return array{grnd:string,expires_at:int}|\WP_Error
	 */
	public function acquire_grnd() {
		return $this->acquire_grnd_for( self::PRODUCT, 'Rank Math Setup Wizard' );
	}

	/**
	 * Echo the shared wizard logo header.
	 */
	private function render_header() {
		?>
		<div class="header">
			<div class="logo text-center">
				<a href="<?php echo esc_url( KB::get( 'seo-suite', 'SW Logo' ) ); ?>" target="_blank" rel="noreferrer">
					<img src="<?php echo esc_url( rank_math()->plugin_url() . 'assets/admin/img/logo.svg' ); ?>" alt="Rank Math SEO" width="245" />
				</a>
			</div>
		</div>

		<?php
	}

	/**
	 * Echo the "Return to dashboard" / "Use the classic Setup Wizard" footer links.
	 */
	private function render_footer() {
		?>
		<div class="rank-math-wizard-agent-links">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=rank-math&view=modules' ) ); ?>">
				<?php esc_html_e( '← Return to dashboard', 'seo-by-rank-math' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=rank-math-wizard' ) ); ?>">
				<?php esc_html_e( 'Use the classic Setup Wizard →', 'seo-by-rank-math' ); ?>
			</a>
		</div>
		<?php
	}
}
