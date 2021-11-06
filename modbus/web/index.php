<?php
ini_set('display_errors', 1);
error_reporting(~0);
require_once dirname(__FILE__) . '/mx200.php';

$_conf = array(
	//конфигурация ячейки    
	//ip адрес МУ220, номер решистра, номер выхода замка, ip адреc МВ220, контакт замка, контакт датчика содержимого
    array("ip1"=>"192.168.10.58","reg1"=>410,"pin1"=>1,	"ip2"=>"192.168.10.58","reg2"=>51,"pin2"=>1,"pin3"=>11), 
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>2,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>2,"pin3"=>12), 
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>3,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>3,"pin3"=>13), 
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>4,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>4,"pin3"=>14), 
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>5,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>5,"pin3"=>15),
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>6,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>6,"pin3"=>16),  
    array("ip1"=>"127.0.0.1","reg1"=>410,"pin1"=>7,	"ip2"=>"127.0.0.1","reg2"=>51,"pin2"=>7,"pin3"=>17)  
);




function get_cell($cell)
{
	global $_conf;

	$bit2_index=0;
	$bit3_index=0;
	$reg=$_conf[$cell]["reg2"];
	if ($_conf[$cell]["pin2"]>16)
	{
		$bit2_index=$_conf[$cell]["pin2"]-17;
		$reg++;
	}else{
		$bit2_index=$_conf[$cell]["pin2"]-1;
	}
	
	$word=readMX200($_conf[$cell]["ip2"],$reg);
	
	$lock=false;
	
	if (($word&(1<<$bit2_index))==0)
	{
		$lock=false;
	}else{
		$lock=true;
	}
	
	$reg=$_conf[$cell]["reg2"];
	
	if ($_conf[$cell]["pin3"]>16)
	{
		$bit3_index=$_conf[$cell]["pin3"]-17;
		$reg++;
	}else{
		$bit3_index=$_conf[$cell]["pin3"]-1;
	}
	
	$word=readMX200($_conf[$cell]["ip2"],$reg);
	
	$empty=false;
	if (($word&(1<<$bit3_index))==0)
	{
		$empty=false;
	}else{
		$empty=true;
	}
	
	return array("lock"=>$lock,"empty"=>$empty);
}

function open_cell($cell) //дать напряжение в катушку
{
	global $_conf;
	//прочитать битовую маску
	$word_magnet=readMX200($_conf[$cell]["ip1"],$_conf[$cell]["reg1"]);
	
	//установить бит в 1
	$word_magnet|=1<<($_conf[$cell]["pin1"]-1);
	
	//открыть замок
	writeMX200($_conf[$cell]["ip1"],$_conf[$cell]["reg1"],$word_magnet); 
	
}

function free_cell($cell) //убрать напряжение с катушки
{
	global $_conf;
	//убрать напряжение с замка
	$word_magnet=readMX200($_conf[$cell]["ip1"],$_conf[$cell]["reg1"]);
	
	//установить бит в 0
	$word_magnet&=~(1<<($_conf[$cell]["pin1"]-1));
	
	//закрыть замок
	writeMX200($_conf[$cell]["ip1"],$_conf[$cell]["reg1"],$word_magnet); 

}


//echo $_GET["method"]."  ".$_GET["cell"];

if (is_int(intval($_GET["cell"]))!=TRUE)
		{
			echo "{\n\"status\":illegal cell\n}";
			exit;
		}

$cell_index=intval($_GET["cell"])-1; //для удобства

switch ($_GET["method"])
{
	case "STATE":
		echo "{\n";
		$state=get_cell($cell_index);
		if ($state["lock"])
		{
		echo "\"LOCKED\":1,";
		}else{
		echo "\"LOCKED\":0,";
		}
		
		if ($state["empty"])
		{
		echo "\"EMPTY\":1\n}";
		}else{
		echo "\"EMPTY\":0\n}";
		}
		
		
	break;
	case "OPEN":
		echo "{\n";
		open_cell($cell_index);
		sleep(1); //ожидаем пока мезанизм сработает
		free_cell($cell_index);
		$state=get_cell($cell_index);
		
		if ($state["lock"])
		{
		echo "\"OPENED\":0\n}";
		}else{
		echo "\"OPENED\":1\n}";
		}
		
		
	break;
	case "LIST":
		$count=count($_conf);
		echo "{\"cells\":[\n";
		for ($i=0; $i<($count-1);$i++)
		{
			echo "{";
			$state=get_cell($i);
			if ($state["lock"])
			{
			echo "\"LOCKED\":1,";
			}else{
			echo "\"LOCKED\":0,";
			}
			
			if ($state["empty"])
			{
			echo "\"EMPTY\":1";
			}else{
			echo "\"EMPTY\":0";
			}
			echo "},\n";
		}
		echo "{";
		$state=get_cell($count-1);
		if ($state["lock"])
		{
		echo "\"LOCKED\":1,";
		}else{
		echo "\"LOCKED\":0,";
		}
		
		if ($state["empty"])
		{
		echo "\"EMPTY\":1}";
		}else{
		echo "\"EMPTY\":0}";
		}
		echo "\n]}\n";
		
	/*
	 {
	    "name":"John",
	    "age":30,
	    "cars": [
		{ "name":"Ford", "models":[ "Fiesta", "Focus", "Mustang" ] },
		{ "name":"BMW", "models":[ "320", "X3", "X5" ] },
		{ "name":"Fiat", "models":[ "500", "Panda" ] }
	     ]
	 }
	*/ 
	
	
	
	
	
	break;
	default:
		echo "{\n\"status\":illegal method\n}";
	break;
}


/*
print_r($conf);

echo writeMX200("127.0.0.1",1203,65458);
echo readMX200("127.0.0.1",1203);
*/
?>
