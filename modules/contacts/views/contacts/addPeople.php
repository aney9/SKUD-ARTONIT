<?php defined('SYSPATH') or die('No direct script access.'); ?>
<?php if (isset($topbuttonbar)) echo $topbuttonbar; ?>
<fieldset>
    <legend><?php echo __('Данные пользователя'); ?></legend>

    <?php if (!empty($alert)): ?>
        <div style="color: <?php echo ($alert === __('Пользователь успешно добавлен')) ? 'green' : 'red'; ?>;">
            <?php echo htmlspecialchars($alert); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label for="surname"><?php echo __('Фамилия'); ?></label>
            <br />
            <input type="text" size="50" name="surname" id="surname" value="" />
            <br />
            <span class="error" id="error_surname" style="color: red; display: none;">
                <?php echo __('Поле "Фамилия" обязательно для заполнения'); ?>
            </span>
        </div>
        <br />

        <div>
            <table align="left">
                <tr>
                    <td>
                        <label for="name"><?php echo __('Имя'); ?></label>
                        <br />
                        <input type="text" size="50" name="name" id="name" value="" style="width: 150px" />
                        <br />
                        <span class="error" id="error_name" style="color: red; display: none;">
                            <?php echo __('Поле "Имя" обязательно для заполнения'); ?>
                        </span>
                    </td>
                    <td style="padding-left: 15px">
                        <label for="patronymic"><?php echo __('Отчество'); ?></label>
                        <br />
                        <input type="text" size="50" name="patronymic" id="patronymic" value="" style="width: 150px" />
                    </td>
                </tr>
            </table>
        </div>
        <br style="clear: both;" />

        <div>
            <table align="left">
                <tr>
                    <td>
                        <label for="username"><?php echo __('Логин'); ?></label>
                        <br />
                        <input type="text" size="50" name="username" id="username" value="" style="width: 150px" />
                        <br />
                        <span class="error" id="error_username" style="color: red; display: none;">
                            <?php echo __('Поле "Логин" обязательно для заполнения'); ?>
                        </span>
                    </td>
                    <td style="padding-left: 15px">
                        <label for="password"><?php echo __('Пароль'); ?></label>
                        <br />
                        <input type="password" size="50" name="password" id="password" style="width: 150px" />
                        <br />
                        <span class="error" id="error_password" style="color: red; display: none;">
                            <?php echo __('Поле "Пароль" обязательно для заполнения'); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <br style="clear: both;" />

        <div>
            <label for="organization"><?php echo __('Организация'); ?></label>
            <br />
            <select name="organization" id="organization" style="width: 315px; color: black; background: white;">
                <option value="">-- Выберите организацию --</option>
                <?php if (!empty($organizations)): ?>
                    <?php foreach ($organizations as $org): ?>
                        <option value="<?php echo htmlspecialchars($org['id']); ?>"
                            <?php if (isset($force_org) && $force_org == $org['id']) echo 'selected="selected"'; ?>>
                            <?php echo htmlspecialchars($org['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>Нет доступных организаций</option>
                <?php endif; ?>
            </select>
            <br />
            <span class="error" id="error_organization" style="color: red; display: none;">
                <?php echo __('Поле "Организация" обязательно для заполнения'); ?>
            </span>
        </div>
        <br />
        <br />

        <div style="margin-top: 15px;">
            <input type="submit" value="<?php echo __('Добавить пользователя'); ?>" />
        </div>
    </form>
</fieldset>

<style>
    select, option {
        color: black !important;
        background: white !important;
    }
</style>

<script>
    // Клиентская валидация
    document.querySelector('form').addEventListener('submit', function(e) {
        var valid = true;
        var fields = [
            { id: 'surname', errorId: 'error_surname' },
            { id: 'name', errorId: 'error_name' },
            { id: 'username', errorId: 'error_username' },
            { id: 'password', errorId: 'error_password' },
            { id: 'organization', errorId: 'error_organization' }
        ];

        fields.forEach(function(field) {
            var input = document.getElementById(field.id);
            var error = document.getElementById(field.errorId);
            if (!input.value.trim()) {
                error.style.display = 'block';
                valid = false;
            } else {
                error.style.display = 'none';
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
</script>