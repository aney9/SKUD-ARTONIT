<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр согласия - <?php echo htmlspecialchars($full_name); ?></title>
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .header {
            background: #337ab7;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .signature-info {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .signature-image {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .signature-image img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #337ab7;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px 10px 0;
        }
        .btn:hover {
            background: #286090;
        }
        .btn-danger {
            background: #d9534f;
        }
        .btn-danger:hover {
            background: #c9302c;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Согласие на обработку персональных данных</h2>
        </div>
        
        <div class="signature-info">
            <h3>Информация о пользователе</h3>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($id_pep); ?></p>
            <p><strong>ФИО:</strong> <?php echo htmlspecialchars($full_name); ?></p>
            <p><strong>Дата создания подписи:</strong> <?php 
                if (file_exists($signature_path)) {
                    echo date('d.m.Y H:i:s', filemtime($signature_path));
                } else {
                    echo 'Неизвестно';
                }
            ?></p>
        </div>
        
        <div class="signature-image">
            <?php if ($signature_url): ?>
                <img src="<?php echo htmlspecialchars($signature_url); ?>" alt="Подпись пользователя <?php echo htmlspecialchars($full_name); ?>" />
            <?php else: ?>
                <p style="color: red;">Изображение подписи не найдено</p>
            <?php endif; ?>
        </div>
        
        <div class="actions">
            <a href="javascript:history.back();" class="btn">Назад</a>
            <a href="<?php echo URL::site('dashboard'); ?>" class="btn">На главную</a>
            <?php if ($signature_url): ?>
                <a href="<?php echo htmlspecialchars($signature_url); ?>" class="btn" target="_blank">Открыть в новом окне</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>