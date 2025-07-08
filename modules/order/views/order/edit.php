<script>
    $(document).ready(function() {
        function formatDateToDMY(date) {
            let day = date.getDate().toString().padStart(2, '0');
            let month = (date.getMonth() + 1).toString().padStart(2, '0');
            let year = date.getFullYear();
            return `${day}.${month}.${year}`;
        }

        function isValidDateDMY(dateStr) {
            const dateRegex = /^\d{2}\.\d{2}\.\d{4}$/;
            if (!dateRegex.test(dateStr)) return false;

            const parts = dateStr.split('.');
            const day = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10) - 1;
            const year = parseInt(parts[2], 10);

            const date = new Date(year, month, day);
            return !isNaN(date.getTime()) && date.getDate() === day && date.getMonth() === month && date.getFullYear() === year;
        }

        function updateEndDate() {
            const startDateStr = $('#carddatestart').val().trim();
            const endDateInput = $('#carddateend');
            const error2 = $('#error2');

            if (!isValidDateDMY(startDateStr)) {
                console.warn('Некорректная дата начала:', startDateStr);
                error2.show();
                return;
            }

            const parts = startDateStr.split('.');
            const startDate = new Date(parts[2], parts[1] - 1, parts[0]);
            startDate.setDate(startDate.getDate() + 1);
            endDateInput.val(formatDateToDMY(startDate));
            error2.hide();
            console.log('Дата окончания обновлена:', endDateInput.val());
        }

        if ($('#carddatestart').length && $('#carddateend').length) {
            $('#carddatestart').on('change', updateEndDate);
            updateEndDate();
        }

        function validate() {
            $('.error').hide();

            // Валидация фамилии
            if ($('#surname').val().trim() === '') {
                $('#error1').show();
                $('#surname').focus();
                return false;
            }

            // Валидация даты документа
            const datedoc = $('#datedoc').val().trim();
            if ($('#numdoc1').val().trim() !== '' && datedoc === '') {
                $('#error31').show();
                $('#datedoc').focus();
                return false;
            }
            if (datedoc !== '' && !isValidDateDMY(datedoc)) {
                $('#error32').show();
                $('#datedoc').focus();
                return false;
            }

            // Валидация дат карты
            const startDateStr = $('#carddatestart').val().trim();
            if (startDateStr === '') {
                $('#error2').show();
                $('#carddatestart').focus();
                return false;
            }
            if (!isValidDateDMY(startDateStr)) {
                $('#error2').show();
                $('#carddatestart').focus();
                return false;
            }

            const endDateStr = $('#carddateend').val().trim();
            if (endDateStr !== '' && !isValidDateDMY(endDateStr)) {
                $('#error3').show();
                $('#carddateend').focus();
                return false;
            }

            if (endDateStr !== '') {
                const startParts = startDateStr.split('.');
                const endParts = endDateStr.split('.');
                const startDate = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                const endDate = new Date(endParts[2], endParts[1] - 1, endParts[0]);

                if (startDate >= endDate) {
                    $('#error3').show();
                    $('#carddateend').focus();
                    return false;
                }
            }

            return true;
        }

        $('form').on('submit', validate);
    });
    </script>

<?php
include Kohana::find_file('views', 'alert');

$guest = new Guest2($id_pep);
$id_card = isset($cardlist[0]['ID_CARD']) ? $cardlist[0]['ID_CARD'] : null;
$key = new Keyk($id_card);
//echo Debug::vars('116', $key);exit;
$mode = isset($mode) ? $mode : 'guest_mode';
$user = new User();
//echo Debug::vars('116', $user);exit;
?>

<?php if ($user -> id_orgctrl == 1){
	switch ($mode){
		case 'buro':

	}
} ?>

<div class="onecolumn">
    <div class="header">
        <span>
            <?php
            switch ($mode) {
                case 'guest_mode':
                    echo $id_pep ? __('guest.title') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : '';
                    break;
                case 'archive_mode':
                    echo $id_pep ? __('guest.titleinArchive') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : '';
                    break;
                case 'newguest':
                    echo '<span>' . __('guest.registration') . '</span>';
                    break;
				// if 
				// case 'buro':
				// 	echo $id_pep ? __('guest.title') . ': ' . iconv('CP1251', 'UTF-8', $guest->name) . ' ' . iconv('CP1251', 'UTF-8', $guest->surname) : '';
				// 	break;
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
                                include Kohana::find_file('views', 'order/block/personal_data');
                                break;
                            case 'guest_mode':
                            case 'archive_mode':
                                include Kohana::find_file('views', 'order/block/personal_data');
                                break;
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
                            case 'archive_mode':
                                // Проверяем, есть ли ID_CARD в cardlist
                                if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                }
                                include Kohana::find_file('views', 'order/block/card_dates');
                                break;
                            case 'buro':
                                //  Проверяем, есть ли ID_CARD в cardlist
                                //if (!empty($cardlist[0]['ID_CARD'])) {
                                    include Kohana::find_file('views', 'order/block/rfid');
                                    echo '<br>';
                                //}
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
								 if ($user->id_pep == 1) {//это может быть только администратор всего СКУД! Ему можно всё!
									echo Form::hidden('todo', 'reissue');
									echo Form::submit('reissue', __('Обновить222'));
									//echo Form::submit('reissue', __('Забрать карту!'));
								} else {
									
									// echo Form::hidden('todo', 'savenew');
									// echo Form::submit('savenew', __('Добавить гостя219'));
								}
							break;
                            case 'archive_mode':
							break;
							case 'buro':
                                 if ($user->id_pep == 1) {
									echo Form::hidden('todo', 'reissue');
									echo Form::submit('reissue', __('Обновить233'));
									echo Form::submit('reissue', __('Забрать карту!'));
									
								} else {
									echo Form::hidden('todo', 'savenew');
									echo Form::submit('savenew', __('Добавить гостя230'));
								}
                            break;
							default:
							break;
                        }
						
           
    echo Form::close();
	
	echo Form::open('order/save');
		switch ($mode) {
          case 'guest_mode':
			if ($user->id_pep == 1) {//это может быть только администратор всего СКУД! Ему можно всё!
				echo Form::hidden('id_pep', $id_pep);
				echo Form::hidden('todo', 'forceexit');
				echo Form::submit('reissue', __('Забрать карту!253'));
			} else {
			
			}				
			break;
            }
	echo Form::close();
	
	
        echo 'id_pep=' . $guest->id_pep;
		echo '<br>';
        echo 'mode=' . $mode;
        echo '<br>';
		echo Debug::vars('249', $user);exit;
        ?>
    </div>

    