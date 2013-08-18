<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Jianshu RSS</title>
    <link rel="icon" type="image/png" href="<?=$rootUri?>/assets/img/icon.png">
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.0.0-rc1/css/bootstrap.min.css">
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
            <input id="url" type="text" class="form-control" name="url" value="" placeholder="请输入简书的地址，如：http://jianshu.io/recommendations/notes">
            <input id="submit-url" type="submit" class="btn btn-primary btn-lg btn-block" value="Go">
        </div>
    </form>

    <div class="hot-links">
        <ul class="list-unstyled list-inline clearfix">
            <li><a target="_blank" href="<?=$rootUri?>/feeds/recommendations/notes" title="简书编辑推荐">编辑推荐</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/collections/u1J6LM" title="思考中国的过去、现在及未来。">想想中国</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/users/y4D4YX" title="滤镜菲林是一个基于互联网平台的新闻实验室，欢迎各位媒体极客。">滤镜菲林</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/users/QAARHp" title="内涵不是吹的">李铃铛</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/users/y3Dbcz" title="我乃简叔，简书联合创始人。">linlis</a></li>
            <li><a target="_blank" href="<?=$rootUri?>/feeds/users/kjtzTG" title="越正经就越不正经，可还是要正经；越希望就越绝望，可还是要希望。">Light</a></li>
        </ul>
    </div>

    <div class="footer">
        <div class="author">
            ♥ Lovingly made by <a data-toggle="tooltip" title="Coder" href="http://milkythinking.com">hutushen222</a> & <a data-toggle="tooltip" title="PAGE MAN" href="http://parazzi.me/about">JoeZhao</a>.
        </div>
    </div>

</div>
<script type="text/javascript" src="http://cdn.staticfile.org/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
</body>
</html>