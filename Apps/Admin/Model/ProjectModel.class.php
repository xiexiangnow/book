<?php
namespace Admin\Model;
use Think\Model;

/**
 * 实战项目
 * @author xiexiang 2016-02-15
 */
class ProjectModel extends CommonModel {


    /* 自动验证规则 */
    protected $_validate = array(
        array('pro_name', 'require', '项目名称不能为空!'),
        array('pro_web','require','项目的域名输入不能为空'),
        array('pro_jia','require','所用框架不能为空'),
        array('is_mobile','require','选择是否带有手机站'),



    );




}
