<?php
require_once dirname(__FILE__) . '/Phpmodbus/ModbusMaster.php';


//обертка функции для работы с модулями ввода вывода -->
function readMX200($ip,$reg)
{
	// Create Modbus object
	$modbus = new ModbusMaster($ip, "TCP");
	try {
	    // FC 3
	    $recData = $modbus->readMultipleRegisters(0, $reg, 1);
	}
	catch (Exception $e) {
	    // Print error information if any
	    echo $modbus;
	    echo $e;
	    return NULL;
	}
	$result=$recData[0]*0x100+$recData[1];
	return $result;
}


function writeMX200($ip,$reg,$value)
{
	// Data to be writen
	$data = array($value);
	$dataTypes = array("WORD");
	// Create Modbus object
	$modbus = new ModbusMaster($ip, "TCP");
	try {
	    // FC6
	    $modbus->writeSingleRegister(0, $reg, $data, $dataTypes);
	}
	catch (Exception $e) {
	    // Print error information if any
	    echo $modbus;
	    echo $e;
	    return FALSE;
	}
	return TRUE;
}
//<-- обертка функции для работы с модулями ввода вывода 


?>
