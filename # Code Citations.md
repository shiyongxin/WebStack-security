# Code Citations

## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/Pentatonic/inc/ajax.php

```
// 生成附件的元数据，并更新数据库记录。
	        print_r(json_encode(array('status'=>1,'msg'=>__('图片添加成功','i_theme'),'data'=>array('id'=>$attach_id,'src'=>wp_get_attachment_url( $attach_id ),'title'=>$basename))));
	        exit();
	    }else{
	        echo '{"status":4,"msg":"'.__('图片上传失败！','i_theme').'"}';
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/Pentatonic/inc/ajax.php

```
图片上传
add_action('wp_ajax_nopriv_img_upload', 'io_img_upload');  
add_action('wp_ajax_img_upload', 'io_img_upload');
function io_img_upload(){  
	$extArr = array("jpg", "png", "jpeg");
	$file = $_FILES['files'];
	if ( !empty( $file ) ) {
	    $wp_upload_dir = wp_upload_dir();                                     // 获取上传目录信息
	    $basename = $file['name'];
	    $baseext = pathinfo($basename, PATHINFO_EXTENSION);
	    $dataname = date("YmdHis_").substr(md5(time()), 0, 8) . '.' . $baseext;
	    $filename = $wp_upload_dir['path'] . '/' . $dataname;
	    rename( $file['tmp_name'], $filename );                               // 将上传的图片文件移动到上传目录
	    $attachment = array(
	        'guid'           => $wp_upload_dir['url'] . '/' . $dataname,      // 外部链接的 url
	        'post_mime_type' => $file['type'],                                // 文件 mime 类型
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
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/NextStack/inc/ajax.php

```
图片上传
add_action('wp_ajax_nopriv_img_upload', 'io_img_upload');  
add_action('wp_ajax_img_upload', 'io_img_upload');
function io_img_upload(){  
	$extArr = array("jpg", "png", "jpeg");
	$file = $_FILES['files'];
	if ( !empty( $file ) ) {
	    $wp_upload_dir = wp_upload_dir();                                     // 获取上传目录信息
	    $basename = $file['name'];
	    $baseext = pathinfo($basename, PATHINFO_EXTENSION);
	    $dataname = date("YmdHis_").substr(md5(time()), 0, 8) . '.' . $baseext;
	    $filename = $wp_upload_dir['path'] . '/' . $dataname;
	    rename( $file['tmp_name'], $filename );                               // 将上传的图片文件移动到上传目录
	    $attachment = array(
	        'guid'           => $wp_upload_dir['url'] . '/' . $dataname,      // 外部链接的 url
	        'post_mime_type' => $file['type'],                                // 文件 mime 类型
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
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/NextStack/inc/ajax.php

```
('wp_ajax_nopriv_img_remove', 'io_img_remove');  
add_action('wp_ajax_img_remove', 'io_img_remove');
function io_img_remove(){    
	$attach_id = $_POST["id"];
	if( empty($attach_id) ){
		echo '{"status":3,"msg":"'.__('没有上传图像！','i_theme').'"}';
		exit;
	}
	if ( false === wp_delete_attachment( $attach_id ) )
		echo '{"status":4,"msg":"'.sprintf(__('图片 %s 删除失败！','i_theme'), $attach_id).'"}';
	else
		echo '{"status":1,"msg":"'.__('删除成功！','i_theme').'
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/NextStack/inc/ajax.php

```
提交文章
add_action('wp_ajax_nopriv_contribute_post', 'io_contribute');  
add_action('wp_ajax_contribute_post', 'io_contribute');
function io_contribute(){  
	$delay = 40; 
	if( isset($_COOKIE["tougao"]) && ( time() - $_COOKIE["tougao"] ) < $delay ){
		error('{"status":2,"msg":"'.sprintf(__('您投稿也太勤快了吧，%s秒后再试！','i_theme'), ($delay - ( time() - $_COOKIE["tougao"] )) ).'"}');
	}
```


## License: unknown
https://github.com/amitshekhawato7/stpi_next/blob/2dc03e33f7c3858ac5a88cdbea76460030c9d94a/wp-content/plugins/total-team-lite/inc/backend/action/save-custom-template-setting-action.php

```
' => array('style' => array()),
			'h1' => array('style' => array()),
			'h2' => array('style' => array()),
			'h3' => array('style' => array()),
			'h4' => array('style' => array()),
			'h5' => array('style' => array()),
			'h6' => array('style' =>
```


## License: unknown
https://github.com/imtanbro/Rotaract/blob/8e2fcd25f3d02dd725016b007e9db7d29d946d19/wp-content/plugins/wpforo/wpf-includes/functions.php

```
' => array('style' => array()),
			'h1' => array('style' => array()),
			'h2' => array('style' => array()),
			'h3' => array('style' => array()),
			'h4' => array('style' => array()),
			'h5' => array('style' => array()),
			'h6' => array('style' =>
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/NextStack/inc/post-type.php

```
'io_add_quick_edit', 10, 2);
function io_add_quick_edit($column_name, $post_type) {
	if ($column_name == 'ordinal') {
		//请注意：<fieldset>类可以是：
		//inline-edit-col-left，inline-edit-col-center，inline-edit-col-right
		//所有列均为float：left，
		//因此，如果要在左列，请使用clear：both元素
		echo '
		<fieldset class="inline-edit-col-left" style="clear: both;">
			<div class="inline-edit-col"> 
				<label class="alignleft">
					<span class="title">排序</span>
					<span class="input-text-wrap"><input type="number" name="ordinal" class="ptitle" value=""></span>
				</label> 
				<em class="alignleft inline-edit-or"> 越大越靠前</em>
			</div>
		</fieldset>';
	}
	if ($column_name == 'ca_ordinal') {  
	  	echo '
	  	<fieldset>
		  	<div class="inline-edit-col"> 
			  	<label class="alignleft">
				  	<span class="title">排序</span>
				  	<span class="input-text-wrap"><input type="number" name="ca_ordinal" class="ptitle" value=""></span>
			  	</label> 
			  	<em class="alignleft inline-edit-or"> 越大越靠前</em>
		  	</div>
	  	</fieldset
```


## License: unknown
https://github.com/kothing/wordpress-themes/blob/9709bcfba6691eba65dd284fca8fdb4159ccc98b/Pentatonic/inc/post-type.php

```
'io_add_quick_edit', 10, 2);
function io_add_quick_edit($column_name, $post_type) {
	if ($column_name == 'ordinal') {
		//请注意：<fieldset>类可以是：
		//inline-edit-col-left，inline-edit-col-center，inline-edit-col-right
		//所有列均为float：left，
		//因此，如果要在左列，请使用clear：both元素
		echo '
		<fieldset class="inline-edit-col-left" style="clear: both;">
			<div class="inline-edit-col"> 
				<label class="alignleft">
					<span class="title">排序</span>
					<span class="input-text-wrap"><input type="number" name="ordinal" class="ptitle" value=""></span>
				</label> 
				<em class="alignleft inline-edit-or"> 越大越靠前</em>
			</div>
		</fieldset>';
	}
	if ($column_name == 'ca_ordinal') {  
	  	echo '
	  	<fieldset>
		  	<div class="inline-edit-col"> 
			  	<label class="alignleft">
				  	<span class="title">排序</span>
				  	<span class="input-text-wrap"><input type="number" name="ca_ordinal" class="ptitle" value=""></span>
			  	</label> 
			  	<em class="alignleft inline-edit-or"> 越大越靠前</em>
		  	</div>
	  	</fieldset
```

