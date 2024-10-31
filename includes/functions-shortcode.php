<?php

add_shortcode('wp_rman','wp_rman_disp');

add_action('wp_enqueue_scripts', 'wp_rman_enqueue_styles');

function wp_rman_enqueue_styles() {
  wp_enqueue_style( 'rankingman-main-style', plugins_url('/css/style.css', __FILE__ ) );
  wp_enqueue_style( 'fontawesome-style', "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" );
}


function wp_rman_disp($atts) {
    
    $meta_white_list = ['_edit_last', '_edit_lock', 'rank_num', 'rank_rate', 'rank_linkurl', 'rank_detailurl','rank_price', '_thumbnail_id', 'button_text'];
    
    $atts = shortcode_atts(array(
        "cat" => 1,
        "num" => 30
    ),$atts);

    $category  = $atts['cat'];
    $disp_num  = $atts['num'];
    $args = array(
        'post_type'      => 'wp_rman',
        'order'          => 'DESC',
        'posts_per_page' => $disp_num,
        'tax_query' => array(
            array(
                'taxonomy' => 'ranking_category',
                'field'    => 'slug',
                'terms'    => $category,
            )
        )
    );
    $posts_array = get_posts( $args );
    
    $html  = "";
    $rank_list = array();
    $mgo_entire_options = get_option('mgo_entire_options', array());
    
    if(!empty($posts_array) && count($posts_array) > 0) {
        foreach ( $posts_array as $post ) : setup_postdata( $post );
            
            $content        = $post->post_content;
            $title          = $post->post_title;
            $rate           = get_post_meta($post->ID,'rank_rate',true);
            $rank           = get_post_meta($post->ID,'rank_num',true);
            $link_url       = get_post_meta($post->ID,'rank_linkurl',true);
            $detail_url     = get_post_meta($post->ID,'rank_detailurl',true);
            $price          = get_post_meta($post->ID,'rank_price',true);
            $button_text    = get_post_meta($post->ID,'button_text',true);
            $meta_list      = get_post_meta($post->ID);
            
            $custom_item = array();
            if(!empty($meta_list) && count($meta_list)) {
                foreach($meta_list as $key => $value) {
                    
                    if(array_search($key, $meta_white_list) === false) {
                        $custom_item[$key]= $value;
                    }
                }
            }
            
            $rank_list[$rank] = array(
                'rank'       => $rank,
                'info'       => array(
                    'content'       => $content,
                    'title'         => $title,
                    'rate'          => $rate,
                    'link_url'      => $link_url,
                    'detail_url'    => $detail_url,
                    'price'         => $price,
                    'button_text'   => $button_text,
                    'post_id'       => $post->ID,
                ),
            );
            
            if(count($custom_item) > 0) {
                foreach($custom_item as $custom_key => $custom_val) {
                    $rank_list[$rank]['info']['custom'][$custom_key] = $custom_val;
                }
            }
            
        endforeach; wp_reset_postdata();
    }
    $css_mode       = get_css_setting();
    $rank_number    = get_ranking_number_setting();
    $rank_container = get_rank_containerdesign_setting();
    
    if(count($rank_list) <= 0) {
        $html = 'Enable show ranking. Please check Ranking Category and shortcodes parameter.';
        return $html;
    }
    
    foreach ($rank_list as $key => $value) {
        $id[$key] = $value['rank'];
    }
    array_multisort($id, SORT_ASC, $rank_list);
    
    if(count($rank_list) > 0) {
        
        $html .= '<div class="ranking_box">';
        
        foreach($rank_list as $data) {
            
            $rank = $data['rank'];
            
            if($rank > $rank_number) {
                break;
            }
            
            $rank_design = get_rank_design_setting();
            $is_disp_rank = get_is_rank_disp();
            $rank_str     = '';
            
            if($is_disp_rank == 1 || intval($rank) > 3) {
                // ランキング表示する
                if($rank_design == 'default') {
                    $rank_str = $rank.'位';
                }
                else {
                    $rank_str = '　　'.$rank.'位';
                }
            }

            $html .= '<div class="rank-container '.$rank_container.'">';
            $html .= '<div class="rank-inner '.$rank_container.'">';
            $html .= '<div class="rank'.$rank.' '.$css_mode.'"><span class="css_'.$rank_design.'_rank'.$rank.'">'.$rank_str.'</span>　<a href="'.$data['info']['detail_url'].'">'.$data['info']['title'].'</a></div>';
            $html .= '<div class="rankbox '.$css_mode.'">';
            $html .= '<div class="rankbox-inner '.$css_mode.'">';
            
            $html .= '<div class="float-l '.$css_mode.'">';
            $html .= '<a href="'.$data['info']['detail_url'].'">';
            
            $eyecatch_img  = get_the_post_thumbnail_url($data['info']['post_id']);
            
            if(!empty($eyecatch_img)) {
                $html .= '<img src="'.$eyecatch_img.'">';
            }
            $html .= '</a>';
            $html .= '</div>';
            
            if(!empty($data['info']['content'])) {
            
                $html .= '<div class="feature '.$css_mode.'">';
                $html .= '<span>'.$data['info']['title'].'の特徴</span>';
                $html .= '</div>';
                $html .= '<p   class="feature '.$css_mode.'">';
                $html .= $data['info']['content'];
                $html .= '</p>';
            }
            
            $html .= '<table class="rank-table">';
            
            $html .= '<tbody>';
            
            if(!empty($data['info']['price'])) {
                $html .= '<tr>';
                $html .= '<th>価格</th>';
                $html .= '<td>'.$data['info']['price'].'</td>';
                $html .= '</tr>';
            }
            
            $rate_img = str_replace('.', '', strval($data['info']['rate']));
            
            if(!empty($data['info']['rate'])) {
                $html .= '<tr>';
                $html .= '<th>評価</th>';
                $html .= '<td><img src="'.plugins_url("../images/rate_".$rate_img.".jpg", __FILE__ ).'" class="rate_image"></td>';
                $html .= '</tr>';
            }
            
            if(!empty($data['info']['custom'])) {
                foreach($data['info']['custom'] as $custome_key => $custom_val) {
                    $html .= '<tr>';
                    $html .= '<th>'.$custome_key.'</th>';
                    $html .= '<td>'.reset($custom_val).'</th>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
            
            $tag_info = get_the_terms($data['info']['post_id'], 'ranking_tag');
            
            if(!empty($tag_info)) {
                $html .= '<ul class="tag_ul">';
                foreach($tag_info as $tag) {
                    $html .= '<li class="tag_li">'.$tag->name.'</li>';
                }
                $html .= '</ul>';
            
            }
            
            if(!empty($data['info']['button_text'])) {
                $buttn_text = $data['info']['button_text'];
            }
            else {
                $buttn_text = 'くわしくみる';
            }
            
            $button_action = 'button_action_type'.get_button_action_setting();
            
            if(!empty($data['info']['link_url'])) {
                $html .= '<div class="more">';
                $html .= '<span class="official">';
                $html .= '<a href="'.$data['info']['link_url'].'" class="rm_btn" target="_blank" rel="nofollow">';
                $html .= '<span class="'.$css_mode.' '.$button_action.'">';
                $html .= $buttn_text;
                $html .= '</span></span>';
                $html .= '</a>';
                //$html .= '<a href="'.$data['info']['link_url'].'" class="rm_btn '.$css_mode.' '.$button_action.'" target="_blank" rel="nofollow">'.$buttn_text.'</a>';
                $html .= '</span>';
                $html .= '</div>';
            }
            
            $html .= '</div> <!-- rankbox-inner -->';
            $html .= '</div> <!-- rankbox -->';
            $html .= '</div> <!-- rank-inner -->';
            $html .= '</div> <!-- rank-container -->';
        }
        
        $html .= '</div>';
    }
    else {
        $html = 'Enable show ranking. Please check Ranking Category and shortcodes parameter.';
    }
    
    return $html;
}

function get_css_setting() {
    $gbSetting = get_option('wp_rman_user_setting');
    
    return $gbSetting['select_mode'];
}

function get_ranking_number_setting() {
    $gbSetting = get_option('wp_rman_user_setting');
    
    return $gbSetting['select_disprank'];
}

function get_button_action_setting() {
    $gbSetting = get_option('wp_rman_user_setting');
    
    return $gbSetting['button_action'];
}

function get_rank_design_setting() {
    $gbSetting = get_option('wp_rman_user_setting');
    
    return $gbSetting['rankdesign'];
}

function get_rank_containerdesign_setting() {
    $gbSetting = get_option('wp_rman_user_setting');
    
    return $gbSetting['containerdesign'];
}
function get_is_rank_disp() {
    $gbSetting = get_option('wp_rmanpro_user_setting');

    if(empty($gbSetting['is_rank_disp'])) {
        $is_rank_disp = 1;
    }
    else {
        $is_rank_disp = $gbSetting['is_rank_disp'];
    }
    
    return $is_rank_disp;
}
