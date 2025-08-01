<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div>
    <fieldset>
        <legend>Доступы</legend>
        <div style="margin-bottom: 10px;">
            <?php 
            if (isset($buro_accesses) && is_array($buro_accesses) && !empty($buro_accesses)) {
                $selected_value = isset($selected_access) ? $selected_access : '';
                
                foreach ($buro_accesses as $access) {
                    echo Form::radio('ACCESS_NAME', $access['ID_ACCESSNAME'], 
                        ($access['ID_ACCESSNAME'] == $selected_value), 
                        array('id' => 'access_'.$access['ID_ACCESSNAME'])
                    );
                    echo Form::label('access_'.$access['ID_ACCESSNAME'], $access['NAME']);
                    echo '<br>';
                }
            } else {
                echo '<p>Нет доступных зон</p>';
            }
            ?>
        </div>
    </fieldset>
</div>