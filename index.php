<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="KonekoKawai" />
    <title>Чек-лист</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link rel="icon" href="source/favicon.png" type="image/png">
</head>

<body>
    <h1>Чек-лист</h1>
   
    <form method="post" autocomplete="off" action="index.php">
        <p class="input_task_pre">
            Добавить задание:<br />

            <input class="input_task_text" type="text" name="input_task" placeholder="Задание" required=required />

            <input class="input_task_image" type="image" src="source/add.jpg" name="submit" />

        </p>
    </form>

    <table> 
        <thead>
            <tr>
                <th class="task_text_pre">Задания</th>
                <th class="task_status">Статус</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                try // Подключаемся к БД 
                {
                    $conn = new PDO("mysql:host=localhost;port=3306;dbname=XXX", "XXX", "XXX");
                }
                catch(PDOException $e) // Если не получилось Выдаём ошибку
                {
                    echo "Connection failed: " . $e->getMessage();
                }
               

                if(isset($_POST)) // Если пришёл какой-либо пост запрос
                {  
                    
                    if(isset($_POST["input_task"])) // Если запрос на добавление нового задания
                    { 
                      

                        $count_string = 0;
                        header('Location: /index.php');
                        $input_task = strip_tags($_POST["input_task"]);

                        $sql_add_task = "INSERT INTO task (task_text, flag) 
                                        VALUES ('$input_task', 0)";

                        $conn->exec($sql_add_task);  
                        $conn = null; // Закрываем соединение с БДшкой
                    }

                    $value = -1;
                    foreach($_POST as $key => $element) // Поиск запроса на изменение статуса задания
                    {   
                        
                        if(str_contains($key, "update_task_") != false)
                        {
                            $value = $element -1;
                            header('Location: /index.php'); 
                        }
                    }

                    if($value !== -1) // Если запрос на изменение статуса задания выполнен
                    {

                        $sql_get_tasks = "SELECT * FROM task"; 
                        $result = $conn->query($sql_get_tasks); // Получаем данные из таблицы

                        while($row = $result->fetch()) // Идём по данным
                        {
                            if($row["id"] == $value+1) // Если id равно номеру задания на изменение
                            {
                                if($row["flag"] == 0) // Если задание не выполнено Ставим выполнено
                                    $conn->exec("UPDATE task SET flag=1 WHERE id=$value+1");
                                else if($row["flag"] == 1) // И наоборот 
                                    $conn->exec("UPDATE task SET flag=0 WHERE id=$value+1");
                                break;
                            }
                        }
                        $conn = null; // Закрываем соединение с БДшкой
                    }      
                }
                
                // Производим запись в таблицу из БД

                $sql_get_tasks = "SELECT * FROM task"; // Запрос на получение всех данных из таблицы

                $result = $conn->query($sql_get_tasks); // Получаем данные из таблицы и засовываем в переменную result


                // Здесь идём 2 раза по таблице 
                // Сначала в самом верху невыполненые задания А в самом низу выполненые
                while($row = $result->fetch()) // Идём по данным
                    {
                        if($row["flag"]==0) // Если задание НЕ выполнено
                        {
                            
                            $source = "source/false.png"; // Присваевам картинку для статуса
                            $task_text_decor = "task_text_status_false"; // Знаение для класса задания Если НЕ выполнено То не зачёркиваем
                            
                            echo // Выводим данные в таблицу
                            '
                                    <tr>
                                        <td class="'.$task_text_decor.'">
                                            '.$row["id"].'. '.$row["task_text"].'
                                        </td>

                                        <td class="task_status">
                                            <form method="post" action="index.php">
                                                <input type="hidden" name="update_task_'.$row["id"].'" value="'.$row["id"].'"/>
                                                <input class="task_checkbox" src='.$source.' type="image"/>
                                            </form>
                                        </td>

                                     </tr>

                             ';
                            }
                    }

                $result = $conn->query($sql_get_tasks); // Снова делаем запрос 
                while($row = $result->fetch()) // Идём по данным
                    {
                        if($row["flag"]==1) // Если задание выполнено 
                        {
                            
                            $source = "source/true.png"; // Присваевам картинку для статуса
                            $task_text_decor = "task_text_status_true"; // Знаение для класса задания Если выполнено То зачёркиваем
                            
                            echo  // Выводим данные в таблицу
                            '
                                    <tr>
                                        <td class="'.$task_text_decor.'">
                                            '.$row["id"].'. '.$row["task_text"].'
                                        </td>

                                        <td class="task_status">
                                            <form method="post" action="index.php">
                                                <input type="hidden" name="update_task_'.$row["id"].'" value="'.$row["id"].'"/>
                                                <input class="task_checkbox" src='.$source.' type="image"/>
                                            </form>
                                        </td>

                                     </tr>

                             ';
                        }
                    }

                $conn = null; // Закрываем соединение с БДшкой
            ?>
                    
        </tbody>

    </table>

    <div class="contac_information">
        <address>
            <a href="https://github.com/KonekoKawai" target="_blank"><img src="https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white" alt="Мой GitHub" width="114" height="30" /></a>
            <a href="https://t.me/konekokawai_channel" target="_blank"><img src="https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white" alt="Мой Telergam" width="114" height="30" /></a>
        </address>
    </div>

</body>

</html>