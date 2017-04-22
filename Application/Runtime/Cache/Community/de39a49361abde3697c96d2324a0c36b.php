<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	 <meta charset="utf-8">
	<title>微+联盟</title>
	 	<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/2017/weixiao/Public/css/style.css">
	<script type="text/javascript" src="/2017/weixiao/Public/js/check.js"></script>

</head>
<body>
 <div id="cover" style="opacity:1;">
 	<div id="zhaozi"></div>
	<div class="top">

        <form action="" method="get" id="search">
        	<input type="text" value="<?php  echo isset($_GET['title'])?$_GET['title']:''; ?>" id="search_txt" />

        	<input type="button"id="search_btn" />
        </form>
	</div>

	<div class="mainbody">
			<table id="list3" class="list">
			  <td><a href="index?user=1" name="wxh">热门微信平台</a></td>
			  <td><a href="index?user=2" name="wxh">最新消息推送</a></td>
			  <tbody>
			 </tbody>
	  </table>
	</div>	
<?php  $i=1; if ($_GET['user']!=2) { $dm=1; ?>
	 <?php  if($dm!=1){?>
	 <div class="mainbody">
	 		<table id="list3" class="list">
	 		  <caption class="text-left"><h2>学校最热公众号</h2></caption>
	 		  <tbody>
	 		  <?php  for($i=0; $i < $dm; $i++) { if($i >3){ break; } ?>
	 		   <tr>
	 		  <?php  for($k=0;$k<$much;$k++) { $j = $i*3+$k; if(isset($dat1[$j]['title'])){ if($j >= 9){ ?>
	 				<?php break; }else{ ?>
	 			<td><a href="javascript:;" name="<?php echo $dat1[$j]['wx']; ?>">
	 				<?php if($dat1[$j]['title'] != ''){ echo $dat1[$j]['title']; } else{ echo "无法显示"; } ?></a></td>
	 			<?php  } }else{ break; } } ?>
	 		  </tr>
	 		  <?php } ?>
	 		 </tbody>
	 		</table>
	 </div>
	 <?php } ?>
	
	 <?php  if($sd){ ?>
	 <div class="mainbody">
	 		<table id="list3" class="list">
	 		  <caption class="text-left"><h2>学院微信平台</h2></caption>
	 		  <tbody>
	 		  <?php  for($i=0; $i < $sd; $i++) { if($i >4){ break; } ?>
	 		   <tr>
	 		  <?php for($k=0;$k<$much;$k++) { $j = $i*3+$k; if(isset($dat2[$i])){ if($j >= 11){ ?>
	 			<td class="more"><a href="index.php?type=1">查看更多&gt;&gt;</a></td>
	 				<?php break; }else{ ?>
	 			<td><a href="javascript:;" name="<?php echo $dat2[$j]['wx']; ?>"><?php if($dat2[$j]['title'] != ''){ echo $dat2[$j]['title']; }else{ echo "无法显示"; } ?></a></td>
	 			<?php  }}else{ break; } } ?>
	 		  </tr>
	 		  <?php } ?>
	 		 </tbody>
	 		</table>
	 </div>
	 <?php } ?>

	 <?php  if($ps){ ?>
	 <div class="mainbody">
	 		<table id="list3" class="list">
	 		  <caption ><h2>学校微信平台</h2></caption>
	 		  <tbody>
	 		  <?php  for ($i=0; $i < 4; $i++) { if($i >4){ break; } ?>
	 		    <tr>
	 		  <?php for($k=0;$k<$much;$k++) { $j = $i*3+$k; $dat3[11]='1'; if(isset($dat3[$j])){ if($j >= 11){ ?>
	 			<td class="more"><a href="index.php?type=2">查看更多&gt;&gt;</a></td>
	 				<?php break; }else{ ?>
	 			<td><a href="javascript:;" name="<?php echo $dat3[$j]['wx']; ?>"><?php if($dat3[$j]['title'] != ''){ echo $dat3[$j]['title']; }else{ echo "无法显示"; } ?></a></td>
	 			<?php  }}else{ break; } } ?>
	 		  </tr>
	 		  <?php } }?>
	 		 </tbody>
	 		</table>
	 </div>
	 <div class="foot">
	 	<h2>微<sup>+</sup>联盟出品</h2>
	 </div>
	 <?php
 }else{ $mu=1; for ($i=0; $i < $ad; $i++) { ?>
	 <div class="mainbody">
	 		<table id="list3" class="list">
	 		  <tr><td class="more"><a href="<?php echo $zrxx[$i]['url'];?>" align="left">
	 		  	<?php  echo $no.$mu."  ".$zrxx[$i]['title']; $mu++; ?> </a></td>
				<td class="more"><a href="<?php echo $zrxx[$i]['url'];?>" align="right"> <?php echo $djl.$zrxx[$i]['readnum'];?></a></td>
	 		  <tbody>
	 		 </tbody>
	 		</table>
	 </div>
		
	 <?php  } $mu=1; for ($i=0; $i < $ad; $i++) { ?>
	 <div class="mainbody">
	 		<table id="list3" class="list">
	 		  <tr><td class="more"><a href="<?php echo $xgzh[$i]['url'];?>" align="left">
	 		  	<?php  echo "其他精彩:".$xgzh[$i]['title']; $mu++; ?> </a></td>
	 		  <tbody>
	 		 </tbody>
	 		</table>
	 </div>
		
	 <?php  } }?>
	 <div id="detail">
	  <nav class="navbar" id="nav">
            <img src="/2017/weixiao/Public/img/header.jpg"/>重邮e站微+平台
            <span id="close"></span>
       </nav>
      
         <div class="jumbotron ">
           <img id="ewm_img" valign="middle" alt="二维码"/>
           <div id="show_wxname" align="left" valign="top">
		  <p class="title_p" align="left" valign="top"></p>
		  <p><b class="weixin" align="left" valign="top"></b><br/>
		  长按识别二维码关注</p>
		  </div>
	   </div>
		 <div class="panel ">
		  <div id="text_title" align="left" valign="top">
		    <h3 class="title_p" align="left" valign="top"></h3>
		  </div>
		  <div class="panel-body" align="left" valign="top">
		   <p class="desc"></p>
		  </div>
		</div>
	 </div>
</div>
	<script type="text/javascript" src="/2017/weixiao/Public/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/2017/weixiao/Public/js/js.js"></script>
</body>
</html>