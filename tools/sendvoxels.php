<?php
function send_voxels($inputFileName,$server,$port,$command) {
	$socketHandle = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	$serverIP = $server; 
	$serverSendPort = $port;

	$inputFile = fopen($inputFileName, "rb"); // these are text files
	$voxNum=0;
	while (!feof($inputFile) ) {
		// [1:'I'][2:num]...
		$netData = pack("cv",ord($command),$voxNum);

		$packetSize = 3; // to start
		while ($packetSize < 800) {
			$octets = fread($inputFile,1);
			$octets = (int)ord($octets);
			echo "read octets=$octets\n";

			$size = ceil($octets*3/8)+3;
			$fileData = fread($inputFile,$size);
			$size++; // add length of octet

			// ...[1:octets][n:xxxxx]
			$netData .= pack("c",$octets).$fileData;
			$packetSize+=$size;
			echo "sending adding octets=$octets size=$size to packet packetSize=$packetSize\n";
		}
		
		echo "sending packet server=$serverIP port=$serverSendPort $voxNum size=$packetSize result=$result\n";
		$result = socket_sendto($socketHandle, $netData, $packetSize, 0, $serverIP, $serverSendPort);
		usleep(20000); // 1,000,000 per second
		$voxNum++;
	}
	socket_close($socketHandle);
}

function testmode_send_voxels($server,$port) {
	echo "psych! test mode not implemented!\n";
}

$options = getopt("i:s:p:c:",array('testmode'));

if (empty($options['i']) || empty($options['i'])) {
	echo "USAGE: sendvoxels.php -i 'inputFileName' -s [serverip] -p [port] -c [I|R] \n";
} else {
	$filename = $options['i'];
	$server = $options['s'];
	$port = empty($options['p']) ? 40106 : $options['p'];
	$command = empty($options['c']) ? 'I' : $options['c'];
	switch($command) {
		case 'I':
		case 'R':
			//$command is good
		break;
		default:
			$command='I';// insert by default!
	}

	if ($options['testmode']) {
		echo "TEST MODE Sending Voxels server:$server port:$port \n";
		testmode_send_voxels($server,$port);
	} else {
		echo "Sending Voxels file:$filename server:$server port:$port command:$command \n";
		send_voxels($filename,$server,$port,$command);
	}
}

?>