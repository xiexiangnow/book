<?php
namespace Admin\Widget;
use Think\Controller;
class CateWidget extends Controller {
    public function menu(){
        echo date('Y-m-d');
    }
}

?>