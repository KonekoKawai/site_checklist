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
                //error_reporting(0);
                if(isset($_POST)) // Если пришёл какой-либо пост запрос
                {  
                    
                    if(isset($_POST["input_task"])) // Если запрос на добавление нового задания
                    { 
                        $count_string = 0;
                        header('Location: /index.php');
                        $input_task = strip_tags($_POST["input_task"]);

                        $file_r = fopen("source/task.txt", 'r');
                        while(!feof($file_r)) // Считаем количество строк в файле
                            {
                                $str = htmlentities(fgets($file_r));
                                $count_string++;
                            }
                        fclose($file_r);

                        $file_w = fopen("source/task.txt", 'a+');
                        fwrite($file_w, $count_string."/".$input_task." /0\n");
                        fclose($file_w);
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
                       
                        $new_string;
                        $old_string;
                        $file_array = file("source/task.txt"); // Загружаем наш текстовый файл в массив 
                        
                        if($file_array) // Меняем данные в загруженном массиве 
                        {
                            if($file_array[$value][strrpos($file_array[$value], "/")+1] == 0)
                                $file_array[$value][strrpos($file_array[$value], "/")+1] = 1;
                            else
                                $file_array[$value][strrpos($file_array[$value], "/")+1] = 0;
                              
                                
                            file_put_contents("source/task.txt", $file_array ); // Записываем в файл изменённые данные из массива
                            
                        }
                        
                    }
                        
                }
             ?>

            <?php // Производим запись строк файла в таблицу
                $file_r = fopen("source/task.txt", 'a+') or die("не удалось открыть файл для считывания данных в таблицу");
                while(!feof($file_r)) 
                {   
                    $str = htmlentities(fgets($file_r)); // Записываем строку 

                    
                    if($str) // Если строка не пустая 
                    {

                        if($separate_str = explode("/", $str)) // Делим строку на 3 части В нашем текстовом документы номер/ Задание /статус
                            {
                                $str_num=$separate_str[0];
                                $str_text = $separate_str[1];
                                $str_status = $separate_str[2];
                            }
                        
                        $source; 
                        $task_text_decor;

                        if($str_status == 1) // Меняем иконку по статусу
                        {
                            $source = "source/true.png";
                            $task_text_decor = "task_text_status_true";
                        }
                        else
                        {
                            $source = "source/false.png";
                            $task_text_decor = "task_text_status_false";
                        }

                        echo 
                        '
                                <tr>
                                    <td class="'.$task_text_decor.'">
                                        '.$str_num.'. '.$str_text.'
                                    </td>

                                    <td class="task_status">
                                        <form method="post" action="index.php">
                                            <input type="hidden" name="update_task_'.$str_num.'" value="'.$str_num.'"/>
                                            <input class="task_checkbox" src='.$source.' type="image"/>
                                        </form>
                                    </td>

                                 </tr>

                         ';
                    }
                }
                fclose($file_r);
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
