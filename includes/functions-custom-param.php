<?php 
/*
 * SetCustomArea
 */
add_action('admin_menu', 'wp_rman_add_custom_inputbox');
add_action('save_post',  'wp_rman_save_custom_postdata');

function wp_rman_add_custom_inputbox() {
    add_meta_box( 'product_id',     'ランキング情報',     'custom_wp_rman',   'wp_rman',  'normal' );
}
/*
 * CustomArea
 */
function custom_wp_rman(){
  
    global $post;

?>
    <style>
    .ranking_set_item{
      min-width:90px;
      display:inline-block;
    }
    </style>
<?php

    echo '<input type="hidden" name="wp_rman_noncename" id="wp_rman_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '<span class="ranking_set_item">順位</span><input type="text" name="rank_num" size="2" value="'.get_post_meta($post->ID,'rank_num',true).'"><br>';
    echo '<span class="ranking_set_item">リンクURL</span><input type="text" name="rank_linkurl" size="60" value="'.get_post_meta($post->ID,'rank_linkurl',true).'"><br>';
    echo '<span class="ranking_set_item">詳細URL</span><input type="text" name="rank_detailurl" size="60" value="'.get_post_meta($post->ID,'rank_detailurl',true).'"><br>';
    echo '<span class="ranking_set_item">価格</span><input type="text" name="rank_price" size="10" value="'.get_post_meta($post->ID,'rank_price',true).'"><br>';
    echo '<span class="ranking_set_item">ボタンテキスト</span><input type="text" name="button_text" size="40" value="'.get_post_meta($post->ID,'button_text',true).'"><br>';
    echo '<span class="ranking_set_item">評価</span><select name="rank_rate">';
    echo '<option name="05"  ' . ((get_post_meta($post->ID,'rank_rate',true) == 0.5)?'selected':'') . ' >0.5</option>';
    echo '<option name="1"   ' . ((get_post_meta($post->ID,'rank_rate',true) == 1 || !get_post_meta($post->ID,'rank_rate',true))?'selected':'') . ' >1</option>';
    echo '<option name="1.5" ' . ((get_post_meta($post->ID,'rank_rate',true) == 1.5)?'selected':'') . ' >1.5</option>';
    echo '<option name="2"   ' . ((get_post_meta($post->ID,'rank_rate',true) == 2)  ?'selected':'') . ' >2</option>';
    echo '<option name="2.5" ' . ((get_post_meta($post->ID,'rank_rate',true) == 2.5)?'selected':'') . ' >2.5</option>';
    echo '<option name="3"   ' . ((get_post_meta($post->ID,'rank_rate',true) == 3)  ?'selected':'') . ' >3</option>';
    echo '<option name="3.5" ' . ((get_post_meta($post->ID,'rank_rate',true) == 3.5)?'selected':'') . ' >3.5</option>';
    echo '<option name="4"   ' . ((get_post_meta($post->ID,'rank_rate',true) == 4)  ?'selected':'') . ' >4</option>';
    echo '<option name="4.5" ' . ((get_post_meta($post->ID,'rank_rate',true) == 4.5)?'selected':'') . ' >4.5</option>';
    echo '<option name="5"   ' . ((get_post_meta($post->ID,'rank_rate',true) == 5)  ?'selected':'') . ' >5</option>';
    echo '</select>';

}

/*
 * Data Save
 */
// 保存メソッド
function wp_rman_save($post_id, $data_name) {
  
  $data = '';
  
  if(!empty($_POST[$data_name])) {
    $_POST[$data_name] = wp_rman_validate($data_name, $_POST[$data_name]);
  }
  if(isset($_POST[$data_name])) {
    $data = wp_rman_validate($data_name, $_POST[$data_name]); 
  }
  else {
    $data = "";
  }
  
  //-1になると項目が変わったことになるので、項目を更新する
  if( $data != get_post_meta($post_id, $data_name, true)) {
    
    update_post_meta($post_id, $data_name,$data);
  } elseif($data === ""){
    delete_post_meta($post_id, $data_name, get_post_meta($post_id,$data_name,true));
  }
}

function wp_rman_save_custom_postdata($post_id){
  
  wp_rman_save($post_id, 'rank_num');
  wp_rman_save($post_id, 'rank_linkurl');
  wp_rman_save($post_id, 'rank_detailurl');
  wp_rman_save($post_id, 'rank_price');
  wp_rman_save($post_id, 'button_text');
  wp_rman_save($post_id, 'rank_rate');
}

function wp_rman_validate($name, $param) {
    
    $return = '';
    
    if($name == 'rank_num' || 
       $name == 'rank_price' || 
       $name == 'button_text' || 
       $name == 'rank_rate') 
    {
        $return = sanitize_text_field($param);
    }
    elseif($name == 'rank_linkurl' || 
           $name == 'rank_detailurl')
    {
        $return = esc_url($param);
    }
    else {
        $return = sanitize_text_field($param);
    }
    
    return $return;
}
