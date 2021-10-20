Скрипт работы с Modbus TCP замками
Особых требований к окружению нет, работает на стандратной сборке пхп для ubuntu, возможно необходимо curl
номера ячеек начинаються с 1


методы:

1. OPEN:

	GET запрос ?method=OPEN&cell=[номер ячейки]
	
	ответ: {
		"OPENED":1
		}
		открыто
		
	ответ: {
		"OPENED":0
		}
		ошибка, ячейка заклинила
	

2. STATE:

	GET запрос ?method=STATE&cell=[номер ячейки]

	ответ:
	{
	"LOCKED":0,"EMPTY":0
	}	

	"LOCKED":0 - ячейка закрыта
	"EMPTY":0 - в ячейке багаж

3. LIST

	GET запрос ?method=LIST&cell=0

	ответ:
	{"cells":[
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0},
	{"LOCKED":0,"EMPTY":0}
	]}

