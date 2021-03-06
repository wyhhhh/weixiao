<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>重邮微校-后台管理</title>
    <link rel="Shortcut Icon" href="/2017/weixiao/Public/index.png">
    <meta name="keywords" content="重邮微校">
    <meta name="description" content="重邮微校">
    <link href="/2017/weixiao/Public/hplus/css/bootstrap.min.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/animate.css" rel="stylesheet">
<link href="/2017/weixiao/Public/hplus/css/style.css" rel="stylesheet">
<link href="/2017/weixiao/Public/system/css/common.css" rel="stylesheet">
    <link href="/2017/weixiao/Public/hplus/css/login.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
    <style>
    	.layui-layer-content{
    		color: #676a6c;
    	}
    </style>
</head>
<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                        <h1>重邮微校管理平台</h1>
                    </div>
                    <div class="m-b"></div>
                    <h4>欢迎进入 <strong>重邮微校管理系统</strong></h4>
                    <ul class="m-b">
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 微信管理</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 联盟管理</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 投票管理</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 百科管理</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 图库管理</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 预约管理</li>
                    </ul>
                    <!--<strong>还没有账号？ <a href="#">立即注册&raquo;</a></strong>-->
                </div>
            </div>
            <div class="col-sm-5">
                <form method="post" id='MainForm' action="">
                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md">登录到重邮微校管理系统</p>
                    <input type="text" class="form-control uname" placeholder="用户名" />
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>&nbsp;请输入你的用户名</span>
                    <input type="password" class="form-control pword m-b" placeholder="密码" /><span class="help-block m-b-none"><i class="fa fa-info-circle"></i>&nbsp;请输入你的密码</span>
                    <a href="javascript:void()">忘记密码了？</a>
                    <button type="button" class="btn btn-success btn-block" onclick="Submit()">登录</button>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2017 All Rights Reserved. 项目部
            </div>
        </div>
    </div>
    <script src="/2017/weixiao/Public/hplus/js/jquery.min.js?v=2.1.4" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/hplus/js/bootstrap.min.js?v=3.3.6" charset="UTF-8"></script>
<script src="/2017/weixiao/Public/system/js/common.js" charset="UTF-8"></script>

<!-- 自定义js -->
<script src="/2017/weixiao/Public/hplus/js/content.js" charset="UTF-8"></script>




    <script src="/2017/weixiao/Public/hplus/js/plugins/layer/layer.min.js"></script> 
    <script type="text/javascript">
        $(function(){
            $(document).keydown(function(e){
                if(e.keyCode == "13"){
                    Submit();
                }
            });
        });
        function Submit() {
            // $("#J_login").submit();
            // die();
            var code = $("#verify").val();
            var username = $("input[placeholder='用户名']").val();
            var password = $("input[placeholder='密码']").val();
            if (username == "") {
			    layer.msg("请输入用户名", {
			        icon: 2,
			        closeBtn: 0,
			        time: 2000
			    }); 
            }
            else if (password == "") {
			    layer.msg("请输入密码", {
			        icon: 2,
			        closeBtn: 0,
			        time: 2000
			    }); 
            }
            else { 
                $.post("<?php echo U('Community/Index/index');?>", {
                    username: username,
                    password: password,
                    code: code
                },
                function (res) {
                    if (res.status == 1) {
                        layer.msg('登录成功，正在跳转...', {
                            icon: 1
                        });
                        window.location.href = "<?php echo U('Community/Index/main');?>";
                    }
                    else {
                        updateCode();
                        layer.alert(res.msg, {
                            icon: 2
                        })
                    }
                })
            }
        }
        function updateCode() {
            var img = $("#verifyImg");
            var local_url = window.location.toString();
            var lj = (local_url.indexOf('?') < 0) ? '?' : '&';
            var src = img.attr('src') + lj + Math.random();
            img.attr('src', src);
        }
    </script>
</body> 
</html>