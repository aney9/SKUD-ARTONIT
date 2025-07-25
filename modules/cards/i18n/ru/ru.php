<?php
return array
(
	'cards.title'					=> 'Список идентификаторов',
	'cards.titleRFID'					=> 'Список идентификаторов RFID',
	'cards.titleGRZ'					=> 'Список идентификаторов GRZ',
	'cards.empty'					=> 'Список карт пуст',
	'cards.code'					=> 'Код идентификатора',
	'cards.datestart'				=> 'Дата начала действия',
	'cards.dateend'					=> 'Дата окончания действия',
	'cards.useenddate'				=> 'Учитывать срок действия карты',
	'cards.active'					=> 'Карта активна',
	'cards.details'					=> 'Данные идентификатора',
	'cards.loadhistory'				=> 'Загрузка карты в точки прохода',
	'cards.nohistory'				=> 'Записи не найдены',
	'cards.access'					=> 'Категория доступа',
	'cards.state'					=> 'Статус карты',
	'cards.reload'					=> 'Загрузить карту повторно',
	'cards.block'					=> 'Заблокировать карту',
	'cards.unblock'					=> 'Разблокировать карту',
	'cards.delete'					=> 'Удалить карту',
	'cards.create'					=> 'Зарегистрировать RFID',
	'cards.create_grz'				=> 'Зарегистрировать ГРЗ',
	'cards.none'					=> 'Карты не найдены',
	'cards.holder'					=> 'Владелец',
	'cards.company'					=> 'Организация',
	'cards.action'					=> 'Действия',
	'cards.confirmdelete'			=> 'Вы действительно хотите удалить карту?',
	'cards.saved'					=> 'Карта создана',
	'cards.updated'					=> 'Карта обновлена',
	'cards.deleted'					=> 'Карта удалена',
	'cards.deletedOk'				=> 'Идентификатор :id_card удалена успешно',
	'cards.deletedErr'				=> 'Ошибка при удалении идентификатора :id_card. :mess.',
	'card.emptyid'					=> 'Введите код карты',
	'card.emptystarttime'			=> 'Укажите дату начала действия карты',
	'card.wrongendtime'				=> 'Дата окончания не может быть раньше даты начала',
	'card.wrongcharactergrz'		=> 'ГРЗ может содержать только цифры и буквы латинские алфавита и не более 9 символов',
	'card.wrongcharacter'			=> 'Ошибка! Код RFID может содержать только цифры и латинские символы A-F и содержать '.constants::RFID_MAX_LENGTH().' символов',
	'card.wrongcharacter1'			=> 'RFID может содержать только цифры не более '.constants::RFID_MAX_LENGTH().' знаков',
	'card.wronglenght'				=> 'Номер карты должен содержать '.constants::RFID_MAX_LENGTH().' символов',
	'cards.grz'						=> 'Данные ГРЗ',
	'cards.delete_grz'				=> 'Удалить ГРЗ',
	'card.common'					=> 'Свойства',
	'card.titleEdit'				=> 'Свойства идентификатора :id_card',
	'card.titleLoad'				=> 'Таблица загрузки идентификатора :id_card (:id_card_on_DEC) в точки прохода',
	'card.titleHistory'				=> 'История идентификатора :id_card',
	'card.updateOk'					=> 'Данные карты обновлены успешно',
	'card.updateErr'				=> 'Ошибка! Данные карты не обновлены',
	'card.reloadOk'					=> 'Команда на повторную загрузку идентификатора :id_card выполнена успешно',
	'card.load'						=> 'Таблица загрузки идентификаторов',
	'card.history'					=> 'История',
	'cards.id_cardtype'				=> 'Тип идентификатора',
	'cards.note'					=> 'Служебные записи об идентификаторе',
	'card.errDataForSearchRFID'		=>'Неправильный формат идентификатора в запросе :mess',
	'cards.status'		=>'Свойства',
);