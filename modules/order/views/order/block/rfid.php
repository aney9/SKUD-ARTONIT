<?php
$key = new Keyk();
$cardlist = $key->getListByPeople($id_pep, 1);
//echo Debug::vars('4', $cardlist);exit;
if (count($cardlist) > 0) {
?>
<fieldset>
    <legend><?php echo __('Зарегистрированные RFID'); ?></legend>
    <?php
    $cardList = $guest->getTypeCardList(1);
    foreach ($cardList as $key1 => $value) {
        $card = new Keyk(Arr::get($value, 'ID_CARD'));
        //echo Debug::vars('13', $card);exit;
        echo $card->id_card . ' (' . $card->id_card_on_DEC . ')<br>';
        
        
    }
    // echo '<p>' . Form::radio('rfidmode', 0, $card->status == 0, array('disabled' => 'disabled')) . __('RFID') . '</p>';
    // echo '<p>' . Form::radio('rfidmode', 2, $card->status == 2, array('disabled' => 'disabled')) . __('RFID Mifare Encrytped') . '</p>';
    // echo '<p>' . Form::radio('rfidmode', 3, $card->status == 3, array('disabled' => 'disabled')) . __('RFID LR UHF') . '</p>';
    echo 'Выдана: ' . date('d.m.Y H:i', strtotime($card->createdat));
    ?>
</fieldset>
<?php } else { ?>
<fieldset>
    <legend><?php echo __('passoffices.regcard'); ?></legend>
    <table>
        <tr>
            <th align="right" style="padding-right: 10px;">
                <label for="idcard"><?php echo __('contact.cardid'); ?></label>
            </th>
            <td>
                <div style="padding-bottom: 10px;">
                    <?php
                    
                    $minlength = constants::RFID_MIN_LENGTH;
                    $maxlength = constants::RFID_MAX_LENGTH;
                    switch (Kohana::$config->load('system')->get('regFormatRfid')) {
                        case 0:
                            switch (Kohana::$config->load('system')->get('baseFormatRfid', 0)) {
                                case 0:
                                    $comment = __('contact.wait_hex8_number');
                                    $patternValid = constants::HEX8_VALID;
                                    //echo Debug::vars('35', $patternValid);exit;
                                    $title = constants::RFID_MIN_LENGTH . '-' . constants::RFID_MAX_LENGTH . ' символов';
                                    break;
                                case 1:
                                    $comment = __('contact.wait_001A_number');
                                    $patternValid = constants::HEX001A_VALID;
                                    $title = constants::MAX_VALUE_001A . ' символов и буквы алфавита строго A-F';
                                    $minlength = constants::MAX_VALUE_001A;
                                    $maxlength = constants::MAX_VALUE_001A;
                                    break;
                                default:
                                    $comment = __('contact.wait_not_point_number');
                                    break;
                            }
                            break;
                        case 2:
                            $comment = __('contact.wait_dec10_number');
                            $patternValid = constants::DEC10_VALID;
                            $title = 'номер идентификатора';
                            $minlength = constants::RFID_DEC_MIN_LENGTH;
                            $maxlength = constants::RFID_DEC_MAX_LENGTH;
                            break;
                        default:
                            $comment = __('contact.check_reg_device_setting');
                            break;
                    }
                    ?>
                    <input type="text" 
       id="idcard" 
       name="idcard" 
       value="<?php if (isset($card)) echo Arr::get($card, 'ID_CARD'); ?>" 
       title="<?php echo $title; ?>" 
       style="width: 120px;"
       pattern="^[0-9A-Fa-f]{10}$"
       minlength="10"
       maxlength="10"
       required
       oninvalid="this.setCustomValidity('Введите 10-значный HEX-код (0-9, A-F)')"
       oninput="this.setCustomValidity('')"
/>
                    <br />
                    
                    <!-- <p><?php echo Form::radio('rfidmode', 0, true) . __('RFID'); ?></p> -->
                    <?php echo Form::hidden('rfidmode', 0); ?>
                    <!-- <p><?php echo Form::radio('rfidmode', 2) . __('RFID Mifare Encrytped'); ?></p>
                    <p><?php echo Form::radio('rfidmode', 3) . __('RFID LR UHF'); ?></p> -->
                    <span class="error" id="error11" style="color: red; display: none;"><?php echo __('card.emptyid'); ?></span>
                    <span class="error" id="error12" style="color: red; display: none;"><?php echo __('card.wrongcharacter'); ?></span>
                    <span class="error" id="error13" style="color: red; display: none;"><?php echo __('card.wronglenght'); ?></span>
                </div>
            </td>
        </tr>
    </table>
</fieldset>
<?php } ?>