<?php
require __DIR__ . '/../conf.php';
if ($_POST['type']==1) {
	$alias=$_POST['tag'];
	// 为一个标签添加设备
	if ($_POST['id']!="") {
		$registration_id=$_POST['id'];
		for ($i=0;$registration_id[$i]; $i++) { 
			$result = $client->device()->isDeviceInTag($registration_id, $alias);
			$r = $result['body']['result'] ? 'true' : 'false';
			print "before add device = " . $r . "\n";

			print 'adding device ... response = ';
			$response = $client->device()->addDevicesToTag($alias, $registration_id);
			print_r($response);

			$result = $client->device()->isDeviceInTag($registration_id, $alias);
			$r = $result['body']['result'] ? 'true' : 'false';
			print "after add tags = " . $r . "\n\n";
		}
	}
}elseif ($_POST['type']==2) {
	// 为一个标签删除设备
	if ($_POST['name']!="") {
		$registration_id=$_POST['id'];
		for ($i=0;$registration_id[$i]; $i++) { 
				$result = $client->device()->isDeviceInTag($registration_id, $alias);
				$r = $result['body']['result'] ? 'true' : 'false';
				print "before remove device = " . $r . "\n";

				print 'removing device ...  response = ';
				$response = $client->device()->removeDevicesFromTag($alias, $registration_id);
				print_r($response);

				$result = $client->device()->isDeviceInTag($registration_id, $alias);
				$r = $result['body']['result'] ? 'true' : 'false';
				print "after remove device = " . $r . "\n\n";
			}
	}
}else{
	echo "1";
}
//$registration_id='140fe1da9ea69a8fe0b';
