<div class="container">
    <h2>Подробная информация о бюро</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <fieldset>
            <div class="form-group">
                <label for="comboboxBuro">Бюро</label>
                <select class="form-control" id="comboboxBuro" name="buro_id" required>
                    <option value="">Выберите бюро</option>
                    <?php foreach ($buroList as $buro): ?>
                        <option value="<?= $buro['id'] ?>"><?= HTML::chars($buro['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="comboboxUser">Сотрудник</label>
                <select class="form-control" id="comboboxUser" name="user_id" required>
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($users as $user): ?>
                        <?php 
                            $surname = !empty(trim($user['SURNAME'])) ? trim($user['SURNAME']) : '';
                            $name = !empty(trim($user['NAME'])) ? trim($user['NAME']) : '';
                            $patronymic = !empty(trim($user['PATRONYMIC'])) ? trim($user['PATRONYMIC']) : '';
                            
                            $displayName = trim("$surname $name $patronymic");
                            $displayName = !empty($displayName) ? $displayName : "[Без имени] (ID: {$user['ID_PEP']})";
                        ?>
                        <option value="<?= $user['ID_PEP'] ?>">
                            <?= HTML::chars($displayName) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="comboboxRole">Роль</label>
                <select class="form-control" id="comboboxRole" name="role_id" required>
                    <option value="">Выберите роль</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= HTML::chars($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Добавить</button>
        </fieldset>
    </form>
</div>

<style>
    select.form-control, 
    select.form-control option {
        color: #333 !important;
        background-color: #fff !important;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
</style>