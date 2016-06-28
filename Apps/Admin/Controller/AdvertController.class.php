<?php
namespace Admin\Controller;
use Think\Controller;
/*
 * 广告管理
 * @author 	xiexiang 2015-04-25
 */
class AdvertController extends ComController {

	protected  $advert ;
	
	function _initialize(){
		parent::_initialize();
		$this->advert = new \Admin\Model\AdvertModel();
	}
	
	
   /*
	*广告列表页面
	*@author viking 2015-05-13
	*/
	public function index(){

		$posik = I('posik');
		if($posik){
		   $map['posi'] = $posik ;
		   $this->assign('posik',$posik);
		}
		$map['hide_flag'] = 0 ;		
		$order = "top desc,sort desc,id desc" ;
		
		$res = $this->advert->search($map,'*',20,$order);		
		$posi = C('ADVERT_POSI');
		
		$this->assign('posi',$posi);
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		
		$this->display();
	}

  	
	/*
	 * 添加修改留言
	* @author viking 2015-05-12
	*/
	public function edit() {
		$id = I('id') ;
	
		if(IS_POST){
			$data = $this->advert->create();
			if(!$data){
				$this->error($this->advert->getError());
			}
				
			//添加留言
			if(!$id){
				$res = $this->advert->add($data);
				if(!$res){
					$this->error('添加失败!');
				}
				$this->success('添加成功',U('index'));
				exit;
			}
					
			$res = $this->advert->save($data);
			if(false===$res){
				$this->error('修改失败');
			}
				
			$this->success('修改成功',U('index'));
			exit;
		}
	
		$map['hide_flag'] = 0 ;
		$map['id'] = $id ;		
		$info = $this->advert->where($map)->find();
				
		$this->assign ('info', $info );
		$this->display ();
	}	
	

	
	
    

	

}



?>
