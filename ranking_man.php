<?php
/*
Plugin Name: RankingMan
Plugin URI: https://meet-good-one.fit/plugin/
Description:簡単にランキングを生成するアフィリエイトツール
Version: 1.0.3
Author: HaSSS→
Author URI: https://meet-good-one.fit/
*/

define( 'WP_RANKINGMAN',        __FILE__ );
define( 'WP_RANKINGMAN_DIR',    untrailingslashit( dirname( WP_RANKINGMAN ) ) );

require_once(WP_RANKINGMAN_DIR . '/includes/functions-custom-post.php');
require_once(WP_RANKINGMAN_DIR . '/includes/functions-custom-param.php');
require_once(WP_RANKINGMAN_DIR . '/includes/functions-plugin-settings.php');
require_once(WP_RANKINGMAN_DIR . '/includes/functions-shortcode.php');

class RankingMan {
  
    public function __construct() {

    }
}
$ranking_man = new RankingMan;

?>
