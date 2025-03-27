<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Ваша форма принята');
	}
  include('form.php');
  
  exit();
}


$fio = isset($_POST['fio']) ? $_POST['fio'] : '';
$number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$date_r = isset($_POST['date_r']) ? $_POST['date_r'] : '';
$radio1 = isset($_POST['radio1']) ? $_POST['radio1'] : '';
$yaps = isset($_POST['yaps']) ? $_POST['yaps'] : '';
$biography = isset($_POST['biography']) ? $_POST['biography'] : '';
$check = isset($_POST['check']) ? $_POST['check'] : '';

$yapses = ($yaps != '') ? implode(", ", $yaps) : [];

$errors = FALSE;

if (empty($_POST['fio']) || preg_match('~[^а-яА-ЯёЁ ]~u', $fio) || (strlen($fio) > 60)) {
	echo "Заполните имя верно.\n";
	$errors = TRUE;
}
if(empty($_POST['fio'])){
	echo "Заполните номер.\n";
	$errors = TRUE;
}
if ((filter_var($email, FILTER_VALIDATE_EMAIL)=== false) || empty($_POST['email']) || (strlen($email) > 30)) {
    echo "e-mail адрес '$email' указан неверно или пуст.\n";
	$errors = TRUE;
}
if (empty($_POST['date_r'])) {
	echo "Укажите дату верно.\n";
	$errors = TRUE;
}
if (empty($_POST['radio1'])) {
	echo "Выберите пол.\n";
	$errors = TRUE;
}
if (empty($_POST['yaps'])) {
	echo "Выберите хотя бы 1 язык.\n";
	$errors = TRUE;
}
if (strlen($biography) > 1000) {
	echo "Длина биографии слишком большая\n";
	$errors = TRUE;
}
if (empty($_POST['check'])) {
	echo "Чекбокс не заполнен!\n";
	$errors = TRUE;
}
if ($errors) {
  
  exit();
}



$user = 'u68787'; 
$pass = '1417112'; 
$db = new PDO('mysql:host=localhost;dbname=u68787', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

$qlang = implode(',', array_fill(0, count($yaps), '?'));

try
{
    $dbyaps = $db->prepare("SELECT lang_id, lang FROM langs WHERE lang IN ($qlang)");
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

echo $dbyaps->rowCount().'**'.count($yaps);


try {
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

header('Location: ?save=1');
