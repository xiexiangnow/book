<?php
namespace Admin\Controller;
use Think\Controller;

/*
 * 留言板管理 
 * @author leiqianyong 2015-05-12
 */
class MessageController extends ComController {
	protected $message;

	public function _initialize() {
		
		parent::_initialize ();
		$this->message = new \Admin\Model\MessageModel ();
		
	}
	
		
    /**
     * 留言列表
     */
	public function index() {
	
		$reply = I('reply',1);
		$this->assign('reply',$reply);

		if(1==$reply){
			$map['isreply'] = 0 ;
		}elseif(2==$reply){
			$map['isreply'] = 1 ;					
		}
        		
		$map['hide_flag'] = 0 ;
        $map['type'] = 1 ;
        $map = $this->condtime($map);
		$order = "sort desc,id desc" ;				
		$res = $this->message->search($map,'*',20,$order);

		$this->assign('list',$res['list']);
	    $this->assign('page',$res['page']);	
		$this->display ();
	}
	
	
	/*
	 * 添加修改留言
	 * @author viking 2015-05-12
	*/
	public function edit() {
		$id = I('id') ;
		if(IS_POST){
			$data = $this->message->create();			
			if(!$data){
				$this->error($this->message->getError());
			}
			
			//添加留言
			if(!$id){
				$res = $this->message->add($data);
				if(!$res){
					$this->error('添加失败!');
				} 
				$this->success('添加成功',U('index'));
				exit;
			}

			if($data['replycontent']){
				$data['isreply'] = 1 ;
				$data['replytime'] = NOW_TIME;
			}else{
				$data['isreply'] = 0 ;
				$data['replytime'] = 0;				
			}
			
			$res = $this->message->save($data);
			if(false===$res){
				$this->error('修改失败');
			}
			
			$this->success('修改成功',U('index'));
			exit;
		}
		
		$map['hide_flag'] = 0 ;
		$map['id'] = $id ;
		$info = $this->message->where($map)->find();
		
		$this->assign ('info', $info );
		$this->display ();
	}	
	
	public function join(){

        $map['hide_flag'] = 0 ;
        $map['type'] = 2 ;
        $map = $this->condtime($map);

        $order = "dispose,sort desc,id desc" ;
        $res = $this->message->search($map,'*',20,$order);

        $this->assign('list',$res['list']);
        $this->assign('page',$res['page']);
        $this->display ();
    }
	
	
}

?>