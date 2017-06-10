<?php
require_once 'function/dir.function.php';
require_once 'function/file.function.php';
require_once 'function/common.function.php';

$path = "file";
$path = $_REQUEST['path']?$_REQUEST['path']:$path;
$info = readDirectory($path);
if (empty($info)){
	echo "<script>alert('没有文件或者目录');location.href='index.php';</script>";
}
$act = $_REQUEST['act'];
$filename = $_REQUEST['filename'];
$dirname = $_REQUEST['dirname'];

$redirect ="index.php?path={$path}";
//创建文件
if ($act == "createFile"){
    $mes = createFile($path.'/'.$filename);
    alertMes($mes,$redirect);
}//查看文件内容
elseif ($act == "showContent"){
    $content = file_get_contents($filename);
    if (strlen($content)){
    echo <<<END
    <div class="container">
    <textarea readonly="readonly" style="width:100%;background-color: #afd9ee">{$content}</textarea>
</div>
END;
    }else{
        alertMes('文件没有内容，请编辑后查看',$redirect);
    }
}//修改文件内容
elseif($act == "editContent"){
    $content = file_get_contents($filename);
   echo  $str = <<<END
    <div class="container">
    <form action="index.php?act=doEdit" method="post">
    <textarea name="content">{$content}</textarea>
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="修改文件内容">
    </div>
</form>
END;

}//编辑文件内容
elseif($act == "doEdit"){
    $content = $_REQUEST['content'];
    if(file_put_contents($filename,$content)){
        $mes = "文件修改成功！";
    }else{
        $mes = "文件修改失败";
    }
    alertMes($mes,$redirect);
}//重命名文件
elseif($act == "renameFile"){
    echo $str = <<<END
    <form action="index.php?act=doRename" method="post">
    请填写新文件名：<input type="text" name="newname" placeholder="重命名">
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="重命名">   
</form>
END;

}//实现重命名
elseif($act == "doRename"){
    $newname = $_REQUEST['newname'];
    $mes = renameFile($filename,$newname);
    alertMes($mes,$redirect);
}//删除文件
elseif($act == "delFile"){
    $mes = delFile($filename);
    alertMes($mes,$redirect);
}//下载文件
elseif($act == "downFile"){
     downloadFile($filename);
}//复制文件、文件夹
elseif($act == "copyFolder"){
	echo $str = <<<END
    <form action="index.php?act=doCopyFolder" method="post">
    将文件夹到：<input type="text" name="dstname" placeholder="将文件夹到">
    <input type="hidden" name="filename" value="{$filename}">
    <input type="hidden" name="dirname" value="{$dirname}">
    <input type="submit" value="复制文件">   
</form>
END;
}//处理复制文件夹
elseif($act == "doCopyFolder"){
     $dstname = $_REQUEST['dstname'];
     $rst = copyFoler($dirname, $path.'/'.$dstname.'/'.basename($dirname));
     if ($rst){
         $mes = " 文件复制成功！";
         alertMes($mes,$redirect);
     }else{
	     $mes = " 文件复制失败！";
	     alertMes($mes,$redirect);
     }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
	<title>nicecms文件管理系统</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">


	<!-- Custom styles for this template -->
	<link href="navbar.css" rel="stylesheet">


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
    <!--new script-->
    <sctipt src="jquery-3.2.1.min.js"></sctipt>
    <script src="jquery-ui/js/jquery-1.10.2.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css"  type="text/css"/>

    <!--new script-->
    <script type="text/javascript">
        function show(dis) {
            document.getElementById(dis).style.display="block";
        }
        function delFile(filename) {
            if (window.confirm("是否确认删除该文件？")){
                location.href="index.php?act=delFile&filename="+filename;
            }
        }
        function delFolder(dirname, path) {
            if (window.confirm("是否确认删除该文件夹？")){
                location.href="index.php?act=delFolder&dirname="+dirname+"&path="+path;
            }
        }
        /*如果是图片 直接展示之类的设置*/

        function showDetail(t,filename){
            alert('hello');
            $("#showImg").attr("src",filename);
            $("#showDetail").dialog({
                height:"auto",
                width: "auto",
                position: {my: "center", at: "center",  collision:"fit"},
                modal:false,//是否模式对话框
                draggable:true,//是否允许拖拽
                resizable:true,//是否允许拖动
                title:t,//对话框标题
                show:"slide",
                hide:"explode"
            });
        }


        function goBack($back) {
            location.href="index.php?path="+$back;
        }
    </script>
</head>

<body>
<div class="container">
    <h2>nicecms文件管理系统</h2>
    <!--隐藏域 出发鼠标点击事件-->
    <div id="showDetail"  style="display:none"><img src="" id="showImg" alt=""/></div>
</div>

<div class="container">

	<!-- Static navbar -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li>
                <a href="index.php">主目录</a>
            </li>
            <li>
                <a href="#" onclick="show('createFile')" title="新建文件">新建文件</a>
            </li>
            <li>
                <a href="#" onclick="show('createFolder')" title="新建文件夹">新建文件夹</a>
            </li>
            <li>
                <a href="#" onclick="show('uploadFile')" title="上传文件">上传文件</a>
            </li>
            <!--go back-->
        <?php

        $back = ($path == "file")?"file":dirname($path);
        ?>
            <li>
                <a href="#" title="返回上级目录" onclick="goBack('<?php echo $back; ?>')">返回</a>
            </li>
            <!--go back-->
        </ul>
			</div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
	</nav>

    <!--form begin-->
    <div class="table-responsive">
    <form action="index.php" method="post" enctype="multipart/form-data">
        <table class="table table-bordered table-hover" >
            <!--隐藏文件名-->
            <tr id="createFolder" style="display: none">
                <td>创建文件夹</td>
                <td>
                    <input type="text" name="dirname" >
                    <input type="hidden" name="path" value="<?php echo $path;?>">
                    <input type="hidden" name="act" value="createFolder">
                    <input type="submit"  value="创建文件夹">
                </td>
            </tr>
            <!--隐藏文件夹名-->
            <!--隐藏文件名-->
            <tr id="createFile" style="display: none">
                <td>创建文件</td>
                <td>
                    <input type="text" name="filename" >
                    <input type="hidden" name="path" value="<?php echo $path;?>">
                    <input type="hidden" name="act" value="createFile">
                    <input type="submit" value="创建文件">
                </td>
            </tr>
            <!--隐藏文件夹名-->
            <!--隐藏文件名-->
            <tr id="uploadFile" style="display: none">
                <td>上传文件</td>
                <td>
                    <input type="file" name="myFile" >
                    <input type="submit" name="act" value="上传文件">
                </td>
            </tr>
            <!--隐藏文件夹名-->


            <tr align="center">
                <td>序号</td>
                <td>名称</td>
                <td>类型</td>
                <td>大小</td>
                <td>可读</td>
                <td>可写</td>
                <td>可执行</td>
                <td>创建时间</td>
                <td>修改时间</td>
                <td>访问时间</td>
                <td>操作</td>
            </tr>
<!--read file +++++++++++++++++++++++   -->
<?php
    if ($info['file']){
        $i = 1;
        foreach ($info['file'] as $val) {
            //封装
        $p = $path."/".$val;
?>
            <!--neirong -->
    <tr align="center">
        <td><?php echo $i; ?></td>
        <td><?php echo $val;?></td>
        <td><?php echo filetype($p);?></td>
        <td><?php echo transByte(filesize($p));?></td>
        <td>
            <?php if (is_readable($p)) echo "是";?>
        </td>
        <td>
            <?php if (is_writable($p)) echo "是";?>
        </td>
        <td>
            <?php if (is_executable($p))echo "是"; else echo "否";?>
        </td>
        <td>
            <?php echo date("Y-m-d H:i:s", filectime($p));?>
        </td>
        <td>
	        <?php echo date("Y-m-d H:i:s", filemtime($p));?>
        </td>
        <td>
	        <?php echo date("Y-m-d H:i:s", fileatime($p));?>
        </td>
        <td>
<!--获取文件拓展名-->
<?php
$ext = strtolower(end(explode(".",$val)));
$imageExt = array('gif', 'jpg', 'jpeg', 'png');
if (in_array($ext,$imageExt)) {
    ?>
    <a href="#" onclick="showDetail('<?php echo $val;?>', '<?php echo $p;?>');">查看</a>|
    <?php
}else {
?>
            <!--获取文件拓展名-->
            <a href="index.php?act=showContent&filename=<?php echo $p;?>">查看</a>|
	<?php
}
?>
            <a href="index.php?act=editContent&filename=<?php echo $p;?>">编辑</a>|
            <a href="index.php?act=renameFile&filename=<?php echo $p;?>">重命名</a>|
            <a href="">复制</a>|
            <a href="">剪切</a>|
            <a href="#" onclick="delFile('<?php echo $p; ?>')">删除</a>|
            <a href="index.php?act=downFile&filename=<?php echo $p;?>">下载</a>
        </td>
        
        
        
    </tr>

<?php

$i++;
        }
    }//end of if

?>
<!--end of read file-->


<!--读取目录呀呀呀-->

	        <?php
	        if ($info['dir']){
		        $i = $i ==null?1:$i;
		        foreach ($info['dir'] as $val) {
			        //封装
			        $p = $path."/".$val;
			        ?>
                    <!--neirong -->
                    <tr align="center">
                        <td><?php echo $i; ?></td>
                        <td><?php echo $val;?></td>
                        <td><?php echo filetype($p);?></td>
                        <td><?php echo transByte(dirSize($p));?></td>
                        <td>
					        <?php if (is_readable($p)) echo "是";?>
                        </td>
                        <td>
					        <?php if (is_writable($p)) echo "是";?>
                        </td>
                        <td>
					        <?php if (is_executable($p))echo "是"; else echo "否";?>
                        </td>
                        <td>
					        <?php echo date("Y-m-d H:i:s", filectime($p));?>
                        </td>
                        <td>
					        <?php echo date("Y-m-d H:i:s", filemtime($p));?>
                        </td>
                        <td>
					        <?php echo date("Y-m-d H:i:s", fileatime($p));?>
                        </td>
                        <td>

                                <a href="index.php?path=<?php echo $p;?>">查看</a>|


                            <a href="index.php?act=renameFile&filename=<?php echo $p;?>">重命名</a>|
                            <a href="index.php?act=copyFolder&&path=<?php echo $path;?>&&dirname=<?php echo $p;?>">复制</a>|
                            <a href="">剪切</a>|
                            <a href="#" onclick="delFile('<?php echo $p; ?>')">删除</a>|
                            <a href="index.php?act=downFile&filename=<?php echo $p;?>">下载</a>
                        </td>



                    </tr>

			        <?php

			        $i++;
		        }
	        }//end of if



	        ?>




            <!--读取目录呀呀呀-->







        </table>
    </form>
    </div>
    <!--form end -->

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

