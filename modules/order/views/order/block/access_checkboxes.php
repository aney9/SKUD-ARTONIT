<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div>
    <fieldset>
        <legend>Доступы</legend>
        <div style="margin-bottom: 10px;">
            <?php 
            if (isset($buro_accesses) && is_array($buro_accesses) && !empty($buro_accesses)) {
                $selected_value = isset($selected_access) ? $selected_access : '';
                
                foreach ($buro_accesses as $access) {
                    if ($user->count_access == 1) {
                        echo '<input type="hidden" name="ACCESS_NAME" value="' . htmlspecialchars($access['ID_ACCESSNAME']) . '">';
                        echo Form::radio(
                            'ACCESS_NAME_display',
                            $access['ID_ACCESSNAME'],
                            true,
                            array(
                                'id' => 'access_' . htmlspecialchars($access['ID_ACCESSNAME']),
                                'disabled' => 'disabled'
                            )
                        );
                    } else {
                        echo Form::radio(
                            'ACCESS_NAME',
                            $access['ID_ACCESSNAME'],
                            ($access['ID_ACCESSNAME'] == $selected_value),
                            array(
                                'id' => 'access_' . htmlspecialchars($access['ID_ACCESSNAME']),
                                'required' => 'required' // Добавлен атрибут required
                            )
                        );
                    }
                    echo Form::label('access_' . htmlspecialchars($access['ID_ACCESSNAME']), htmlspecialchars($access['NAME']));
                    echo '<br>';
                }
            } else {
                echo '<p>Нет доступных зон</p>';
            }
            ?>
        </div>
    </fieldset>
</div>