<?php

namespace Admin\Controller;
use Think\Controller;
/**
 * 个人简历
 */
class ResumeController extends ComController{

    //加载简历修改页面
    public function index(){
        $resume =  M('Resume');
        $info = $resume->find();
        $this->assign('info',$info);
        $this->display();
    }

    //简历数据的修改
    public function edit(){
        $resume =  M('Resume');
        $name = I('name');
        $birthday = I('birthday');
        $sex =  I('sex');
        $nation = I('nation');
        $xueli = I('xueli');
        $native = I('native');
        $specialty = I('specialty');
        $school= I('school');
        $tel = I('tel');
        $email = I('email');
        $education = I('education');
        $job = I('job');
        $tools = I('tools');
        $technical = I('technical');
        $interest = I('interest');
        $technical_skill = I('technical_skill');
        $work_experience = I('work_experience');
        $self_assessment = I('self_assessment');
        $result = $resume->execute("update by_resume set name='{$name}',birthday='{$birthday}',sex='{$sex}',nation='{$nation}',xueli='{$xueli}',native='{$native}',specialty='{$specialty}',school='{$school}',tel='{$tel}',email='{$email}',education='{$education}',job='{$job}',tools='{$tools}',technical='{$technical}',interest='{$interest}',technical_skill='{$technical_skill}',work_experience='{$work_experience}',self_assessment='{$self_assessment}'");
        if($result){
            $this->success("保存成功！",U('index'));
        }else{
            $this->error('操作失败！');
        }

    }

    //实战项目添加页面
    public function add_pro(){
        //如果有id值 那么将执行查询 用作后面修改
        $id = I('id');
        if($id){
            $pro=M('Project');
            $info = $pro->where("id='{$id}'")->find();
            $this->assign('info',$info);
        }
        $this->display();
    }

    //实战项目列表
    public function pro_index(){
        //数据列表
        $pro=M('Project');
        $where['hide_flag']=0;
        $count=$pro->where($where)->count();
        $page=new \Think\Page($count,20);
        $show=$page->show();
        $list=$pro->where($where)->order("sort DESC,id DESC")->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    //实战项目数据添加操作
    public function pro_add_data(){
        $pro = D('Project');
        $re = $pro->create();
        if(!$re){
           exit($pro->getError());
        }else{
             $pro->time = NOW_TIME;
             if($pro->add()){
                 $this->success('操作成功',U('Pro_index'));
             }else{
                 $this->error('操作失败！');
             }
        }

    }
    //实战项目数据修改操作
    public function pro_edit(){
        $pro = D('Project');
        $re = $pro->create();
        if(!$re){
            exit($pro->getError());
        }else{
            if($pro->save()){
                $this->success('操作成功',U('Pro_index'));
            }else{
                $this->error('操作失败！');
            }
        }
    }

    //执行删除
    public function delete(){
        $id=I('id');
        if(is_numeric($id)){
            $data['id']=$id;
        }
        if(is_array($id)){
            $data['id']=array('in',$id);
        }
        $re=M('Project')->where($data)->delete();
        if($re){
            $this->success('删除成功！');
        }else{
            $this->error('失败！');
        }

    }

    /**
     * 批量排序
     * @param string $model
     */
    public function pro_sort(){
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
        $model  =   M('Project');
        $sql = "UPDATE by_project SET sort= CASE id ".$sql_bat_sort." END WHERE id IN (".trim($sql_ids,', ').")" ;
        $model->execute($sql);

        $this->success('操作成功！');
    }



}






?>
