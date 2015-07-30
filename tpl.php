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

</head>
<body>
    <div class="container theme-showcase" role="main">
    <?php

    $jstr=file_get_contents('./json.txt');
    $news = json_decode($jstr, true);
    $wordsstyle = array('label-primary', 'label-primary' , 'label-success', 'label-info',  'label-warning', 'label-danger');
    $max = 0;
    foreach($news as $item)
    {
        if ($max===0) $max=$item[1]['c'];

        $labelstyle = $wordsstyle[array_rand($wordsstyle)];
        echo "<div style='padding: 8px;0px;8px;0px;'>";
        echo "<span class=\"label {$labelstyle}\">{$item[0]}</span><span class=\"badge\">{$item[1]['c']}</span>";
        echo "</div>";
        $xxpercent = intval(floatval($item[1]['c'])*100/$max);
$pro=<<<EOF
        <div class="progress">
            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{$xxpercent}" aria-valuemin="0" aria-valuemax="100" style="width: {$xxpercent}%">
                <span class="sr-only">60% Complete (warning)</span>
            </div>
        </div>
EOF;
        echo $pro;
        echo "<div class=\"well\">";

            foreach($item[1]['l'] as $ne)
            {
                echo "<button type=\"button\" class=\"btn btn-xs btn-default\"><a href='{$ne['u']}'>{$ne['t']}</a></code></button><br>";
            }
            echo "</div>";
    }


    ?>

    </div>
</body>
</html>
