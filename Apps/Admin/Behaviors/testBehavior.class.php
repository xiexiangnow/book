<?php
namespace Admin\Behaviors;
class testBehavior extends \Think\Behavior{
    //行为执行入口
    public function run(&$param){
       logRec(date('Y-m-d H:i:s'),'gold');
    }
}
