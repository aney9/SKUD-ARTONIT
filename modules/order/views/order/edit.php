<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php
$flash_success = Session::instance()->get_once('flash_success');
$flash_error = Session::instance()->get_once('flash_error');
if ($flash_success) {
    echo '<div style="color: green; margin-bottom: 10px;">' . htmlspecialchars($flash_success) . '</div>';
}
if ($flash_error) {
    echo '<div style="color: red; margin-bottom: 10px;">' . htmlspecialchars($flash_error) . '</div>';
}

include Kohana::find_file('views', 'alert_line');

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
                case 'neworder':
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
        <form action="order/save" method="post" id="main_form" onsubmit="return validate()">
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
                            case 'neworder':
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
                                include Kohana::find_file('views', 'order/block/selectBuro');
                                break;
                            case 'guest_mode':  
                                if ($user->id_role == 1) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                    echo '<br>';
                                    include Kohana::find_file('views', 'order/block/card_dates');
                                }
                                break;
                            case 'archive_mode':
                                if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                }
                                include Kohana::find_file('views', 'order/block/card_dates');
                                break;
                            case 'neworder':
                                include Kohana::find_file('views', 'order/block/rfid');
                                echo '<br>';
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
                            case 'neworder':
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/note');
                                if (($mode == 'buro' || $mode == 'neworder') && $user->id_role == 2 ) {
                                    echo '<br>';
                                    include Kohana::find_file('views', 'order/block/access_checkboxes');
                                }
                                break;
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <br />
            <?php
            switch ($mode) {
                case 'neworder':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        echo Form::hidden('todo', 'savenewwithcard'); 
                        echo Form::submit('savenewwithcard', __('Добавить гостя'), array(
                            'onclick' => "this.form.elements.todo.value='savenewwithcard'"
                        ));
                        
                        // $pd = new PD($id_pep);
                        // $signature_file = $pd->checkSignature($id_pep);
                        // if ($signature_file === false || !file_exists($signature_file)) {
                        //     echo Form::submit('consent', __('Согласие'), array(
                        //         'onclick' => "this.form.elements.todo.value='consent'"
                        //     ));
                        // } else {
                        //     $signature_url = '/Uploads/signatures/' . basename($signature_file);
                        //     echo '<a href="' . htmlspecialchars($signature_url) . '" target="_blank" class="btn">' . __('Просмотреть подпись') . '</a>';
                        // }
                        
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::submit('forceexit', __('Забрать карту!'), array(
                                'onclick' => "this.form.elements.todo.value='forceexit'"
                            ));
                        }
                    } else {
                        echo Form::hidden('todo', 'update');
                        echo Form::submit('update', __('Обновить гостя'));
                    }
                    break;
                case 'newguest':
                    echo Form::hidden('todo', 'savenew');
                    echo Form::submit('savenew', __('Добавить гостя214'));
                    break;
                case 'guest_mode':
                    if ($user->id_role != 1) {
                        echo Form::open('order/save');
                        echo Form::hidden('id_pep', $id_pep);
                        echo Form::hidden('todo', 'forceexit');
                        echo Form::close();
                    } else if ($user->id_role == 1) {
                        $mode = 'buro';
                        echo Form::hidden('todo', 'reissue'); 
                        echo Form::submit('reissue', __('Обновить233'), array(
                            'onclick' => "this.form.elements.todo.value='reissue'"
                        ));
                        
                        $pd = new PD($id_pep);
                        $signature_file = $pd->checkSignature($id_pep);
                        if ($signature_file === false || !file_exists($signature_file)) {
                            echo Form::submit('consent', __('Согласие'), array(
                                'onclick' => "this.form.elements.todo.value='consent'"
                            ));
                        } else {
                            $signature_url = '/downloads' . basename($signature_file);
                            echo '<a href="' . htmlspecialchars($signature_url) . '" target="_blank" class="btn">' . __('Просмотреть подпись') . '</a>';
                        }
                        
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::submit('forceexit', __('Забрать карту!'), array(
                                'onclick' => "this.form.elements.todo.value='forceexit'"
                            ));
                        }
                    }
                    break;
                case 'archive_mode':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        if (!empty($cardlist[0]['ID_CARD'])) {
                        echo Form::hidden('todo', 'forceexit');
                        echo Form::submit('forceexit', __('Забрать карту!133'));
                        }
                        echo Form::hidden('todo', 'newguestorder');
                        echo Form::submit('newguestorder', __('Создать новую заявку222'), array(
                                'onclick' => "this.form.elements.todo.value='newguestorder'"
                            ));
                    }
                    elseif($user->id_role == 2 || $user->id_role == 3)
                        echo Form::hidden('todo', 'newguestorder');
                        echo Form::submit('newguestorder', __('Создать новую заявку'), array(
                                'onclick' => "this.form.elements.todo.value='newguestorder'"
                            ));
                    break;

                case 'buro':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        echo Form::hidden('todo', 'reissue'); 
                        echo Form::submit('reissue', __('Обновить233'), array(
                            'onclick' => "this.form.elements.todo.value='reissue'"
                        ));
                        
                        $pd = new PD($id_pep);
                        $signature_file = $pd->checkSignature($id_pep);
                        if ($signature_file === false || !file_exists($signature_file)) {
                            echo Form::submit('consent', __('Согласие'), array(
                                'onclick' => "this.form.elements.todo.value='consent'"
                            ));
                        } else {
                            $signature_url = '/downloads' . basename($signature_file);
                            echo '<a href="' . htmlspecialchars($signature_url) . '" target="_blank" class="btn">' . __('Просмотреть подпись') . '</a>';
                        }
                        
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::submit('forceexit', __('Забрать карту!'), array(
                                'onclick' => "this.form.elements.todo.value='forceexit'"
                            ));
                        }
                    } else {
                        echo Form::hidden('todo', 'update');
                        echo Form::submit('update', __('Обновить гостя'));
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
            ?>
        </form>
    </div>
</div>