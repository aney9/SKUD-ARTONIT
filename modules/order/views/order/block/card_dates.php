<fieldset>
    <legend><?php echo __('Дата визита'); ?></legend>
    <table>
        <tr>
            <th align="right" style="padding-right: 10px;">
                <label for="carddatestart"><?php echo __('cards.datestart'); ?></label>
            </th>
            <td>
                <div style="padding-bottom: 10px;">
                    <input type="text" size="12" name="carddatestart" id="carddatestart" value="<?php 
                        if (isset($card) && !empty($card->timestart)) {
                            echo date('d.m.Y', strtotime($card->timestart));
                        } else {
                            echo date('d.m.Y'); // Сегодняшняя дата, если timestart пустой
                        }
                    ?>" style="width: 100px;" />
                    <br />
                    <span class="error" id="error2" style="color: red; display: none;"><?php echo __('card.emptystarttime'); ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <th align="right" style="padding-right: 10px;">
                <label for="carddateend"><?php echo __('cards.dateend'); ?></label>
            </th>
            <td>
                <div style="padding-bottom: 10px;">
                    <input type="text" size="12" name="carddateend" id="carddateend" value="<?php 
                        if (isset($card) && !empty($card->timeend)) {
                            echo date('d.m.Y', strtotime($card->timeend));
                        } elseif (isset($card) && !empty($card->timestart)) {
                            echo date('d.m.Y', strtotime($card->timestart . ' +1 day'));
                        } else {
                            echo date('d.m.Y', strtotime('+1 day'));
                        }
                    ?>" style="width: 100px;" />
                    <br />
                    <span class="error" id="error3" style="color: red; display: none;"><?php echo __('card.wrongendtime'); ?></span>
                </div>
            </td>
        </tr>
    </table>
</fieldset>