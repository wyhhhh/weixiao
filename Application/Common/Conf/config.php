<?php
return array( 
	//'配置项'=>'配置值'
	'LOAD_EXT_CONFIG'	=>'db',
	'APP_GROUP_LIST'        => array('Manage','Community','Home','AppUser','AppRepairUser','AppCommunityRepair','AppKeeper'), //Manage 管理后台,Community 物业管理后台, Home 前端页面, AppUser 用户端, AppRepairUser 服务人员端, AppCommunityRepair 社区维修人员端	,AppKeeper app管理员端
	'DEFAULT_MODULE'        => 'Community', 		// 默认分组
	'URL_ROUTER_ON'         => true,   // 是否开启URL路由
    'VAR_PAGE'  => 'pageNumber', //分页变量名
	'URL_MODEL'      => 1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 'TMPL_ACTION_ERROR'=>'./Public/Tips/error.html', // 定义公共错误模板
    // 'TMPL_ACTION_SUCCESS'=>'./Public/Tips/success.html', // 定义公共错误模板
    // 'TMPL_EXCEPTION_FILE'=>'./Public/tips/500.html',// 异常页面的模板文件
    // 'TMPL_404_FILE'=>'/Public/tips/404.html',// 异常页面的模板文件 
    
    //邮件配置,注释配置从数据库获取的
    //'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    'MAIL_PORT' =>'465',//smtp服务器端口
    'MAIL_SECURE' =>'tls',//
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    //'MAIL_USERNAME' =>'13648306805@163.com',//你的邮箱名
    //'MAIL_FROM' =>'13648306805@163.com',//发件人地址
    //'MAIL_FROMNAME'=>'lincong',//发件人姓名
    //'MAIL_PASSWORD' =>'admin888',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    // 'DEFAULT_FILTER'  =>  'strip_tags,stripslashes',
);