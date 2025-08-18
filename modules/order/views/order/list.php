<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<script type="text/javascript">
    $(function() {        
        $("#tablesorter").tablesorter({ headers: { 3:{sorter: false}}, widgets: ['zebra']});
    });    
</script>

<?php 
include Kohana::find_file('views', 'alert');
if ($alert) { ?>
<div class="alert_success">
    <p>
        <img class="mid_align" alt="success" src="images/icon_accept.png" />
        <?php echo $alert; ?>
    </p>
</div>
<?php } ?>

<div class="onecolumn">
    <div class="header">
        <?php 
        switch (Session::instance()->get('mode')) {
            case 'guest_mode':
                echo '<span>'.__('Список заявок'). ' '. __('(всего заявок: :count)', array(':count' => count($people))).'</span>';
                break;
            case 'archive_mode':
                echo '<span>'.__('guests.titleinArchive'). ' '. __('guest.countArchive', array(':count' => count($people))).'</span>';
                break;
            default:
                echo '<span>'.__('guests.unknow').'</span>';
                break;
        }
        ?>
    </div>
    <br class="clear"/>
    <div class="content">
        <?php if ($user->id_role == 1 || $user->id_role == 2): ?>
        <?php 
        echo Form::open('order/edit/0/neworder', array('style' => 'margin-left: 500px;'));
        echo Form::hidden('todo', 'neworder');
        echo Form::submit('neworder', __('Добавить гостя'), array(
            'style' => 'margin-left: 0;',
            'onclick' => "this.form.elements.todo.value='neworder';"
        ));
        echo Form::close();
        ?>
        <br>
        <?php endif;?>
        
        <?php 
        include Kohana::find_file('views', 'paginatoion_controller_template'); 
        if (count($people) > 0) { 
            if (Session::instance()->get('mode') == 'archive_mode') { 
                // ТАБЛИЦА ДЛЯ АРХИВА (ФИО + Номер документа)
        ?>
                <form id="form_data" name="form_data" action="" method="post">
                    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter">
                        <thead>
                            <tr>
                                <th><?php echo __('ФИО гостя'); ?></th>
                                <th><?php echo __('Номер документа')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($people as $pep) { 
                                $surname = isset($pep['GUEST_SURNAME']) ? $pep['GUEST_SURNAME'] : '';
                                $name = isset($pep['GUEST_NAME']) ? $pep['GUEST_NAME'] : '';
                                $patronymic = isset($pep['GUEST_PATRONYMIC']) ? $pep['GUEST_PATRONYMIC'] : '';
                                $fio = trim("$surname $name $patronymic");
                                $id_guest = isset($pep['ID_GUEST']) ? $pep['ID_GUEST'] : 0;
                                $numdoc = isset($pep['NUMDOC']) ? $pep['NUMDOC'] : '';
                                $doc_display = '-';
                                if ($numdoc && $numdoc !== '#@') {
                                    Log::instance()->add(Log::DEBUG, 'Processing NUMDOC for id_guest=' . $id_guest . ': ' . $numdoc);
                                    $parts = explode('#', $numdoc);
                                    $series = !empty($parts[0]) ? $parts[0] : '-';
                                    $number = '-';
                                    $id_doc = 0;
                                    $doc_type = 'Неизвестный тип';

                                    if (isset($parts[1])) {
                                        $number_parts = explode('@', $parts[1]);
                                        $number = !empty($number_parts[0]) ? $number_parts[0] : '-';
                                        $id_doc = isset($number_parts[1]) ? (int)$number_parts[1] : 0;
                                        $docs = Documents::getDoc();
                                        $doc_type = ($id_doc && isset($docs[$id_doc])) 
                                            ? $docs[$id_doc]['docname'] 
                                            : 'Неизвестный тип';
                                    }

                                    // Проверяем, есть ли хотя бы одна непустая часть
                                    if ($series !== '-' || $number !== '-' || $doc_type !== 'Неизвестный тип') {
                                        Log::instance()->add(Log::DEBUG, 'NUMDOC parts: series=' . $series . ', number=' . $number . ', id_doc=' . $id_doc . ', doc_type=' . $doc_type);
                                        $doc_display = 'Серия: ' . HTML::chars(iconv('CP1251', 'UTF-8', $series)) . 
                                                       ' Номер: ' . HTML::chars(iconv('CP1251', 'UTF-8', $number)) . 
                                                       ' Тип: ' . HTML::chars($doc_type);
                                    } else {
                                        Log::instance()->add(Log::DEBUG, 'No valid parts in NUMDOC for id_guest=' . $id_guest . ': ' . $numdoc);
                                        $doc_display = '-';
                                    }
                                } else {
                                    Log::instance()->add(Log::DEBUG, 'Empty or invalid NUMDOC for id_guest=' . $id_guest . ': ' . $numdoc);
                                    $doc_display = '-';
                                }
                            ?>
                            <tr>
                                <td>
                                    <?php echo HTML::anchor(
                                        'order/edit/' . $id_guest . '/archive_mode',
                                        iconv('CP1251', 'UTF-8', $fio)
                                    ); ?>
                                </td>
                                <td>
                                    <?php echo HTML::chars($doc_display); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
        <?php 
            } else { 
                // ТАБЛИЦА ДЛЯ ГОСТЕВОГО РЕЖИМА (ПОЛНАЯ ВЕРСИЯ)
        ?>
                <form id="form_data" name="form_data" action="" method="post">
                    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter">
                        <thead>
                            <tr>
                                <th><?php echo __('ID') ?></th>
                                <th><?php echo __('ФИО гостя'); ?></th>
                                <th><?php echo __('Номер карты')?></th>
                                <th><?php echo __('Фамилия заказчика')?></th>
                                <th><?php echo __('contacts.company'); ?></th>
                                <th><?php echo __('Бюро пропусков');?></th>
                                <th><?php echo __('Время заказа'); ?></th>
                                <th><?php echo __('Запланированное время визита'); ?></th>
                                <th><?php echo __('contacts.action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($people as $pep) { ?>
                            <tr>
                                <td><?php echo $pep['ID_GUESTORDER']; ?></td>
                                <td>
                                    <?php 
                                    $surname = isset($pep['GUEST_SURNAME']) ? $pep['GUEST_SURNAME'] : '';
                                    $name = isset($pep['GUEST_NAME']) ? $pep['GUEST_NAME'] : '';
                                    $patronymic = isset($pep['GUEST_PATRONYMIC']) ? $pep['GUEST_PATRONYMIC'] : '';
                                    $fio = trim("$surname $name $patronymic");
                                    $id_guest = isset($pep['ID_GUEST']) ? $pep['ID_GUEST'] : 0;
                                    echo HTML::anchor(
                                        'order/edit/' . $id_guest . '/guest_mode',
                                        iconv('CP1251', 'UTF-8', $fio)
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $card_number = isset($pep['GUEST_CARD_NUMBER']) ? $pep['GUEST_CARD_NUMBER'] : '';
                                    $timestart = isset($pep['CREATEDAT']) ? date('d.m.Y H:i', strtotime($pep['CREATEDAT'])) : '';
                                    $output = '';
                                    if ($card_number) $output .= iconv('CP1251', 'UTF-8', $card_number);
                                    if ($timestart) $output .= ($output ? ' ' : '') . $timestart;
                                    echo $output ?: ''; 
                                    ?>
                                </td>
                                <td><?php echo isset($pep['P_SURNAME']) ? iconv('CP1251', 'UTF-8', $pep['P_SURNAME']) : ''; ?></td>
                                <td>
                                    <?php 
                                    $org = new Company($pep['ID_ORG']);
                                    echo $org->name ? iconv('CP1251', 'UTF-8//IGNORE', $org->name) : 'Не указано';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $buro = new Buro();
                                    $guestBuro = $buro->getGuestBuro($pep['ID_GUEST']); 
                                    echo !empty($guestBuro) ? HTML::chars($guestBuro[0]['buro_name']) : 'Не указано';
                                    ?>
                                </td>
                                <td><?php echo isset($pep['TIMEORDER']) ? date('d.m.Y H:i', strtotime($pep['TIMEORDER'])) : 'Не указано'; ?></td>
                                <td><?php echo isset($pep['TIMEPLAN']) ? date('d.m.Y', strtotime($pep['TIMEPLAN'])) : 'Не указано'; ?></td>
                                <td>
                                    <?php
                                    if (empty($pep['GUEST_CARD_NUMBER'])) {
                                        echo HTML::anchor(
                                            'order/delete/' . $pep['ID_GUESTORDER'],
                                            HTML::image('images/icon_delete.png', array('alt' => 'delete', 'class' => 'help', 'title' => __('tip.delete2'))),
                                            array('onclick' => 'return confirm(\''.__('guest.confirmdelete2').'\')')
                                        );
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
        <?php 
            } 
            echo $pagination; 
        } else { 
        ?>
            <div style="margin: 100px 0; text-align: center;">
                <?php echo __('contacts.empty'); ?><br /><br />
            </div>
        <?php } ?>
    </div>
</div>