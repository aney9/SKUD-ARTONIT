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
        <div style="margin-left: 500px;">
            <?php 
            echo Form::open('order/edit/0/neworder', array('style' => 'display: inline-block; margin-right: 10px;'));
            echo Form::hidden('todo', 'neworder');
            echo Form::submit('neworder', __('Добавить гостя'), array(
                'style' => 'margin-left: 0;',
                'onclick' => "this.form.elements.todo.value='neworder';"
            ));
            echo Form::close();
            ?>
            
            <?php 
            // Кнопка экспорта
            $current_mode = Session::instance()->get('mode');
            $export_url = 'order/export';
            if ($current_mode) {
                $export_url .= '/' . $current_mode;
            }
            echo Form::open($export_url, array('style' => 'display: inline-block;'));
            echo Form::submit('export', __('Экспорт в CSV'), array(
                'style' => 'margin-left: 0;'
            ));
            echo Form::close();
            ?>
            
            <?php if (Session::instance()->get('mode') == 'guest_mode'): ?>
            <?php 
            // Кнопка "Показать все"
            $show_all_param = isset($_GET['show_all']) && $_GET['show_all'] ? 0 : 1;
            $button_text = $show_all_param ? 'Показать все' : 'Показать только актуальные';
            $current_url = Request::current()->uri() . '?show_all=' . $show_all_param;
            echo Form::open($current_url, array('style' => 'display: inline-block;'));
            echo Form::submit('show_all', __($button_text), array(
                'style' => 'margin-left: 10px;'
            ));
            echo Form::close();
            ?>
            <?php endif; ?>
        </div>
        <br>
        <?php endif;?>
        
        <?php 
        include Kohana::find_file('views', 'paginatoion_controller_template'); 
        if (count($people) > 0) { 
            if (Session::instance()->get('mode') == 'archive_mode') { 
        ?>
                <form id="form_data" name="form_data" action="" method="post">
                    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter">
                        <thead>
                            <tr>
                                <th><?php echo __('ФИО гостя'); ?></th>
                                <th><?php echo __('Номер документа')?></th>
                                <th><?php echo __('Персональные данные')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $pd = new PD(0); // Создаем экземпляр класса PD
                            foreach ($people as $pep) { 
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

                                // Проверяем наличие персональных данных
                                $has_signature = $pd->checkSignature($id_guest) !== false ? true : false;
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
                                <td>
                                    <?php if ($has_signature) { ?>
                                        <?php echo HTML::anchor(
                                            'order/view_signature_page/' . $id_guest,
                                            __('Посмотреть согласие')
                                        ); ?>
                                    <?php } else { ?>
                                        <?php echo HTML::chars('-'); ?>
                                    <?php } ?>
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
                            <?php 
                            $today = strtotime(date('Y-m-d'));
                            foreach ($people as $pep) { 
                                $createdat = isset($pep['CREATEDAT']) ? strtotime(date('Y-m-d', strtotime($pep['CREATEDAT']))) : 0;
                                
                                // Log values for debugging
                                Log::instance()->add(Log::DEBUG, 'Guest ID: ' . $pep['ID_GUEST'] . 
                                    ', CREATEDAT: ' . (isset($pep['CREATEDAT']) ? $pep['CREATEDAT'] : 'null') . 
                                    ', GUEST_CARD_NUMBER: ' . (isset($pep['GUEST_CARD_NUMBER']) ? $pep['GUEST_CARD_NUMBER'] : 'null'));

                                // Check if card is expired (CREATEDAT before today)
                                $is_expired_card = !empty($pep['GUEST_CARD_NUMBER']) && $createdat < $today;
                                $cell_style = $is_expired_card ? 'style="color: red !important;"' : '';
                            ?>
                            <tr>
                                <td <?php echo $cell_style; ?>><?php echo $pep['ID_GUESTORDER']; ?></td>
                                <td <?php echo $cell_style; ?>>
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
                                <td <?php echo $cell_style; ?>>
                                    <?php 
                                    $card_number = isset($pep['GUEST_CARD_NUMBER']) ? $pep['GUEST_CARD_NUMBER'] : '';
                                    $timestart = isset($pep['CREATEDAT']) ? date('d.m.Y H:i', strtotime($pep['CREATEDAT'])) : '';
                                    $output = '';
                                    if ($card_number) $output .= iconv('CP1251', 'UTF-8', $card_number);
                                    if ($timestart) $output .= ($output ? ' ' : '') . $timestart;
                                    echo $output ?: ''; 
                                    ?>
                                </td>
                                <td <?php echo $cell_style; ?>><?php echo isset($pep['P_SURNAME']) ? iconv('CP1251', 'UTF-8', $pep['P_SURNAME']) : ''; ?></td>
                                <td <?php echo $cell_style; ?>>
                                    <?php 
                                    $org = new Company($pep['ID_ORG']);
                                    echo $org->name ? iconv('CP1251', 'UTF-8//IGNORE', $org->name) : 'Не указано';
                                    ?>
                                </td>
                                <td <?php echo $cell_style; ?>>
                                    <?php 
                                    $buro = new Buro();
                                    $guestBuro = $buro->getGuestBuro($pep['ID_GUEST']); 
                                    echo !empty($guestBuro) ? HTML::chars($guestBuro[0]['buro_name']) : 'Не указано';
                                    ?>
                                </td>
                                <td <?php echo $cell_style; ?>><?php echo isset($pep['TIMEORDER']) ? date('d.m.Y H:i', strtotime($pep['TIMEORDER'])) : 'Не указано'; ?></td>
                                <td <?php echo $cell_style; ?>><?php echo isset($pep['TIMEPLAN']) ? date('d.m.Y', strtotime($pep['TIMEPLAN'])) : 'Не указано'; ?></td>
                                <td <?php echo $cell_style; ?>>
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
<?php
    echo 'mode=' . $mode;
?>