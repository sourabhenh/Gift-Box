<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php /** * The public-facing functionality of the plugin. */
class Mastery_Box_Direct_Public {
    private $plugin_name;
    private $version;
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    public function enqueue_styles() {
        if ( is_admin() ) { return; }
        $should = false;
        if ( is_singular() ) {
            $post = get_post();
            if ( $post ) {
                $should = ( has_shortcode( $post->post_content, 'masterybox_direct_game' ) || has_shortcode( $post->post_content, 'masterybox_direct_result' ) );
            }
        }
        if ( ! $should ) { return; }

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mastery-box-direct-public.css', array(), $this->version, 'all' );
    }
    public function enqueue_scripts() {
        if ( is_admin() ) { return; }
        $should = false;
        if ( is_singular() ) {
            $post = get_post();
            if ( $post ) {
                $should = ( has_shortcode( $post->post_content, 'masterybox_direct_game' ) || has_shortcode( $post->post_content, 'masterybox_direct_result' ) );
            }
        }
        if ( ! $should ) { return; }

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mastery-box-direct-public.js', array('jquery'), $this->version, true );

        // Try to locate a page containing the result shortcode; fall back to /game-result/
        $result_url = '';
        $pages = get_pages( array( 'post_status' => array('publish') ) );
        if ( !empty( $pages ) ) {
            foreach ( $pages as $p ) {
                if ( strpos( $p->post_content, '[masterybox_direct_result]' ) !== false ) {
                    $result_url = get_permalink( $p->ID );
                    break;
                }
            }
        }
        if ( empty( $result_url ) ) {
            $result_url = home_url( '/game-result/' );
        }

        wp_localize_script(
            $this->plugin_name,
            'mastery_box_direct_ajax',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'mastery_box_direct_nonce' ),
                'result_page_url' => $result_url,
            )
        );
    }
    public function init_shortcodes() {
        add_shortcode( 'masterybox_direct_game', array( $this, 'display_game_shortcode' ) );
        add_shortcode( 'masterybox_direct_result', array( $this, 'display_result_shortcode' ) );
    }
    public function display_game_shortcode( $atts ) {
        $atts = shortcode_atts( array( 'boxes' => get_option( 'mastery_box_direct_number_of_boxes', 3 ) ), $atts );
        ob_start();
        $this->display_game( $atts );
        return ob_get_clean();
    }
    private function display_game( $atts ) {
        $num_boxes = max( 1, min( 10, intval( $atts[ 'boxes' ] ) ) );
        if ( $num_boxes < 1 )$num_boxes = 3;
        if ( $num_boxes > 10 )$num_boxes = 10; // Get box images 
        $default_box_image = get_option( 'mastery_box_direct_default_box_image', '' );
        $box_images = get_option( 'mastery_box_direct_box_images', array() );
        if ( !is_array( $box_images ) ) {
            $box_images = array();
        }
        echo '<div id="mastery-box-game-container">';
        echo '<div id="mastery-box-boxes" class="boxes-container">';
        for ( $i = 1; $i <= $num_boxes; $i++ ) {
            $img_url = isset( $box_images[ $i ] ) ? $box_images[ $i ] : $default_box_image;
            echo '<div class="mastery-box" data-box="' . $i . '">';
            echo '<div class="box-inner">';
            echo '<div class="box-front">';
            if ( !empty( $img_url ) ) {
                echo '<div class="box-art"><img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( sprintf( __( 'Box %d', 'mastery-box-direct' ), $i ) ) . '" /></div>';
            } else {
                echo '<div class="box-number">' . $i . '</div>';
            }
            echo '</div>';
            echo '<div class="box-back"><div class="box-content"></div></div>';
            echo '</div></div>';
        }
        echo '</div>';
        echo '<div id="mastery-box-result" style="display: none;">';
        echo '<div id="result-content"></div>';
        echo '<button id="play-again-btn" style="display: none;">' . __( 'Play Again', 'mastery-box-direct' ) . '</button>';
        echo '</div></div>';
    }
    public function display_result_shortcode() {
        if ( !session_id() ) {
            session_start();
        }
        $result = isset( $_SESSION[ 'mastery_box_direct_last_result' ] ) ? $_SESSION[ 'mastery_box_direct_last_result' ] : null;
        $win_message = get_option( 'mastery_box_direct_win_message', 'Congratulations! You won!' );
        $lose_message = get_option( 'mastery_box_direct_lose_message', 'Better luck next time!' );
        ob_start();
        echo '<div class="mastery-box-result-page">';
        if ( $result ) {
            if ( !empty( $result[ 'is_winner' ] ) ) {
                echo '<h2>' . esc_html( $win_message ) . '</h2>';
                if ( !empty( $result[ 'gift_image' ] ) ) {
                    echo '<div class="gift-image"><img src="' . esc_url( $result[ 'gift_image' ] ) . '" alt="' . esc_attr( $result[ 'gift_name' ] ?? 'Gift' ) . '"></div>';
                }
                echo '<div class="winner-details">';
                if ( !empty( $result[ 'gift_name' ] ) ) {
                    echo '<p>' . esc_html( $result[ 'gift_name' ] ) . '</p>';
                }
                if ( !empty( $result[ 'message' ] ) ) {
                    echo '<p>' . esc_html( $result[ 'message' ] ) . '</p>';
                }
                echo '</div>';
            } else {
                echo '<h2>' . esc_html( $lose_message ) . '</h2>';
                echo '<p>' . esc_html( 'Try again for your chance to win!' ) . '</p>';
            }
        } else {
            echo '<p>' . __( 'No game result found. Please play the game first.', 'mastery-box-direct' ) . '</p>';
        }
        echo '</div>';
        return ob_get_clean();
    }
    public function handle_game_play() {
        if ( !wp_verify_nonce( $_POST[ 'nonce' ], 'mastery_box_direct_nonce' ) ) {
            wp_send_json_error( __( 'Security check failed', 'mastery-box-direct' ) );
        }
        $chosen_box = intval( $_POST[ 'box' ] );
        $winning_gift = Mastery_Box_Direct_Database::determine_winner();
        $is_winner = !is_null( $winning_gift );
        $entry_data = array( 'gift_won' => $is_winner ? $winning_gift->id : null, 'is_winner' => $is_winner ? 1 : 0, 'chosen_box' => $chosen_box, 'ip_address' => $this->get_client_ip(), 'user_agent' => sanitize_text_field( $_SERVER[ 'HTTP_USER_AGENT' ] ) );
        Mastery_Box_Direct_Database::insert_entry( $entry_data );
        if ( $is_winner ) {
            $response = array( 'is_winner' => true, 'message' => $winning_gift->description, 'gift_name' => $winning_gift->name, 'gift_quality' => $winning_gift->quality, 'gift_image' => !empty( $winning_gift->gift_image ) ? esc_url_raw( $winning_gift->gift_image ) : '' );
        } else {
            $response = array( 'is_winner' => false, 'message' => get_option( 'mastery_box_direct_lose_message', __( 'Better luck next time!', 'mastery-box-direct' ) ) );
        } // Store the result in session for the results page 
        if ( !session_id() ) {
            session_start();
        }
        $_SESSION[ 'mastery_box_direct_last_result' ] = $response;
        wp_send_json_success( $response );
    }
    private function get_client_ip() {
        $ip_keys = array( 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR' );
        foreach ( $ip_keys as $key ) {
            if ( !empty( $_SERVER[ $key ] ) ) {
                foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
                    $ip = trim( $ip );
                    if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER[ 'REMOTE_ADDR' ] ?? '0.0.0.0';
    }
}