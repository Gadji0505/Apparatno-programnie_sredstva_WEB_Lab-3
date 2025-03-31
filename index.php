<?php
header('Content-Type: text/html; charset=UTF-8');

// Подключение к базе данных
$user = 'u68647'; // Замените на ваш логин
$pass = '123'; // Замените на ваш пароль
$db = new PDO('mysql:host=localhost;dbname=u68647', $user, $pass, [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
        include('form.php');
        echo '<div class="success">Спасибо, результаты сохранены.</div>';
        exit();
    }
    include('form.php');
    exit();
}

// Обработка POST-запроса
$errors = [];
$fields = [
    'fio' => [
        'required' => true,
        'pattern' => '/^[а-яА-ЯёЁa-zA-Z\s\-]+$/u',
        'max_length' => 150,
        'error_message' => 'ФИО должно содержать только буквы, пробелы и дефисы (макс. 150 символов)'
    ],
    'phone' => [
        'required' => true,
        'pattern' => '/^\+?[\d\s\-\(\)]{7,20}$/',
        'error_message' => 'Введите корректный номер телефона'
    ],
    'email' => [
        'required' => true,
        'filter' => FILTER_VALIDATE_EMAIL,
        'error_message' => 'Введите корректный email'
    ],
    'birth_date' => [
        'required' => true,
        'validate' => function($value) {
            $date = DateTime::createFromFormat('Y-m-d', $value);
            return $date && $date->format('Y-m-d') === $value;
        },
        'error_message' => 'Введите корректную дату рождения'
    ],
    'gender' => [
        'required' => true,
        'allowed' => ['male', 'female', 'other'],
        'error_message' => 'Выберите пол'
    ],
    'languages' => [
        'required' => true,
        'validate' => function($value) use ($db) {
            if (!is_array($value) return false;
            $stmt = $db->prepare("SELECT COUNT(*) FROM programming_languages WHERE id = ?");
            foreach ($value as $langId) {
                $stmt->execute([$langId]);
                if ($stmt->fetchColumn() == 0) return false;
            }
            return true;
        },
        'error_message' => 'Выберите хотя бы один язык программирования'
    ],
    'biography' => [
        'required' => true,
        'max_length' => 5000,
        'error_message' => 'Биография должна содержать не более 5000 символов'
    ],
    'contract' => [
        'required' => true,
        'validate' => function($value) { return $value === 'on'; },
        'error_message' => 'Необходимо принять условия контракта'
    ]
];

// Валидация данных
foreach ($fields as $field => $rules) {
    $value = $_POST[$field] ?? null;
    
    if ($rules['required'] && empty($value)) {
        $errors[$field] = 'Это поле обязательно для заполнения';
        continue;
    }
    
    if (isset($rules['pattern']) && !preg_match($rules['pattern'], $value)) {
        $errors[$field] = $rules['error_message'];
    }
    
    if (isset($rules['filter']) && !filter_var($value, $rules['filter'])) {
        $errors[$field] = $rules['error_message'];
    }
    
    if (isset($rules['validate']) && !$rules['validate']($value)) {
        $errors[$field] = $rules['error_message'];
    }
    
    if (isset($rules['max_length']) && mb_strlen($value) > $rules['max_length']) {
        $errors[$field] = $rules['error_message'];
    }
    
    if (isset($rules['allowed']) && !in_array($value, $rules['allowed'])) {
        $errors[$field] = $rules['error_message'];
    }
}

if (!empty($errors)) {
    include('form.php');
    exit();
}

// Сохранение в базу данных
try {
    $db->beginTransaction();
    
    // Сохраняем основную информацию
    $stmt = $db->prepare("
        INSERT INTO applications 
        (full_name, phone, email, birth_date, gender, biography, contract_accepted) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['fio'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['birth_date'],
        $_POST['gender'],
        $_POST['biography'],
        $_POST['contract'] === 'on' ? 1 : 0
    ]);
    
    $applicationId = $db->lastInsertId();
    
    // Сохраняем выбранные языки программирования
    $stmt = $db->prepare("
        INSERT INTO application_languages (application_id, language_id) 
        VALUES (?, ?)
    ");
    
    foreach ($_POST['languages'] as $languageId) {
        $stmt->execute([$applicationId, $languageId]);
    }
    
    $db->commit();
    
    // Перенаправляем с флагом успешного сохранения
    header('Location: ?save=1');
} catch (PDOException $e) {
    $db->rollBack();
    die('Ошибка при сохранении данных: ' . $e->getMessage());
}