<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 内容信息管理
 * @author viking 2015-02-23
 */
class DocController extends ComController {
	
	protected $public_action = array('info');
	
	protected $doc ;
	
    function _initialize(){
		parent::_initialize();
		$this->doc = new \Admin\Model\DocModel();
	}
	
	
	/**
	 * 添加内容
	 * @author leiqianyong 2015-04-29
	 */
	public function add(){

		if(IS_POST){
			$_POST['uid'] = is_login();
 			$res = $this->doc->edit();
 			if(true===$res){
 				$this->success('添加成功',U('index'));
 				exit;
 			}
 			
 			$this->error($res);
		}

		
		//获取文件类型
		$menu_id = I('id');
		$menu_info = M('navigate')->field('id,title,type,module')->where('id='.$menu_id)->find();
		if(empty($menu_info)){
			return false;
		}
		
		$this->assign('type',$menu_info['type']);
		$this->assign('module',$menu_info['module']);
		$this->assign('catid',$menu_info['id']);
		$this->assign('title',$menu_info['title']);

		//单网页内容模板
		if(2==$menu_info['type']){
			$where='category_id='.$menu_info['id'];
			$info = $this->doc->where($where)->find();
			if($info){
				$content=M('doc_content')->field('content')->where("id=".$info['id'])->find();				
				$info['content']=$content['content'];				
				$this->assign('info',$info);
			}
			$this->display('single');
			exit;
		}
		
		//内部栏目内容类型 1-文章 2-图册 3-下载 4-视频
		//$tpl_ary = array(1=>'doc',2=>'album',3=>'download',4=>'video');						
		//$this->display($tpl_ary[$menu_info['module']]);

		if(1==$menu_info['module']){
			$this->display('doc');
			exit;
		}
		
		$ary = array(2=>0,3=>2,4=>1);

		$this->assign('cat',$ary[$menu_info['module']]);		
		$this->display('album');
	}
	
	
	/**
	 * 修改内容信息
	 * @author leiqianyong 2015-04-29
	 */
	public function edit(){
		$id = I('id');
		if(!$id){
			$this->error('缺少参数');
		}
	
		if(IS_POST){			
			$res = $this->doc->edit();
			if(true===$res){
				$this->success('修改成功',U('index'));
				exit;
			}
		
			$this->error($res);
		}
		
		//文档信息
		$info = $this->doc->info($id);		
		$this->assign('info',$info);				
		//获取文件类型
		$menu_id = $info['category_id'];
		$menu_info = M('navigate')->field('id,title,type,module')->where('id='.$menu_id)->find();
		if(empty($menu_info)){
			return false;
		}
		
		$this->assign('type',$menu_info['type']);
		$this->assign('module',$menu_info['module']);
		$this->assign('catid',$menu_info['id']);
		$this->assign('title',$menu_info['title']);
		//单网页内容模板
	
		if(2==$menu_info['type']){
			$this->display('single');
			exit;
		}
		
		//内部栏目内容类型 1-文章 2-图册 3-下载 4-视频
		//$tpl_ary = array(1=>'doc',2=>'album',3=>'download',4=>'video');
		//$this->display($tpl_ary[$menu_info['module']]);	

		if(1==$menu_info['module']){
			$this->display('doc');
			exit;
		}
		
		$ary = array(2=>0,4=>1,3=>2);
		
		$this->assign('cat',$ary[$menu_info['module']]);
		$this->display('album');		
	}
		
	
	
	
	
	/**
	 * 回收站机制
	 */
	public function recycle(){
		$map['state']  = -1 ;
		$map['hide_flag'] = 0 ;
		
		if(is_numeric(I('type'))){
			$map['type'] = I('type');
			$this->assign('type',I('type'));
		}
		if(I('keywords')){
			$map['title|keywords']    =   array('like', '%'.(string)I('keywords').'%');
			$this->assign('keywords',I('keywords'));
		}
		
		//时间段
		$map = $this->condtime($map);
		
        $res = $this->doc->search($map,'*',20);
			
		$this->assign ('list', $res['list'] );
		$this->assign('page',$res['page']);
		$this->assign('doctype',C('DOC_TYPE'));		
		$this->display('recycle');		
	}
	
	
	
	/**
	 * 列表管理
	 */
	public function index(){

		$map['state'] = array('gt',-1) ;
		$map['hide_flag'] = 0 ;

		if(I('keywords')){
			$map['title|keywords']    =   array('like', '%'.(string)I('keywords').'%');
			$this->assign('keywords',I('keywords'));
		}

		//时间段
		$map = $this->condtime($map);

		if(I('category_id')){
			$pid=I('category_id');
			$map['_string'] = "FIND_IN_SET('{$pid}',category_ids) " ;
			$this->assign('category_id',I('category_id'));
		}


		//排序
        $sort_str = 'time DESC' ;
        if(I('show')){
            $key = I('show');
            $sort_ary =array(1=>"top DESC,sort DESC,time DESC",2=>'time DESC',3=>'updatetime DESC',4=>'sort DESC');
            $sort_str = $sort_ary[$key] ;
            $this->assign('show',I('show'));
        }


        //栏目权限
        $navigate = D('navigate');
        $key = I('category_id');
        $conf['type'] = array('neq',3);
        // 是否是超级管理员
        if(is_administrator()){
            $tree = get_doc_tree($key,$conf);
        }else{
            $adminobj=D('Admin');
            $colids=$adminobj->getcolumns();
            $coids=implode(',',$colids);
            $map['category_id']=array('in',$coids);
            $conf['id']=array('in',$coids);
            $tree = get_doc_tree($key,$conf);
        }

        $res = $this->doc->search($map,'*',20,$sort_str);





		$this->assign('menu',$tree);
		$this->assign('ary',$navigate->getAry());
		$this->assign ('list', $res['list'] );
		$this->assign('page',$res['page']);
		$this->display();
	}  
    
	
	/**
	 * 编辑器上传图片
	 * @author leiqianyong 2015-02-26
	 */
	private function upload(){
		$allow_ext = array('jpg','png','jpeg','bmp','gif');
		 
		$rootPath = "./Uploads/doc/image/" ;
		$upload = new \Think\Upload();
		$upload->__set('exts', $allow_ext);
		$upload->__set('rootPath', $rootPath);
		$res = $upload->upload($_FILES);
		if($res){			
			//裁切图片			
			if(is_numeric(I('iw')) && is_numeric(I('ih'))){
			    $filename = $rootPath.$res['file']['savepath'].$res['file']['savename'] ;
		    	$image = new \Think\Image();
				$image->open($filename);
				$image->thumb(I('iw'),I('ih'))->save($filename);
				$back = substr($filename, 1);
			}else{
				$back = substr($rootPath.$res['file']['savepath'].$res['file']['savename'], 1);
				
				$data ['module'] = $type;
				$data ['path'] = $back;
				$data ['time'] = NOW_TIME;
				$data ['ip'] = get_client_ip ();
				$id = M ( 'attachment' )->add ( $data );				
			}									
		}
		return $back ;
	}	
	
	
	/**
	 * 查看内容详情
	 */
	public function info(){
		
		$id = I('id');
		if(!$id){
			$this->error('缺少文章参数ID');
		}
				
		$base = $this->doc->where("id={$id}")->find();
		if(!$base){
			$this->error('文章不存在');
		}		
		$info = M('doc_content')->where("id={$id}")->find();
		
		$this->assign('base',$base);		
		$this->assign('info',$info);		
		$this->display();
		
	}
	/*清空首页缓存*/
	public function cache(){
        $path=HTML_PATH.'index.html';
        if(is_file($path)){
            unlink($path);
        }
        $this->success("设置成功",'/');
    }
	
    
}