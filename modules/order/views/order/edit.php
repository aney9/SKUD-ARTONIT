<?php
include Kohana::find_file('views', 'alert');

$guest = new Guest2($id_pep);
$id_card = isset($cardlist[0]['ID_CARD']) ? $cardlist[0]['ID_CARD'] : null;
$key = new Keyk($id_card);
$mode = isset($mode) ? $mode : 'guest_mode';
$user = new User();
?>

<?php if ($user->id_orgctrl == 1) {
    switch ($mode) {
        case 'buro':
            break;
    }
} ?>

<div class="onecolumn">
    <div class="header">
        <span>
            <?php
            switch ($mode) {
                case 'guest_mode':
                    echo $id_pep ? __('guest.title') . ': ' . htmlspecialchars($guest->name) . ' ' . htmlspecialchars($guest->surname) : '';
                    break;
                case 'archive_mode':
                    echo $id_pep ? __('guest.titleinArchive') . ': ' . htmlspecialchars($guest->name) . ' ' . htmlspecialchars($guest->surname) : '';
                    break;
                case 'newguest':
                    echo '<span>' . __('guest.registration') . '</span>';
                    break;
                case 'buro':
                    echo $id_pep ? __('guest.title') . ': ' . htmlspecialchars($guest->name) . ' ' . htmlspecialchars($guest->surname) : '';
                    break;
            }
            ?>
        </span>
    </div>
    <br class="clear" />
    <div class="content">
        <form action="order/save" method="post" onsubmit="return validate()">
            <input type="hidden" name="hidden" value="form_sent" />
            <input type="hidden" name="id_pep" value="<?php echo $id_pep; ?>" />

            <table style="margin: 0">
                <tr>
                    <td>
                        <?php
                        switch ($mode) {
                            case 'newguest':
                            case 'guest_mode':
                            case 'archive_mode':
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/personal_data');
                                break;
                        }
                        ?>
                    </td>
                    <td style="padding-left: 40px; vertical-align: top;">
                        <?php
                        switch ($mode) {
                            case 'newguest':
                                include Kohana::find_file('views', 'order/block/card_dates');
                                break;
                            case 'guest_mode':  
                                if ($user -> id_role == 1){
                                    include Kohana::find_file('views', 'order/block/rfid');
                                    echo '<br>';
                                    include Kohana::find_file('views', 'order/block/card_dates');
                                    break;
                                }
                            case 'archive_mode':
                                if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                }
                                include Kohana::find_file('views', 'order/block/card_dates');
                                break;
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/rfid');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/card_dates');
                                break;
                        }
                        ?>
                    </td>
                    <td style="padding-left: 40px; vertical-align: top;">
                        <?php
                        switch ($mode) {
                            case 'newguest':
                            case 'guest_mode':
                            case 'archive_mode':
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/note');
                                break;
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <br />
            <?php
            switch ($mode) {
                case 'newguest':
                    echo Form::hidden('todo', 'savenew');
                    echo Form::submit('savenew', __('Добавить гостя214'));
                    break;
                case 'guest_mode':
                    if ($user->id_role != 1){
                        echo Form::open('order/save');
                        echo Form::hidden('id_pep', $id_pep);
                        echo Form::hidden('todo', 'forceexit');
                        echo Form::close();
                    } else if ($user->id_role == 1) {
                        $mode = 'buro';
    
                    echo Form::hidden('todo', 'reissue'); 
                    echo Form::submit('reissue', __('Обновить233'), [
                        'onclick' => "this.form.elements.todo.value='reissue'"
                    ]);
                    
                    if (!empty($cardlist[0]['ID_CARD'])) {
                        echo Form::submit('forceexit', __('Забрать карту!'), [
                            'onclick' => "this.form.elements.todo.value='forceexit'"
                        ]);
                    }

                    }
                    break;
                case 'archive_mode':
                    if ($user->id_role == 1){
                        echo Form::hidden('todo', 'forceexit');
                        echo Form::submit('forceexit', __('Забрать карту!133'));
                    }
                    break;
                case 'buro':
                
                    if ($user->id_role == 1) {
                         echo Form::hidden('todo', 'reissue'); 
                    echo Form::submit('reissue', __('Обновить233'), [
                        'onclick' => "this.form.elements.todo.value='reissue'"
                        
                    ]);
                    
                    echo Form::submit('consent', __('Согласие'), array(
                            'onclick' => "this.form.elements.todo.value='consent'"
                        ));
                    if (!empty($cardlist[0]['ID_CARD'])) {
                        echo Form::submit('forceexit', __('Забрать карту!'), [
                            'onclick' => "this.form.elements.todo.value='forceexit'"
                        ]);
                    }
                    
                    } else {
                        echo Form::hidden('todo', 'savenew');
                        echo Form::submit('savenew', __('Добавить гостя230'));
                    }
                    break;
                default:
                    break;
            }
            echo Form::close();
            ?>

            <?php
            echo 'id_pep=' . $guest->id_pep;
            echo '<br>';
            echo 'mode=' . $mode;
            echo '<br>';
            echo Debug::vars('249', $user);
            ?>
        </form>
    </div>
</div>