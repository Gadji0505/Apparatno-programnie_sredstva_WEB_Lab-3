<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета программиста</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .radio-group, .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .radio-option, .checkbox-option {
            display: flex;
            align-items: center;
        }
        .radio-option input, .checkbox-option input {
            margin-right: 10px;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            color: #27ae60;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Анкета программиста</h1>
    
    <?php if (!empty($_GET['save'])): ?>
        <div class="success">Спасибо, ваши данные сохранены!</div>
    <?php endif; ?>
    
    <form action="" method="POST">
        <div class="form-group">
            <label for="fio">ФИО*</label>
            <input type="text" id="fio" name="fio" required 
                   value="<?= htmlspecialchars($_POST['fio'] ?? '') ?>">
            <?php if (isset($errors['fio'])): ?>
                <div class="error"><?= $errors['fio'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="phone">Телефон*</label>
            <input type="tel" id="phone" name="phone" required 
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            <?php if (isset($errors['phone'])): ?>
                <div class="error"><?= $errors['phone'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="email">Email*</label>
            <input type="email" id="email" name="email" required 
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?= $errors['email'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="birth_date">Дата рождения*</label>
            <input type="date" id="birth_date" name="birth_date" required 
                   value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
            <?php if (isset($errors['birth_date'])): ?>
                <div class="error"><?= $errors['birth_date'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Пол*</label>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" id="male" name="gender" value="male" 
                           <?= ($_POST['gender'] ?? '') === 'male' ? 'checked' : '' ?> required>
                    <label for="male">Мужской</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="female" name="gender" value="female" 
                           <?= ($_POST['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
                    <label for="female">Женский</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="other" name="gender" value="other" 
                           <?= ($_POST['gender'] ?? '') === 'other' ? 'checked' : '' ?>>
                    <label for="other">Другой</label>
                </div>
            </div>
            <?php if (isset($errors['gender'])): ?>
                <div class="error"><?= $errors['gender'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="languages">Любимый язык программирования*</label>
            <select id="languages" name="languages[]" multiple required>
                <option value="1" <?= in_array('1', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Pascal</option>
                <option value="2" <?= in_array('2', $_POST['languages'] ?? []) ? 'selected' : '' ?>>C</option>
                <option value="3" <?= in_array('3', $_POST['languages'] ?? []) ? 'selected' : '' ?>>C++</option>
                <option value="4" <?= in_array('4', $_POST['languages'] ?? []) ? 'selected' : '' ?>>JavaScript</option>
                <option value="5" <?= in_array('5', $_POST['languages'] ?? []) ? 'selected' : '' ?>>PHP</option>
                <option value="6" <?= in_array('6', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Python</option>
                <option value="7" <?= in_array('7', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Java</option>
                <option value="8" <?= in_array('8', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Haskell</option>
                <option value="9" <?= in_array('9', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Clojure</option>
                <option value="10" <?= in_array('10', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Prolog</option>
                <option value="11" <?= in_array('11', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Scala</option>
                <option value="12" <?= in_array('12', $_POST['languages'] ?? []) ? 'selected' : '' ?>>Go</option>
            </select>
            <?php if (isset($errors['languages'])): ?>
                <div class="error"><?= $errors['languages'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="biography">Биография*</label>
            <textarea id="biography" name="biography" required><?= htmlspecialchars($_POST['biography'] ?? '') ?></textarea>
            <?php if (isset($errors['biography'])): ?>
                <div class="error"><?= $errors['biography'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <div class="checkbox-option">
                <input type="checkbox" id="contract" name="contract" 
                       <?= ($_POST['contract'] ?? '') === 'on' ? 'checked' : '' ?> required>
                <label for="contract">С контрактом ознакомлен(а)*</label>
            </div>
            <?php if (isset($errors['contract'])): ?>
                <div class="error"><?= $errors['contract'] ?></div>
            <?php endif; ?>
        </div>
        
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>