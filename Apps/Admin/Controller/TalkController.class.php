<?php
namespace Admin\Controller;
use Think\Controller;

/**
*说说管理
* @author xiexiang  2015-08-10
*
*/
 class TalkController extends ComController {
     

     //说说列表
 	public function index(){
 		//数据列表
 		$talk=M('Talk');
 		//$where['state']=1;
 		$where['hide_flag']=0;
 		$count=$talk->where($where)->count();
 		$page=new \Think\Page($count,20);
 		$show=$page->show();
 		$list=$talk->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();

 		$this->assign('list',$list);
 		$this->assign('page',$show);

 		$this->display();
 	}





 	//说说的添加
 	public function add(){
 		$this->display();
 	}

    

    //执行添加
   public function upload(){ 
 
	    if(!IS_POST){
	    	$this->error('没有数据');
	    }

	    $title   = I('title');               //标题
	    $content = I('content');            //内容
	    $top=I('top')?I('top'):0;
        if(empty($title) || empty($content)){
            $this->error('标题和内容不能为空！');
        }
 if($_FILES['file']['error'] != 4){      //判断如果没有上传图片文件的话 就是存title 和 content 数据到数据库
    	   $upload = new \Think\Upload();                                                   // 实例化上传类    
    	   $upload->maxSize   =     1048576 ;                                              // 设置附件上传大小    
    	   $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');                   // 设置附件上传类型    
    	   $upload->savePath  =     '/Uploads/talk/pic';                                 // 设置附件上传目录    
    	         // 上传文件    
    	    $info   =   $upload->upload(); 
    	    if(!$info) {                                                               
    	         // 上传错误提示错误信息        
    	         $this->error($upload->getError());    
    	    }else{                                                                         
    	        // 上传成功  
                $talk=M('Talk');
                $talk->title   = $title;
                $talk->content = $content;
                $talk->time    = NOW_TIME;
                $talk->imgname = $info['file']['savename'];
                $talk->imgurl  = $info['file']['savepath'].$info['file']['savename'];
                $talk->top     = $top;
                $talk->add();

    	    	$this->success('上传成功！',U('index'));    
    	       }
        
        }else{
           //判断如果没有上传图片文件的话 就是存title 和 content 数据到数据库
            $talk=M('Talk');
            $talk->title   = $title;
            $talk->content = $content;
            $talk->time    = NOW_TIME;
            $talk->top     = $top;
            $re=$talk->add();
            if($re>0){
                $this->success('上传成功！',U('index'));    
            }else{
                 $this->error('上传错误！');
            } 
        }   


	    }






	    //执行删除
	    public function del(){
	    	$id=I('id');
           if(is_numeric($id)){
           	  $data['id']=$id;
           }
           if(is_array($id)){
           	  $data['id']=array('in',$id);
           }
            
            $data['hide_flag']=1;   //hide_flag=1的时候为删除
            $re=M('Talk')->save($data);
            if($re){
            	$this->success('删除成功！');
            }else{
            	$this->error('失败！');
            }

	    }

	    //隐藏 +显示操作
	    public function hide(){
	    	$id=I('id');
            if(I('state')==1){
            	$data['state']=1;
            }else if(I('state')==0){
            	$data['state']=0;
            }
            //$data['state']=0;   //state=0的时候为隐藏
            $data['id']=$id;
            $re=M('Talk')->save($data);
            if($re){
            	$this->success('操作成功！');
            }else{
            	$this->error('失败！');
            }
	    }



        //修改页面的加载
        public function edit(){
            $id=I('id');
            if(!$id){
                $this->error('参数错误!');
            }
            $where['id']=$id;
            $talk=M('Talk');
            $talk_info=$talk->where($where)->field('id,title,imgurl,content,top')->find();
            $this->assign('talk_info',$talk_info);

            $this->display();

        }



        //执行改修
    public function gai(){
        $id      = I('id');
        $title   = I('title');
        $content = I('content');
        $top     = I('top');
       if(empty($title) || empty($content)) {
            $this->error('标题和内容不能为空！');
       }
 
     if($_FILES['file']['error'] != 4){      //判断如果没有上传图片文件的话 就是存title 和 content 数据到数据库
           $upload = new \Think\Upload();                                                   // 实例化上传类    
           $upload->maxSize   =     1048576 ;                                              // 设置附件上传大小    
           $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');                   // 设置附件上传类型    
           $upload->savePath  =     '/Uploads/talk/pic';                                 // 设置附件上传目录    
                 // 上传文件    
            $info   =   $upload->upload(); 
            if(!$info) {                                                               
                 // 上传错误提示错误信息        
                 $this->error($upload->getError());    
            }else{                                                                         
                // 上传成功  
                $talk=M('Talk');
                
                $data['id']      = $id;
                $data['title']   = $title;
                $data['content'] = $content;
                $data['time']    = NOW_TIME;
                $data['imgname'] = $info['file']['savename'];
                $data['imgurl']  = $info['file']['savepath'].$info['file']['savename'];
                $data['top']     = $top;
                $re=$talk->save($data);    
                if($re>0){
                     $this->success('修改成功！',U('index'));    
                }else{
                    $this->error('修改失败！');
                }

               
               }
        
        }else{


       $talk=M('Talk');
        $data['id']      = $id;
        $data['title']   = $title;
        $data['content'] = $content;
        $data['top']     = $top;
        $result = $talk->save($data);
        if($result>0){
            $this->success('修改成功！',U('index'));
        }else{
            $this->error('修改失败！');
        }
     
    }
  }

}

?>