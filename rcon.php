<?php
/* ^-^  15.09.2020  PHP 7.0 - 7.4
 * Author: admin@ileies.de | Ileies LLC
 * example: rcon("example.com", "password123", "kill Notch");
*/
function rcon($host, $password, $command) {
	// start a new session where the host is pinged
	$socket = fsockopen($host, 25575);
	// Don't continue if the request failed
	if(!$socket) return false;
	// Format the data you want to send
	$packet = pack('VV', 5, 3)."$password\x00\x00";
	$packet = pack('V', strlen($packet)).$packet;
	// Send the data to access the server
	fwrite($socket, $packet, strlen($packet));
	// Get the answer of the server
	$packet_pack = unpack('V1id/V1type/a*body', fread($socket, unpack('V1size', fread($socket, 4))['size']));
	// Don't continue if it says that it failed
	if($packet_pack['type'] != 2 || $packet_pack['id'] != 5) {if($socket) fclose($socket);return false;}
	// Format the command
	$packet2 = pack('VV', 6, 2)."$command\x00\x00";
	$packet2 = pack('V', strlen($packet2)).$packet2;
	// Send it to the server
	fwrite($socket, $packet2, strlen($packet2));
}
?>