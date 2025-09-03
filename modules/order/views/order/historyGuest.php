<?php defined('SYSPATH') or die('No direct script access.'); ?>

<script type="text/javascript">
    $(function() {        
        $("#tablesorter").tablesorter({ headers: { 2:{sorter: false}}, widgets: ['zebra']});
    });    
</script>

<div class="onecolumn">
    <div class="header">
        <span><?php echo __('История событий для гостя: ') . HTML::chars($guest->surname) . ' ' . HTML::chars($guest->name)
        . ' ' . HTML::chars($guest->patronymic); ?></span>
    </div>
    <br class="clear"/>
    <div class="content">
        <?php 
        include Kohana::find_file('views', 'paginatoion_controller_template'); 
        if (count($events) > 0) { ?>
            <form id="form_data" name="form_data" action="" method="post">
                <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo __('Название события'); ?></th>
                            <th><?php echo __('Дата и время'); ?></th>
                            <th><?php echo __('Примечание'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event) { ?>
                            <tr>
                                <td><?php echo HTML::chars(iconv('CP1251', 'UTF-8', $event['NAME'])) . ' (' . HTML::chars($event['ID_EVENTTYPE']) . ')'; ?></td>
                                <td><?php echo HTML::chars($event['DATETIME']); ?></td>
                                <td>
                                    <?php
                                    $note = '';
                                    if (!empty($event['ID_CARD'])) {
                                        $note .= HTML::chars($event['ID_CARD']);
                                    }
                                    if (!empty($event['ID_DEV']) && !empty($event['DEVICE_NAME'])) {
                                        $note .= ($note ? '; ' : '') . HTML::chars(iconv('CP1251', 'UTF-8', $event['DEVICE_NAME']));
                                    }
                                    if (empty($note) && !empty($event['NOTE2'])) {
                                        //$note = iconv('CP1251', 'UTF-8', $event['NOTE2']);
                                        $note = iconv('CP1251', 'UTF-8', $event['NOTE2']);
                                    }
                                    echo $note;
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
                <?php echo __('События не найдены'); ?><br /><br />
            </div>
        <?php } ?>
        <br />
        <?php 
            echo Form::open('order/edit/' .$id_pep . '/archive_mode')
        ?>
        <?php
echo Form::open('order/edit/' . $id_pep . '/archive_mode', array('class' => 'edit-form'));
echo Form::hidden('todo', 'editguest');
echo Form::submit('editguest', __('Назад к редактированию'), array(
    'class' => 'btn',
));
echo Form::close();
?>
    </div>
</div>