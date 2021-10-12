<?php

// ####################################################################################################
class Debug {

    // -------------------------------------------------------
	public static function consoleMessage($unMessage) {
		echo '<script>';
		echo 'console.log('. json_encode( "[" . date("d/m/Y") . " " . date("H:i:s") . "] ==>" . $unMessage . "<==") .')';
		echo '</script>';
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public static function logMessage($leMessage) {
		file_put_contents(
			'./logs/' . date("D M d") . '.log',
			'[' . date("D M d, Y G:i") . '] ' . $leMessage . PHP_EOL,
			FILE_APPEND
		);
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public static function boiteMessage($leMessage) {
        echo "<script>alert(\"" . $leMessage . "\");</script>";
	}
    // -------------------------------------------------------

}
// ####################################################################################################