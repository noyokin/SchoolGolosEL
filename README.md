# SchoolGolosEL

ВНИМАНИЕ! Этот проект не рекомендуется хостить на серверах. Остаётся открыт вопрос про безопасность!!!

<!--Блок информации о репозитории в бейджах-->
![GitHub top language](https://img.shields.io/github/languages/top/noyokin/SchoolGolosEL)
![GitHub](https://img.shields.io/github/license/noyokin/SchoolGolosEL)
![GitHub Repo stars](https://img.shields.io/github/stars/noyokin/SchoolGolosEL)
![GitHub issues](https://img.shields.io/github/issues/noyokin/SchoolGolosEL)

SchoolGolosEL- проект для проведение школьного голосования в электронном виде. 

Этот проект можно использовать для проведение выборов/опросов в электронном варианте. Также имеется БД в котором можно прописать номер студенческого билета, ФИО, класс и уникальный код для допуска к голосованию.

Данный проект использует языки HTML5, CSS3, PHP, SQL.

Для администрирование используется PhpMyAdmin
##


## Настройка
### Первые шаги до хостинга в локальной сети.
У вас должна быть установлена программа "MAMP" или какая-то другая по вашему усмотрению.

## Настройка БД
1. Создаём новую БД под названием "student_voting" в веб-интерфейсе PhpMyAdmin
2. Отправляем SQL запрос для создания таблицы "students":
```
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,  -- ID студента, голосующего
    name VARCHAR(255) NOT NULL,  --ФИО
    class VARCHAR(50) NOT NULL    -- класс студента
);
```
3. Отправляем SQL запрос для создания таблицы "votes":
```
CREATE TABLE votes (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор голоса
    student_id VARCHAR(10),                  -- ID студента, голосующего
    vote VARCHAR(50) NOT NULL,               -- Выбор студента
    vote_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Время голосования
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE  -- Связь с таблицей студентов
);
```

4. Отправляем SQL запрос для создания таблицы "admin_password":
 ```
   CREATE TABLE admin_password (
    id INT AUTO_INCREMENT PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL
);
```

Готово! Теперь ваша БД готова для работы.



## Настройка веб-интрефейса для дальнейшей работы
Для установки пароля для меню "Добавить студента", вам необходимо сделать PHP запрос, изменив в ```addpass.php``` строку ```$plain_password = 'admin123';```  вместо ```'admin123'``` пишем ваш пароль. (По умолчанию пароль стоит ```admin123```)

## Добавление в БД учеников
 Вы можете добавить студентов двумя способами:

1. Вручую, путём функции "Добавить студента".

2. Через импорт в PhpMyAdmin
Чтоб организовать импорт, вам необходимо создать EXCEL таблицу, где в первой колонке будет ```student_id```, в второй ```name```, в третьей ```class```, в четвёртой ```сlass_code```
```
student-id- уникальный индификатор студента
name- ФИО
class- группа или класс студента
class_code- уникальный индификатор класса, для допуска к голосованию. также его можно сделать для каждого индивидуальным.
```
После создания EXCEL таблицы, вам необходимо её сохранить в ```.CSV``` формате. Потом заходим в PhpMyAdmin, жмём кнопку ```Импорт```, там выбираем наш файл, во вкладке формат выбираем ```CSV using LOAD DATA``` и нажимаем кнопку ```Импорт```. Также обращаем внимание на кодировку файла! Она должна быть ```UTF-8```. Чтоб наверняка сохранить файл в нужной нам кодировке, то можно таблицу открыть через Блокнот и сохранить нам файл в нужной кодировке.
