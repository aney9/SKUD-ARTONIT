<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div>
    <fieldset>
        <legend>Доступы</legend>
        <div style="margin-bottom: 10px;">
            <?php 
            if (isset($buro_accesses) && is_array($buro_accesses) && !empty($buro_accesses)) {
                foreach ($buro_accesses as $access) {
                    // Приводим ID к строке для надежности
                    $access_id = (string)$access['ID_ACCESSNAME'];
                    // Проверяем, есть ли ID в $selected_access (приводим к строкам)
                    $is_checked = isset($selected_access) && is_array($selected_access) && in_array($access_id, array_map('strval', $selected_access));
                    
                    if ($user->count_access == 1) {
                        // Для одного доступа используем скрытое поле и отключенную радиокнопку
                        echo '<input type="hidden" name="ACCESS_NAME" value="' . htmlspecialchars($access_id, ENT_QUOTES, 'UTF-8') . '">';
                        echo Form::radio(
                            'ACCESS_NAME_display',
                            $access_id,
                            true,
                            array(
                                'id' => 'access_' . htmlspecialchars($access_id, ENT_QUOTES, 'UTF-8'),
                                'disabled' => 'disabled'
                            )
                        );
                    } else {
                        // Для множественных доступов используем радиокнопку
                        echo Form::radio(
                            'ACCESS_NAME',
                            $access_id,
                            $is_checked,
                            array(
                                'id' => 'access_' . htmlspecialchars($access_id, ENT_QUOTES, 'UTF-8'),
                                'required' => 'required' // Сохраняем атрибут required
                            )
                        );
                    }
                    echo Form::label('access_' . htmlspecialchars($access_id, ENT_QUOTES, 'UTF-8'), htmlspecialchars($access['NAME'], ENT_QUOTES, 'UTF-8'));
                    echo '<br>';
                }
            } else {
                echo '<p>Нет доступных зон</p>';
            }
            ?>
        </div>
    </fieldset>
</div>