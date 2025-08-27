<?php
$key = new Keyk();
$cardlist = $key->getListByPeople($id_pep, 1);

// Проверяем настройки согласия
$require_consent = isset($app_settings['require_consent_for_card']) && $app_settings['require_consent_for_card'];

// Проверяем наличие согласия
$pd = new PD($id_pep);
$signature_file = $pd->checkSignature($id_pep);
$has_consent = ($signature_file !== false && file_exists($signature_file));

// Определяем, должно ли быть поле заблокировано
$should_block_card_field = $require_consent && !$has_consent;

if (count($cardlist) > 0) {
?>
<fieldset>
    <legend><?php echo __('Зарегистрированные RFID'); ?></legend>
    <?php
    $cardList = $guest->getTypeCardList(1);
    foreach ($cardList as $key1 => $value) {
        $card = new Keyk(Arr::get($value, 'ID_CARD'));
        echo $card->id_card . ' (' . $card->id_card_on_DEC . ')<br>';
    }
    echo 'Выдана: ' . date('d.m.Y H:i', strtotime($card->createdat));
    ?>
</fieldset>
<?php } else { ?>
<fieldset>
    <legend><?php echo __('passoffices.regcard'); ?></legend>
    
    <?php if ($should_block_card_field) { ?>
    <div class="consent-warning" style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 10px; margin-bottom: 15px; color: #856404;">
        Для выдачи карты требуется согласие на обработку персональных данных. 
        
    </div>
    <?php } ?>
    
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
                    
                    // Дополнительные атрибуты для блокировки поля
                    $field_attributes = 'id="idcard" name="idcard" title="' . $title . '" style="width: 120px;" pattern="^[0-9A-Fa-f]{10}|^$" minlength="10" maxlength="10" oninvalid="this.setCustomValidity(\'Введите 10-значный HEX-код (0-9, A-F)\')" oninput="this.setCustomValidity(\'\');"';
                    
                    if ($should_block_card_field) {
                        $field_attributes .= ' disabled readonly style="width: 120px; background-color: #f5f5f5; cursor: not-allowed;"';
                        $placeholder_text = 'Требуется согласие';
                    } else {
                        $placeholder_text = '';
                    }
                    
                    $field_value = '';
                    if (isset($card)) {
                        $field_value = Arr::get($card, 'ID_CARD');
                    }
                    ?>
                    
                    <input type="text" 
                           <?php echo $field_attributes; ?>
                           value="<?php echo $field_value; ?>"
                           placeholder="<?php echo $placeholder_text; ?>"
                    />
                    <br />
                    
                    <?php echo Form::hidden('rfidmode', 0); ?>
                    
                    <?php if ($should_block_card_field) { ?>
                    <div class="field-explanation" style="font-size: 12px; color: #666; margin-top: 5px;">
                        Поле заблокировано до получения согласия на обработку персональных данных
                    </div>
                    <?php } ?>
                    
                    <span class="error" id="error11" style="color: red; display: none;"><?php echo __('card.emptyid'); ?></span>
                    <span class="error" id="error12" style="color: red; display: none;"><?php echo __('card.wrongcharacter'); ?></span>
                    <span class="error" id="error13" style="color: red; display: none;"><?php echo __('card.wronglenght'); ?></span>
                </div>
            </td>
        </tr>
    </table>
</fieldset>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($should_block_card_field) { ?>
    // Дополнительная защита через JavaScript
    const cardField = document.getElementById('idcard');
    if (cardField) {
        cardField.addEventListener('focus', function() {
            alert('Для выдачи карты сначала необходимо получить согласие на обработку персональных данных');
            this.blur();
        });
        
        cardField.addEventListener('input', function() {
            this.value = '';
        });
        
        // Блокируем копирование в поле
        cardField.addEventListener('paste', function(e) {
            e.preventDefault();
            alert('Поле заблокировано до получения согласия');
        });
    }
    <?php } ?>
});
</script>

<?php } ?>