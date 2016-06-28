<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Image\Driver\GIF;

/**
 * 附件信息管理
 * 
 * @author Administrator
 */
class AttachmentController extends Controller {
	
	/**
	 * 附件信息管理
	 * 
	 * @author leiqianyong 2015-05-05
	 */
	public function index() {
		$filename = I ( 'keywords' );
		if ($filename) {
			$map ['filename'] = array ('LIKE','%' . $filename . '%');
		}
		$starttime = I ( 'starttime' );
		if ($starttime) {
			$map ['time'] [] = array ('egt',strtotime ( $starttime ));
			$this->assign ( 'starttime', $starttime );
		}
		$endtime = I ( 'endtime' );
		if ($endtime) {
			$map ['time'] [] = array ('elt',strtotime ( $endtime ) + 86400);
			$this->assign ( 'endtime', $endtime );
		}
		$type = I ( 'type' );
		if (is_numeric ( $type )) {
			$map ['cat'] = $type;
			$this->assign ( 'type', $type );
		}
		
		$attach = new \Admin\Model\AttachmentModel ();
		$field = '*';
		$pagesize = 15;
		$res = $attach->search ( $map, $field, $pagesize );
		
		$type_ary = array (0 => '图片',1 => '文件',2 => '视频');
		
		$this->assign ( 'typeary', $type_ary );
		$this->assign ( 'keywords', $filename );
		$this->assign ( 'list', $res ['list'] );
		$this->assign ( 'page', $res ['page'] );
		$this->display ();
	}
	
	
	public function search() {
		$module = $map ['module'] = I ( 'module', 0 );
		$filename = I ( 'keywords' );
		if ($filename) {
			$map ['filename'] = array ('LIKE','%' . $filename . '%');
			$this->assign ( 'keywords', $filename );
		}
		$starttime = I ( 'starttime' );
		if ($starttime) {
			$map ['time'] [] = array ('egt',strtotime ( $starttime ));
			$this->assign ( 'starttime', $starttime );
		}
		$endtime = I ( 'endtime' );
		if ($endtime) {
			$map ['time'] [] = array ('elt',strtotime ( $endtime ) + 86400);
			$this->assign ( 'endtime', $endtime );
		}
		
		$num = I ( 'num', 1 );
		$this->assign ( 'module', $module );
		$this->assign ( 'num', $num );
		
		$attach = new \Admin\Model\AttachmentModel ();
		$field = 'id,filename,path,time,module';
		$pagesize = 8;
		$res = $attach->search ( $map, $field, $pagesize );
		
		$this->assign ( 'cat', $map ['cat'] );
		$this->assign ( 'list', $res ['list'] );
		$this->assign ( 'page', $res ['page'] );
		$this->display ();
	}
	
	/**
	 * 添加/修改 附件信息
	 * 
	 * @author leiqianyong 2015-05-04
	 */
	public function edit() {
		$module = I ( 'module', 0 );
		if (IS_POST) {
			$module = $_GET ['module'];
			$res = $this->upload ( $module, 'Filedata' );
			echo $res ['ret'];
			return;
		}
	}
	
	/**
	 * 上传附件
	 * 
	 * @param int $type 类型 0-图片 1-文件 2-视频
	 * @param string $file 文件名称 对应控件名称	
	 */
	private function upload($type = 0, $file = '') {
		$allow_ext_ary = array (
				0 => array (
						'jpg',
						'png',
						'jpeg',
						'bmp',
						'gif' 
				),
				1 => array (
						'zip',
						'rar',

                ),
				2 => array (
						'flv' 
				) 
		);
		
		$allow_path_ary = array (
				0 => 'image',
				1 => 'file',
				2 => 'video' 
		);
		
		$allow_ext = $allow_ext_ary [$type];
		$allow_path = $allow_path_ary [$type];
		$upload = new \Think\Upload ();
		$rootPath = "./Uploads/attachment/{$allow_path}/";
		$upload->__set ( 'subName', array ('date','Ymd') );
		$upload->__set ( 'exts', $allow_ext );
		$upload->__set ( 'rootPath', $rootPath );
		$res = $upload->uploadOne ( $_FILES [$file] );
		if ($res) {
			// 上传成功
			$filename = substr ( $rootPath, 1 ) . $res ['savepath'] . $res ['savename'];
			
			//生成缩略图 
			$filenamea= $rootPath . $res ['savepath'] . $res ['savename'];//生成缩略原图

            if($type == 0){//是否是图片
                if(C('IMAGES_CROP_SAVE')){//是否图片缩略
                    $imgobj = new \Think\Image();
                    $width=C('IMAGES_THUMB_WIDTH');
                    $height=C('IMAGES_THUMB_HEIGHT');
                    $img_path=$rootPath. $res ['savepath'] .$width.'x'.$height.'_'.$res['savename'];

                    $imgobj->open($filenamea);
                    $imgobj->thumb($width,$height)->save($img_path);
                }
            }


			$data ['module'] = $type;
			$data ['path'] = $filename;
			$data ['filext'] = $res ['ext'];
			$data ['filename'] = $res ['name'];
			$data ['filesize'] = $res ['size'];
			$data ['time'] = NOW_TIME;
			$data ['ip'] = get_client_ip ();
			$data ['type'] = $res ['type'];
			$data ['md5'] = $res ['md5'];
			$data ['sha1'] = $res ['sha1'];
			$data ['key'] = $res ['key'];
			$id = M ( 'attachment' )->add ( $data );
			
			$ret = $id . ',' . $filename . ',' . $type . ',' . $res ['name'];
			
			return array (
					"error" => 0,
					"url" => $filename,
					"ret" => $ret 
			);
		} else {
			$error = $upload->getError ();
			return array (
					"error" => 1,
					"message" => $error 
			);
		}
	}
	
	public function info($id) {
		$res = M ( 'attachment' )->where ( "id={$id}" )->find ();
		$this->assign ( 'res', $res );
		$this->display ();
	}
	
	public function edit_name($id, $name) {
		$data ['filename'] = $name;
		$res = M ( 'attachment' )->where ( "id={$id}" )->save ( $data );
		if (false === $res) {
			$this->error ( '修改失败' );
		}
		
		$this->success ( '修改成功' );
	}
	
	public function plus() {

		$module = I ( 'module', 0 ); // 模型类型 0-图片 1-文件 2-视频
		$num = I ( 'num', 1 ); // 附件的数量
		$ext = I ( 'ext' ); // 可上传的附件后缀
		if (empty ( $ext )) {
			return false;
		}
		$size = I ( 'size', 50 ); // 附件大小M
		$ext_ary = explode ( '|', $ext );
		$this->assign ( 'exty', $ext );
		$this->assign ( 'exts', implode ( '、', $ext_ary ) );
		foreach ( $ext_ary as &$val ) {
			$val = '*.' . $val;
		}
		$_ext = implode ( ';', $ext_ary );
		$this->assign ( 'ext', $_ext );
		$this->assign ( 'module', $module );
		$this->assign ( 'num', $num );
		$this->assign ( 'size', $size * 1024 );
		$this->assign ( 'sizes', $size );
		
		$this->display ( 'plus' );
	}
	
	public function crop() {
		$pic = I ( 'pic' );
		if (IS_POST) {
			$pic = strstr ( $pic, '/Uploads' );
			$x1 = I ( 'x1' );
			$y1 = I ( 'y1' );
			$x2 = I ( 'x2' );
			$y2 = I ( 'y2' );
			
			$pic_ary = pathinfo ( $pic );
			$save_path = $pic_ary ['dirname'] . '/' . $pic_ary ['filename'] . '_thumb_' . NOW_TIME . '.' . $pic_ary ['extension'];
			
			$image = new \Think\Image ();
			$info = $image->open ( '.' . $pic );
			$res = $image->crop ( $x2 - $x1, $y2 - $y1, $x1, $y1 )->save ( '.' . $save_path );
			
			$data ['module'] = 0;
			$data ['path'] = $save_path;
			$data ['filext'] = $pic_ary ['extension'];
			$data ['filename'] = $pic_ary ['filename'] . '_thumb_' . NOW_TIME . '.' . $pic_ary ['extension'];
			$data ['time'] = NOW_TIME;
			$data ['ip'] = get_client_ip ();
			$id = M ( 'attachment' )->add ( $data );
			
			$pic = $save_path;
		}
		
		$this->assign ( 'pic', $pic );
		$this->display ( 'crop' );
	}
	public function plugin() {
		$this->display ();
	}
}