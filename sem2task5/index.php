<?php
header('Content-Type: text/html; charset=UTF-8');

session_start();

$error = FALSE;
$auth=!empty($_SESSION['login']);

if($_SERVER['REQUEST_METHOD']=='POST')
{

$fio = isset($_POST['fio']) ? $_POST['fio'] : '';
$number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$date_r = isset($_POST['date_r']) ? $_POST['date_r'] : '';
$radio1 = isset($_POST['radio1']) ? $_POST['radio1'] : '';
$yaps = isset($_POST['yaps']) ? $_POST['yaps'] : '';
$biography = isset($_POST['biography']) ? $_POST['biography'] : '';
$check = isset($_POST['check']) ? $_POST['check'] : '';

if (isset($_POST['logout_form']))
{
  setcookie('fio_value', '', time() - 60*60*24*30);
  setcookie('number_value', '', time() - 60*60*24*30);
  setcookie('email_value', '', time() - 60*60*24*30);
  setcookie('date_r_value', '', time() - 60*60*24*30);
  setcookie('radio1_value', '', time() - 60*60*24*30);
  setcookie('yaps_value', '', time() - 60*60*24*30);
  setcookie('biography_value', '', time() - 60*60*24*30);
  setcookie('check_value', '', time() - 60*60*24*30);
  session_destroy();
  header('Location: ./');
  exit();
}
function check($cook,$txt,$usl)
{
  global $error;
  $checkd=false;
  $val=isset($_POST[$cook]) ?$_POST[$cook]: '';
  if($usl)
  {
    setcookie($cook.'_error',$txt,time()+60*60*24*7);
    $error=true;
    $checkd=true;
  }
  if($cook == 'yaps')
  {
    global $yaps;
    $val=($yaps!='')? implode(",",$yaps):'';
  }
  setcookie($cook.'_val',$val,time()+60*60*24*60);
  return $checkd;
}

if(!check('fio','Обязательно заполните это поле',empty($fio)))
  check('fio','В поле ФИО можно использовать только буквы киррилицы',!preg_match('/^([а-яё]+-?[а-яё]+)([а-яё]+-?[а-яё]+)([а-яё]+-?[а-яё]+){1,2,3}$/Diu', $fio));

if(!check('number','Обязательно заполните это поле',empty($number)))
{
  check('number','Номер должен содержать только цифры',$number!= preg_replace('/\D/','',$number));
  check('number','Номер должен содержать 11 цифр',strlen($number)!=11);
}
if(!check('email', 'Это поле пустое', empty($email)))
        check('email', ' Формат вида example@mail.ru', !preg_match('/^\w+([.-]?\w+)@\w+([.-]?\w+)(.\w{2,3})+$/', $email));
    if(!check('date_r', 'Это поле пустое', empty($date_r)))
        check('date_r', 'Неправильная дата', strtotime('now') < strtotime($date_r));
    check('radio1', "Не выбран пол", empty($radio1));
    if(!check('biography', 'Это поле пустое', empty($biography)))
        check('biography', 'Слишком длинное поле, максимум символов - 1000', strlen($biography) > 1000);
    check('check', 'Ознакомьтесь с контрактом', empty($check));


$user = 'u68787'; 
$pass = '1417112'; 
$db = new PDO('mysql:host=localhost;dbname=u68787', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

$qlang = implode(',', array_fill(0, count($yaps), '?'));

if(!check('yaps','Выберите язык программирования',empty($yaps)))
{

try
{
    $dbyaps = $db->prepare("SELECT lang_id, name FROM langs WHERE name IN ($qlang)");
    foreach ($yaps as $key => $value)
        $dbyaps->bindValue(($key+1), $value);
    $dbyaps->execute();
    $yapses = $dbyaps->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    print('Error : ' . $e->getMessage());
    exit();
}
  check('yaps','Ошибка',$dbyaps->rowCount()!=count($yaps));
}


if(!$error)
{
  setcookie('fio_error', '', time() - 60*60*24*60);
  setcookie('number_error', '', time() - 60*60*24*60);
  setcookie('email_error', '', time() - 60*60*24*60);
  setcookie('date_r_error', '', time() - 60*60*24*60);
  setcookie('radio1_error', '', time() - 60*60*24*60);
  setcookie('yaps_error', '', time() - 60*60*24*60);
  setcookie('biography_error', '', time() - 60*60*24*60);
  setcookie('check_error', '', time() - 60*60*24*60);

  if($auth)
  {
    $tb=$dbprepare("UPDATE form_data SET fio = ?, number = ?, email = ?, date_r = ?, male = ?, biography = ? WHERE form_id = ?");
    $tb->execute([$fio, $number, $email, $date, $radio, $bio, $_SESSION['form_id']]);

    $tb = $db->prepare("DELETE FROM users_langs WHERE form_id = ?");
    $tb->execute([$_SESSION['form_id']]);

    $tb0=$db->prepare("INSERT INTO users_langs (form_id, lang_id) VALUES (?,?)");
  }
  else
  {
    $login=uniqid();
    $password=uniqid();
    setcookie('login',$login);
    setcookie('password',$password);
    $hpass=md5($password);
  
try {
  $stmt = $db->prepare("INSERT INTO passwords (login, password) VALUES (?, ?)");
  $stmt->execute([$login, $hpass]);


	$tb1 = $db->prepare("INSERT INTO users (fio, number, email, date_r, male, biography) VALUES (?, ?, ?, ?, ?, ?)");
    $tb1->execute([$fio, $number, $email, $date_r, $radio1, $biography]);
	$form_id = $db->lastInsertId();
    $tb2 = $db->prepare("INSERT INTO users_langs (form_id, lang_id) VALUES (?, ?)");
    foreach($languages as $row)
        $tb2->execute([$form_id, $row['lang_id']]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
setcookie('fio_val', '', time() - 60*60*24*60*12);
setcookie('number_val', '', time() - 60*60*24*60*12);
setcookie('email_val', '', time() - 60*60*24*60*12);
setcookie('date_r_val', '', time() - 60*60*24*60*12);
setcookie('radio1_val', '', time() - 60*60*24*60*12);
setcookie('yaps_val', '', time() - 60*60*24*60*12);
setcookie('biography_val', '', time() - 60*60*24*60*12);
setcookie('check_val', '', time() - 60*60*24*60*12);
  }
setcookie('save','1');

}
header('Location: index.php');
}
else
{
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
    $error=true;

    function set($name, $vl)
    {
    global $values;
    $values[$name]=empty($vl)? '': strip_tags($vl);


    }

    function errs($txt,$pole)
    {
      global $errors, $messages, $values;
        $errors[$txt] = !empty($pole);
        $messages[$txt] = "<div class=\"error\">$pole</div>";
        $values[$txt] = empty($_COOKIE[$txt.'_val']) ? '' : $_COOKIE[$txt.'_val'];
        setcookie($txt.'_error', '', time() - 60*60*24*60);
        return;
    }

    if(!empty($_COOKIE['save']))
    {
      setcookie('save','',100000);
      setcookie('login','',100000);
      setcookie('password','',100000);
      $messages['success'] = '<div class="messages">Данные сохранены</div>';
    
  if(!empty($_COOKIE['password']))
    $messages['success']=sprintf('Вы можете <a href="auth.php">войти</a> <strong>%s</strong><br>
           <strong>%s</strong> для изменения данных.', strip_tags($_COOKIE['login']), strip_tags($_COOKIE['password']));
  }
    errs('fio',$fio);
    errs('number',$number);
    errs('email',$email);
    errs('date_r',$date_r);
    errs('radio1',$radio1);
    errs('yaps',$yaps);
    errs('biography',$biography);
    errs('check',$check);

    $yapses=explode(',',$values['yaps']);

    if($error && !empty($_SESSION['login']))
    {
      try
      {
        $dbLangs = $db->prepare("SELECT * FROM users WHERE form_id = ?");
            $dbLangs->execute([$_SESSION['form_id']]);
            $user_data = $dbLangs->fetchAll(PDO::FETCH_ASSOC)[0];

            $form_id = $user_data['id'];
            $_SESSION['form_id'] = $form_id;

            $dbL = $db->prepare("SELECT l.name FROM users_langs f
                                JOIN langs l ON l.lang_id = f.lang_id
                                WHERE f.form_id = ?");

            $dbL->execute([$form_id]);

            $yapses = [];
            foreach ($dbL->fetchAll(PDO::FETCH_ASSOC) as $item)
                $yapses[] = $item['name'];

            set('fio', $user_inf['fio']);
            set('number', $user_inf['number']);
            set('email', $user_inf['email']);
            set('date_r', $user_inf['date_r']);
            set('radio1', $user_inf['radio1']);
            set('yaps', $yaps);
            set('biography', $user_inf['biography']);
            set('check', "1");
      }
      catch (PDOException $e) {
        print ('Error : ' . $e->getMessage());
        exit();
    }
  }


    include('form.php');
}

?>
