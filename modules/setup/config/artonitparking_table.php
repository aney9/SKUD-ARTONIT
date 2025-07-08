<?php defined('SYSPATH') or die('No direct script access.');
 
return array(
	'HL_EVENTCODE'=>array('CREATE TABLE HL_EVENTCODE (
					ID     INTEGER NOT NULL,
					NAME   VARCHAR(255) NOT NULL,
					COLOR  INTEGER)',
					'Update Rdb$Relations set Rdb$Description =
						\'Содержит названия для кодов внутренних событий парковки\'
						where Rdb$Relation_Name=\'HL_EVENTCODE\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
					\'Код события\'
					where Rdb$Relation_Name=\'HL_EVENTCODE\' and Rdb$Field_Name=\'ID\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
					\'Наименование события\'
					where Rdb$Relation_Name=\'HL_EVENTCODE\' and Rdb$Field_Name=\'NAME\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
						\'Цвет фона при выводе на экран
						\'
						where Rdb$Relation_Name=\'HL_EVENTCODE\' and Rdb$Field_Name=\'COLOR\';',
					'ALTER TABLE HL_EVENTCODE ADD CONSTRAINT PK_HL_EVENTCODE PRIMARY KEY (ID);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (1, \'Въезд на парковку\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (2, \'Выезд с парковки\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (46, \'Неизвестный ГРЗ\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (65, \'Проезд запрещен\', 16711680);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (13, \'Получен ГРЗ\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (50, \'Проезд разрешен\', 5898050);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (51, \'Система распознавания ГРЗ работает нормально\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (52, \'Ошибка системы распознавания ГРЗ\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (61, \'Восстановлено подключение к базе данных\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (91, \'ПО запущено\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (92, \'ПО остановлено\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (3, \'Отметка о выезде поставлена оператором\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (4, \'Отметка о въезде поставлена оператором\', NULL);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (81, \'Проезд запрещен. Нет свободных мест в гараже\', 65535);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (5, \'Грз не распознан, въезд открыт оператором\', 65535);',
					'INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (6, \'Повторный въезд на парковку\', 65535);',
					),
	'HL_EVENTS'=>array(
			'CREATE GENERATOR GEN_HL_EVENTS_ID',
			'CREATE TABLE HL_EVENTS (
					ID          INTEGER NOT NULL,
					EVENT_CODE  INTEGER NOT NULL,
					EVENT_TIME  TIMESTAMP DEFAULT \'Now\' NOT NULL,
					IS_ENTER    INTEGER,
					RUBI_CARD   VARCHAR(10),
					PARK_CARD   VARCHAR(10),
					GRZ         VARCHAR(12),
					COMMENT     VARCHAR(255),
					PHOTO       BLOB SUB_TYPE 0 SEGMENT SIZE 80,
					ID_PEP      INTEGER,
					ID_GATE     INTEGER,
					CREATED     TIMESTAMP DEFAULT \'now\'
				)',
				'ALTER TABLE HL_EVENTS ADD CONSTRAINT PK_HL_EVENTS PRIMARY KEY (ID);',
				'ALTER TABLE HL_EVENTS ADD CONSTRAINT FK_HL_EVENTS_1 FOREIGN KEY (EVENT_CODE) REFERENCES HL_EVENTCODE (ID) ON DELETE CASCADE',
				'CREATE TRIGGER HL_EVENTS_BI FOR HL_EVENTS
					ACTIVE BEFORE INSERT POSITION 0
					AS
					BEGIN
					  IF (NEW.ID IS NULL) THEN
						NEW.ID = GEN_ID(GEN_HL_EVENTS_ID,1);
					END',
				'GRANT INSERT ON HL_EVENTS TO PROCEDURE REGISTERPASS_HL_2'
				),
				
		'HL_ORGACCESS'=>array(
			
				'ALTER TABLE ORGANIZATION ADD CONSTRAINT UNQ1_ORGANIZATION UNIQUE (ID_ORG);',
				'CREATE GENERATOR GEN_HL_ORGACCESS_ID;',

				'CREATE TABLE HL_ORGACCESS (
					ID         INTEGER NOT NULL,
					ID_ORG     INTEGER,
					ID_GARAGE  INTEGER,
					IS_ACTIVE  INTEGER DEFAULT 1,
					CREATED    TIMESTAMP DEFAULT \'no\'
				);',
				'Update Rdb$Relation_Fields set Rdb$Description =
				\'Режим работы шлюза
				0 - режим шлюза
				1 - реле 0 ворота
				2 - реле 1 шлагбаум
				3 - и реле 0 ,и реле 1\'
				where Rdb$Relation_Name=\'HL_PARAM\' and Rdb$Field_Name=\'MODE\';',
				'ALTER TABLE HL_ORGACCESS ADD CONSTRAINT PK_HL_ORGACCESS PRIMARY KEY (ID);',
				'ALTER TABLE HL_ORGACCESS ADD CONSTRAINT FK_HL_ORGACCESS_1 FOREIGN KEY (ID_GARAGE) REFERENCES HL_GARAGENAME (ID) ON DELETE CASCADE;',
				'ALTER TABLE HL_ORGACCESS ADD CONSTRAINT FK_HL_ORGACCESS_2 FOREIGN KEY (ID_ORG) REFERENCES ORGANIZATION (ID_ORG) ON DELETE CASCADE;',
				'CREATE TRIGGER HL_ORGACCESS_BI FOR HL_ORGACCESS
						ACTIVE BEFORE INSERT POSITION 0
						as
						begin
						  if (new.id is null) then
							new.id = gen_id(gen_hl_orgaccess_id,1);
						end;',

				'GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_2;',
				'GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_3;'
				),
				
		'HL_GARAGENAME'=>array(
			

				'CREATE GENERATOR GEN_HL_GARAGENAME_ID;',

				'CREATE TABLE HL_GARAGENAME (
					ID         INTEGER NOT NULL,
					NAME       STR_250,
					CREATED    TIMESTAMP DEFAULT \'now\',
					NOT_COUNT  INTEGER DEFAULT 0,
					DIV_CODE   STR_50 NOT NULL
				);',
				
				'Update Rdb$Relations set Rdb$Description =
					\'Перечень гаражей\'
					where Rdb$Relation_Name=\'HL_GARAGENAME\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
					\'Название гаража
					\'
					where Rdb$Relation_Name=\'HL_GARAGENAME\' and Rdb$Field_Name=\'NAME\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
					\'Признак НЕ считать количество свободных мест в гараже.\'
					where Rdb$Relation_Name=\'HL_GARAGENAME\' and Rdb$Field_Name=\'NOT_COUNT\';',

					'Update Rdb$Relation_Fields set Rdb$Description =
					\'Уникальное обозначение для интеграции.\'
					where Rdb$Relation_Name=\'HL_GARAGENAME\' and Rdb$Field_Name=\'DIV_CODE\';',

				'ALTER TABLE HL_GARAGENAME ADD CONSTRAINT UNQ1_HL_GARAGENAME UNIQUE (DIV_CODE);',
				'ALTER TABLE HL_GARAGENAME ADD CONSTRAINT PK_HL_GARAGENAME PRIMARY KEY (ID);',
				'CREATE TRIGGER HL_GARAGENAME_BI FOR HL_GARAGENAME
						ACTIVE BEFORE INSERT POSITION 0
						AS
						BEGIN
						  IF (NEW.ID IS NULL) THEN
							NEW.ID = GEN_ID(GEN_HL_GARAGENAME_ID,1);
						   if (new.div_code is null) then
						   new.div_code=\'garage_\'||new.id  ;
						END',
				),
	
		'HL_GARAGE'=>array(
			'CREATE GENERATOR GEN_HL_GARAGE_ID;',

					'CREATE TABLE HL_GARAGE (
						ID             INTEGER NOT NULL,
						CREATED        TIMESTAMP DEFAULT \'now\',
						ID_PLACE       INTEGER,
						ID_GARAGENAME  INTEGER
					);',
'Update Rdb$Relations set Rdb$Description =
\'Гараж - группировка машиномест.
В одном гараже может быть несколько машиномест.
В один гараж могут заезжать транспортные средства из нескольких организаций.\'
where Rdb$Relation_Name=\'HL_GARAGE\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Метка времени создания.
\'
where Rdb$Relation_Name=\'HL_GARAGE\' and Rdb$Field_Name=\'CREATED\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Номер или иное обозначение машиноместа\'
where Rdb$Relation_Name=\'HL_GARAGE\' and Rdb$Field_Name=\'ID_PLACE\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Ссылка на имя гаража\'
where Rdb$Relation_Name=\'HL_GARAGE\' and Rdb$Field_Name=\'ID_GARAGENAME\';',

					'ALTER TABLE HL_GARAGE ADD CONSTRAINT UNQ1_HL_GARAGE UNIQUE (ID_PLACE);',

					'ALTER TABLE HL_GARAGE ADD CONSTRAINT PK_HL_GARAGE PRIMARY KEY (ID);',

					'ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_1 FOREIGN KEY (ID_GARAGENAME) REFERENCES HL_GARAGENAME (ID) ON DELETE CASCADE;',
					'ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_2 FOREIGN KEY (ID_PLACE) REFERENCES HL_PLACE (ID) ON DELETE CASCADE;',

				
					'CREATE TRIGGER HL_GARAGE_BI FOR HL_GARAGE
					ACTIVE BEFORE INSERT POSITION 0
					AS
					BEGIN
					  IF (NEW.ID IS NULL) THEN
						NEW.ID = GEN_ID(GEN_HL_GARAGE_ID,1);
					END',
					'GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;',
					'GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_3;',
				
				),
	
		'HL_PARAM'=>array(
				'CREATE GENERATOR GEN_HL_PARAM_ID;',

				'CREATE TABLE HL_PARAM (
					ID          INTEGER NOT NULL,
					TABLO_IP    STR_50 /* STR_50 = VARCHAR(50) */,
					TABLO_PORT  INTEGER,
					BOX_IP      STR_50 /* STR_50 = VARCHAR(50) */,
					BOX_PORT    INTEGER,
					ID_GATE     INTEGER,
					ID_CAM      INTEGER,
					ID_DEV      INTEGER,
					MODE        INTEGER DEFAULT  0,
					NAME        STR_250 /* STR_250 = VARCHAR(250) */,
					ID_PARKING  INTEGER,
					IS_ENTER    INTEGER DEFAULT 1,
					CREATED     TIMESTAMP DEFAULT \'now\'
				);',
				'Update Rdb$Relation_Fields set Rdb$Description =
					\'Режим работы шлюза
					0 - режим шлюза
					1 - реле 0 ворота
					2 - реле 1 шлагбаум
					3 - и реле 0 ,и реле 1\'
					where Rdb$Relation_Name=\'HL_PARAM\' and Rdb$Field_Name=\'MODE\';',
					'ALTER TABLE HL_PARAM ADD CONSTRAINT PK_HL_PARAM PRIMARY KEY (ID);',

					'ALTER TABLE HL_PARAM ADD CONSTRAINT FK_HL_PARAM_1 FOREIGN KEY (ID_PARKING) REFERENCES HL_PARKING (ID) ON DELETE SET NULL;',


					'CREATE TRIGGER HL_PARAM_BI FOR HL_PARAM
					ACTIVE BEFORE INSERT POSITION 0
					as
					begin
					  if (new.id is null) then
						new.id = gen_id(gen_hl_param_id,1);
					end',
					'GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_2;',
					'GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_3;',
									
				),
	
		'HL_COUNTERS'=>array(
				'CREATE GENERATOR GEN_HL_COUNTERS_ID;',

					'CREATE TABLE HL_COUNTERS (
						ID             INTEGER NOT NULL,
						PARKINGNUMBER  INTEGER,
						ID_ORG         INTEGER,
						NAME           VARCHAR(128) NOT NULL,
						MAXCOUNT       INTEGER,
						ID_DB          INTEGER DEFAULT 1 NOT NULL,
						"POSITION"     INTEGER DEFAULT 1,
						CREATED        TIMESTAMP DEFAULT \'now\'
					);',
					
					'Update Rdb$Relations set Rdb$Description =
\'Содержит список организаций с количеством выделенных им мест\'
where Rdb$Relation_Name=\'HL_COUNTERS\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Первичный ключ, автоинкремент\'
where Rdb$Relation_Name=\'HL_COUNTERS\' and Rdb$Field_Name=\'ID\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'ИД организации, для которой определен счетчик\'
where Rdb$Relation_Name=\'HL_COUNTERS\' and Rdb$Field_Name=\'ID_ORG\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Наименование счетчика (организации)\'
where Rdb$Relation_Name=\'HL_COUNTERS\' and Rdb$Field_Name=\'NAME\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Максимальное количество занятых мест\'
where Rdb$Relation_Name=\'HL_COUNTERS\' and Rdb$Field_Name=\'MAXCOUNT\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'мусор для поддержания связи\'
where Rdb$Relation_Name=\'HL_COUNTERS\' and Rdb$Field_Name=\'ID_DB\';',


						'ALTER TABLE HL_COUNTERS ADD CONSTRAINT PK_HL_COUNTERS PRIMARY KEY (ID);',
						'ALTER TABLE HL_COUNTERS ADD CONSTRAINT FK_HL_COUNTERS_1 FOREIGN KEY (PARKINGNUMBER) REFERENCES HL_PARKING (ID) ON DELETE CASCADE;',
						'CREATE TRIGGER HL_COUNTERS_BI FOR HL_COUNTERS
					ACTIVE BEFORE INSERT POSITION 0
					as
					begin
					  if (new.id is null) then
						new.id = gen_id(gen_HL_COUNTERS_id,1);
					end',
					'GRANT SELECT ON HL_COUNTERS TO PROCEDURE VALIDATEPASS_HL_PARKING;',


				
				),
	
		'HL_PARKING'=>array(
				'CREATE GENERATOR GEN_HL_PARKING_ID;',

			'CREATE TABLE HL_PARKING (
				ID        INTEGER NOT NULL,
				NAME      STR_50 NOT NULL /* STR_50 = VARCHAR(50) */,
				ENABLED   INTEGER DEFAULT 1 NOT NULL,
				CREATED   TIMESTAMP DEFAULT \'now\',
				MAXCOUNT  INTEGER,
				PARENT    INTEGER
			);',
			
			'Update Rdb$Relations set Rdb$Description =
\'Перечень парковочноых комплексов (parent=0)
и перечень парковок, входяищ в комплекс (parent>0)\'
where Rdb$Relation_Name=\'HL_PARKING\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Количество мест на парковке согласно проектной документации\'
where Rdb$Relation_Name=\'HL_PARKING\' and Rdb$Field_Name=\'MAXCOUNT\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Родительская парковка\'
where Rdb$Relation_Name=\'HL_PARKING\' and Rdb$Field_Name=\'PARENT\';',


			'ALTER TABLE HL_PARKING ADD CONSTRAINT PK_HL_PARKING PRIMARY KEY (ID);',
			'CREATE TRIGGER HL_PARKING_BI FOR HL_PARKING
			ACTIVE BEFORE INSERT POSITION 0
			as
			begin
			  if (new.id is null) then
				new.id = gen_id(gen_hl_parking_id,1);
			end',
			'DESCRIBE FIELD PARENT TABLE HL_PARKING
			\'Р РѕРґРёС‚РµР»СЊСЃРєР°СЏ РїР°СЂРєРѕРІРєР°\';',
							
				),
	
		'HL_PLACE'=>array(
			
			'CREATE GENERATOR GEN_HL_PLACE_ID;',

			'CREATE TABLE HL_PLACE (
				ID           INTEGER NOT NULL,
				PLACENUMBER  INTEGER,
				ID_COUNTERS  INTEGER,
				DESCRIPTION  STR_250 /* STR_250 = VARCHAR(250) */,
				NOTE         STR_250 /* STR_250 = VARCHAR(250) */,
				STATUS       INTEGER,
				NAME         STR_100 /* STR_100 = VARCHAR(100) */,
				ID_PARKING   INTEGER,
				CREATED      TIMESTAMP DEFAULT \'now\'
			);',
			'ALTER TABLE HL_PLACE ADD CONSTRAINT UNQ1_HL_PLACE UNIQUE (PLACENUMBER);',
			'ALTER TABLE HL_PLACE ADD CONSTRAINT PK_HL_PLACE PRIMARY KEY (ID);',
			'ALTER TABLE HL_PLACE ADD CONSTRAINT FK_HL_PLACE_1 FOREIGN KEY (ID_PARKING) REFERENCES HL_PARKING (ID);',
			'CREATE INDEX HL_PLACE_IDX1 ON HL_PLACE (ID_PARKING);',
			'CREATE TRIGGER HL_PLACE_BI FOR HL_PLACE
				ACTIVE BEFORE INSERT POSITION 0
				AS
				BEGIN
				  IF (NEW.ID IS NULL) THEN
					NEW.ID = GEN_ID(GEN_HL_PLACE_ID,1);
				END',
			'GRANT SELECT ON HL_PLACE TO PROCEDURE VALIDATEPASS_HL_PARKING_3;',
				
				),
	'HL_INSIDE'=>array(
			'CREATE TABLE HL_INSIDE (
					ENTERTIME  TIMESTAMP DEFAULT \'now\' NOT NULL,
					ID_CARD    VARCHAR(32) NOT NULL,
					COUNTERID  INTEGER NOT NULL
				);',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Порядковый номер машиноместа.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'PLACENUMBER\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Совпадает с ID_PARKING. Поле оставлено для совместимости на период отладки.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'ID_COUNTERS\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Описание мишиноместа.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'DESCRIPTION\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Заметки по машиноместу.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'NOTE\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Статус.
0 - рабочее состояние
1 - использование запрещено.
Возможны другие варианты.
Пока не используется.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'STATUS\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Название машиноместа. Название может отличаться от номера. Например, В-21.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'NAME\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Ссылка на парковку, к которой "привязано" машиноместо.\'
where Rdb$Relation_Name=\'HL_PLACE\' and Rdb$Field_Name=\'ID_PARKING\';',


				'ALTER TABLE HL_INSIDE ADD CONSTRAINT PK_HL_INSIDE PRIMARY KEY (ID_CARD);',
				'GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING;',
				'GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;',

				
				),
	
	'HL_MESSAGES'=>array(
			'CREATE TABLE HL_MESSAGES (
				ID           INTEGER,
				EVENTCODE    INTEGER,
				TEXT         VARCHAR(250),
				PARAM        VARCHAR(250),
				"TIMESTAMP"  TIMESTAMP DEFAULT \'now\',
				SMALNAME     VARCHAR(10)
			);',

'Update Rdb$Relations set Rdb$Description =
\'Таблица HL_MESSAGES предназначена для хранения сообщений, выводимых на средства отображения информации (табло или иные приборы)\'
where Rdb$Relation_Name=\'HL_MESSAGES\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Ссылка на код события, связанного с этим сообщением.\'
where Rdb$Relation_Name=\'HL_MESSAGES\' and Rdb$Field_Name=\'EVENTCODE\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Текст, выводимый на табло\'
where Rdb$Relation_Name=\'HL_MESSAGES\' and Rdb$Field_Name=\'TEXT\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Параметры текста в строковом виде (начальные координакты, цвет и т.п.)\'
where Rdb$Relation_Name=\'HL_MESSAGES\' and Rdb$Field_Name=\'PARAM\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Метка времени создания или последного обновления\'
where Rdb$Relation_Name=\'HL_MESSAGES\' and Rdb$Field_Name=\'TIMESTAMP\';',

'Update Rdb$Relation_Fields set Rdb$Description =
\'Краткое название сообщения. Например alarm, time и т.п.\'
where Rdb$Relation_Name=\'HL_MESSAGES\' and Rdb$Field_Name=\'SMALNAME\';',

//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, 46, \'Проезд запрещен. Неизвестная карта.\', \'{"dx":"0","dy":"8","messColor":"4","messScroll":"1"}\', NULL, NULL);',
//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, 50, \'Добро пожаловать!\', \'{"dx":"0","dy":"8","messColor":"2","messScroll":"1"}\', \'2023-04-07 12:31:47\', NULL);',
//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, 65, \'Проезд запрещен. Нет прав.\', \'{"dx":"0","dy":"8","messColor":"1","messScroll":"1"}\', \'2023-04-07 12:32:18\', NULL);',
//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, 81, \'Проезд запрещен. Нет мест.\', \'{"dx":"0","dy":"8","messColor":"6","messScroll":"1"}\', \'2023-04-07 12:32:46\', NULL);',
//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, NULL, \'Паркинг\', \'{"dx":"31","dy":"32","messColor":"33","messScroll":"34"}\', \'2023-04-07 12:33:54\', \'text1\');',
//'INSERT INTO HL_MESSAGES (ID, EVENTCODE, TEXT, PARAM, "TIMESTAMP", SMALNAME) VALUES (NULL, NULL, \'Шмитовский, 39\', \'{"dx":"41","dy":"42","messColor":"43","messScroll":"44"}\', \'2023-04-07 12:34:14\', \'text2\');',
			
				),
	'HL_SETTING'=>array(
			'CREATE TABLE HL_SETTING (
				NAME         STR_50 NOT NULL /* STR_50 = VARCHAR(50) */,
				VALUE_STR    STR_100 /* STR_100 = VARCHAR(100) */,
				VALUE_INT    INTEGER,
				"DESCRIBE"   STR_250 /* STR_250 = VARCHAR(250) */,
				"TIMESTAMP"  TIMESTAMP DEFAULT \'now\',
				SMALLNAME    STR_50 /* STR_50 = VARCHAR(50) */
			);',
			
			'Update Rdb$Relations set Rdb$Description =
\'Эта таблица предназначена для хранения различных вспомогательных параметров.\'
where Rdb$Relation_Name=\'HL_SETTING\';',


			'ALTER TABLE HL_SETTING ADD CONSTRAINT PK_HL_SETTING PRIMARY KEY (NAME);',
			'GRANT SELECT ON HL_SETTING TO PROCEDURE VALIDATEPASS_HL_PARKING_2;',

				
				),
		
	'HL_UPDATE_GARAGE_NAME'=>array(
		
		'CREATE PROCEDURE HL_UPDATE_GARAGE_NAME(PLACENUM INTEGER,
		NAME_GARAGE VARCHAR(250))
		AS
		BEGIN EXIT; END',
		
		'ALTER PROCEDURE HL_UPDATE_GARAGE_NAME(PLACENUM INTEGER,
			NAME_GARAGE VARCHAR(250))
			 AS
			declare variable IDGARAGENAME integer;
			/*
			  процедура обновляет название гаража, используя номер машиноместа и новое название
			*/

			begin
			  select hlg.id_garagename from hl_garage hlg where hlg.id_place=:placenum into :IDGARAGENAME;
			  update hl_garagename hlgn set hlgn.name=:name_garage
			  where hlgn.id=:IDGARAGENAME;
			  suspend;
			end',
	),
	
	'HL_UPDATE_GARAGE_NAME_'=>array(
		
		'CREATE PROCEDURE HL_UPDATE_GARAGE_NAME (
    PLACENUM INTEGER,
    NAME_GARAGE VARCHAR(250))
AS
DECLARE VARIABLE igg INTEGER;
begin
  select hlg.id_garagename from hl_garage hlg where hlg.id_place=:placenum into igg;
  update hl_garagename hlgn set hlgn.name= :name_garage
  where hlgn.id = igg;
  suspend;
end',
			
			
			'GRANT SELECT ON HL_GARAGE TO PROCEDURE HL_UPDATE_GARAGE_NAME;',

			'GRANT SELECT,UPDATE ON HL_GARAGENAME TO PROCEDURE HL_UPDATE_GARAGE_NAME;',

			'GRANT EXECUTE ON PROCEDURE HL_UPDATE_GARAGE_NAME TO SYSDBA;',
		),
	
	
	'REGISTERPASS_HL_2'=>array(
	
			'CREATE PROCEDURE REGISTERPASS_HL_2 (
    ID_DEV INTEGER,
    ID_CARD VARCHAR(12),
    GRZ VARCHAR(12))
RETURNS (
    ID_PEP INTEGER,
    RC INTEGER)
AS
DECLARE VARIABLE ID_ORG INTEGER;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = \'\') then grz = null;
  rc=-1;
  -- определяю ID_ORG для ess2
  select p.id_org from card c
  join people p on p.id_pep=c.id_pep
  where c.id_card=:id_card into :id_org    ;

  --выполняю валидацию ГРЗ
  execute procedure validatepass_hl_parking_2 :id_dev, :id_card, :grz returning_values :RC, :id_pep;

 -- фиксирую обращене к валидации  Отключен 9.08.2023
 --   INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
 --   VALUES (13, :id_card, :id_dev);




-- если ГРЗ уже на территории, то при въезде формирую события повторного въезда и удаляю ГРЗ из таблицы inside
  if(((rc=81) or (rc=50))  and (exists(select * from hl_inside hli where hli.id_card=:id_card)) and (exists(select * from hl_param hlp where hlp.id_dev=:id_dev and hlp.is_enter=1))) then
    begin
        INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, ID_PEP)  VALUES (6, :id_card, :id_dev, :id_pep);
        delete from hl_inside hli2 where hli2.id_card=:id_card;
        rc=50;
    end
-- фиксирую результат валидации
 INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, ID_PEP)
    VALUES (:rc, :id_card, :id_dev, :id_pep);


     -- запишем результат в ЖС
    --1 ID_DB integer,
    --2 ID_EVENTTYPE integer,
    --3 ID_CTRL integer,
    --4 ID_READER integer,
    --5 NOTE varchar(100),
    --6 "TIME" timestamp,
    --7 ID_VIDEO integer,
    --8 ID_USER integer,
    --9 ESS1 integer,
    --10 ESS2 integer

INSERT INTO EVENTS (ID_DB, ID_EVENTTYPE, ID_DEV,  DATETIME, ID_CARD, NOTE,  ID_PEP, ESS1, ESS2)
VALUES (1, :rc, :id_dev,  \'now\', :id_card, :id_card, 1, :id_pep, :id_org);

  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end',

'GRANT SELECT ON CARD TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT SELECT ON PEOPLE TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT SELECT,DELETE ON HL_INSIDE TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT SELECT ON HL_PARAM TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT INSERT ON HL_EVENTS TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT INSERT ON EVENTS TO PROCEDURE REGISTERPASS_HL_2;',

'GRANT EXECUTE ON PROCEDURE REGISTERPASS_HL_2 TO SYSDBA;',
		),
	
	
	
				
	
);