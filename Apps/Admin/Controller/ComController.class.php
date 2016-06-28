<?php
namespace Admin\Controller;
use Think\Controller;


/**
 * 通用函数类 （列表记录 修改状态、删除、排序、时间段查询）
 * @author leiqianyong 2015-05-15
 */
class ComController extends CommonController {
	

	/**
	 * 修改记录状态  
	 * @param int $id 需要操作的记录id
	 * @param int $state 状态  
	 * @param string $model
	 */
	public function state($model=CONTROLLER_NAME){
		$id = array_unique((array)I('id',0));
		$id = implode(',',$id) ;
	
		if ( empty($id) ) {
			$this->error('请选择要操作的数据!');
		}
		$where['id'] =   array('in',$id);

            $state = I('state');
            if(is_numeric($state)){
                $data['state']=$state;
            }
        $dispose = I('dispose');
        if(is_numeric($dispose)){
            $data['dispose']=$dispose;
        }
		if(is_string($model)){
			$model  =   M($model);
		}

		$res = $model->where($where)->save($data);		
		if($res!==false){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}
	}
	
	
	/**
	 * 删除记录
	 * @param int $id 需要操作的记录id
	 * @param string $model
	 */
	public function del($model=CONTROLLER_NAME){
		$id = array_unique((array)I('id',0));
		$id = implode(',',$id) ;
	
		if ( empty($id) ) {
			$this->error('请选择要操作的数据!');
		}
		$where['id'] =   array('in',$id);
		$data['hide_flag'] = 1;
	
		if(is_string($model)){
			$model  =   M($model);
		}		
		$res = $model->where($where)->save($data);
		if($res!==false){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}
	}	
	
	
	/**
	 * 批量排序
	 * @param string $model
	 */
	public function sort($model=CONTROLLER_NAME){
		$sort = I('sort');
		if(empty($sort)) {
			$this->error('请选择要操作的数据!');
		}
	
		foreach($sort as $k=>$v){
			if(!is_numeric($v)){
				continue;
			}
			$sql_bat_sort   .= " WHEN {$k} THEN {$v}" ;
			$sql_ids .= ','.$k ;
		}
	
		if(is_string($model)){
			$model  =   M($model);
		}		
		
		$sql = "UPDATE {$model->getTableName()} SET sort= CASE id ".$sql_bat_sort." END WHERE id IN (".trim($sql_ids,', ').")" ;
		$model->execute($sql);
	
		$this->success('操作成功！');
	}
    
   
	
	/**
	 * 按时间段查询的条件设置
	 * @author leiqianyong 2015-03-16
	 */
	function condtime($map){
		if(I('starttime') && !I('endtime')){
			$starttime = I('starttime');
			$map['time'] = array('egt',strtotime($starttime));
			$this->assign('starttime',$starttime);
		}
		if(I('endtime') && !I('starttime')){
			$endtime = I('endtime');
			$map['time'] = array('lt',strtotime($endtime)+86400);
			$this->assign('endtime',$endtime);
		}
		if(I('endtime') && I('starttime')){
			$starttime = I('starttime');
			$endtime = I('endtime');
			$map['time'] = array(array('egt',strtotime($starttime)),array('lt',strtotime($endtime)+86400));
			$this->assign('starttime',$starttime);
			$this->assign('endtime',$endtime);
		}
	
		return $map;
	}	
	
	
   
}