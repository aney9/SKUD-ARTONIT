<div class="container">
    <h2>Подробная информация о бюро</h2>
    
    <div class="buro-details">
        <?php if (!empty($buro)): ?>
            <p><strong>Название:</strong> <?php echo HTML::chars($buro['name']); ?></p>
            <p><strong>Адрес:</strong> <?php echo HTML::chars($buro['information']); ?></p>
            
            <h3>Пользователи</h3>
            <?php if (!empty($users)): ?>
                <?php 
                // Группируем пользователей по ролям
                $users_by_role = array();
                foreach ($users as $user) {
                    $role_id = $user['id_role'];
                    if (!isset($users_by_role[$role_id])) {
                        $users_by_role[$role_id] = array();
                    }
                    $users_by_role[$role_id][] = $user;
                }
                
                // Выводим таблицы для каждой роли
                foreach ($users_by_role as $role_id => $role_users): 
                    $role_info = isset($roles[$role_id][0]) ? $roles[$role_id][0] : null;
                ?>
                    <h4><?php echo $role_info ? HTML::chars($role_info['name']) : 'Роль #'.$role_id; ?></h4>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ФИО</th>
                                <th>Дополнительная информация</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($role_users as $user): 
                                $person = isset($people[$user['id_pep']][0]) ? $people[$user['id_pep']][0] : null;
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($person): ?>
                                            <?php echo HTML::chars($person['SURNAME'] . ' ' . $person['NAME'] . ' ' . $person['PATRONYMIC']); ?>
                                        <?php else: ?>
                                            Неизвестно
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // Здесь можно добавить дополнительную информацию в зависимости от роли
                                        switch ($role_id) {
                                            case 1: // Администратор
                                                echo "Доступ ко всем функциям системы";
                                                break;
                                            case 3: // Арендатор
                                                echo "Доступ к аренде помещений";
                                                break;
                                            default:
                                                echo "Пользователь системы";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Нет пользователей, связанных с этим бюро.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Информация о бюро не найдена.</p>
        <?php endif; ?>
        
        <a href="<?php echo URL::site('order/UpdateBuro'); ?>" class="btn btn-default">
            Вернуться к списку
        </a>
        <a href="<?php echo URL::site('order/delete_buro/'.$buro['id']); ?>" class="btn" 
                   onclick="return confirm('Вы уверены, что хотите удалить это бюро? Все связанные пользователи также будут удалены.');">
                    Удалить бюро
                </a>
    </div>
</div>

<style>
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    h3 {
        color: #444;
        margin: 20px 0 10px;
    }
    h4 {
        color: #555;
        margin: 15px 0 5px;
        font-size: 16px;
    }
    .buro-details {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
    }
    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .user-table th, .user-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .user-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .user-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .user-table tr:hover {
        background-color: #f5f5f5;
    }
    .btn {
        display: inline-block;
        padding: 8px 16px;
        margin-top: 20px;
        background: #337ab7;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn:hover {
        background: #286090;
    }
    p {
        margin: 8px 0;
    }
    strong {
        font-weight: bold;
        min-width: 100px;
        display: inline-block;
    }
</style>