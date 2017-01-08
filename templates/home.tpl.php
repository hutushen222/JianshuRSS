<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Jianshu RSS</title>
    <link rel="icon" type="image/png" href="<?=$rootUri?>/assets/img/icon.png">
    <link rel="stylesheet" href="<?=$rootUri?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$rootUri?>/assets/css/animate.min.css">
    <link rel="stylesheet" href="<?=$rootUri?>/assets/css/style.css">
</head>
<body>

<div id="JSR-container">

    <h1 class="animated bounceInUp ">Jianshu RSS
        <span class="particle particle--c"></span><span class="particle particle--a"></span><span class="particle particle--b"></span>
    </h1>

    <form action="<?php echo $action; ?>" method="post">
        <div class="form-group">
            <input id="url" type="text" class="form-control" name="url" value="" placeholder="请输入简书的地址，如：http://www.jianshu.com">
            <input id="submit-url" type="submit" class="btn btn-primary btn-lg btn-block" value="Go">
        </div>
    </form>

    <div class="hot-links">
        <ul class="list-unstyled list-inline clearfix">
            <li><a target="_blank" href="<?=$rootUri?>/feeds/homepage" title="简书首页">首页</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/recommendations/notes/latest" title="新上榜">新上榜</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/recommendations/notes/daily" title="日报">日报</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/trending/weekly" title="7日热门">7日热门</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/trending/monthly" title="30日热门">30日热门</a></li>
        </ul>
    </div>

    <div class="footer">
        <div class="author">
            <span>♥ Lovingly made by <a data-toggle="tooltip" title="Coder" href="http://milkythinking.com">hutushen222</a> & <a data-toggle="tooltip" title="PAGE MAN" href="http://parazzi.me/about">JoeZhao</a>.</span>
            <br>
            <span>Feedback to: <a href="http://weibo.com/hutushen222" target="_blank" title="Weibo">hutushen222</a></span>
        </div>
    </div>

</div>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-43275645-1', 'milkythinking.com');
    ga('send', 'pageview');

</script>
</body>
</html>
