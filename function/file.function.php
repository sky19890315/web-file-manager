<?php
/**
 * @param $size
 * @return string 转换字节
 */
function transByte($size){
$arr = array("B","KB","MB","GB","TB","EB");
$i = 0;
while ($size >= 1024){
	$size /= 1024;
	$i++;
}
return round($size,2).$arr[$i];
}

/**
 * @param $filename
 * @return string
 */
function createFile($filename){
	//验证合法性

	if (checkFileName($filename)){
		if (!file_exists($filename)){

			if (touch($filename)){
				return "文件创建成功！";
			}else{
				return "文件创建失败！请检查权限！";
			}
		}else{
			return "文件已存在，请重命名！";
		}
	}else{
		return "文件名不可用！";
	}
}

/**
 * @param $oldname
 * @param $newname
 * @return string
 */
function renameFile($oldname,$newname){
	if (checkFileName($newname)){
		$path = dirname($oldname);
		if (!file_exists($path."/".$newname)){
				$rst = rename($oldname,$path."/".$newname);
				if ($rst) {
					return "重命名成功！";
				}else {
					return "重命名失败！";
				}
		}else {
			return "文件名重复，请重试";
		}
	}else{
		return "文件名不可用，请重试";
	}
}

/**
 * @param $filename
 * @return bool
 */
function checkFileName($filename){
	$pattern = "/[\/,\*,<>,\?\|]/";
	if (!preg_match($pattern,basename($filename))){
		return true;
	}else{
		return false;
	}
}

/**
 * @param $filename
 * @return string
 */
function delFile($filename){
	if (unlink($filename)){
		$mes = "文件删除成功";
	}else{
		$mes = "文件删除失败！";
	}
	return $mes;
}


function downloadFile($filename){
	header("content-disposition:attachment;filename=".basename($filename));
	header("content-length:".filesize($filename));
	readfile($filename);

}
?>