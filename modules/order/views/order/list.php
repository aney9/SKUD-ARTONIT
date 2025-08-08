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
        <!-- <div id="search"<?php //if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
            <form action="passoffices/search" method="post">
                <input type="text" class="search noshadow" title="<?php //echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
            </form>
        </div> -->
        
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
        <button style="margin-left: 500px;" onclick="window.location.href='order/edit/0/neworder'">Добавить гостя</button>
        <br>
        <?php endif;?>
        <?php 
        include Kohana::find_file('views', 'paginatoion_controller_template'); 
        if (count($people) > 0) { ?>
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
                        <th>Время визита</th>
                        <th><?php echo __('contacts.action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($people as $pep) { 
                       // echo Debug::vars('62', $people);exit;
                        ?>
                    <tr>
                        <td>
                            <?php 
                            echo $pep['ID_GUESTORDER'];
                            ?>
                        </td>
                        <td>
                            <?php 
                            $surname = isset($pep['GUEST_SURNAME']) ? $pep['GUEST_SURNAME'] : '';
                            $name = isset($pep['GUEST_NAME']) ? $pep['GUEST_NAME'] : '';
                            $patronymic = isset($pep['GUEST_PATRONYMIC']) ? $pep['GUEST_PATRONYMIC'] : '';
                            $fio = trim($surname . ' ' . $name . ' ' . $patronymic);
                            $id_guest = isset($pep['ID_GUEST']) ? $pep['ID_GUEST'] : 0;

                            echo HTML::anchor('order/edit/' . $id_guest . '/' . Session::instance()->get('mode'), 
                                iconv('CP1251', 'UTF-8', $fio));
                            ?>
                        </td>
                        <td>
                            <?php 
                            $card_number = isset($pep['GUEST_CARD_NUMBER']) ? $pep['GUEST_CARD_NUMBER'] : '';
                            $timestart = isset($pep['CREATEDAT']) ? date('d.m.Y H:i', strtotime($pep['CREATEDAT'])) : '';
                            $output = '';
                            if ($card_number) {
                                $output .= iconv('CP1251', 'UTF-8', $card_number);
                            }
                            if ($timestart) {
                                $output .= ($output ? ' ' : '') . $timestart;
                            }
                            echo $output ?: ''; 
                            ?>
                        </td>
                        <td>
                            <?php
                            $p_surname = isset($pep['P_SURNAME']) ? $pep['P_SURNAME'] : '';
                            echo $p_surname ? iconv('CP1251', 'UTF-8', $p_surname) : '';
                            ?>
                        </td>
                        <td>
                            <?php 
                            $org = new Company($pep['ID_ORG']);
                            echo $org->name ? iconv('CP1251', 'UTF-8//IGNORE', $org->name) : 'Не указано';
                            ?>
                        </td>
                        <td>
                            <?php 
                            $buro = new Buro();
                            $guestBuro = $buro->getGuestBuro($pep['ID_GUEST']); // Используем ID гостя
                            
                            if (!empty($guestBuro)) {
                                echo HTML::chars($guestBuro[0]['buro_name']);
                            } else {
                                echo 'Не указано';
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo isset($pep['TIMEORDER']) ? date('d.m.Y H:i', strtotime($pep['TIMEORDER'])) : 'Не указано'; ?>
                        </td>
                        <td><?php echo isset($pep['TIMEPLAN']) ? date('d.m.Y', strtotime($pep['TIMEPLAN'])) : 'Не указано'; ?></td>
                        <td>
                            <?php
                            if (empty($pep['GUEST_CARD_NUMBER'])) {
                                echo HTML::anchor('order/delete/' . $pep['ID_GUESTORDER'], 
                                    HTML::image('images/icon_delete.png', array('alt' => 'delete', 'class' => 'help', 'title' => __('tip.delete2'))),
                                    array('onclick' => 'return confirm(\''.__('guest.confirmdelete2').'\')')
                                );
                            } else {
                                echo '';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <?php echo $pagination; ?>
        <?php } else { ?>
        <div style="margin: 100px 0; text-align: center;">
            <?php echo __('contacts.empty'); ?><br /><br />
        </div>
        <?php } 
        $user = new User();
        echo Debug::vars('155', $user);//exit;
        ?>
    </div>
    
</div>