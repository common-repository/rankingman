<?php

//設定値を保存するグローバル変数
$gbSetting = null;

//設定ページを追加するためにadmin_menuをフックする
add_action( 'admin_menu','wp_rman_add_my_setting');
//アクションフックで呼ぶ関数
function wp_rman_add_my_setting() {
    add_options_page(
        'RankingManプラグイン設定',     // ページタイトル
        'RankingMan',                   // 設定メニューに表示されるメニュータイトル
        'administrator',                // 権限
        'wp_rman_setup_page',           // 設定ページのURL。options-general.php?page=wp_rman_setup_page
        'wp_rman_setting_htmlpage'      // 設定ページのHTMLをはき出す関数の定義
    );
}
//設定ページのHTML
function wp_rman_setting_htmlpage() {
    
    global $gbSetting;
    
    $gbSetting = get_option('wp_rman_user_setting');
    
    if( !$gbSetting ) {
        $gbSetting = array(
            'select_rankdesign' => 'default',
            'containerdesign'   => 'default',
            'select_mode'       => 'default',
            'select_disprank'   => 5,
            'button_action'     => 1,
            'is_rank_disp'      => 1,
        );
        update_option( 'wp_rman_user_setting', $gbSetting );
    }

?>
    <div class="wrap">
     
      <h2>RankingManプラグイン設定</h2>
     
      <form method="post" action="options.php">
     
    <?php
         settings_fields( 'wp_rman_option_group' );
         do_settings_sections( 'wp_rman_setup_page' );
         submit_button(); // 送信ボタン 
    ?>
     
      </form>
     
      <h2>RankingMan使い方</h2>
      ※ 画像付きの説明、および更に機能を拡張した RankingMan Pro は<a href="https://meet-good-one.fit/plugin/" target="_blank"> RankingMan公式ページ</a> にて公開中
      <ul style="list-style: decimal;margin-left: 1em;">
        <li>ダッシュボードメニュー -> ランキング -> ランキングカテゴリー にて、ランキングのカテゴリを作成する　例）『ドメインランキング』『レンタルサーバランキング』<br />
        この時、カテゴリのスラッグ名を必ず半角英数字で設定してください。例）スラッグ『domain_rank』
        </li>
        <li>ダッシュボードメニュー -> ランキング -> 新規追加にて、ランキングを作成する。<br />
        タイトルには、紹介するサービス名を設定。<br />
        本文には、サービスの内容を設定。（未設定の場合は非表示）<br />
        カテゴリーには、先ほど作成したランキングカテゴリーを選択。（タグおよびアイキャッチ画像は任意）<br />
        ランキング情報の順位にランキング順位を設定。<br />
        ランキング情報のリンクURLにコンバージョンリンクを設定。<br />
        ランキング情報の詳細URLに自サイトの商品詳細ページのURLを設定。<br />
        ランキング情報の価格を設定。（未設定の場合は非表示）<br />
        ボタンテキストにコンバージョンボタンのテキストを設定。（未設定の場合は「くわしくみる」が表示）<br />
        ランキング情報の評価にサービスの評価を選択。<br />
        </li>
        <li>上記以外に表示させる項目がある場合、ランキングの新規追加ページにて カスタムフィールドの「カスタムフィールドを追加」欄の「新規追加」をクリックして項目名を入力。<br />
        値に任意の値を設定して「カスタムフィールドを追加」ボタンで追加します。
        </li>
        <li>ダッシュボード -> 固定ページ -> 新規追加にて、ランキングページを作成します。<br />
        ショートコードは [wp_rman cat=XXX] （「XXX」はランキングカテゴリーのスラッグ名）でランキングが表示します。例） [wp_rman cat=domain_rank]
        </li>
        <li>
        不具合、機能追加要望がございましたら大変お手数をおかけしますが<a href="mailto:hasudon7171@gmail.com">サポート</a>までご連絡ください。
        </li>
      <ul>
      
    </div><!-- .wrap -->
<?php
}

//admin initのアクションフック
add_action( "admin_init", "wp_rman_setting_init");

//設定値を読込保存を実行するSetting API群
function wp_rman_setting_init() {
    register_setting(
        'wp_rman_option_group',              // option group
        'wp_rman_user_setting'               // option name(DB)
    );        

    add_settings_section(
        'wp_rman_section_id',                // id
        'RankingManプラグイン設定',          // title
        'wp_rman_print_section_info',        // callback
        'wp_rman_setup_page'                 // page
    );
    add_settings_field(
        'containerdesign',                   // id
        'ランキングコンテナデザイン:',       // title
        'wp_rman_containerdesign_callback',  // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
    add_settings_field(
        'select_rankdesign',                 // id
        'ランキングデザイン:',               // title
        'wp_rman_rankdesign_callback',       // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
    add_settings_field(
        'select_mode',                       // id
        'ボタン色選択:',                     // title
        'wp_rman_select_mode_callback',      // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
    add_settings_field(
        'select_disprank',                   // id
        'ランキング表示:',                   // title
        'wp_rman_select_disprank_callback',  // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
    add_settings_field(
        'is_rank_disp',                      // id
        'ランキング順位表示:',               // title
        'wp_rman_is_rank_disp_callback',     // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
    add_settings_field(
        'button_action',                     // id
        'ボタンアクション:',                 // title
        'wp_rman_button_action_callback',    // callback
        'wp_rman_setup_page',                // page
        'wp_rman_section_id'                 // Section
    );
}

// コールバック関数
function wp_rman_print_section_info() {
}

function wp_rman_containerdesign_callback() {
    
    global $gbSetting;

    $mode_val = $gbSetting['containerdesign'];
    $select_tbl = array(
        array( 'val'=>'default',    'name'=>'デフォルト'),
        array( 'val'=>'type1',      'name'=>'タイプ１'),
        array( 'val'=>'type2',      'name'=>'タイプ２'),
        array( 'val'=>'type3',      'name'=>'タイプ３'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[containerdesign]\">" . $html . "</select>";
    //作成したHTMLコードをはき出す
    echo $html;

}

function wp_rman_rankdesign_callback() {
    
    global $gbSetting;

    if(empty($gbSetting['rankdesign'])) {
        $gbSetting['rankdesign'] = '';
    }
    $mode_val = $gbSetting['rankdesign'];
    $select_tbl = array(
        array( 'val'=>'default',    'name'=>'デフォルト'),
        array( 'val'=>'type1',      'name'=>'タイプ１'),
        array( 'val'=>'type2',      'name'=>'タイプ２'),
        array( 'val'=>'type3',      'name'=>'タイプ３'),
        array( 'val'=>'type4',      'name'=>'タイプ４'),
        array( 'val'=>'type5',      'name'=>'タイプ５'),
        array( 'val'=>'type6',      'name'=>'タイプ６'),
        array( 'val'=>'type7',      'name'=>'タイプ７'),
        array( 'val'=>'type8',      'name'=>'タイプ８'),
        array( 'val'=>'type9',      'name'=>'タイプ９'),
        array( 'val'=>'type10',     'name'=>'タイプ１０'),
        array( 'val'=>'type11',     'name'=>'タイプ１１'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[rankdesign]\">" . $html . "</select>";
    //作成したHTMLコードをはき出す
    echo $html;

}

function wp_rman_select_mode_callback() {
    
    global $gbSetting;

    $mode_val = $gbSetting['select_mode'];
    $select_tbl = array(
        array( 'val'=>'default',    'name'=>'赤'),
        array( 'val'=>'blue',       'name'=>'青'),
        array( 'val'=>'green',      'name'=>'緑'),
        array( 'val'=>'orange',     'name'=>'オレンジ'),
        array( 'val'=>'gray',       'name'=>'灰'),
        array( 'val'=>'pink',       'name'=>'ピンク'),
        array( 'val'=>'yellow',     'name'=>'黄'),
        array( 'val'=>'lightblue',  'name'=>'水色'),
        array( 'val'=>'purple',     'name'=>'紫'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[select_mode]\">" . $html . "</select>";
    //作成したHTMLコードをはき出す
    echo $html;

}

function wp_rman_select_disprank_callback() {
    
    global $gbSetting;

    $mode_val = $gbSetting['select_disprank'];
    
    $select_tbl = array(
        array( 'val'=>1, 'name'=>'1位まで表示'),
        array( 'val'=>2, 'name'=>'2位まで表示'),
        array( 'val'=>3, 'name'=>'3位まで表示'),
        array( 'val'=>4, 'name'=>'4位まで表示'),
        array( 'val'=>5, 'name'=>'5位まで表示'),
        array( 'val'=>6, 'name'=>'6位まで表示'),
        array( 'val'=>7, 'name'=>'7位まで表示'),
        array( 'val'=>8, 'name'=>'8位まで表示'),
        array( 'val'=>9, 'name'=>'9位まで表示'),
        array( 'val'=>10, 'name'=>'10位まで表示'),
        array( 'val'=>11, 'name'=>'11位まで表示'),
        array( 'val'=>12, 'name'=>'12位まで表示'),
        array( 'val'=>13, 'name'=>'13位まで表示'),
        array( 'val'=>14, 'name'=>'14位まで表示'),
        array( 'val'=>15, 'name'=>'15位まで表示'),
        array( 'val'=>16, 'name'=>'16位まで表示'),
        array( 'val'=>17, 'name'=>'17位まで表示'),
        array( 'val'=>18, 'name'=>'18位まで表示'),
        array( 'val'=>19, 'name'=>'19位まで表示'),
        array( 'val'=>20, 'name'=>'20位まで表示'),
        array( 'val'=>21, 'name'=>'21位まで表示'),
        array( 'val'=>22, 'name'=>'22位まで表示'),
        array( 'val'=>23, 'name'=>'23位まで表示'),
        array( 'val'=>24, 'name'=>'24位まで表示'),
        array( 'val'=>25, 'name'=>'25位まで表示'),
        array( 'val'=>26, 'name'=>'26位まで表示'),
        array( 'val'=>27, 'name'=>'27位まで表示'),
        array( 'val'=>28, 'name'=>'28位まで表示'),
        array( 'val'=>29, 'name'=>'29位まで表示'),
        array( 'val'=>30, 'name'=>'30位まで表示'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[select_disprank]\">" . $html . "</select>";
    
    echo $html;

}

function wp_rman_is_rank_disp_callback() {
    
    global $gbSetting;
    
    $mode_val = $gbSetting['is_rank_disp'];
    $select_tbl = array(
        array( 'val'=>1, 'name'=>'ランキング順位を表示する'),
        array( 'val'=>2, 'name'=>'ランキング順位を表示しない'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[is_rank_disp]\">" . $html . "</select>";
    echo $html;

}

function wp_rman_button_action_callback() {
    
    global $gbSetting;
    
    $mode_val = $gbSetting['button_action'];
    $select_tbl = array(
        array( 'val'=>1, 'name'=>'デフォルト'),
        array( 'val'=>2, 'name'=>'色変化'),
        //array( 'val'=>3, 'name'=>'奥から背景が変更'),
    );

    $html = "";
    foreach( $select_tbl as $r ) {
        $v = $r['val'];
        $n = $r['name'];
        if( $v==$mode_val ) {
            $checked="selected";
        }
        else
        {
            $checked = "";
        }
        $html .= "<option value=\"$v\" $checked>$n</option>";
    }
    $html = "<select name=\"wp_rman_user_setting[button_action]\">" . $html . "</select>";
    echo $html;

}
