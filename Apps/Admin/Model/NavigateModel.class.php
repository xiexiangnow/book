<?php
namespace Admin\Model;
use Think\Model;

class NavigateModel extends Model{

	/* 自动验证规则 */
	protected $_validate = array(
			array('title', 'require', '标题必填！'),
			array('id', 'require', '缺少参数！',0,'',2),
			array('url','checkUrl','请正确填写链接',0,'callback')
	);
	
	
	public function checkUrl(){
		//当栏目类型为链接时才进行验证		
		if(3!=I('type')){
			return true;
		}
		
		$url = I('url');
		if(empty($url)){
			return false;
		}
		
		return true;
	}
	

	/**
	 * 添加/修改记录 
	 * @return  string|boolean
    */
	public function edit(){
		$data = $this->create();
		if(!$data){
			return $this->error;
		}
		
		//等级
		$level = 1 ;
		$id = $data['id'];
		$ids='';
		$pid=$data['pid'];
		if($pid){
			$pidinfo = $this->field('level,ids')->where("id={$pid}")->find();
			$level = $pidinfo['level']?($pidinfo['level']+1):1;
			$ids   = $pidinfo['ids'];
		}
		$data['level'] = $level ;
		
		//==========操作数据		
		//添加
		if(!$id){
			$res = $this->add($data);
			$update['ids'] .= $ids?$ids.','.$res:$res;
			$where="id={$res}";
			$res = $this->where($where)->save($update);			
			return $res?true:false;				
		}
		
		//修改
		$data['ids'] = $ids?$ids.','.$id:$id;		
		$res = $this->where("id={$id}")->save($data);		
		return is_numeric($res)?true:false;  		
	}
	
		
	/**
	 * 获取后台菜单结构树(不含操作级别)
	 * @author leiqianyong 2015-01-20	 
	 */
	public function getAry(){
				
		$res = $this->where('id,title')->where($where)->select();
		$back = array();
		if(!$res){
			return $back;
		}
				
		foreach($res as $val){
		   $back[$val['id']] = $val['title'];	
		}
		
		return $back;
	}
	
	
	/**
	 * 获取全部菜单项
	 * @author viking 2015-01-23
	 */
	public function getAll($map){
				
		$tmp = $this->getMenu($map);
						
		//id关键字必须,需要提取的参数
		$fields = array('id','level','title','pid','sort','module','type','is_nav');
		
		return $this->get_array_by_field($tmp, $fields);
						
	}
	
	
	/**
	 * 获取多级分类的树形结构（多维数组）
	 * @author leiqianyong 2015-04-28	 	 
	 */
	public function getMenu($map,$field='*'){		
		$mod_list = $this->field($field)->where($map)->order('level ASC,sort DESC')->select();			
		if(!$mod_list || empty($mod_list)){
			return false ;
		}
		
		$tmp = array();
		$address = array(); //父级地址
		foreach($mod_list as $k=>$v){
			$id = $v['id'];
			if($v['level'] <= 1){
				//一级
				$tmp[$id] = $v;
				$address[$id] = & $tmp[$id];
			}else{
				//子集
				$pid = $v['pid'];
				if(!$address[$pid]['child']){
					$address[$pid]['child'] = array();
				}
				$address[$pid]['child'][$id] = $v;
				$address[$id] = & $address[$pid]['child'][$id];
			}
		}
		
		return $tmp ;				
	}
	
	
	/**
	 * 将树形结构的多维数组转换成按顺序排列的一维数组
	 * @param array $tree
	 * @param array $fields
	 */
	private function get_array_by_field($tree,$fields){
		if(empty($tree)||!is_array($tree)||empty($fields)||!is_array($fields)){
			return false;
		}				
				
		$fields_ary = array();
		foreach($fields as $val){
			$fields_ary[$val] = $this->array_get_by_key($tree, $val);
		}
				
		//获取数组长度
		$len=count($fields_ary[$fields[0]]);
		if(empty($len)){
			return false;
		}
		
		$res = array();
		for($i=0;$i<$len;$i++){
			foreach($fields as $value){
				$res[$i][$value] = $fields_ary[$value][$i] ;
			}				
		}
		
		return $res;
	}
	
		
	/**
	 * 获取一维或多维数组某个特定键的所有值
	 * @author leiqianyong 2013-03-16
	 *
	 * @param array $array 数组
	 * @param 键值 $string
	 */
	private function array_get_by_key($array, $string){
		if (!trim($string) || !is_array($array)) return false;
		preg_match_all("/\"{$string}\";\w{1}:(?:\d+:|)\"(.*?)\";/", serialize($array), $res);
		return $res[1];
	}	
	
}