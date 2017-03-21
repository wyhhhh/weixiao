<?php
header('Content-type:text/json'); 
if ($_POST['type']) {
    $type=$_POST['type'];
    switch ($type) {
        case '1':
            //报修
            $from=$_POST['from'];
            switch ($from) {
                case '1':
                    $repairid=$_POST['repairid'];
                    $RegistrationId="";
                    $content='点击查看详情';
                    $title='保修订单';
                    $url=$_POST['url'];
                    $Alias='guanli';
                    $tag=1;
                    post($tag,$title,$content,$url,$repairid,$RegistrationId,$Alias);
                    break;
                case '2':
                    $repairid=$_POST['repairid'];
                    // $db=M('');
                    // $sql1='SELECT com.name as cname,bd.alias_name,floor.fname,un.uname,house.name as houname,user.real_name,user.id as name FROM `xly_house` as house left join xly_floor as floor on fl.or.id = house.floorid left join xly_unit as un on un.id = floor.unitid left join xly_building as bd on bd.id = un.buildingid left join xly_users as user on user.house_id = house.id left join xly_community as com on com.id = house.communityid WHERE user.RegistrationId="'.$repairuserid.'" AND user.id is not null AND bd.id is not null AND un.id is not null ORDER BY id desc ';
                    // $rolelist=$db->query($sql1);
                    // $RegistrationId=$rolelist[0]['RegistrationId'];
                    $content='点击查看详情';
                    $title='保修订单';
                    $url=$_POST['url'];
                    $tag=$_POST['tag'];
                    post($tag,$title,$content,$url,$repairid,$RegistrationId);
                    break;
                case '3':
                    $repairid=$_POST['repairid'];
                    // $sql1='SELECT com.name as cname,bd.alias_name,floor.fname,un.uname,house.name as houname,user.real_name,user.id as name FROM `xly_house` as house left join xly_floor as floor on fl.or.id = house.floorid left join xly_unit as un on un.id = floor.unitid left join xly_building as bd on bd.id = un.buildingid left join xly_users as user on user.house_id = house.id left join xly_community as com on com.id = house.communityid WHERE user.RegistrationId="'.$RegistrationId.'" AND user.id is not null AND bd.id is not null AND un.id is not null ORDER BY id desc ';
                    // $rolelist=$db->query($sql1);
                    // $RegistrationId=$rolelist[0]['RegistrationId'];
                    $content='点击查看详情';
                    $title='保修人员正在派送';
                    $url=$_POST['url'];
                    $tag=$_POST['tag'];
                    post($tag,$title,$content,$url,$repairid,$RegistrationId);
                    break;
                
                default:
                    exit;
                    break;
            }
            break;
        case '2':
            // //微卖私信
            $repairid=$_POST['id'];
            $content='点击查看详情';
            $title='微卖私信';
            $url=$_POST['url'];
            $tag=$_POST['tag'];
            post($tag,$title,$content,$url,$repairid,$RegistrationId);
            break;
        case '3':
            // //论坛评论
            $repairid=$_POST['id'];
            $content='点击查看详情';
            $title='论坛评论';
            $url=$_POST['url'];
            $tag=$_POST['tag'];
            post($tag,$title,$content,$url,$repairid,$RegistrationId);
            break;
        case '4':
            //维修提交
            $from=$_POST['from'];
            switch ($from) {
                case '1':
                    $repairid=$_POST['repairid'];
                    $content='点击查看详情';
                    $title='保修订单/管理端收';
                    $url=$_POST['url'];
                    $Alias='guanli';
                    $tag=1;
                    post($tag,$title,$content,$url,$repairid,$RegistrationId,$Alias);
                    break;
                case '2':
                    $repairid=$_POST['repairid'];
                    // $db=M('');
                    // $sql1='SELECT com.name as cname,bd.alias_name,floor.fname,un.uname,house.name as houname,user.real_name,user.id as name FROM `xly_house` as house left join xly_floor as floor on fl.or.id = house.floorid left join xly_unit as un on un.id = floor.unitid left join xly_building as bd on bd.id = un.buildingid left join xly_users as user on user.house_id = house.id left join xly_community as com on com.id = house.communityid WHERE user.RegistrationId="'.$repairuserid.'" AND user.id is not null AND bd.id is not null AND un.id is not null ORDER BY id desc ';
                    // $rolelist=$db->query($sql1);
                    // $RegistrationId=$rolelist[0]['RegistrationId'];
                    $content='点击查看详情';
                    $title='保修订单/维修端收';
                    $url=$_POST['url'];
                    $tag=$_POST['tag'];
                    post($tag,$title,$content,$url,$repairid,$RegistrationId);
                    break;
                case '3':
                    $repairid=$_POST['repairid'];
                    // $sql1='SELECT com.name as cname,bd.alias_name,floor.fname,un.uname,house.name as houname,user.real_name,user.id as name FROM `xly_house` as house left join xly_floor as floor on fl.or.id = house.floorid left join xly_unit as un on un.id = floor.unitid left join xly_building as bd on bd.id = un.buildingid left join xly_users as user on user.house_id = house.id left join xly_community as com on com.id = house.communityid WHERE user.RegistrationId="'.$RegistrationId.'" AND user.id is not null AND bd.id is not null AND un.id is not null ORDER BY id desc ';
                    // $rolelist=$db->query($sql1);
                    // $RegistrationId=$rolelist[0]['RegistrationId'];
                    $content='点击查看详情';
                    $title='保修人员正在派送';
                    $url=$_POST['url'];
                    $tag=$_POST['tag'];
                    post($tag,$title,$content,$url,$repairid,$RegistrationId);
                    break;
                
                default:
                    exit;
                    break;
            }
        default:
            $content=$_POST['content'];
            $title=$_POST['title'];
            $url=$_POST['url'];
            $tag=$_POST['tag'];
            post($tag,$title,$content,$url,$repairid,$RegistrationId);
            exit;
            break;
    }
}else{
    echo "0";
}
function post($tag=1,$title,$content,$url="",$repairid="",$RegistrationId="",$Alias="")
{
    require 'conf.php';
    if($tag!=1){
        try {
            $response = $client->push()
                ->setPlatform(array('ios', 'android'))
                // ->addAllAudience()
                // ->addAlias('all')
                ->addTag(array($tag))
                // ->addRegistrationId('140fe1da9ea69a8fe0b')
                // ->addRegistrationId($RegistrationId)
                ->setNotificationAlert($content)
                ->iosNotification($content, array(
                    'title' => $title,
                    // 'badge' => '+1',
                    // 'content-available' => true,
                    // 'mutable-content' => true,
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->androidNotification($content, array(
                    'title' => $title,
                    // 'build_id' => 2,v
                ))
                ->message('message content', array(
                    // 'content_type' => 'text',
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->options(array(
                    'sendno' => 100,
                    'time_to_live' => 10,
                    'apns_production' => false,
                    'big_push_duration' => 2
                ))
                ->send();
                // $arr1=array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
                // $J=json_encode($arr1);
                // print_r($J); 
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            print $e;
        }
    }elseif ($RegistrationId!="") {
        try {
            $response = $client->push()
                ->setPlatform(array('ios', 'android'))
                // ->addAllAudience()
                // ->addAlias('all')
                // ->addTag(array($tag))
                // ->addRegistrationId('140fe1da9ea69a8fe0b')
                ->addRegistrationId($RegistrationId)
                ->setNotificationAlert($content)
                ->iosNotification($content, array(
                    'title' => $title,
                    // 'badge' => '+1',
                    // 'content-available' => true,
                    // 'mutable-content' => true,
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->androidNotification($content, array(
                    'title' => $title,
                    // 'build_id' => 2,v
                ))
                ->message('message content', array(
                    // 'content_type' => 'text',
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->options(array(
                    'sendno' => 100,
                    'time_to_live' => 10,
                    'apns_production' => false,
                    'big_push_duration' => 2
                ))
                ->send();
                // $arr1=array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
                // $J=json_encode($arr1);
                // print_r($J); 
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            print $e;
        }
    }if ($Alias!="") {
        try {
            $response = $client->push()
                ->setPlatform(array('ios', 'android'))
                // ->addAllAudience()
                ->addAlias($Alias)
                // ->addTag(array($tag))
                // ->addRegistrationId('140fe1da9ea69a8fe0b')
                // ->addRegistrationId($RegistrationId)
                ->setNotificationAlert($content)
                ->iosNotification($content, array(
                    'title' => $title,
                    // 'badge' => '+1',
                    // 'content-available' => true,
                    // 'mutable-content' => true,
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->androidNotification($content, array(
                    'title' => $title,
                    // 'build_id' => 2,v
                ))
                ->message('message content', array(
                    // 'content_type' => 'text',
                    'extras' => array(
                        'url' => $url,
                        'repairid' => $repairid
                    )
                ))
                ->options(array(
                    'sendno' => 100,
                    'time_to_live' => 10,
                    'apns_production' => false,
                    'big_push_duration' => 2
                ))
                ->send();
                // $arr1=array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
                // $J=json_encode($arr1);
                // print_r($J); 
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            print $e;
        }
    }
    print_r($response);
}
