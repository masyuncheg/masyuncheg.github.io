<?php
header('Content-Type: text/html; charset=UTF-8');

session_start();

$user = 'u68787'; 
$pass = '1417112'; 
$db = new PDO('mysql:host=localhost;dbname=u68787', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

// Определяем тип запроса
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$accept_json = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

$error = FALSE;
$auth = !empty($_SESSION['login']);
$response = ['success' => false, 'messages' => []];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные в зависимости от типа запроса
    if ($is_ajax && $accept_json) {
        $input = json_decode(file_get_contents('php://input'), true);
        $fio = isset($input['fio']) ? $input['fio'] : '';
        $number = isset($input['number']) ? preg_replace('/\D/', '', $input['number']) : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $date_r = isset($input['date_r']) ? $input['date_r'] : '';
        $radio1 = isset($input['radio1']) ? $input['radio1'] : '';
        $yaps = isset($input['yaps']) ? $input['yaps'] : [];
        $biography = isset($input['biography']) ? $input['biography'] : '';
        $check = isset($input['check']) ? $input['check'] : '';
    } else {
        $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
        $number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $date_r = isset($_POST['date_r']) ? $_POST['date_r'] : '';
        $radio1 = isset($_POST['radio1']) ? $_POST['radio1'] : '';
        $yaps = isset($_POST['yaps']) ? $_POST['yaps'] : [];
        $biography = isset($_POST['biography']) ? $_POST['biography'] : '';
        $check = isset($_POST['check']) ? $_POST['check'] : '';
    }

    if (isset($_POST['logout_form'])) {
    // Удаляем все куки
    $cookies = ['fio', 'number', 'email', 'date_r', 'radio1', 'yaps', 'biography', 'check'];
    foreach ($cookies as $name) {
        setcookie($name.'_value', '', time() - 3600);
        setcookie($name.'_error', '', time() - 3600);
    }
    
    // Уничтожаем сессию
    session_unset();
    session_destroy();
    
    // Возвращаем JSON для AJAX
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['redirect' => './']);
        exit();
    } else {
        header('Location: ./');
        exit();
    }
}
        
        if ($is_ajax) {
            $response['redirect'] = './';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            header('Location: ./');
            exit();
        }
    }

    // Функция валидации и добавления ошибок
    function validateField($field, $value, $validationFn, $errorMessage) {
        global $error, $response, $is_ajax;
        
        if (empty($value)) {
            if ($is_ajax) {
                $response['messages'][$field] = $errorMessage;
            } else {
                setcookie($field.'_error', $errorMessage, time() + 60*60*24*7);
            }
            $error = TRUE;
            return true;
        }
        
        if ($validationFn && !$validationFn($value)) {
            if ($is_ajax) {
                $response['messages'][$field] = $errorMessage;
            } else {
                setcookie($field.'_error', $errorMessage, time() + 60*60*24*7);
            }
            $error = TRUE;
            return true;
        }
        
        return false;
    }

    // Валидация полей
    validateField('fio', $fio, function($v) { 
        return preg_match('/^([а-яё]+-?[а-яё]+)( [а-яё]+-?[а-яё]+){1,2}$/Diu', $v); 
    }, 'Неверный формат ФИО');

    validateField('number', $number, function($v) { 
        return strlen($v) == 11 && ctype_digit($v); 
    }, 'Номер должен содержать 11 цифр');

    validateField('email', $email, function($v) { 
        return filter_var($v, FILTER_VALIDATE_EMAIL); 
    }, 'Неверный формат email');

    validateField('date_r', $date_r, function($v) { 
        return strtotime($v) < strtotime('now'); 
    }, 'Дата рождения не может быть в будущем');

    validateField('radio1', $radio1, null, 'Не выбран пол');
    validateField('check', $check, null, 'Необходимо согласие с контрактом');
    
    if (strlen($biography) > 1000) {
        if ($is_ajax) {
            $response['messages']['biography'] = 'Биография слишком длинная (макс. 1000 символов)';
        } else {
            setcookie('biography_error', 'Биография слишком длинная (макс. 1000 символов)', time() + 60*60*24*7);
        }
        $error = TRUE;
    }

    if (empty($yaps)) {
        if ($is_ajax) {
            $response['messages']['yaps'] = 'Выберите хотя бы один язык';
        } else {
            setcookie('yaps_error', 'Выберите хотя бы один язык', time() + 60*60*24*7);
        }
        $error = TRUE;
    }

    // Если нет ошибок - сохраняем данные
    if (!$error) {
        try {
            if ($auth) {
                // Обновление данных существующего пользователя
                $stmt = $db->prepare("UPDATE users SET fio = ?, number = ?, email = ?, date_r = ?, male = ?, biography = ? WHERE form_id = ?");
                $stmt->execute([$fio, $number, $email, $date_r, $radio1, $biography, $_SESSION['form_id']]);

                // Удаляем старые языки
                $stmt = $db->prepare("DELETE FROM users_langs WHERE form_id = ?");
                $stmt->execute([$_SESSION['form_id']]);

                // Добавляем новые языки
                $stmt = $db->prepare("INSERT INTO users_langs (form_id, lang_id) VALUES (?, ?)");
                foreach ($yaps as $lang_id) {
                    $stmt->execute([$_SESSION['form_id'], $lang_id]);
                }

                if ($is_ajax) {
                    $response['success'] = true;
                    $response['messages']['success'] = 'Данные успешно обновлены';
                    $response['profile_url'] = 'profile.php?id='.$_SESSION['form_id'];
                } else {
                    setcookie('save', '1', time() + 60*60*24);
                }
            } else {
                // Создание нового пользователя
                $login = uniqid();
                $password = uniqid();
                $hpass = md5($password);

                // Сохраняем логин/пароль
                $stmt = $db->prepare("INSERT INTO passwords (login, password) VALUES (?, ?)");
                $stmt->execute([$login, $hpass]);

                // Сохраняем данные пользователя
                $stmt = $db->prepare("INSERT INTO users (fio, number, email, date_r, male, biography) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fio, $number, $email, $date_r, $radio1, $biography]);
                $form_id = $db->lastInsertId();

                // Сохраняем языки
                $stmt = $db->prepare("INSERT INTO users_langs (form_id, lang_id) VALUES (?, ?)");
                foreach ($yaps as $lang_id) {
                    $stmt->execute([$form_id, $lang_id]);
                }

                if ($is_ajax) {
                    $response['success'] = true;
                    $response['login'] = $login;
                    $response['password'] = $password;
                    $response['messages']['success'] = 'Аккаунт успешно создан';
                } else {
                    setcookie('login', $login, time() + 60*60*24*30);
                    setcookie('password', $password, time() + 60*60*24*30);
                    setcookie('save', '1', time() + 60*60*24);
                }
            }

            // Очищаем куки с ошибками
            if (!$is_ajax) {
                $fields = ['fio', 'number', 'email', 'date_r', 'radio1', 'yaps', 'biography', 'check'];
                foreach ($fields as $field) {
                    setcookie($field.'_error', '', time() - 3600);
                }
            }
        } catch (PDOException $e) {
            if ($is_ajax) {
                $response['messages']['database'] = 'Ошибка базы данных: ' . $e->getMessage();
            } else {
                // Обработка ошибки БД для обычного запроса
                die('Error: ' . $e->getMessage());
            }
        }
    }

    // Возвращаем ответ для AJAX
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        // Редирект для обычного POST-запроса
        header('Location: index.php');
        exit();
    
} else {
    // Обработка GET-запроса (показать форму)
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request method']);
        exit();
    }

    // Старая логика отображения формы
    $fio = !empty($_COOKIE['fio_error']) ? $_COOKIE['fio_error'] : '';
    $number = !empty($_COOKIE['number_error']) ? $_COOKIE['number_error'] : '';
    $email = !empty($_COOKIE['email_error']) ? $_COOKIE['email_error'] : '';
    $date_r = !empty($_COOKIE['date_r_error']) ? $_COOKIE['date_r_error'] : '';
    $radio1 = !empty($_COOKIE['radio1_error']) ? $_COOKIE['radio1_error'] : '';
    $yaps = !empty($_COOKIE['yaps_error']) ? $_COOKIE['yaps_error'] : '';
    $biography = !empty($_COOKIE['biography_error']) ? $_COOKIE['biography_error'] : '';
    $check = !empty($_COOKIE['check_error']) ? $_COOKIE['check_error'] : '';

    $errors = array();
    $messages = array();
    $values = array();
    $error = true;

    function set($name, $vl) {
        global $values;
        $values[$name] = empty($vl) ? '' : strip_tags($vl);
    }

    function errs($txt, $pole) {
        global $errors, $messages, $values;
        $errors[$txt] = !empty($pole);
        $messages[$txt] = "<div class=\"error\">$pole</div>";
        $values[$txt] = empty($_COOKIE[$txt.'_val']) ? '' : $_COOKIE[$txt.'_val'];
        setcookie($txt.'_error', '', time() - 60*60*24*60);
        return;
    }

    $messages['success'] = '';
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('password', '', 100000);
        $messages['success'] = '<div class="messages">Данные сохранены</div>';
    
        if (!empty($_COOKIE['password'])) {
            $messages['success'] = sprintf('Вы можете <a href="auth.php">войти для изменения данных</a> Логин: <strong>%s</strong><br>
                   Пароль: <strong>%s</strong>', strip_tags($_COOKIE['login']), strip_tags($_COOKIE['password']));
        }
    }

    errs('fio', $fio);
    errs('number', $number);
    errs('email', $email);
    errs('date_r', $date_r);
    errs('radio1', $radio1);
    errs('yaps', $yaps);
    errs('biography', $biography);
    errs('check', $check);

    $yapses = explode(',', $values['yaps']);

    if ($error && !empty($_SESSION['login'])) {
        try {
            $dbLangs = $db->prepare("SELECT * FROM users WHERE form_id = ?");
            $dbLangs->execute([$_SESSION['form_id']]);
            $user_data = $dbLangs->fetchAll(PDO::FETCH_ASSOC)[0];

            $form_id = $user_data['form_id'];
            $_SESSION['form_id'] = $form_id;

            $dbL = $db->prepare("SELECT l.lang FROM users_langs f
                                JOIN langs l ON l.lang_id = f.lang_id
                                WHERE f.form_id = ?");
            $dbL->execute([$form_id]);

            $yapses = [];
            foreach ($dbL->fetchAll(PDO::FETCH_ASSOC) as $item) {
                $yapses[] = $item['lang'];
            }

            set('fio', $user_data['fio']);
            set('number', $user_data['number']);
            set('email', $user_data['email']);
            set('date_r', $user_data['date_r']);
            set('radio1', $user_data['male']);
            set('yaps', implode(',', $yapses));
            set('biography', $user_data['biography']);
            set('check', "1");
        } catch (PDOException $e) {
            print ('Error : ' . $e->getMessage());
            exit();
        }
    }

    include('form.php');
}
?>
