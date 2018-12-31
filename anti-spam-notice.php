<?php

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}


/**
 * Plugin Notice.
 *
 * @version 3.6.8
 */
class AntiSpamNotice {
    /* Recommend plugins.
     *
     * @since 3.6.8
     */
    protected static $sponsors = array(
        'mailoptin' => 'mailoptin/mailoptin.php',
    );

    /**
     * AntiSpamNotice constructor.
     *
     * @since 3.6.8
     */
    public function __construct() {
        // admin notices.
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
        add_action( 'network_admin_notices', array( $this, 'admin_notice' ) );

        add_action( 'admin_init', array( $this, 'dismiss_admin_notice' ) );
    }

    /**
     * Dismiss admin notice.
     *
     * @since 3.6.8
     * @access public
     *
     * @return void
     */
    public function dismiss_admin_notice() {
        if ( ! isset( $_GET['antispam_action'] ) || $_GET['antispam_action'] != 'antispam_dismiss_notice' ) {
            return;
        }

        $url = admin_url();
        update_option( 'antispam_dismiss_notice', 'true' );

        wp_redirect( $url );
        exit;
    }

    /**
     * Add admin notices.
     *
     * @since 3.6.8
     * @access public
     *
     * @return void
     */
    public function admin_notice() {
        if ( get_option( 'antispam_dismiss_notice', 'false' ) == 'true' ) {
            return;
        }

        if ( $this->is_plugin_installed( 'mailoptin' ) && $this->is_plugin_active( 'mailoptin' ) ) {
            return;
        }

        $dismiss_url = esc_url_raw(
            add_query_arg(
                array(
                    'antispam_action' => 'antispam_dismiss_notice',
                ),
                admin_url()
            )
        );

        $this->notice_css();

        $install_url = wp_nonce_url(
            admin_url( 'update.php?action=install-plugin&plugin=mailoptin' ),
            'install-plugin_mailoptin'
        );

        $activate_url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=mailoptin%2Fmailoptin.php' ),
            'activate-plugin_mailoptin/mailoptin.php' );
        ?>
        <div class="mo-admin-notice notice notice-success">
			<div class="mo-notice-row">
				<div class="mo-notice-first-half">
					<p>
						<?php
						printf(
							__( 'Free optin form plugin that will %1$sincrease your email list subscribers%2$s and keep them engaged with %1$sautomated and schedule newsletters%2$s.' ),
							'<strong>', '</strong>' );
						?>
					</p>
					<p style="font-size: 11px;">Recommended by Anti-Spam plugin</p>
				</div>
				<div class="mo-notice-other-half">
					<?php if ( ! $this->is_plugin_installed( 'mailoptin' ) ) : ?>
						<a class="button button-primary button-hero" id="mo-install-mailoptin-plugin"
						   href="<?php echo $install_url; ?>">
							<?php _e( 'Install MailOptin Now for Free!' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $this->is_plugin_installed( 'mailoptin' ) && ! $this->is_plugin_active( 'mailoptin' ) ) : ?>
						<a class="button button-primary button-hero" id="mo-activate-mailoptin-plugin"
						   href="<?php echo $activate_url; ?>">
							<?php _e( 'Activate MailOptin Now!' ); ?>
						</a>
					<?php endif; ?>
					<p>
						<a target="_blank" href="https://mailoptin.io">Learn more</a>
					</p>
				</div>
			</div>
            <a class="notice-dismiss" href="<?php echo $dismiss_url; ?>">
                
                    <span class="screen-reader-text"><?php _e( 'Dismiss this notice' ); ?>.</span>
            </a>
        </div>
        <?php
    }

    /**
     * Check if plugin is installed.
     *
     * @param $key
     *
     * @return bool
     */
    protected function is_plugin_installed( $key ) {
        $installed_plugins = get_plugins();

        return isset( $installed_plugins[ self::$sponsors[ $key ] ] );
    }

    /**
     * Check if plugin is active.
     *
     * @param $key
     *
     * @return bool
     */
    protected function is_plugin_active( $key )  {
        return is_plugin_active( self::$sponsors[ $key ] );
    }

    /**
     * Styles for notice.
     *
     * @return void
     */
    protected function notice_css() {
        ?>
        <style type="text/css">
            .mo-admin-notice {
                background: #fff;
                color: #000;
                border-left-color: #46b450;
                position: relative;
            }

			.mo-admin-notice .notice-dismiss {
				text-decoration: none;
			}
			
            .mo-admin-notice .notice-dismiss:before {
                color: #72777c;
            }

			.mo-notice-row {
				display: flex;
				flex-wrap: wrap;
				padding: 15px 0;
			}
			
            .mo-notice-first-half {
				flex-basis: 0;
				flex-grow: 1;
				flex: 0 0 66%;
				max-width: 66%;
            }

            .mo-notice-other-half {
				flex-basis: 0;
				flex-grow: 1;
				flex: 0 0 33%;
				max-width: 33%;
                text-align: center;
				padding-top: 20px;
            }

            .mo-notice-first-half p {
                font-size: 14px;
				line-height: 18px;
            }
			
			@media (max-width: 768px) {
				.mo-notice-first-half,
				.mo-notice-other-half {
					flex: 0 0 100%;
					max-width: 100%;
				}
			}
			
        </style>
        <?php
    }
}
