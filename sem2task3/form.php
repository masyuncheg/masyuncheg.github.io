<!DOCTYPE html>

<html lang="ru">


<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <title>
        Задание 3.
    </title>
    </head>
    <body class="body flex-column d-flex align-items-center">
        
        <h2 id="form" class="logotext mb-3 mt-4">
        Форма.
        </h2>
        
        <form action="" method="POST" class="mt-3 form">
            <label>
                Введите ФИО:
                <br/>
                <input name="fio" placeholder="Иванов Иван Иванович">
            </label>
            <br/>
            <br/>
            <label>
                Введите номер телефона:
                <br/>
                <input name="number" value="+7" type="tel">
            </label>
            <br/>
            <br/>
            <label>
                Введите свою почту:
                <br/>
                <input name="email" placeholder="email" type="email">
            </label>
            <br/>
            <br/>
            <label>
                Выберите дату:
                <br/>
                <input name="date_r" value="2024-01-01" type="date">
            </label>
            <br/>
            <br/>
            <label>
                Ваш пол:
                <br/>
                 <input name="radio1" type="radio" value="male" checked="checked"> Мужской
                <br/>
                </label>
                <label>
                <input name="radio1" type="radio" value="female" > Женский
            </label>
            <br/>
            <br/>
            <label>
                Выберите любимый язык программирования:
                <br/>
            <select name="yaps[]" multiple="multiple">
                <option value="Pascal">Pascal</option>
                <option value="C">C</option>
                <option value="C++">C++</option>
                <option value="JavaScript">JavaScript</option>
                <option value="PHP">PHP</option>
                <option value="Python">Python</option>
                <option value="Java">Java</option>
                <option value="Haskel">Haskel</option>
                <option value="Clojure">Clojure</option>
                <option value="Prolog">Prolog</option>
                <option value="Scala">Scala</option>
            </select>
            </label>
            <br/>
            <br/>
            <label>
                Введите вашу биографию:
                <br/>
                <textarea name="biografy" placeholder="Ваша биография" style="height: 100px;width:400px ;"></textarea>
            </label>
            <br/>
            <br/>
            <label>
                <input name="check" type="checkbox" value="Значение 1"> С контрактом ознакомлен(а).
            </label>
            <br/>
            <br/>
            <label>
                <input name="save" type="submit" value="Сохранить">
            </label>

        </form>
    </div>
    <footer class="footerstyle order-1 order-md-0 container-fluid mt-5 pb-5 pt-3">
        Выполнил Максим Лебедев
    </footer>
    </body>
</html>
