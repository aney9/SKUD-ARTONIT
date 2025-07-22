<?php defined('SYSPATH') or die('No direct script access.'); ?>
<?php if (isset($topbuttonbar)) echo $topbuttonbar; ?>
<?php echo Debug::vars('73', $people); ?>
<fieldset>
    <legend><?php echo __('Данные пользователя'); ?></legend>

    <?php if (!empty($alert)): ?>
        <div style="color: <?php echo ($alert === __('Пользователь успешно добавлен') || $alert === __('Данные пользователя успешно обновлены')) ? 'green' : 'red'; ?>;">
            <?php echo htmlspecialchars($alert); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo URL::base() . 'contacts/setFlag/' . Arr::get($people, 'id_pep', 0); ?>">
        <div>
            <label for="surname"><?php echo __('Фамилия'); ?></label>
            <br />
            <input type="text" size="50" name="surname" disabled="disabled" id="surname" value="<?php echo htmlspecialchars(Arr::get($people, 'surname', '')); ?>" />
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
                        <input type="text" size="50" name="name" id="name" disabled="disabled" value="<?php echo htmlspecialchars(Arr::get($people, 'name', '')); ?>" style="width: 150px" />
                        <br />
                        <span class="error" id="error_name" style="color: red; display: none;">
                            <?php echo __('Поле "Имя" обязательно для заполнения'); ?>
                        </span>
                    </td>
                    <td style="padding-left: 15px">
                        <label for="patronymic"><?php echo __('Отчество'); ?></label>
                        <br />
                        <input type="text" size="50" name="patronymic" id="patronymic" disabled="disabled" value="<?php echo htmlspecialchars(Arr::get($people, 'patronymic', '')); ?>" style="width: 150px" />
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
                        <input type="text" size="50" name="login" id="login" value="<?php echo htmlspecialchars(Arr::get($people, 'login', '')); ?>" style="width: 150px" />
                        <br />
                        <span class="error" id="error_username" style="color: red; display: none;">
                            <?php echo __('Поле "Логин" обязательно для заполнения'); ?>
                        </span>
                    </td>
                    <td style="padding-left: 15px">
                        <label for="password"><?php echo __('Пароль'); ?></label>
                        <br />
                        <input type="password" size="50" name="password" id="password" value="<?php echo htmlspecialchars(Arr::get($people, 'pswd', '')); ?>" style="width: 150px" />
                        <br />
                        <span class="error" id="error_password" style="color: red; display: none;">
                            <?php echo __('Поле "Пароль" обязательно для заполнения при создании'); ?>
                        </span>
                    </td>
                    <td style="padding-left: 15px">
        <label for="organization"><?php echo __('Организация'); ?></label>
        <br />
        <select name="organization" id="organization" style="width: 150px; color: black; background: white;">
            <?php 
            $current_org_id = Arr::get($people, 'id_org');
            $current_org_name = Arr::get($people, 'org_name');
            
            if (!empty($current_org_id) && !empty($current_org_name)): ?>
                <option value="<?php echo htmlspecialchars($current_org_id); ?>" selected="selected">
                    <?php echo htmlspecialchars($current_org_name); ?>
                </option>
            <?php else: ?>
                <option value="" selected="selected">Организация не указана</option>
            <?php endif; ?>
        </select>
        <br />
        <span class="error" id="error_organization" style="color: red; display: none;">
            <?php echo __('Поле "Организация" обязательно для заполнения'); ?>
        </span>
    </td>
                </tr>
            </table>
        </div>
        <br style="clear: both;" />
        <div>
            <input type="submit" value="<?php echo __('Сохранить данные'); ?>" />
        </div>
    </form>
</fieldset>

<fieldset>
    <legend><?php echo __('Флаги доступа'); ?></legend>
    <form method="post" action="<?php echo URL::base() . 'contacts/setFlag/' . Arr::get($people, 'id_pep', 0); ?>" id="flagsForm">
        <table class="data" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo __('Модули'); ?></th>
                    <th><?php echo __('Монитор'); ?></th>
                    <th><?php echo __('Отчеты'); ?></th>
                    <th><?php echo __('Менеджер карт'); ?></th>
                    <th><?php echo __('Прочие'); ?></th>
                </tr>
            </thead>
        <tbody style="max-height: 200px; overflow-y: auto;">
        <tr>
            <td>
                <input type="checkbox" name="konfigurator" id="konfigurator" value="1" 
                    <?= Arr::get($people, 'konfigurator', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="konfigurator">Конфигуратор</label>
                <br />

                <input type="checkbox" name="managecard" id="managecard" value="1" 
                    <?= Arr::get($people, 'managecard', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="managecard">Менеджер карточек</label>
                <br />

                <input type="checkbox" name="manageuser" id="manageuser" value="1" 
                    <?= Arr::get($people, 'manageuser', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="manageuser">Менеджер пользователей</label>
                <br />

                <input type="checkbox" name="reports" id="reports" value="1" 
                    <?= Arr::get($people, 'reports', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports">Отчеты</label>
                <br />

                <input type="checkbox" name="monitorEvents" id="monitorEvents" value="1" 
                    <?= Arr::get($people, 'monitorEvents', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="monitorEvents">Монитор событий</label>
                <br />

                <input type="checkbox" name="integrator" id="integrator" value="1" 
                    <?= Arr::get($people, 'integrator', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="integrator">Интегратор</label>
            </td>
            <td>
                <input type="checkbox" name="monitor1" id="monitor1" value="1" 
                    <?= Arr::get($people, 'monitor1', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="monitor1">Управление дверьми</label>
                <br />

                <input type="checkbox" name="monitor2" id="monitor2" value="1" 
                    <?= Arr::get($people, 'monitor2', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="monitor2">Выбор группы входа в мониторе</label>
            </td>
            
            <td>
                <input type="checkbox" name="reports1" id="reports1" value="1" 
                    <?= Arr::get($people, 'reports1', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports1">Список карточек</label>
                <br />

                <input type="checkbox" name="reports2" id="reports2" value="1" 
                    <?= Arr::get($people, 'reports2', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports2">Журнал событий</label>
                <br />

                <input type="checkbox" name="reports3" id="reports3" value="1" 
                    <?= Arr::get($people, 'reports3', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports3">Журнал рабочего времени</label>
                <br />

                <input type="checkbox" name="reports4" id="reports4" value="1" 
                    <?= Arr::get($people, 'reports4', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports4">Журнал уволенных</label>
                <br />

                <input type="checkbox" name="reports5" id="reports5" value="1" 
                    <?= Arr::get($people, 'reports5', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports5">Журнал рабочего времени 2</label>
                <br />

                <input type="checkbox" name="reports6" id="reports6" value="1" 
                    <?= Arr::get($people, 'reports6', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="reports6">Журнал сотрудников</label>
            </td>
            <td>
                <input type="checkbox" name="card_manager" id="card_manager" value="1" 
                    <?= Arr::get($people, 'card_manager', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="card_manager">Гостевое рабочее место</label>
            </td>
            <td>
                <input type="checkbox" name="other" id="other" value="1" 
                    <?= Arr::get($people, 'other', 0) == 1 ? 'checked="checked"' : '' ?>>
                <label for="other">Утверждение заявок</label>
            </td>
        </tr>
    </tbody>
            </table>
            <div>
                <input type="submit" form="flagsForm" value="<?php echo __('Сохранить флаги'); ?>" />
            </div>
        </form>
    </fieldset>

    <style>
        select, option {
            color: black !important;
            background: white !important;
        }
        table.data tbody {
            max-height: 200px;
            overflow-y: auto;
        }
        table.data {
            width: 100%;
        }
    </style>

    <script>
function handleFormSubmit(form, formId) {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Предотвращаем стандартную отправку формы
        console.log('Отправка формы:', formId); // Отладка

        var valid = true;
        var fields = [
            { id: 'surname', errorId: 'error_surname' },
            { id: 'name', errorId: 'error_name' },
            { id: 'login', errorId: 'error_username' },
            { id: 'organization', errorId: 'error_organization' }
        ];

        // Валидация только для формы с данными пользователя
        if (formId === 'userForm') {
            fields.forEach(function(field) {
                var input = document.getElementById(field.id);
                var error = document.getElementById(field.errorId);
                if (!input || !input.value.trim()) {
                    error.style.display = 'block';
                    valid = false;
                } else {
                    error.style.display = 'none';
                }
            });

            var password = document.getElementById('password');
            var errorPassword = document.getElementById('error_password');
            if (!<?php echo Arr::get($people, 'id_pep', 0); ?> && (!password || !password.value.trim())) {
                errorPassword.style.display = 'block';
                valid = false;
            } else if (errorPassword) {
                errorPassword.style.display = 'none';
            }
        }

        if (!valid) {
            console.log('Валидация не пройдена для формы:', formId);
            return;
        }

        // Отправка данных через AJAX
        var formData = new FormData(form);
        console.log('Отправляем данные:', Array.from(formData.entries())); // Отладка

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Ответ сервера:', response); // Отладка
            if (!response.ok) throw new Error('Сетевая ошибка: ' + response.status);
            return response.json();
        })
        .then(data => {
            console.log('Полученные данные:', data); // Отладка
            if (data.success) {
                // Обновляем поля формы (только для userForm)
                if (formId === 'userForm') {
                    document.getElementById('surname').value = data.contact_data.surname || '';
                    document.getElementById('name').value = data.contact_data.name || '';
                    document.getElementById('patronymic').value = data.contact_data.patronymic || '';
                    document.getElementById('login').value = data.contact_data.login || '';
                    document.getElementById('password').value = data.contact_data.pswd || '';
                    document.getElementById('organization').value = data.contact_data.id_org || '';
                }

                // Обновляем флаги (для обеих форм)
                ['monitor1', 'monitor2', 'konfigurator', 'managecard', 'manageuser', 'reports', 
                 'monitorEvents', 'other', 'integrator', 'reports1', 'reports2', 'reports3', 
                 'reports4', 'card_manager', 'reports5', 'reports6'].forEach(function(key) {
                    var checkbox = document.getElementById(key);
                    if (checkbox) {
                        checkbox.checked = data.contact_data[key] == 1;
                    }
                });

                // Показываем сообщение об успехе
                var alertDiv = document.createElement('div');
                alertDiv.style.color = 'green';
                alertDiv.textContent = data.alert;
                form.prepend(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);
            } else {
                // Показываем сообщение об ошибке
                var alertDiv = document.createElement('div');
                alertDiv.style.color = 'red';
                alertDiv.textContent = data.alert || 'Ошибка при сохранении данных';
                form.prepend(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);
            }
        })
        .catch(error => {
            console.error('Ошибка AJAX:', error); // Отладка
            var alertDiv = document.createElement('div');
            alertDiv.style.color = 'red';
            //alertDiv.textContent = 'Произошла ошибка при отправке данных: ' + error.message;
            form.prepend(alertDiv);
            setTimeout(() => alertDiv.remove(), 3000);
        });
    });
}

// Привязываем обработчики к обеим формам
var userForm = document.querySelector('form[action*="contacts/setFlag"]:not([id="flagsForm"])');
var flagsForm = document.querySelector('form#flagsForm');

if (userForm) handleFormSubmit(userForm, 'userForm');
if (flagsForm) handleFormSubmit(flagsForm, 'flagsForm');
</script>