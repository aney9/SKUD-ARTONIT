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
//echo Debug::vars('19', $mode);exit;
//$mode = 'newguest';
$user = new User();
//echo Debug::vars('20', $user);exit;
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
            //echo Debug::vars('35', $mode);exit;
            switch ($mode) {
                case 'newguest':
                case 'neworder':
                    echo '<span>' . __('guest.registration') . '</span>';
                    break;
                case 'guest_mode':
                    echo $id_pep ? __('guest.title') . ': ' . htmlspecialchars($guest->surname) . ' ' . htmlspecialchars($guest->name) . (!empty($guest->patronymic) ? ' ' . htmlspecialchars($guest->patronymic) : '') : '';
                    break;
                case 'archive_mode':
                    echo $id_pep ? __('guest.titleinArchive') . ': ' . htmlspecialchars($guest->surname) . ' ' . htmlspecialchars($guest->name) . (!empty($guest->patronymic) ? ' ' . htmlspecialchars($guest->patronymic) : '') : '';
                    break;
                
                case 'buro':
                    echo $id_pep ? __('guest.title') . ': ' . htmlspecialchars($guest->surname) . ' ' . htmlspecialchars($guest->name) . ' ' . htmlspecialchars($guest->patronymic) : '';
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
                        //echo Debug::vars('65', $mode);exit;
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
                        //echo Debug::vars('79', $mode);exit;
                        switch ($mode) {
                            case 'newguest':
                                include Kohana::find_file('views', 'order/block/card_dates');
                                include Kohana::find_file('views', 'order/block/selectBuro');
                                break;
                            case 'guest_mode':  
                                if ($user->id_role == 1||$user->id_role == 2) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/card_dates');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/forPD');
                                }
                                elseif ($user->id_role == 3){
                                    if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                }
                                }
                                break;
                            case 'archive_mode':
                                if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                }
                                include Kohana::find_file('views', 'order/block/card_dates');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/forPD');
                                break;
                            case 'neworder':
                                include Kohana::find_file('views', 'order/block/rfid');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/card_dates');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/selectOrg');
                                break;
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/rfid');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/card_dates');
                                echo '<br>';
                                include Kohana::find_file('views', 'order/block/forPD');
                                break;
                        }
                        ?>
                    </td>
                    <td style="padding-left: 40px; vertical-align: top;">
                        <?php
                        //echo Debug::vars('121', $mode);exit;
                        switch ($mode) {
                            
                            case 'newguest':
                            case 'guest_mode':
                            case 'archive_mode':
                            case 'neworder':
                            case 'buro':
                                include Kohana::find_file('views', 'order/block/note');
                                if (($mode == 'buro' || $mode == 'neworder' || $mode == 'guest_mode') && $user->id_role == 2 || $user->id_role == 1 ) {
                                    echo '<br>';
                                    include Kohana::find_file('views', 'order/block/access_checkboxes');
                                }
                                //echo Debug::vars('134', $mode);exit;
                                break;
                        }
                        //echo Debug::vars('136', $mode);exit;
                        ?>
                    </td>
                </tr>
            </table>
            
            <br />
            <?php
            //$mode = 'newguest';
            //echo Debug::vars('143', $mode);exit;
            //echo Debug::vars('144', $_SESSION);exit;
            switch ($mode) {
                case 'neworder':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        echo Form::hidden('todo', 'savenewwithcard'); 
                        echo Form::submit('savenewwithcard', __('order.edit.nameAddGuestWithCardBtn'), array(
                            'onclick' => "this.form.elements.todo.value='savenewwithcard'"
                        ));
                        
                        $pd = new PD($id_pep);
                        $signature_file = $pd->checkSignature($id_pep);
                        if ($signature_file === false || !file_exists($signature_file)) {
                            echo Form::submit('consent1', __('order.edit.nameConsentBtn'), array(
                                'onclick' => "this.form.elements.todo.value='consent1'"
                            ));
                        } else {
                            echo '<a href="/index.php/order/view_signature/' . htmlspecialchars($id_pep) . '" class="btn">' . __('order.edit.nameViewSignature') . '</a>';
                        }
                        
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::submit('forceexit', __('order.edit.nameForceexitBtn'), array(
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
                    echo Form::submit('savenew', __('order.edit.nameAddGuestBtn'));
                    break;
                case 'guest_mode':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        echo Form::hidden('todo', 'reissue'); 
                        echo Form::submit('reissue', __('order.edit.nameUpdateBtn'), array(
                            'onclick' => "this.form.elements.todo.value='reissue'"
                        ));
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::open();
                            echo Form::hidden('todo', 'forceexit');
                            echo Form::submit('forceexit', __('order.edit.nameForceexitBtn'), array(
                                'onclick' => "this.form.elements.todo.value='forceexit'"
                            ));
                        }
                        echo Form::close();
                        
                        $pd = new PD($id_pep);
                        $signature_file = $pd->checkSignature($id_pep);
                        if ($signature_file === false || !file_exists($signature_file)) {
                            echo Form::open('order/PersonalData/' . $id_pep, array('class'=>'consent'));
                            echo Form::hidden('todo', 'consent');
                            echo Form::submit('consent', __('order.edit.nameConsentBtn'), array(
                                'onclick' => "this.form.elements.todo.value='consent'"
                            ));
                            echo Form::close();
                        } else {
                            echo Form::open('order/view_signature_page/'. $id_pep, array('class'=>'signature'));
                            echo Form::hidden('todo', 'signature');
                            echo Form::submit('signature', __('order.edit.nameViewSignature'), array(
                                'onclick'=> "this.form.elements.todo.value='signature'"
                            ));
                           //echo '<a href="' . URL::site('order/view_signature_page/' . $id_pep) . '" class="btn">' . __('Посмотреть согласие') . '</a>';
                            echo Form::close();
                        }
                        
                    } else {
                        echo Form::hidden('todo', 'update');
                        echo Form::submit('update', __('Обновить гостя'));
                    }
                    echo Form::close();
                    echo Form::open('order/historyGuest/' . $id_pep, array('class' => 'history-form'));
                    echo Form::hidden('todo', 'viewhistory');
                    echo Form::submit('viewhistory', __('order.edit.nameHistoryBtn'), array(
                        'class' => 'btn',
                        'onclick' => "this.form.elements.todo.value='viewhistory';"
                    ));
                    echo Form::close();
                    
                    if ($user->id_role === 3){
                        // echo Form::open('order/historyGuest/' . $id_pep, array('class' => 'history-form'));
                        // echo Form::hidden('todo', 'viewhistory');
                        // echo Form::submit('viewhistory', __('order.edit.nameHistoryBtn'), array(
                        //     'class' => 'btn',
                        //     'onclick' => "this.form.elements.todo.value='viewhistory';"
                        // ));
                        // echo Form::close();
                    }
                    break;
                case 'archive_mode':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::hidden('todo', 'forceexit');
                            echo Form::submit('forceexit', __('Зorder.edit.nameForceexitBtn'));
                        }
                        echo Form::hidden('todo', 'newguestorder');
                        echo Form::submit('newguestorder', __('order.edit.nameRepeatOrderBtn'), array(
                            'onclick' => "this.form.elements.todo.value='newguestorder'; return confirm('Вы уверены, что хотите повторить заявку?');"
                        ));
                    } elseif ($user->id_role == 2 || $user->id_role == 3) {
                        echo Form::hidden('todo', 'newguestorder');
                        echo Form::submit('newguestorder', __('Повторить заявку'), array(
                            'onclick' => "this.form.elements.todo.value='newguestorder'; return confirm('Вы уверены, что хотите повторить заявку?');"
                        ));
                    }
                    echo Form::close();
                    echo '<br>';
                    echo Form::open('order/historyGuest/' . $id_pep, array('class' => 'history-form'));
                    echo Form::hidden('todo', 'viewhistory');
                    echo Form::submit('viewhistory', __('order.edit.nameHistoryBtn'), array(
                        'class' => 'btn',
                        'onclick' => "this.form.elements.todo.value='viewhistory';"
                    ));
                    echo Form::close();
                    break;
                case 'buro':
                    if ($user->id_role == 1 || $user->id_role == 2) {
                        echo Form::hidden('todo', 'reissue'); 
                        echo Form::submit('reissue', __('order.edit.nameUpdateBtn'), array(
                            'onclick' => "this.form.elements.todo.value='reissue'"
                        ));
                        if (!empty($cardlist[0]['ID_CARD'])) {
                            echo Form::open();
                            echo Form::hidden('todo', 'forceexit');
                            echo Form::submit('forceexit', __('order.edit.nameForceexitBtn'), array(
                                'onclick' => "this.form.elements.todo.value='forceexit'"
                            ));
                        }
                        echo Form::close();
                        
                        $pd = new PD($id_pep);
                        $signature_file = $pd->checkSignature($id_pep);
                        if ($signature_file === false || !file_exists($signature_file)) {
                            echo Form::open('order/PersonalData/' . $id_pep, array('class'=>'consent'));
                            echo Form::hidden('todo', 'consent');
                            echo Form::submit('consent', __('order.edit.nameConsentBtn'), array(
                                'onclick' => "this.form.elements.todo.value='consent'"
                            ));
                            echo Form::close();
                        } else {
                            echo Form::open('order/view_signature_page/'. $id_pep, array('class'=>'signature'));
                            echo Form::hidden('todo', 'signature');
                            echo Form::submit('signature', __('order.edit.nameViewSignature'), array(
                                'onclick'=> "this.form.elements.todo.value='signature'"
                            ));
                           //echo '<a href="' . URL::site('order/view_signature_page/' . $id_pep) . '" class="btn">' . __('Посмотреть согласие') . '</a>';
                            echo Form::close();
                        }
                        
                    } else {
                        echo Form::hidden('todo', 'update');
                        echo Form::submit('update', __('Обновить гостя'));
                    }
                    echo Form::close();
                    echo Form::open('order/historyGuest/' . $id_pep, array('class' => 'history-form'));
                    echo Form::hidden('todo', 'viewhistory');
                    echo Form::submit('viewhistory', __('order.edit.nameHistoryBtn'), array(
                        'class' => 'btn',
                        'onclick' => "this.form.elements.todo.value='viewhistory';"
                    ));
                    echo Form::close();
                    break;
                default:
                    break;
            }
            if ($mode != 'archive_mode') {
                echo Form::close();
            }
            ?>

            <?php
            echo 'id_pep=' . $guest->id_pep;
            echo '<br>';
            echo 'mode=' . $mode;
            ?>
        </form>
    </div>
</div>