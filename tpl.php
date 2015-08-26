<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>
        新闻
    </title>

    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- 可选的Bootstrap主题文件（一般不用引入） -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    
    <!--[if lt IE 9]><script type="text/javascript" src="excanvas.js"></script><![endif]-->
 
    <script src="./tagcanvas.min.js" type="text/javascript"></script>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!--此处添加-->
    <style type="text/css">
    .container-fluid,.main{
        overflow:hidden;
    }
    .sidebar{
        overflow-y:scroll;
        overflow-x:hidden;
        height:60%;
    }
    .bottom-canvas{
        background:#dfdfdf;
        border-top:solid 2px #444;
        clear:both;
    }
    </style>
</head>
<body screen_capture_injected="true">
<script type="text/javascript">
  var o = {
      textHeight: 25, 
      shape: "sphere"
    };
  window.onload = function() {
    try {
      
      TagCanvas.Start('myCanvas','extags0',o);
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById('myCanvasContainer').style.display = 'none';
    }
    
    try {

      //<!--此处添加-->
      var h = document.documentElement.clientHeight;
      $(".container-fluid").height(h);
      $(".container-fluid .main").height(h*0.9);
      $(".container-fluid .sidebar").height(h*0.9);
      $('.container-fluid .bottom-canvas').height(h*0.1-2);
  
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById('myCanvasContainer').style.display = 'none';
    }
  };
  
  function cloudreload(id){
      
     try {
      TagCanvas.Start('myCanvas',id,o);
    } catch(e) {
      // something went wrong, hide the canvas container
      document.getElementById('myCanvasContainer').style.display = 'none';
    }
  }
 </script>
 
 <?php
$jstr=file_get_contents('./json.txt');
$news = json_decode($jstr, true);
$wordsstyle = array('label-primary' , 'label-success', 'label-info',  'label-warning', 'label-danger');
$progstyle = array('progress-bar-primary' , 'progress-bar-success', 'progress-bar-info',  'progress-bar-warning', 'progress-bar-danger');
$total = 0;
$max = 0;
foreach($news as $item)
{
    $total += $item[1]['c'];
}

$i=0;
foreach($news as $item)
{
?>
     <div style="display: none" id="extags<?php echo $i;?>">
          <?php
            foreach($item[1]['l'] as $ne)
            {
                echo "<a href='{$ne['u']}'>{$ne['t']}</a>";
            }
          ?>
     </div>
<?php
     $i++;
}
?>
    
    <div class="container-fluid">

    
        <div class="row">
            <div class="col-md-9 main">
                <div id="myCanvasContainer">
                    <canvas width="800px" height="800px" id="myCanvas" style="width: 100%">
                    <p>Example canvas</p>
                    </canvas>
                </div>
            </div>
            <div class="col-md-3 sidebar ">
                <table width="100%">
                <?php
                $i=0;
                foreach($news as $item)
                {
                    if($max === 0)
                    {
                        $max = $item[1]['c'];
                    }

                    $labelstyle = $wordsstyle[array_rand($wordsstyle)];
                    $pstyle = $progstyle[array_rand($progstyle)];
                    $xxpercent = intval(floatval($item[1]['c'])*100/$max);
                    ?>
                    
                        <tr>
                            <td align="right">
                                <span class="badge"><?php echo $item[1]['c'];?></span>
                                <span onclick="cloudreload('extags<?php echo $i;?>');" class="label <?php echo $labelstyle;?>"><?php echo $item[0];?></span>
                            </td>
                            <td align="left">
                                <div  style="width:200px;height:2px;margin-bottom: 0px;margin-left:6px;overflow: hidden;">
                                    <div class="progress-bar <?php echo $pstyle;?>" role="progressbar" aria-valuenow="<?php echo $xxpercent;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $xxpercent;?>%">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="5px"></td>
                            <td></td>
                        </tr>
                   
                    
             
                    <?php

                    $i++;
                }


                ?>
                 </table>
            </div>
        </div>
        <!--此处添加-->
        <div class="bottom-canvas"> 
        </div>
        

    </div>
</body>
</html>
