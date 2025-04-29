<!DOCTYPE html>

<html lang="ru">


<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <title>
        Задание 5.
    </title>
    </head>
    <body class="body flex-column d-flex align-items-center">
        
        <h2 id="form" class="logotext mb-3 mt-4">
        Форма.
        </h2>
        
        <form action="" method="POST" class="mt-3 form">

        <div class="din"> <?php echo $messages['success']; ?> </div>

            <label>
                Введите ФИО:
                <br/>
                <input name="fio" class="<?php echo ($errors['fio'] != NULL) ? 'err' : ''; ?>" value="<?php echo $values['fio']; ?>" placeholder="Иванов Иван Иванович">
                <div class="error"> <?php echo $messages['fio']?> </div>
            </label>
            <br/>
            <br/>
            <label>
                Введите номер телефона:
                <br/>
                <input name="number" class="<?php echo ($errors['number'] != NULL) ? 'err' : ''; ?>" value="<?php echo $values['number']; ?>" type="tel">
                <div class="error"> <?php echo $messages['number']?> </div>
            </label>
            <br/>
            <br/>
            <label>
                Введите свою почту:
                <br/>
                <input name="email" class="<?php echo ($errors['email'] != NULL) ? 'err' : ''; ?>" value="<?php echo $values['email']; ?>" placeholder="email" type="email">
                <div class="error"> <?php echo $messages['number']?> </div>
            </label>
            <br/>
            <br/>
            <label>
                Выберите дату:
                <br/>
                <input name="date_r" class="<?php echo ($errors['date_r'] != NULL) ? 'err' : ''; ?>" value="<?php echo $values['date_r']; ?>" type="date">
                <div class="error"> <?php echo $messages['date_r']?> </div>
        
            </label>
            <br/>
            <br/>
            <div>
        <div>Пол</div>
        <div class="mb-1">
          <label>
            <input name="radio1" class="ml-2" type="radio" value="male" <?php if($values['radio1'] == 'male') echo 'checked'; ?>/>
            <span class="<?php echo ($errors['radio1'] != NULL) ? 'err' : ''; ?>"> Мужской </span>
          </label>
          <label>
            <input name="radio1" class="ml-2" type="radio" value="female" <?php if($values['radio1'] == 'female') echo 'checked'; ?>/>
            <span class="<?php echo ($errors['radio1'] != NULL) ? 'err' : ''; ?>"> Женский </span>
          </label>
        </div>
        <div class="error"> <?php echo $messages['radio1']?> </div>
      </div>
            <br/>
            <br/>
            <label>
                Выберите любимый язык программирования:
                <br/>
            <select name="yaps[]" class="<?php echo ($errors['yaps'] != NULL) ? 'err' : ''; ?>" multiple="multiple">
                <option value="Pascal" <?php echo (in_array('Pascal', $yapses)) ? 'selected' : ''; ?>>Pascal</option>
                <option value="C" <?php echo (in_array('C', $yapses)) ? 'selected' : ''; ?>>C</option>
                <option value="C++"<?php echo (in_array('C++', $yapses)) ? 'selected' : ''; ?>>C++</option>
                <option value="JavaScript"<?php echo (in_array('JavaScript', $yapses)) ? 'selected' : ''; ?>>JavaScript</option>
                <option value="Python" <?php echo (in_array('Python', $yapses)) ? 'selected' : ''; ?>>Python</option>
                <option value="Java" <?php echo (in_array('Java', $yapses)) ? 'selected' : ''; ?>>Java</option>
                <option value="Haskel" <?php echo (in_array('Haskel', $yapses)) ? 'selected' : ''; ?>>Haskel</option>
                <option value="Clojure" <?php echo (in_array('Clojure', $yapses)) ? 'selected' : ''; ?>>Clojure</option>
                <option value="Prolog" <?php echo (in_array('Prolog', $yapses)) ? 'selected' : ''; ?>>Prolog</option>
                <option value="Scala" <?php echo (in_array('Scala', $yapses)) ? 'selected' : ''; ?>>Scala</option>
            </select>
            <div class="error"> <?php echo $messages['yaps']?> </div>
            </label>
            <br/>
            <br/>
            <label>
                Введите вашу биографию:
                <br/>
                <textarea name="biografy" placeholder="Ваша биография" style="height: 100px;width:400px ;" class="<?php echo ($errors['biography'] != NULL) ? 'err' : ''; ?>"></textarea>
                <div class="error"> <?php echo $messages['biography']?> </div>

            </label>
            <br/>
            <br/>
            <label>
                <input name="check" type="checkbox" value="Значение 1" <?php echo ($values['check'] != NULL) ? 'checked' : ''; ?>> С контрактом ознакомлен(а).
                <div class="error"> <?php echo $messages['check']?> </div>
            </label>
            <br/>
            <br/>
            <label>
               <?php
          if($auth) echo '<button class="button edbut" type="submit">Изменить</button>';
          else echo '<button class="button" type="submit">Отправить</button>';
          if($auth) echo '<button class="button" type="submit" name="logout_form">Выйти</button>'; 
          else echo '<a class="btnlike" href="login.php" name="logout_form">Войти</a>';
        ?>

            </label>

        </form>
    </div>
    <footer class="footerstyle order-1 order-md-0 container-fluid mt-5 pb-5 pt-3">
        Выполнил Максим Лебедев
    </footer>
    </body>
</html>
