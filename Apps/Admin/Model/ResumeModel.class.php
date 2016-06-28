<?php
namespace Admin\Model;
use Think\Model;

/**
 * 简历
 * @author xiexiang 2016-02-15
 */
class ResumeModel extends CommonModel {


    /* 自动验证规则 */
    protected $_validate = array(
        array('name', 'require', '姓名不能为空!'),
        array('birthday','require','生日的输入不能为空'),
        array('nation','require','民族不能为空'),
        array('xueli','require','学历输入不能为空'),
        array('native','require','籍贯输入不能为空'),
        array('specialty','require','专业输入不能为空'),
        array('school','require','毕业院校输入不能为空'),
        array('tel','require','电话输入不能为空'),
        array('email','require','邮箱输入不能为空'),
        array('education','require','教育背景输入不能为空'),
        array('job','require','求职意向输入不能为空'),
        array('tools','require','开发工具输入不能为空'),
        array('technical','require','技术鉴定输入不能为空'),
        array('interest','require','兴趣爱好输入不能为空'),
        array('technical_skill','require','专业技能输入不能为空'),
        array('work_experience','require','工作经历 输入不能为空'),
        array('self_assessment','require','自我评价输入不能为空'),


    );




}
