<?php
/**
 * Created by PhpStorm.
 * User: sunkeyi
 * Date: 2017/6/9
 * Time: 下午1:00
 */
/**
 * @param $mes
 * @param $url
 */
function alertMes($mes, $url){
	echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}
?>