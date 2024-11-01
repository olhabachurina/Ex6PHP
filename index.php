<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Органайзер</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('image1.jpg') no-repeat center top;
            background-size: cover;
            background-attachment: fixed;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
            animation: fadeInBody 1s ease-in-out;
        }

        /* Полупрозрачный контейнер для контента */
        .content-wrapper {
            background-color: rgba(255, 255, 255, 0.85); /* Белый фон с прозрачностью */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin-top: 20px;
        }

        form {
            background: rgba(255, 255, 255, 0.9); /* Полупрозрачный фон */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            animation: fadeIn 2s ease-in-out 0.5s; /* Увеличили продолжительность анимации до 2 секунд и добавили задержку 0.5 секунды */
            transition: transform 0.3s ease;
        }
        /* Анимация для плавного появления страницы */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: fadeIn 1.5s ease-in-out;
            text-align: center;
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }


        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }



        form:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        label {
            color: #4A4A4A;
        }

        input, select {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid #ddd;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.7);
            transition: box-shadow 0.3s ease;
        }

        input:focus, select:focus {
            box-shadow: 0 0 8px rgba(140, 150, 170, 0.4);
            outline: none;
        }

        /* Стили для кнопок */
        button {
            background: linear-gradient(135deg, #d1c4e9, #b39ddb);
            color: #4A4A4A;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            padding: 10px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #b39ddb, #9575cd);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button:active {
            transform: translateY(0);
        }


        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #E57373; font-weight: bold; }
        .info { color: #64B5F6; font-weight: bold; }


        #tasksList {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }

        #tasksList ul {
            list-style: none;
            padding: 0;
        }

        #tasksList li {
            background: rgba(240, 240, 255, 0.8);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        #tasksList li:hover {
            background: rgba(224, 224, 255, 0.9);
            transform: translateX(5px);
        }
    </style>
    <script>
        // функция для отправки запросов
        function sendAjaxRequest(action, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "organizer.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
            xhr.send(`action=${action}&${data}`);
        }

        // Функция для добавления задачи
        function addTask() {
            const task = document.getElementById('task').value;
            const date = document.getElementById('date').value;
            const data = `task=${encodeURIComponent(task)}&date=${encodeURIComponent(date)}`;
            sendAjaxRequest("addTask", data, function(response) {
                document.getElementById('message').innerHTML = response;
                document.getElementById('task').value = '';
                document.getElementById('date').value = '';
                printTasks(); // Обновляем список задач после добавления
            });
        }

        // Функция для отмены задачи
        function cancelTask() {
            const taskIndex = document.getElementById('taskIndex').value;
            const data = `taskIndex=${encodeURIComponent(taskIndex)}`;
            sendAjaxRequest("cancelTask", data, function(response) {
                document.getElementById('message').innerHTML = response;
                document.getElementById('taskIndex').value = '';
                printTasks(); // Обновляем список задач после удаления
            });
        }

        // Функция для печати задач
        function printTasks() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const data = `startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`;
            sendAjaxRequest("printTasks", data, function(response) {
                document.getElementById('tasksList').innerHTML = response;
            });
        }
    </script>
</head>
<body>

<h1>Органайзер</h1>

<div class="content-wrapper">
    <div id="message"></div> <!-- Для сообщений об успехе и ошибках -->

    <div>
        <form onsubmit="event.preventDefault(); addTask();">
            <h2>Добавить задачу</h2>
            <label for="task">Задача:</label>
            <input type="text" id="task" name="task" required>
            <label for="date">Дата (ГГГГ-ММ-ДД):</label>
            <input type="date" id="date" name="date" required>
            <button type="submit">Добавить задачу</button>
        </form>

        <form onsubmit="event.preventDefault(); printTasks();">
            <h2>Печать задач</h2>
            <label for="startDate">С какой даты:</label>
            <input type="date" id="startDate" name="startDate" required>
            <label for="endDate">По какую дату:</label>
            <input type="date" id="endDate" name="endDate" required>
            <button type="submit">Печать задач</button>
        </form>

        <form onsubmit="event.preventDefault(); cancelTask();">
            <h2>Отменить задачу</h2>
            <label for="taskIndex">Индекс задачи для отмены:</label>
            <input type="number" id="taskIndex" name="taskIndex" min="0" required>
            <button type="submit">Отменить задачу</button>
        </form>
    </div>

    <div id="tasksList"></div> <!-- Для отображения списка задач -->
</div>

</body>
</html>