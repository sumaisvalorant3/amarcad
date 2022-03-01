<?php

/* q3query.class.php - Quake 3 query class
 *
 * Copyright (C) 2009 Manuel Kress
 * Author(s): Manuel Kress (manuel.strider@web.de)
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses/>.
 *
 */

class q3query {

	private $address;
	private $serverport;
    private $rconpassword = false;
    private $fp;
    private $lastPing = false;

    public function __construct($address, $serverport, &$success = NULL, &$errno = NULL, &$errstr = NULL) {
    	$this->address = $address;
    	$this->port = $serverport;

        $this->fp = fsockopen("udp://$address", $serverport, $errno, $errstr, 5);
        if (!$this->fp) {
        	$success = false;
        }
        else {
        	$success = true;
        }
    }

    public function setRconpassword($pw) {
        $this->rconpassword = $pw;
    }

    public function setServerPort($port) {
        $this->port = $port;
    }

    public function rcon($str) {
    	if (!$this->rconpassword) {
    		return false;
    	}
    	$this->send("rcon " . $this->rconpassword . " $str");
		return $this->getResponse();
    }

    private function send($str) {
        fwrite($this->fp, "\xFF\xFF\xFF\xFF$str\x00");
    }

    private function getResponse() {
    	stream_set_timeout($this->fp, 0, 7e5);
        $s = '';
	    $start = microtime(true);
        do {
        	$read = fread($this->fp, 9999);
			$s .= substr($read, strpos($read, "\n") + 1);
    		if (!isset($end)) {
    			$end = microtime(true);
    		}
			$info = stream_get_meta_data($this->fp);
		}
		while (!$info["timed_out"]);

		$this->lastPing = round(($end - $start) * 1000);
					
        return $s;
    }

    public function quit() {
    	if (is_resource($this->fp)) {
			fclose($this->fp);
			return true;
    	}
    	return false;
    }


}

?>