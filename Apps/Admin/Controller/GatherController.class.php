<?php
namespace Admin\Controller;
use Think\Controller;


/**
 * 内容信息管理
 * @author viking 2015-02-23
 */
class GatherController extends ComController {





    public function index(){
        $gaobj=D('Gather');


        if(I('keywords')){
            $map['title']    =   array('like', '%'.(string)I('keywords').'%');
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
        $sort_str = 'sort DESC,time DESC' ;
        if(I('show')){
            $key = I('show',4);
            $sort_ary =array(1=>"sort DESC,time DESC",2=>'time DESC',3=>'sort DESC');
            $sort_str = $sort_ary[$key] ;
            $this->assign('show',I('show'));
        }



        $list=$gaobj->search($map,$sort_str);

        $this->assign('list',$list['list']);
        $this->assign("page",$list['page']);
        $this->display();
    }
	



/*添加*/
    public function add(){


        if(IS_POST){//处理添加的数据

            $xuan=I('xuan');

            if(!is_array($xuan)){
                $this->error('投票选项错误');
            }
             if(!$xuan){
                $this->error('请填写投票选项内容');
             }
            if(count($xuan)<2){
                $this->error('投票选项必须2条以上');
            }
            $gaobj=D("Gather");
           $res=$gaobj->edit();

            if(true===$res){
                $this->success('修改成功',U('index'));
                exit;
            }

            $this->error($res);
        }

        $this->display();
    }



    /*修改*/
    public function edit(){
        $gaobj= D('Gather');
        $id = I('id');

        if(!$id){
            $this->error('参数不能为空');
        }
        $this->assign('id',$id);

        if(IS_POST){
            $xuan=I('xuan');

            if(!is_array($xuan)){
                $this->error('投票选项错误');
            }
            if(!$xuan){
                $this->error('请填写投票选项内容');
            }
            if(count($xuan)<2){
                $this->error('投票选项必须2条以上');
            }
            $ids=I('ids');
            if(count($ids)>count($xuan)){
                $this->error('数据出错');
            }

            $res=$gaobj->edit();

            if(true===$res){
                $this->success('修改成功',U('index'));
                exit;
            }

            $this->error($res);

        }

        $data=$gaobj->where('id='.$id)->find();

          if(!$data){
              $this->error('内容不存在');
          }
        $list=M('gather_node')->where('pid='.$id)->select();
        $this->assign('list',$list);
        $this->assign('data',$data);

        $this->display('add');
    }




    /*删除*/
    public function delete(){

        $id=I('id');
        if(!$id){
            $this->error('缺少参数id');
        }
        $gaobj= D('Gather');
        $gather=$gaobj->where('id='.$id)->find();
        if(!$gather){
            $this->error('没有该条数据或数据已删除');
        }
        //查找选项卡
       $node= M('gather_node')->where('pid='.$id)->select();
        if(!$node){
            $this->error('数据已删除');
        }


        foreach($node as $nd){
            //查找选项卡的投标项
            if($gather['options']==2){
                $ndid=$nd['id'];
                $tmps="node_id = $ndid or find_in_set('$ndid',node_id) ";
                $cats = M('gather_cat')->where($tmps)->select();
            }else{
                $cats = M('gather_cat')->where('node_id='.$nd['id'])->select();
            }




            if($cats) {
                foreach ($cats as $ct) {
                    $ctids .= $ct['id'] . ',';
                }
                $ctids = substr($ctids, 0, -1);
                $ctwhere['id'] = array('in', $ctids);

                //删除选项卡的投标项
                $ctres = M('gather_cat')->where($ctwhere)->delete();

                if (!is_numeric($ctres)) {
                    $this->error('删除投票失败');
                }
            }
            $ndids .= $nd['id'].',';
        }
        $ndids= substr($ndids,0,-1);
        //删除选项卡
        $ndwhere['id']=array('in',$ndids);
        $ndres= M('gather_node')->where($ndwhere)->delete();

        if(!is_numeric($ndres)){
            $this->error('删除选项卡失败');
        }
       $geres = $gaobj->where("id={$id}")->delete();


        if(!is_numeric($geres)){
            $this->error('删除选项卡失败');
        }

        $this->success('删除成功',U('index'));
    }


    public function info(){
        $id=I('id');
        if(!$id){
            $this->error("传参错误");
        }

        $gaobj=D('Gather');
        $gather=$gaobj->where("id={$id}")->find();

       $nodes= M('GatherNode')->where("pid={$gather['id']}")->select();
       foreach($nodes as $nd){
          $count += $nd['count'];

       }

        $this->assign('base',$gather);
        $this->assign('info',$nodes);
        $this->assign('count',$count);
        $this->display();
    }







}