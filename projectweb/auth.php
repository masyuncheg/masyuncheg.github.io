<?php
    header('Content-Type: text/html; charset=UTF-8');
    session_start();

    if (!empty($_SESSION['login']))
    {
        header('Location: ./');
        exit();
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $user = 'u68787'; 
        $pass = '1417112'; 
        $db = new PDO('mysql:host=localhost;dbname=u68787', $user, $pass,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

        $login = $_POST['login'];
        $password = md5($_POST['password']);
        try
        {
            $tb = $db->prepare("SELECT id FROM passwords WHERE login = ? and password = ?");
            $tb->execute([$login, $password]);
            $its = $tb->rowCount();
            if($its)
            {
                $uid = $tb->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
                $_SESSION['login'] = $_POST['login'];
                $_SESSION['form_id'] = $uid;
                header('Location: ./');
            }
            else
                $error = 'Неверный логин или пароль';
        }
        catch(PDOException $e)
        {
            print('Error : ' . $e->getMessage());
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <title>Задание_5</title>
</head>
<body>
    <form action="" method="post" class="form">
        <h2>Вход в форму</h2>
        <div> <input class="input" style="width: 100%;" type="text" name="login" placeholder="Логин"> </div>
        <div> <input class="input" style="width: 100%;" type="text" name="password" placeholder="Пароль"> </div>
        <button class="button" type="submit">Войти</button>
    </form>
</body>
</html>