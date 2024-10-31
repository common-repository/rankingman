<?php 
add_action('init', 'wp_rman_register_post_type_and_taxonomy');

function wp_rman_register_post_type_and_taxonomy() {
  register_post_type(
    'wp_rman',
    array(
      'labels' => array(
        'name'          => 'ランキング',
        'add_new_item'  => 'ランキングの新規追加',
        'edit_item'     => 'ランキングの編集',
      ),
      'menu_icon'       => 'dashicons-awards',
      'public'          => true,
      'hierarchical'    => true, // 階層型にするか否か
      'has_archive'     => false, // アーカイブ（一覧表示機能）を持つか否か
      'supports' => array( // カスタム投稿ページに表示される項目
        'title',
        'editor',
        'thumbnail',
        'excerpt' ,
        'custom-fields',
        'author',
      ),
      'menu_position' => 6, // ダッシュボードで投稿の下に表示
      'rewrite' => array('with_front' => false), // パーマリンクの編集（newsの前の階層URLを消して表示）
    )
  );

  /* カテゴリタクソノミー(カテゴリー分け)を使えるように設定する */

  register_taxonomy(
    'ranking_category',
    'wp_rman',
    array(
      'hierarchical' => true,   // カテゴリ
      'update_count_callback' => '_update_post_term_count',
      'label'           => 'ランキングカテゴリー',
      'singular_label'  => 'ランキングカテゴリー',
      'public'          => true,
      'show_ui'         => true
    )
  );

  /* カスタムタクソノミー、タグを使えるようにする */

  register_taxonomy(
    'ranking_tag',
    'wp_rman',
    array(
      'hierarchical'            => false,  // タグ
      'update_count_callback'   => '_update_post_term_count',
      'label'                   => 'ランキングタグ',
      'singular_label'          => 'ランキングタグ',
      'public'                  => true,
      'show_ui'                 => true
    )
  );

}
