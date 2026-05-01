<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

//图片上传
add_action('wp_ajax_nopriv_img_upload', 'io_img_upload');  
add_action('wp_ajax_img_upload', 'io_img_upload');
function io_img_upload(){  
    // 添加 nonce 检查
    if ( ! check_ajax_referer( 'io_img_upload_nonce', 'nonce', false ) ) {
        echo '{"status":4,"msg":"' . __('安全验证失败！', 'i_theme') . '"}';
        exit();
    }

    $extArr = array("jpg", "png", "jpeg");
    $file = $_FILES['files'];
    if ( !empty( $file ) ) {
        // 验证实际上传文件类型（不只是扩展名）
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mimes = array(
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
        );

        // 检查MIME类型
        if ( ! isset($allowed_mimes[$mime_type]) ) {
            echo '{"status":4,"msg":"' . __('图片类型只能是jpeg,jpg,png！', 'i_theme') . '"}';
            exit();
        }

        // 检查扩展名
        $basename = $file['name'];
        $baseext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
        if ( ! in_array($baseext, $extArr, true) ) {
            echo '{"status":4,"msg":"' . __('图片类型只能是jpeg,jpg,png！', 'i_theme') . '"}';
            exit();
        }

        // 使用exif_imagetype进一步验证（防止伪造MIME）
        if ( ! in_array(exif_imagetype($file['tmp_name']), array(IMAGETYPE_JPEG, IMAGETYPE_PNG), true) ) {
            echo '{"status":4,"msg":"' . __('图片类型只能是jpeg,jpg,png！', 'i_theme') . '"}';
            exit();
        }

        // 检查文件大小（最大1MB）
        if ( $file['size'] > 1024 * 1024 ) {
            echo '{"status":4,"msg":"' . __('图片大小不能超过1M', 'i_theme') . '"}';
            exit();
        }

        $wp_upload_dir = wp_upload_dir();                                     // 获取上传目录信息
        $dataname = date("YmdHis_").substr(md5(time()), 0, 8) . '.' . $baseext;
        $filename = $wp_upload_dir['path'] . '/' . $dataname;

        // 使用move_uploaded_file替代rename（更安全）
        if ( ! move_uploaded_file($file['tmp_name'], $filename) ) {
            echo '{"status":4,"msg":"' . __('图片上传失败！', 'i_theme') . '"}';
            exit();
        }

        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $dataname,      // 外部链接的 url
            'post_mime_type' => $mime_type,                                // 文件 mime 类型
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $basename ),  // 附件标题，采用去除扩展名之后的文件名
            'post_content'   => '',                                           // 文章内容，留空
            'post_status'    => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $filename );          // 插入附件信息
        if($attach_id != 0){
            require_once( ABSPATH . 'wp-admin/includes/image.php' );          // 确保包含此文件，因为wp_generate_attachment_metadata（）依赖于此文件。
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );        // 生成附件的元数据，并更新数据库记录。
            print_r(json_encode(array('status'=>1,'msg'=>__('图片添加成功','i_theme'),'data'=>array('id'=>$attach_id,'src'=>wp_get_attachment_url( $attach_id ),'title'=>$basename))));
            exit();
        }else{
            echo '{"status":4,"msg":"'.__('图片上传失败！','i_theme').'"}';
            exit();
        }
    } 
}

//删除图片
add_action('wp_ajax_nopriv_img_remove', 'io_img_remove');  
add_action('wp_ajax_img_remove', 'io_img_remove');
function io_img_remove(){    
    // 添加 nonce 检查
    if ( ! check_ajax_referer( 'io_img_remove_nonce', 'nonce', false ) ) {
        echo '{"status":4,"msg":"' . __('安全验证失败！', 'i_theme') . '"}';
        exit();
    }

    $attach_id = isset($_POST["id"]) ? absint($_POST["id"]) : 0;
    if( empty($attach_id) ){
        echo '{"status":3,"msg":"'.__('没有上传图像！','i_theme').'"}';
        exit;
    }
    // 添加权限检查：只有上传文件的用户或管理员可以删除
    if ( ! current_user_can('upload_files') && get_post_field('post_author', $attach_id) != get_current_user_id() ) {
        echo '{"status":4,"msg":"'.__('权限不足！','i_theme').'"}';
        exit;
    }
    if ( false === wp_delete_attachment( $attach_id ) )
        echo '{"status":4,"msg":"'.sprintf(__('图片 %s 删除失败！','i_theme'), $attach_id).'"}';
    else
        echo '{"status":1,"msg":"'.__('删除成功！','i_theme').'"}';
    exit; 
}

//提交文章
add_action('wp_ajax_nopriv_contribute_post', 'io_contribute');  
add_action('wp_ajax_contribute_post', 'io_contribute');
function io_contribute(){  
    // 添加 nonce 检查
    if ( ! check_ajax_referer( 'io_contribute_nonce', 'io_nonce', false ) ) {
        error('{"status":4,"msg":"' . __('安全验证失败！', 'i_theme') . '"}');
    }

    $delay = 40; 
    if( isset($_COOKIE["tougao"]) && ( time() - intval($_COOKIE["tougao"]) ) < $delay ){
        error('{"status":2,"msg":"'.sprintf(__('您投稿也太勤快了吧，%s秒后再试！','i_theme'), ($delay - ( time() - intval($_COOKIE["tougao"]) )) ).'"}');
    } 
	
	//表单变量初始化
	$sites_link = isset( $_POST['tougao_sites_link'] ) ? trim(htmlspecialchars($_POST['tougao_sites_link'], ENT_QUOTES)) : '';
	$sites_sescribe = isset( $_POST['tougao_sites_sescribe'] ) ? trim(htmlspecialchars($_POST['tougao_sites_sescribe'], ENT_QUOTES)) : '';
	$title = isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'], ENT_QUOTES)) : '';
	$category = isset( $_POST['tougao_cat'] ) ? $_POST['tougao_cat'] : '0';
	$sites_ico = isset( $_POST['tougao_sites_ico'] ) ? trim(htmlspecialchars($_POST['tougao_sites_ico'], ENT_QUOTES)) : '';
	$wechat_qr = isset( $_POST['tougao_wechat_qr'] ) ? trim(htmlspecialchars($_POST['tougao_wechat_qr'], ENT_QUOTES)) : '';
	$content = isset( $_POST['tougao_content'] ) ? trim(htmlspecialchars($_POST['tougao_content'], ENT_QUOTES)) : '';
	
	// 表单项数据验证
	if ( $category == "0" ){
		error('{"status":4,"msg":"'.__('请选择分类。','i_theme').'"}');
	}
	if ( !empty(get_term_children($category, 'favorites'))){
		error('{"status":4,"msg":"'.__('不能选用父级分类目录。','i_theme').'"}');
	}
	if ( empty($sites_sescribe) || mb_strlen($sites_sescribe) > 50 ) {
		error('{"status":4,"msg":"'.__('网站描叙必须填写，且长度不得超过50字。','i_theme').'"}');
	}
	if ( empty($sites_link) && empty($wechat_qr) ){
		error('{"status":3,"msg":"'.__('网站链接和公众号二维码至少填一项。','i_theme').'"}');
	}
	elseif ( !empty($sites_link) && !preg_match('/http(s)?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $sites_link)) {
		error('{"status":4,"msg":"'.__('网站链接必须符合URL格式。','i_theme').'"}');
	}
	if ( empty($title) || mb_strlen($title) > 30 ) {
		error('{"status":4,"msg":"'.__('网站名称必须填写，且长度不得超过30字。','i_theme').'"}');
	}
	//if ( empty($content) || mb_strlen($content) > 10000 || mb_strlen($content) < 6) {
	//	error('{"status":4,"msg":"内容必须填写，且长度不得超过10000字，不得少于6字。"}');
	//}
	
	$tougao = array(
		'comment_status'   => 'closed',
		'ping_status'      => 'closed',
		//'post_author'      => 1,//用于投稿的用户ID
		'post_title'       => $title,
		'post_content'     => $content,
		'post_status'      => 'pending',
		'post_type'        => 'sites',
		//'tax_input'        => array( 'favorites' => array($category) ) //游客不可用
	);
	
	// 将文章插入数据库
	$status = wp_insert_post( $tougao );
	if ($status != 0){
		global $wpdb;
		add_post_meta($status, '_sites_sescribe', $sites_sescribe);
		add_post_meta($status, '_sites_link', $sites_link);
		add_post_meta($status, '_sites_order', '0');
		if( !empty($sites_ico))
			add_post_meta($status, '_thumbnail', $sites_ico); 
		if( !empty($wechat_qr))
			add_post_meta($status, '_wechat_qr', $wechat_qr); 
		wp_set_post_terms( $status, array($category), 'favorites'); //设置文章分类
		setcookie("tougao", time(), time()+$delay+10);
		error('{"status":1,"msg":"'.__('投稿成功！','i_theme').'"}');
	}else{
		error('{"status":4,"msg":"'.__('投稿失败！','i_theme').'"}');
	}
}
function error($ErrMsg) {
	echo $ErrMsg;
	exit;
} 
