<?php
$registration_id=$_POST['registid'];
switch ($_POST['id']) {
	case '1':
// 更新 Alias
	require __DIR__ . '/../conf.php';
		$Alias=$_POST['Alias'];
		$result = $client->device()->getDevices($registration_id);
		print "before update alias = " . $result['body']['alias'] . "\n";

		print 'updating alias ... response = ';
		$response = $client->device()->updateAlias($registration_id, $Alias);
		print_r($response);

		$result = $client->device()->getDevices($registration_id);
		print "after update alias = " . $result['body']['alias'] . "\n\n";
		break;
	case '2':
// 添加 tag
	require __DIR__ . '/../conf.php';
		$tag=$_POST['tag'];
		$result = $client->device()->getDevices($registration_id);
		print "before add tags = [" . implode(',', $result['body']['tags']) . "]\n";

		print 'add tag1 tag2 ... response = ';

		$response = $client->device()->addTags($registration_id, $tag);
		print_r($response);

		// $response = $client->device()->addTags($registration_id, ['tag1', 'tag2']);
		// print_r($response);

		$result = $client->device()->getDevices($registration_id);
		print "after add tags = [" . implode(',', $result['body']['tags']) . "]\n\n";
		break;
	case '3':
// 移除 tag
	require __DIR__ . '/../conf.php';
		$tag=$_POST['tag'];
		$result = $client->device()->getDevices($registration_id);
		print "before remove tags = [" . implode(',', $result['body']['tags']) . "]\n";

		print 'removing tag1 tag2 ...  response = ';

		$response = $client->device()->removeTags($registration_id, $tag);
		print_r($response);

		// $response = $client->device()->removeTags($registration_id, ['tag1', 'tag2']);
		// print_r($response);

		$result = $client->device()->getDevices($registration_id);
		print "after remove tags = [" . implode(',', $result['body']['tags']) . "]\n\n";
		break;
	case '4':
// 更新 mobile
	require __DIR__ . '/../conf.php';
		$result = $client->device()->getDevices($registration_id);
		print "before update mobile = " . $result['body']['mobile'] . "\n";

		print 'updating mobile ... response = ';
		$response = $client->device()->updateMoblie($registration_id, '13800138000');
		print_r($response);

		$result = $client->device()->getDevices($registration_id);
		print "after update mobile = " . $result['body']['mobile'] . "\n\n";
		break;
	
	default:
		echo "1";
		break;
}