<?php
namespace Index\Model;
use Think\Model;
use Common\Model\CommonModel;

class AccountModel extends CommonModel{


    //根据用户名获取详细信息
    public function findUserInfo($username){
      $map['username'] = $username;
      return  $this->find($map);
    }







}



?>