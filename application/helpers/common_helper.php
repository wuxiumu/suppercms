<?php
    /**
     * 打印数据的方法，
     * 
     * @access: public
     * @author: Meiyou Fan
     */
    function p($data){
			echo '<pre>'; 
			print_r($data); 
			echo '</pre>';  
    } 
    /**
     * 提示操作信息的，并且跳转
     * @param string $mes
     * @param string $url
     */
    function alertMes($mes,$url){
        echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
    }    
    /**
     * 打印json数据的方法，
     * 
     * @access: public
     * @author: Meiyou Fan
     */
    function pjs($data){          
        echo json_encode($data,JSON_UNESCAPED_UNICODE);die;  
    } 
    /**
     * 消息提示，跳转
     * 
     * @access: public
     * @author: Meiyou Fan
     */
    function mess_redirect($typeid,$message,$url){
        switch ($typeid) {
            case '1':
                # 提示跳转返回上一页
                echo "<script>alert('".$message."');
                javascript:history.go(-1)</script>";exit;
                break;
            case '2':
                # 提示跳转返回上上一页
                echo "<script>alert('".$message."');
                javascript:history.go(-2)</script>";exit;
                break;
            case '3':
                # 提示跳转指定$url
                echo "<script>alert('".$message."');window.location.href='".site_url($url)."'</script>";exit;
                break;
            
            default:
                # 默认返回上一页
                echo "<script>
                javascript:history.go(-1)</script>";exit;
                break;
        }         
    }
?>