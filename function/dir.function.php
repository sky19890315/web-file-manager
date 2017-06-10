<?php
/**
 * Created by PhpStorm.
 * User: sunkeyi
 * Date: 2017/6/8
 * Time: 下午7:46
 */

/**
 * 只读取最外层目录
 * @param $path
 * @return mixed
 */
function readDirectory($path){
$handle = opendir($path);
while (($item = readdir($handle)) !== false){

	$var = $path."/".$item;
	if ($item != "." && $item != ".."){
		if (is_file($var)){
			//是文件
			$arr['file'][] = $item;
		}
		if (is_dir($var)){
			//是路径
			$arr['dir'][] = $item;
		}
	}

}

//关闭句柄
closedir($handle);
return $arr;
}

/**
 * @param $path
 * @return int
 */
function dirSize($path){
	//定为全局变量，以免出现调用后没有值的情况
	$sum = 0;
	global $sum;
	$handle = opendir($path);
	//检查是哪种函数 递归
	while (($item = readdir($handle)) !== false){
		//$var = $path."/".$item;
		//排除特殊情况
		if ($item != "."&& $item != ".."){
			//文件
			if (is_file($path."/".$item)){
				$sum += filesize($path."/".$item);
			}
			//是目录 则调用它自己 循环遍历到出结果为止
			if (is_dir($path."/".$item)){
				$func = __FUNCTION__;
				$func($path."/".$item);
			}

		}
	}
	closedir($handle);
	return $sum;
}

function copyFoler($src,$dst){
	//如果目标文件不存在 则创建
	if (!file_exists($dst)){
		//true 表示允许创建多级目录
		mkdir($dst,0777,true);
	}
	$handel = opendir($src);
	//读取句柄中的内容 并加以区分 即使是0 类型不同
	while ($item = readdir($handel) !== false){
		if ($item !='.'&& $item != '..'){
			if (is_file($src.'/'.$item)){
				copy($src.'/'.$item, $dst.'/'.$item);
			}
			if (is_dir($src.'/'.$item)){
				$func = __FUNCTION__;
				$func($src.'/'.$item,$dst.'/'.$item);
			}
		}
	}
	closedir($handel);
	return true;

}







?>