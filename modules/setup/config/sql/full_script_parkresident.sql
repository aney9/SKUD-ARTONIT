/* Server Version: WI-V6.3.6.5026 Firebird 1.5.  ODS Version: 10.1. */
SET NAMES WIN1251;

SET SQL DIALECT 3;

CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

SET AUTODDL ON;

/* Drop Constraints... */
CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

ALTER TABLE OVER_SERVER DROP CONSTRAINT PK_OVER_SERVER;


CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

ALTER TABLE PERIMETER DROP CONSTRAINT PK_PERIMETER;


ALTER TABLE PERIMETER_GATE DROP CONSTRAINT UNQ1_PERIMETER_GATE;


CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

ALTER TABLE PERIMETER_INSIDE DROP CONSTRAINT PK_PERIMETER_INSIDE;


ALTER TABLE SERVER DROP CONSTRAINT UNQ1_SERVER;



/* Declare UDF */
DECLARE EXTERNAL FUNCTION ABS
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_abs' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ACOS
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_acos' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ASCII_CHAR
INTEGER
RETURNS CSTRING(1) FREE_IT
ENTRY_POINT 'IB_UDF_ascii_char' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ASCII_VAL
CHAR(1)
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_ascii_val' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ASIN
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_asin' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ATAN
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_atan' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION ATAN2
DOUBLE PRECISION,DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_atan2' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION BIN_AND
INTEGER,INTEGER
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_bin_and' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION BIN_OR
INTEGER,INTEGER
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_bin_or' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION BIN_XOR
INTEGER,INTEGER
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_bin_xor' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION CEILING
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_ceiling' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION COS
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_cos' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION COSH
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_cosh' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION COT
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_cot' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION DIV
INTEGER,INTEGER
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_div' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION FLOOR
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_floor' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LN
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_ln' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LOG
DOUBLE PRECISION,DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_log' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LOG10
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_log10' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LOWER
CSTRING(255)
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_lower' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LPAD
CSTRING(255),INTEGER,CSTRING(1)
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_lpad' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION LTRIM
CSTRING(255)
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_ltrim' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION MOD
INTEGER,INTEGER
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_mod' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION PI

RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_pi' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION RAND

RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_rand' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION RPAD
CSTRING(255),INTEGER,CSTRING(1)
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_rpad' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION RTRIM
CSTRING(255)
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_rtrim' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SIGN
DOUBLE PRECISION
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_sign' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SIN
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_sin' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SINH
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_sinh' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SQRT
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_sqrt' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION STRLEN
CSTRING(32767)
RETURNS INTEGER BY VALUE
ENTRY_POINT 'IB_UDF_strlen' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SUBST2
CSTRING(255),SMALLINT,SMALLINT
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_substr' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION SUBSTRLEN
CSTRING(255),SMALLINT,SMALLINT
RETURNS CSTRING(255) FREE_IT
ENTRY_POINT 'IB_UDF_substrlen' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION TAN
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_tan' MODULE_NAME 'ib_udf';

DECLARE EXTERNAL FUNCTION TANH
DOUBLE PRECISION
RETURNS DOUBLE PRECISION BY VALUE
ENTRY_POINT 'IB_UDF_tanh' MODULE_NAME 'ib_udf';


/* Empty trigger body before drop... */
SET TERM ^ ;

ALTER TRIGGER EVENTS_ANALIT
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER EVENTS_PEOPLE
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER EVENTS_PERIMETER
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER OVER_SERVER_BI
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER PEOPLE_AU
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER PERIMETER_BI
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER SS_ACCESSUSER_AI_ADD_AN
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER SS_ACCESSUSER_BD0
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Empty trigger body before drop... */
ALTER TRIGGER TEST_BI
 AS Declare variable I integer;
BEGIN I = 0; END
^

/* Alter Procedure (Before Drop)... */
/* AssignEmptyBody proc */
ALTER PROCEDURE A_EVENT_INSERT(EVENTCODE INTEGER,
ID_DEV INTEGER,
ID_PEP INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE ADD_CARD_FOR_VNII(TN VARCHAR(10),
CARDD VARCHAR(32))
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE CARD_GETPARAM4DEV_MUL(IDDB INTEGER,
ID_DEV INTEGER,
ID_PEP INTEGER)
 RETURNS(TIMEZONES INTEGER,
STATUS INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE CARDIDX_CHECK_DEV(ID_DEV INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE DEVICE_GETFREEIDCTRL(IDDB INTEGER)
 RETURNS(DEVIDX INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE EVENT_ANALIT_MULTI(ID_DB INTEGER,
ID_DEV INTEGER,
ID_EVENTTYPE INTEGER,
IDENTIFICATOR VARCHAR(32))
 RETURNS(ANALIT_CODE INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE EVENTS_INSERT_50_CARD_DEV(CARD VARCHAR(12),
DEVICETIME TIMESTAMP,
ID_DEV INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE GETAUTHMODE(ID_PEP INTEGER)
 RETURNS(AUTHMODE INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE SET_ORG_FOR_VNII(IDDPEP INTEGER,
IDORG INTEGER)
 AS
 BEGIN EXIT; END
^

/* AssignEmptyBody proc */
ALTER PROCEDURE VALIDATEPASS_APB(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
 BEGIN EXIT; END
^


/* Drop Procedure... */
SET TERM ; ^

DROP PROCEDURE A_EVENT_INSERT;

DROP PROCEDURE ADD_CARD_FOR_VNII;

DROP PROCEDURE ANALYT_ACTION;

/* AssignEmptyBody proc */
SET TERM ^ ;

ALTER PROCEDURE CARDINDEV_GETLIST(IDDB INTEGER)
 RETURNS(ID_DEV INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
ID_CARD VARCHAR(32),
ID_PEP INTEGER,
DEVIDX INTEGER,
OPERATION INTEGER,
TIMEZONES INTEGER,
STATUS INTEGER,
ID_CARDINDEV INTEGER,
ATTEMPTS INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

DROP PROCEDURE CARD_GETPARAM4DEV_MUL;

DROP PROCEDURE CARDIDX_CHECK_DEV;

DROP PROCEDURE DEVICE_GETFREEIDCTRL;

DROP PROCEDURE EVENT_ANALIT_MULTI;

DROP PROCEDURE EVENTS_INSERT_50_CARD_DEV;

DROP PROCEDURE GETAUTHMODE;

DROP PROCEDURE SET_ORG_FOR_VNII;

/* AssignEmptyBody proc */
SET TERM ^ ;

ALTER PROCEDURE REGISTERPASS(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(RC INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

DROP PROCEDURE VALIDATEPASS_APB;


/* Drop Trigger... */
DROP TRIGGER EVENTS_ANALIT;

DROP TRIGGER EVENTS_PEOPLE;

DROP TRIGGER EVENTS_PERIMETER;

DROP TRIGGER OVER_SERVER_BI;

DROP TRIGGER PEOPLE_AU;

DROP TRIGGER PERIMETER_BI;

DROP TRIGGER SS_ACCESSUSER_AI_ADD_AN;

DROP TRIGGER SS_ACCESSUSER_BD0;

DROP TRIGGER TEST_BI;


/* Drop Fields on Table... */
DROP VIEW CARD_ACCESS;

ALTER TABLE CARD DROP CREATEDAT;

/* Empty EVENTS_GETLISTFROMID for drop CARDTYPE(SMALLNAME) */
/* AssignEmptyBody proc */
SET TERM ^ ;

ALTER PROCEDURE EVENTS_GETLISTFROMID(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
ID_EVENTFROM INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

ALTER TABLE CARDTYPE DROP SMALLNAME;

ALTER TABLE PARKING_INSIDE DROP EXIT_TIME;

ALTER TABLE PARKING_INSIDE DROP ENTER_ID_DEV;

ALTER TABLE PARKING_INSIDE DROP EXIT_ID_DEV;

ALTER TABLE PEOPLE DROP AUTHMODE;


CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

/* Drop Tables... */
DROP TABLE OVER_DEVICELIST;

DROP TABLE OVER_SERVER;

DROP TABLE PERIMETER;

DROP TABLE PERIMETER_GATE;

DROP TABLE PERIMETER_INSIDE;

DROP TABLE TEST;


Update Rdb$Relations set Rdb$Description =
''
where Rdb$Relation_Name='PARKING_INSIDE';

CREATE DOMAIN IBCXXX INTEGER;

UPDATE RDB$RELATION_FIELDS SET RDB$DEFAULT_SOURCE=
(SELECT RDB$DEFAULT_SOURCE FROM RDB$FIELDS where RDB$FIELD_NAME='IBCXXX'),
RDB$DEFAULT_VALUE=
(SELECT RDB$DEFAULT_VALUE FROM RDB$FIELDS where RDB$FIELD_NAME='IBCXXX')
WHERE RDB$FIELD_NAME='ANALIT' AND RDB$RELATION_NAME='EVENTS';

DROP DOMAIN IBCXXX;

UPDATE RDB$FIELDS SET RDB$DEFAULT_VALUE = NULL,
RDB$DEFAULT_SOURCE = '' WHERE RDB$FIELD_NAME =
(SELECT RDB$FIELD_SOURCE FROM RDB$RELATION_FIELDS
WHERE RDB$FIELD_NAME = 'ANALIT' AND RDB$RELATION_NAME = 'EVENTS');

/* Alter Field (Null / Not Null)... */
UPDATE RDB$RELATION_FIELDS SET RDB$NULL_FLAG = 1 WHERE RDB$FIELD_NAME='ENTER_TIME' AND RDB$RELATION_NAME='PARKING_INSIDE';

CREATE DOMAIN IBCXXX TIMESTAMP DEFAULT 'now' NOT NULL;

UPDATE RDB$RELATION_FIELDS SET RDB$DEFAULT_SOURCE=
(SELECT RDB$DEFAULT_SOURCE FROM RDB$FIELDS where RDB$FIELD_NAME='IBCXXX'),
RDB$DEFAULT_VALUE=
(SELECT RDB$DEFAULT_VALUE FROM RDB$FIELDS where RDB$FIELD_NAME='IBCXXX')
WHERE RDB$FIELD_NAME='ENTER_TIME' AND RDB$RELATION_NAME='PARKING_INSIDE';

DROP DOMAIN IBCXXX;

UPDATE RDB$FIELDS SET RDB$DEFAULT_VALUE = NULL,
RDB$DEFAULT_SOURCE = '' WHERE RDB$FIELD_NAME =
(SELECT RDB$FIELD_SOURCE FROM RDB$RELATION_FIELDS
WHERE RDB$FIELD_NAME = 'ENTER_TIME' AND RDB$RELATION_NAME = 'PARKING_INSIDE');

/* String-length truncation not supported:
ALTER TABLE SETTING ALTER COLUMN VALUE_STR TYPE STR_100 */

ALTER TABLE SERVERTYPE ADD SNAME STR_50;

Update Rdb$Relation_Fields set Rdb$Description =
'Алиас (краткое название) Транспортного сервера
'
where Rdb$Relation_Name='SERVERTYPE' and Rdb$Field_Name='SNAME';

/* Create Table... */
CREATE TABLE BAS_DEVICE(ID INTEGER,
ID_DEV INTEGER,
IP INTEGER,
PORT INTEGER,
CONNECTIONSTRING STR_250,
LAST_EVENT INTEGER,
CREATED TIMESTAMP DEFAULT 'now',
UPDATED TIMESTAMP,
DEV_VERSION STR_250);


Update Rdb$Relation_Fields set Rdb$Description =
'Порядковй номер записи'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='ID';

Update Rdb$Relation_Fields set Rdb$Description =
'Ссылка на ID контроллера СКУД.'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='ID_DEV';

Update Rdb$Relation_Fields set Rdb$Description =
'IP адрес контроллера'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='IP';

Update Rdb$Relation_Fields set Rdb$Description =
'Номер сетевого порта для подключения к устройству.'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='PORT';

Update Rdb$Relation_Fields set Rdb$Description =
'Дополнительные параметры подключения к устройству'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='CONNECTIONSTRING';

Update Rdb$Relation_Fields set Rdb$Description =
'Номер последнего полученного события.'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='LAST_EVENT';

Update Rdb$Relation_Fields set Rdb$Description =
'Метка времени создания записи о контроллере.'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='CREATED';

Update Rdb$Relation_Fields set Rdb$Description =
'Метка времени последнего изменения данных о контроллере.'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='UPDATED';

Update Rdb$Relation_Fields set Rdb$Description =
'Версия устройства'
where Rdb$Relation_Name='BAS_DEVICE' and Rdb$Field_Name='DEV_VERSION';

CREATE TABLE BAS_PARAM(ID_DEV INTEGER,
PARAM STR_250,
INTVALUE INTEGER,
STRVALUE STR_250,
INSERTTIME TIMESTAMP DEFAULT 'now');


CREATE TABLE CONFIG(GROUP_NAME STR_12 NOT NULL,
CONFIG_KEY STR_12 NOT NULL,
CONFIG_VALUE STR_250);


CREATE TABLE HL_COUNTERS(ID INTEGER NOT NULL,
PARKINGNUMBER INTEGER,
ID_ORG INTEGER,
NAME VARCHAR(128) NOT NULL,
MAXCOUNT INTEGER,
ID_DB INTEGER DEFAULT 1 NOT NULL,
"POSITION" INTEGER DEFAULT 1,
CREATED TIMESTAMP DEFAULT 'now');


Update Rdb$Relations set Rdb$Description =
'Содержит список организаций с количеством выделенных им мест'
where Rdb$Relation_Name='HL_COUNTERS';

Update Rdb$Relation_Fields set Rdb$Description =
'Первичный ключ, автоинкремент'
where Rdb$Relation_Name='HL_COUNTERS' and Rdb$Field_Name='ID';

Update Rdb$Relation_Fields set Rdb$Description =
'ИД организации, для которой определен счетчик'
where Rdb$Relation_Name='HL_COUNTERS' and Rdb$Field_Name='ID_ORG';

Update Rdb$Relation_Fields set Rdb$Description =
'Наименование счетчика (организации)'
where Rdb$Relation_Name='HL_COUNTERS' and Rdb$Field_Name='NAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Максимальное количество занятых мест'
where Rdb$Relation_Name='HL_COUNTERS' and Rdb$Field_Name='MAXCOUNT';

Update Rdb$Relation_Fields set Rdb$Description =
'мусор для поддержания связи'
where Rdb$Relation_Name='HL_COUNTERS' and Rdb$Field_Name='ID_DB';

CREATE TABLE HL_EVENTCODE(ID INTEGER NOT NULL,
NAME VARCHAR(255) NOT NULL,
COLOR INTEGER);


Update Rdb$Relations set Rdb$Description =
'Содержит названия для кодов внутренних событий парковки'
where Rdb$Relation_Name='HL_EVENTCODE';

Update Rdb$Relation_Fields set Rdb$Description =
'Код события'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='ID';

Update Rdb$Relation_Fields set Rdb$Description =
'Наименование события'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='NAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Цвет фона при выводе на экран
'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='COLOR';

CREATE TABLE HL_EVENTS(ID INTEGER NOT NULL,
EVENT_CODE INTEGER NOT NULL,
EVENT_TIME TIMESTAMP DEFAULT 'Now' NOT NULL,
IS_ENTER INTEGER,
RUBI_CARD VARCHAR(10),
PARK_CARD VARCHAR(10),
GRZ VARCHAR(12),
COMMENT VARCHAR(255),
PHOTO BLOB SEGMENT SIZE 80,
ID_PEP INTEGER,
ID_GATE INTEGER,
CREATED TIMESTAMP DEFAULT 'now');


CREATE TABLE HL_GARAGE(ID INTEGER NOT NULL,
CREATED TIMESTAMP DEFAULT 'now',
ID_PLACE INTEGER,
ID_GARAGENAME SMALLINT);


Update Rdb$Relations set Rdb$Description =
'Гараж - группировка машиномест.
В одном гараже может быть несколько машиномест.
В один гараж могут заезжать транспортные средства из нескольких организаций.'
where Rdb$Relation_Name='HL_GARAGE';

Update Rdb$Relation_Fields set Rdb$Description =
'Метка времени создания.
'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='CREATED';

Update Rdb$Relation_Fields set Rdb$Description =
'Номер или иное обозначение машиноместа'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_PLACE';

Update Rdb$Relation_Fields set Rdb$Description =
'Ссылка на имя гаража'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_GARAGENAME';

CREATE TABLE HL_GARAGENAME(ID INTEGER NOT NULL,
NAME STR_250,
CREATED TIMESTAMP DEFAULT 'now',
NOT_COUNT INTEGER DEFAULT 0,
DIV_CODE STR_50 NOT NULL);


Update Rdb$Relations set Rdb$Description =
'Перечень гаражей'
where Rdb$Relation_Name='HL_GARAGENAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Название гаража
'
where Rdb$Relation_Name='HL_GARAGENAME' and Rdb$Field_Name='NAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Признак НЕ считать количество свободных мест в гараже.'
where Rdb$Relation_Name='HL_GARAGENAME' and Rdb$Field_Name='NOT_COUNT';

Update Rdb$Relation_Fields set Rdb$Description =
'Уникальное обозначение для интеграции.'
where Rdb$Relation_Name='HL_GARAGENAME' and Rdb$Field_Name='DIV_CODE';

CREATE TABLE HL_INSIDE(ENTERTIME TIMESTAMP DEFAULT 'now' NOT NULL,
ID_CARD VARCHAR(32) NOT NULL,
COUNTERID INTEGER NOT NULL);


CREATE TABLE HL_MESSAGES(ID INTEGER,
EVENTCODE INTEGER,
TEXT VARCHAR(250),
PARAM VARCHAR(250),
"TIMESTAMP" TIMESTAMP DEFAULT 'now',
SMALNAME VARCHAR(10));


Update Rdb$Relations set Rdb$Description =
'Таблица HL_MESSAGES предназначена для хранения сообщений, выводимых на средства отображения информации (табло или иные приборы)'
where Rdb$Relation_Name='HL_MESSAGES';

Update Rdb$Relation_Fields set Rdb$Description =
'Ссылка на код события, связанного с этим сообщением.'
where Rdb$Relation_Name='HL_MESSAGES' and Rdb$Field_Name='EVENTCODE';

Update Rdb$Relation_Fields set Rdb$Description =
'Текст, выводимый на табло'
where Rdb$Relation_Name='HL_MESSAGES' and Rdb$Field_Name='TEXT';

Update Rdb$Relation_Fields set Rdb$Description =
'Параметры текста в строковом виде (начальные координакты, цвет и т.п.)'
where Rdb$Relation_Name='HL_MESSAGES' and Rdb$Field_Name='PARAM';

Update Rdb$Relation_Fields set Rdb$Description =
'Метка времени создания или последного обновления'
where Rdb$Relation_Name='HL_MESSAGES' and Rdb$Field_Name='TIMESTAMP';

Update Rdb$Relation_Fields set Rdb$Description =
'Краткое название сообщения. Например alarm, time и т.п.'
where Rdb$Relation_Name='HL_MESSAGES' and Rdb$Field_Name='SMALNAME';

CREATE TABLE HL_ORGACCESS(ID INTEGER NOT NULL,
ID_ORG INTEGER,
ID_GARAGE INTEGER,
IS_ACTIVE INTEGER DEFAULT 1,
CREATED TIMESTAMP DEFAULT 'now');


Update Rdb$Relation_Fields set Rdb$Description =
'На какие парковки может заезжать представитель организации'
where Rdb$Relation_Name='HL_ORGACCESS' and Rdb$Field_Name='ID_GARAGE';

CREATE TABLE HL_PARAM(ID INTEGER NOT NULL,
TABLO_IP STR_50,
TABLO_PORT INTEGER,
BOX_IP STR_50,
BOX_PORT INTEGER,
ID_GATE SMALLINT,
ID_CAM SMALLINT,
ID_DEV SMALLINT,
MODE SMALLINT DEFAULT 0,
NAME STR_250,
ID_PARKING SMALLINT,
IS_ENTER SMALLINT DEFAULT 1,
CREATED TIMESTAMP DEFAULT 'now');


Update Rdb$Relation_Fields set Rdb$Description =
'Режим работы шлюза
0 - режим шлюза
1 - реле 0 ворота
2 - реле 1 шлагбаум
3 - и реле 0 ,и реле 1'
where Rdb$Relation_Name='HL_PARAM' and Rdb$Field_Name='MODE';

CREATE TABLE HL_PARKING(ID INTEGER NOT NULL,
NAME STR_50 NOT NULL,
ENABLED INTEGER DEFAULT 1 NOT NULL,
CREATED TIMESTAMP DEFAULT 'now',
MAXCOUNT SMALLINT,
PARENT SMALLINT);


Update Rdb$Relations set Rdb$Description =
'Перечень парковочноых комплексов (parent=0)
и перечень парковок, входяищ в комплекс (parent>0)'
where Rdb$Relation_Name='HL_PARKING';

Update Rdb$Relation_Fields set Rdb$Description =
'Количество мест на парковке согласно проектной документации'
where Rdb$Relation_Name='HL_PARKING' and Rdb$Field_Name='MAXCOUNT';

Update Rdb$Relation_Fields set Rdb$Description =
'Родительская парковка'
where Rdb$Relation_Name='HL_PARKING' and Rdb$Field_Name='PARENT';

CREATE TABLE HL_PLACE(ID INTEGER NOT NULL,
PLACENUMBER INTEGER,
ID_COUNTERS INTEGER,
DESCRIPTION STR_250,
NOTE STR_250,
STATUS INTEGER,
NAME STR_100,
ID_PARKING SMALLINT,
CREATED TIMESTAMP DEFAULT 'now');


Update Rdb$Relation_Fields set Rdb$Description =
'Порядковый номер машиноместа.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='PLACENUMBER';

Update Rdb$Relation_Fields set Rdb$Description =
'Совпадает с ID_PARKING. Поле оставлено для совместимости на период отладки.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='ID_COUNTERS';

Update Rdb$Relation_Fields set Rdb$Description =
'Описание мишиноместа.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='DESCRIPTION';

Update Rdb$Relation_Fields set Rdb$Description =
'Заметки по машиноместу.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='NOTE';

Update Rdb$Relation_Fields set Rdb$Description =
'Статус.
0 - рабочее состояние
1 - использование запрещено.
Возможны другие варианты.
Пока не используется.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='STATUS';

Update Rdb$Relation_Fields set Rdb$Description =
'Название машиноместа. Название может отличаться от номера. Например, В-21.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='NAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Ссылка на парковку, к которой "привязано" машиноместо.'
where Rdb$Relation_Name='HL_PLACE' and Rdb$Field_Name='ID_PARKING';

CREATE TABLE HL_PLACEGROUP(ID INTEGER NOT NULL);


Update Rdb$Relations set Rdb$Description =
'Группировка машиномест с группы для органзиации множественного доступа.'
where Rdb$Relation_Name='HL_PLACEGROUP';

CREATE TABLE HL_SETTING(NAME STR_50 NOT NULL,
VALUE_STR STR_100,
VALUE_INT INTEGER,
"DESCRIBE" STR_250,
"TIMESTAMP" TIMESTAMP DEFAULT 'now',
SMALLNAME STR_50);


Update Rdb$Relations set Rdb$Description =
'Эта таблица предназначена для хранения различных вспомогательных параметров.'
where Rdb$Relation_Name='HL_SETTING';

CREATE TABLE KP_EVENTS(ID INTEGER NOT NULL,
EVENT_CODE INTEGER NOT NULL,
EVENT_TIME TIMESTAMP DEFAULT 'Now' NOT NULL,
IS_ENTER INTEGER,
RUBI_CARD VARCHAR(10),
PARK_CARD VARCHAR(10),
GRZ VARCHAR(12),
COMMENT VARCHAR(255),
PHOTO BLOB SEGMENT SIZE 80,
ID_PEP INTEGER);


Update Rdb$Relations set Rdb$Description =
'Внутренний журнал событий парковки'
where Rdb$Relation_Name='KP_EVENTS';

Update Rdb$Relation_Fields set Rdb$Description =
'Первичный ключ'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='ID';

Update Rdb$Relation_Fields set Rdb$Description =
'Код события'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='EVENT_CODE';

Update Rdb$Relation_Fields set Rdb$Description =
'1 - въезд, 0 - выезд, null - без привязки'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='IS_ENTER';

Update Rdb$Relation_Fields set Rdb$Description =
'Код карты Рубитех'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='RUBI_CARD';

Update Rdb$Relation_Fields set Rdb$Description =
'Код парковочной карты'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='PARK_CARD';

Update Rdb$Relation_Fields set Rdb$Description =
'ГРЗ'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='GRZ';

Update Rdb$Relation_Fields set Rdb$Description =
'Примечание'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='COMMENT';

Update Rdb$Relation_Fields set Rdb$Description =
'Фотография'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='PHOTO';

Update Rdb$Relation_Fields set Rdb$Description =
'ID сотрудника'
where Rdb$Relation_Name='KP_EVENTS' and Rdb$Field_Name='ID_PEP';


/* Create Procedure... */
SET TERM ^ ;

CREATE PROCEDURE ADD_ORG_PLACE(DIV_CODE VARCHAR(50),
PLACENUM INTEGER)
 AS
 BEGIN EXIT; END
^

CREATE PROCEDURE ADD_PARKING_PLACE_LINK(PLACE INTEGER,
DIV VARCHAR(12),
HOUSE INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedure_Parameters set Rdb$Description =
'Номер парковочного места'
where Rdb$Procedure_Name='ADD_PARKING_PLACE_LINK' and Rdb$Parameter_Name='PLACE';

Update Rdb$Procedure_Parameters set Rdb$Description =
'Уровень парковки'
where Rdb$Procedure_Name='ADD_PARKING_PLACE_LINK' and Rdb$Parameter_Name='DIV';

Update Rdb$Procedure_Parameters set Rdb$Description =
'квартира, с которой есть связь'
where Rdb$Procedure_Name='ADD_PARKING_PLACE_LINK' and Rdb$Parameter_Name='HOUSE';

SET TERM ^ ;

CREATE PROCEDURE ADD_PEOPLE_WHITH_CARD_AND_TAB(CARD VARCHAR(32),
TABNUM VARCHAR(50),
NOTE VARCHAR(50),
CARDTYPE INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
' Insery people into PEOPLE and insert card for it people.'
where Rdb$Procedure_Name='ADD_PEOPLE_WHITH_CARD_AND_TAB';

SET TERM ^ ;

CREATE PROCEDURE ADD_PEOPLE_WHITH_DIV_ORG(ID_DB INTEGER,
SURNAME VARCHAR(50),
NAME VARCHAR(50),
PATRONYMIC VARCHAR(50),
NOTE VARCHAR(250),
DIV_ORG VARCHAR(50),
TABNUM VARCHAR(50))
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Вставка ФИО в организацию, для которой указан DIVCODE.'
where Rdb$Procedure_Name='ADD_PEOPLE_WHITH_DIV_ORG';

SET TERM ^ ;

CREATE PROCEDURE HL_UPDATE_GARAGE_NAME(PLACENUM INTEGER,
NAME_GARAGE VARCHAR(250))
 AS
 BEGIN EXIT; END
^

CREATE PROCEDURE INTTOHEX(INPUTNUMBER BIGINT)
 RETURNS(OUTPUTNUMBER VARCHAR(8))
 AS
 BEGIN EXIT; END
^

CREATE PROCEDURE REGISTERPASS_HL(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Проверка допустимости прохода + запись события в журнал'
where Rdb$Procedure_Name='REGISTERPASS_HL';

SET TERM ^ ;

CREATE PROCEDURE REGISTERPASS_HL_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Проверка допустимости прохода + запись события в журнал'
where Rdb$Procedure_Name='REGISTERPASS_HL_2';

SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Производит проверку наличия свободных мест при въезде на парковку'
where Rdb$Procedure_Name='VALIDATEPASS_HL_PARKING';

SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Производит проверку наличия свободных мест при въезде на парковку'
where Rdb$Procedure_Name='VALIDATEPASS_HL_PARKING_2';

SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING_3(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
 BEGIN EXIT; END
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Производит проверку наличия свободных мест при въезде на парковку'
where Rdb$Procedure_Name='VALIDATEPASS_HL_PARKING_3';


/* Create Views... */
/* Create view: CARD_ACCESS (ViwData.CreateDependDef) */
CREATE VIEW CARD_ACCESS(
ID_PEP,
ID_CARD,
ID_DEV,
ID_ACCESSNAME)
 AS 
/*
select distinct c.id_pep, c.id_card, ac.id_dev, ac.id_accessname
from Card c
    join access ac on (c.id_accessname = ac.id_accessname and c.id_db = ac.id_db)
where
    (c."ACTIVE" <> 0) and ( c.timeend is null or c.timeend > 'NOW' )
*/

    select  ssa.id_pep, c.id_card,ac.id_dev, ssa.id_accessname from ss_accessuser ssa
    join card c on ssa.id_pep=c.id_pep
    join access ac on ssa.id_accessname=ac.id_accessname
    where
    (c."ACTIVE" <> 0) and ( c.timeend is null or c.timeend > 'NOW' )  and (c.id_cardtype=1)
;


/* Create Index... */
CREATE INDEX HL_PLACE_IDX1 ON HL_PLACE(ID_PARKING);


/* Create Generator... */
CREATE GENERATOR GEN_HL_COUNTERS_ID;

CREATE GENERATOR GEN_HL_DEVICELIST_ID;

CREATE GENERATOR GEN_HL_EVENTS_ID;

CREATE GENERATOR GEN_HL_GARAGENAME_ID;

CREATE GENERATOR GEN_HL_ORGACCESS_ID;

CREATE GENERATOR GEN_HL_PARAM_ID;

CREATE GENERATOR GEN_HL_PARKING_GATE_ID;

CREATE GENERATOR GEN_HL_PARKING_ID;

CREATE GENERATOR GEN_HL_PLACE_ID;

CREATE GENERATOR GEN_HL_PLACEGROUP_ID;

CREATE GENERATOR GEN_SERVERTYPE_ID;


/* Create Primary Key... */
ALTER TABLE CONFIG ADD PRIMARY KEY (GROUP_NAME,CONFIG_KEY);

ALTER TABLE HL_COUNTERS ADD CONSTRAINT PK_HL_COUNTERS PRIMARY KEY (ID);

ALTER TABLE HL_EVENTCODE ADD CONSTRAINT PK_HL_EVENTCODE PRIMARY KEY (ID);

ALTER TABLE HL_EVENTS ADD CONSTRAINT PK_HL_EVENTS PRIMARY KEY (ID);

ALTER TABLE HL_GARAGE ADD CONSTRAINT PK_HL_GARAGE PRIMARY KEY (ID);

ALTER TABLE HL_GARAGENAME ADD CONSTRAINT PK_HL_GARAGENAME PRIMARY KEY (ID);

ALTER TABLE HL_INSIDE ADD CONSTRAINT PK_HL_INSIDE PRIMARY KEY (ID_CARD);

ALTER TABLE HL_ORGACCESS ADD CONSTRAINT PK_HL_ORGACCESS PRIMARY KEY (ID);

ALTER TABLE HL_PARAM ADD CONSTRAINT PK_HL_PARAM PRIMARY KEY (ID);

ALTER TABLE HL_PARKING ADD CONSTRAINT PK_HL_PARKING PRIMARY KEY (ID);

ALTER TABLE HL_PLACE ADD CONSTRAINT PK_HL_PLACE PRIMARY KEY (ID);

ALTER TABLE HL_SETTING ADD CONSTRAINT PK_HL_SETTING PRIMARY KEY (NAME);

/* Create Unique... */
ALTER TABLE DEVICE ADD CONSTRAINT UNQ1_DEVICE UNIQUE (ID_DEV);

ALTER TABLE HL_GARAGE ADD CONSTRAINT UNQ1_HL_GARAGE UNIQUE (ID_PLACE);

ALTER TABLE HL_GARAGENAME ADD CONSTRAINT UNQ1_HL_GARAGENAME UNIQUE (DIV_CODE);

ALTER TABLE HL_PLACE ADD CONSTRAINT UNQ1_HL_PLACE UNIQUE (PLACENUMBER);

ALTER TABLE SERVERTYPE ADD CONSTRAINT UNQ1_SERVERTYPE UNIQUE (SNAME);

/* Create Foreign Key... */
CONNECT '127.0.0.1:c:\vnii\vnii.GDB' USER 'SYSDBA' PASSWORD 'temp';

ALTER TABLE HL_COUNTERS ADD CONSTRAINT FK_HL_COUNTERS_1 FOREIGN KEY (PARKINGNUMBER) REFERENCES HL_PARKING(ID) ON DELETE CASCADE;

ALTER TABLE HL_EVENTS ADD CONSTRAINT FK_HL_EVENTS_1 FOREIGN KEY (EVENT_CODE) REFERENCES HL_EVENTCODE(ID) ON DELETE CASCADE;

ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_1 FOREIGN KEY (ID_GARAGENAME) REFERENCES HL_GARAGENAME(ID) ON DELETE CASCADE;

ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_2 FOREIGN KEY (ID_PLACE) REFERENCES HL_PLACE(ID) ON DELETE CASCADE;

ALTER TABLE HL_ORGACCESS ADD CONSTRAINT FK_HL_ORGACCESS_1 FOREIGN KEY (ID_GARAGE) REFERENCES HL_GARAGENAME(ID) ON DELETE CASCADE;

ALTER TABLE HL_ORGACCESS ADD CONSTRAINT FK_HL_ORGACCESS_2 FOREIGN KEY (ID_ORG) REFERENCES ORGANIZATION(ID_ORG) ON DELETE CASCADE;

ALTER TABLE HL_PARAM ADD CONSTRAINT FK_HL_PARAM_1 FOREIGN KEY (ID_PARKING) REFERENCES HL_PARKING(ID) ON DELETE SET NULL;

ALTER TABLE HL_PLACE ADD CONSTRAINT FK_HL_PLACE_1 FOREIGN KEY (ID_PARKING) REFERENCES HL_PARKING(ID);

/* Alter Procedure... */
/* Alter (ADD_ORG_PLACE) */
SET TERM ^ ;

ALTER PROCEDURE ADD_ORG_PLACE(DIV_CODE VARCHAR(50),
PLACENUM INTEGER)
 AS
declare variable ID_ORG integer;
declare variable ID_GARAGE integer;
begin
  select o.id_org from organization o
  where o.divcode=:div_code into :id_org;
  select hlg.id_garagename from hl_garage hlg
  where hlg.id_place=:placenum into :id_garage;
delete from hl_orgaccess hlo1 where hlo1.id_org=:id_org and hlo1.id_garage=:id_garage;
INSERT INTO HL_ORGACCESS (ID_ORG, ID_GARAGE, IS_ACTIVE) VALUES (:id_org, :id_garage, 1);
  suspend;
end
^

/* Alter (ADD_PARKING_PLACE_LINK) */
ALTER PROCEDURE ADD_PARKING_PLACE_LINK(PLACE INTEGER,
DIV VARCHAR(12),
HOUSE INTEGER)
 AS
DECLARE VARIABLE ID_GARAGE INTEGER;
DECLARE VARIABLE NAME_GARAGE VARCHAR(100) CHARACTER SET WIN1251;
begin
    name_garage= cast(:div as varchar(12))||'-'||cast(:house as varchar(5));
  select hlc.id from hl_counters hlc
  where hlc.name= :name_garage into :id_garage;
  update hl_place hlp
  set hlp.id_counters=:id_garage
  where hlp.placenumber=:place    ;
  suspend;
end
^

/* Alter (ADD_PEOPLE_WHITH_CARD_AND_TAB) */
ALTER PROCEDURE ADD_PEOPLE_WHITH_CARD_AND_TAB(CARD VARCHAR(32),
TABNUM VARCHAR(50),
NOTE VARCHAR(50),
CARDTYPE INTEGER)
 AS
DECLARE VARIABLE ID_PEP INTEGER;
begin
    select p.id_pep from people p where p.tabnum=:tabnum into :id_pep;

  /* Procedure Text */
  insert into card (ID_CARD, ID_DB, ID_PEP, TIMESTART, NOTE, STATUS, "ACTIVE", ID_CARDTYPE)
  values (
  :card, 1, :id_pep, 'now', :note,0, 1, :cardtype);
  /*
    cardtype 4 - GRZ

  */
  suspend;
end
^

/* Alter (ADD_PEOPLE_WHITH_DIV_ORG) */
ALTER PROCEDURE ADD_PEOPLE_WHITH_DIV_ORG(ID_DB INTEGER,
SURNAME VARCHAR(50),
NAME VARCHAR(50),
PATRONYMIC VARCHAR(50),
NOTE VARCHAR(250),
DIV_ORG VARCHAR(50),
TABNUM VARCHAR(50))
 AS
DECLARE VARIABLE ID_PEP INTEGER;
DECLARE VARIABLE ID_ORG INTEGER;
begin
    select o.id_org from organization o where o.divcode = :div_org into :id_org;
    INSERT INTO PEOPLE (
    ID_DB, ID_ORG, SURNAME, NAME, PATRONYMIC, WORKSTART, "ACTIVE", FLAG, NOTE, ID_AREA, TABNUM )
  VALUES (
    :ID_DB,  :ID_ORG, :SURNAME, :NAME, :PATRONYMIC, 'now', 1, 1, :NOTE, 0, :tabnum);
   suspend;
end
^

/* empty dependent procedure body */
/* Clear: CARDIDX_REFRESH for: CARDIDX_REFRESH */
/* AssignEmptyBody proc */
ALTER PROCEDURE CARDIDX_REFRESH(ID_DEV INTEGER)
 AS
 BEGIN EXIT; END
^

/* Alter (CARDIDX_REFRESH) */
ALTER PROCEDURE CARDIDX_REFRESH(ID_DEV INTEGER)
 AS
declare variable IS_ACTIVE integer;
declare variable IDCTRL integer;
declare variable IDREADER integer;
declare variable IDDEV integer;
declare variable IDDB integer;
declare variable CARD_DEL varchar(32);
declare variable CARD_INS varchar(32);
declare variable CARDIDX integer;
BEGIN
    /* Create procedure update 29.03.2016*/

    select d.ID_CTRL, d.ID_READER, d.ID_DB from device d where d.ID_DEV = :ID_DEV into :IDCTRL, :IDREADER, :IDDB;
    if (IDREADER is null) then begin
        for select d.ID_DEV from Device d where d.ID_CTRL = :IDCTRL and d.ID_READER is not null into :IDDEV
        do begin
            execute procedure CARDIDX_REFRESH :IDDEV ;

        end
    end

    execute procedure DEVICE_CHECKACTIVE :ID_DEV returning_values :IS_ACTIVE;
    if (:IS_ACTIVE is not null) then begin
        delete from CARDIDX where ID_DB = :IDDB AND ID_DEV = :ID_DEV;
        for select distinct c.id_card from access a
            join ss_accessuser su on su.id_accessname=a.id_accessname
             join card c on c.id_pep=su.id_pep and c."ACTIVE" <> 0 and (c.timeend is null or c.timeend > 'Now')
            where a.id_dev=:id_dev into :CARD_INS
        do begin
          /* 28.11.2017
          insert into CARDIDX (ID_DB, ID_DEV, ID_CARD) values (:IDDB, :ID_DEV, :CARD_INS);
          */
          execute procedure cardidx_insert :IDDB, :ID_DEV, :CARD_INS returning_values :CardIdx;
        end
      end

END
^

/* Alter (CARDINDEV_GETLIST) */
ALTER PROCEDURE CARDINDEV_GETLIST(IDDB INTEGER)
 RETURNS(ID_DEV INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
ID_CARD VARCHAR(32),
ID_PEP INTEGER,
DEVIDX INTEGER,
OPERATION INTEGER,
TIMEZONES INTEGER,
STATUS INTEGER,
ID_CARDINDEV INTEGER,
ATTEMPTS INTEGER)
 AS
begin
for select c.id_card, c.devidx, c.id_dev, c.operation, d.id_ctrl, d.id_reader, c.id_cardindev, c.ATTEMPTS, c.id_pep
     from CardInDev c
     join Device d  on (c.id_dev=d.id_dev) and (c.id_db=d.id_db)
     /*26.10.2017 Добавлена проверка на предмет того, что контроллер имеет тип 1 и 4 (работает с RFID и отпечатком пальца) и работает с идентификаторами вида 1 и 2 (RFID и отпечаток).
   Карты других типов АСервер не увидит */
    left join card cc on cc.id_card=c.id_card
    join device d2 on d2.id_ctrl=d.id_ctrl and (d2.id_devtype in (1,4)) and d2.id_reader is null
             join servertypelist stt on stt.id_server=d2.id_server and stt.id_type=1
    where (c.id_db=:iddb) and ( 0 <> (select IS_ACTIVE from DEVICE_CHECKACTIVE(d.id_dev)) ) and attempts < 2
/*28.10.2018 При удалении тип карты отсутствует поэтому проверка на тип карты не имеет смысла*/
   /* and (not exists (select * from cardindev where id_dev = c.id_dev and attempts > 3))     */
    order by c.id_cardindev
    into :id_card, :devidx, :id_dev, :operation, :id_ctrl, :id_reader, :id_cardindev, :attempts, :id_pep

/* Update 10.01.2015*/

do begin
   timezones=null;
   status=null;
   /* временное решение 12.04.2016. У всех будет временная зона 1 и статус 0. В дальнейшем надо будет сделать сборку временных масок  */
   timezones=1;
   status=0;

   if (operation=1) then
      begin
        select c_gp.timezones, c_gp.status
        from Card_GetParam4Dev(:iddb, :id_card) c_gp
        where (c_gp.id_dev=:id_dev)
        into :timezones, :status;

      end

    if(id_dev = 162) then
        begin
          -- id_card=INTTOHEX(cast(id_card as bigint));
           SELECT OUTPUTNUMBER FROM INTTOHEX(cast(:id_card as bigint)) into :id_card;
        end
   suspend;
   end
end
^

/* Alter (DELETE_UNKNOW_CARD) */
ALTER PROCEDURE DELETE_UNKNOW_CARD(CARD VARCHAR(32))
 AS
DECLARE VARIABLE ID_DEV INTEGER;
begin
  for
        select d.id_dev from device d
        join device d2 on d2.id_ctrl=d.id_ctrl
        where d2.id_reader is null
        and d.id_reader is not null
        and d."ACTIVE">0
        and d2."ACTIVE">0
        order by d.id_dev
     into :id_dev
    do begin
              INSERT INTO CARDINDEV (ID_DB,ID_CARD, DEVIDX, ID_PEP, ID_DEV,OPERATION,ATTEMPTS) VALUES (1,:card, 100, 0,:id_dev,2,0);

       end
end
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Процедура выставляет указаннй код карты в таблицу cardindev для удаления из всех контроллеров.
29.05.2019 Бухаров.'
where Rdb$Procedure_Name='DELETE_UNKNOW_CARD';

/* Alter (DEVICEEVENTS_INSERT) */
SET TERM ^ ;

ALTER PROCEDURE DEVICEEVENTS_INSERT(ID_DB INTEGER,
ID_EVENTTYPE INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
NOTE VARCHAR(100),
"TIME" TIMESTAMP,
ID_VIDEO INTEGER,
ID_USER INTEGER,
ESS1 INTEGER,
ESS2 INTEGER,
IDSOURCE INTEGER,
IDSERVERTS INTEGER)
 RETURNS(ID_EVENT INTEGER)
 AS
DECLARE VARIABLE ID_DEV INTEGER;
DECLARE VARIABLE ID_PLAN INTEGER;
DECLARE VARIABLE FLAGCARD INTEGER;
DECLARE VARIABLE ID_ORG INTEGER;
DECLARE VARIABLE ID_PEP INTEGER;
DECLARE VARIABLE FLAG INTEGER;
DECLARE VARIABLE SURNAME VARCHAR(50);
DECLARE VARIABLE NAME BLOB SUB_TYPE 0 SEGMENT SIZE 80;
DECLARE VARIABLE PATRONYMIC VARCHAR(50);
DECLARE VARIABLE ID_CARD VARCHAR(32);
DECLARE VARIABLE GUESTNOTE VARCHAR(250);
DECLARE VARIABLE FULLNAME VARCHAR(410);
DECLARE VARIABLE SOUND BLOB SUB_TYPE 0 SEGMENT SIZE 80;
DECLARE VARIABLE DOOR_NAME VARCHAR(50);
DECLARE VARIABLE SERVER_NAME VARCHAR(50);
DECLARE VARIABLE ANALIT INTEGER;
DECLARE VARIABLE S_BEFOR TIMESTAMP;
DECLARE VARIABLE S_END TIMESTAMP;
DECLARE VARIABLE EXEC_TIME DOUBLE PRECISION;
Begin
s_befor='now';

select ID_DEV from device where  (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)  INTO :ID_DEV;
--INSERT INTO EVENTS2 (ID_DB,ID_DEV, ID_EVENTTYPE,ID_OBJECT,DATETIME,NOTE,DEVTIME,ID_SERVER,ID_EVENTTS) VALUES (1,:ID_DEV,:ID_EVENTTYPE,1,'NOW',:NOTE,:"TIME",:IDSOURCE,:IDSERVERTS);

IF (:id_eventtype IN (49,53,54,57,58, 90, 91)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else IF (:id_eventtype IN (46,47,48,50, 145, 81)) THEN
    BEGIN
      SELECT MAX(ID_DEV) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV;

      fullname = ' <'||coalesce(:note, '')||'>';
      id_pep = :ess1;                            
      id_org = :ess2;
      select C.FLAG, c.ID_PEP from card c where (c.ID_CARD STARTING WITH :NOTE)
      into :FLAGCARD, :id_pep;

      SELECT P.ID_ORG, P.SOUND, P.ID_PLAN, P.FLAG, P.note
        , coalesce(' '||p.surname,'')||coalesce(' '||p.name,'')||coalesce(' '||p.patronymic,'')
      FROM PEOPLE P where (P.ID_PEP = :id_pep)
      INTO :id_org, :sound, :id_plan, :flag, :GUESTNOTE
        , :fullname
      ;

      if ((:id_eventtype=46) and (coalesce(:id_pep, 0)<>0)) then
         id_eventtype=65;

         if ((:id_eventtype=50) and (:id_pep is null)) then
         id_eventtype=80;

      if ((:id_eventtype=50) and (bitAnd(:flagcard,1)<>0) ) then

         fullname = fullname||coalesce(' в '||:GUESTNOTE, '');



      if ((:id_eventtype=145) and (bitAnd(:flagcard,1)<>0) ) then
         fullname = fullname||coalesce(' в '||:GUESTNOTE, '');

       if((:id_eventtype=50) or (:id_eventtype=47) or (:id_eventtype=65)) then
        execute procedure event_analit :id_db, :ID_DEV, :ID_PEP, :id_eventtype returning_values :analit;

      fullname = substring(:fullname from 2 for 100) || ',';
       s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ID_CARD, ANALIT,  ESS1, ESS2, NOTE, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :NOTE, :analit ,:ID_PEP, :ID_ORG, :fullname, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else  IF (:id_eventtype IN (51,52,55,56)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ESS1, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :ESS1, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else begin   /*Неизвестное событие*/
        fullname='';
        door_name='no_device_name';
        server_name='no_server_name';
        select d.name, s.name from device d
        join server s on d.id_server=s.id_server
        where d.id_ctrl=:id_ctrl  and d.id_reader is null
         into :door_name, :server_name ;
        if(:id_ctrl is null) then fullname = 'no id_ctrl';
        if(:id_reader is null) then fullname = fullname||' no id_reader';
        fullname=fullname||', idserverts='||:idserverts;
        s_end= 'now';
        exec_time=s_end-s_befor;
        INSERT INTO events (ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, IDSOURCE, IDSERVERTS, NOTE, EXEC_TIME)
        VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :IDSOURCE, :IDSERVERTS,
        'Device event='||:note,:exec_time );
        /*
        ||', device="'||:door_name
        ||'", server="'||:server_name
        ||'", '||:fullname );
        */

    end

select distinct gen_id(gen_event_id,0)
    from RDB$DATABASE
    into :id_event;
    suspend;

post_event('EventMonitor');   
if ((:id_eventtype=50) and (:sound is not null)) then post_event('EventSound');
if ((:id_eventtype=50) and (bitAND(:flag,128)<>0)) then post_event('EventSpeak');


end
^

/* Alter (EVENT_ANALIT) */
ALTER PROCEDURE EVENT_ANALIT(ID_DB INTEGER,
ID_DEV INTEGER,
ID_PEP INTEGER,
ID_EVENTTYPE INTEGER)
 RETURNS(ANALIT_CODE INTEGER)
 AS
DECLARE VARIABLE SINGLE_LIST INTEGER;
DECLARE VARIABLE CARD VARCHAR(32);
DECLARE VARIABLE CARD_FOR_DELETE INTEGER;
DECLARE VARIABLE CARD_FOR_LOAD INTEGER;
DECLARE VARIABLE CARD_IS_ACTIVE INTEGER;
DECLARE VARIABLE PEOPLE_IS_ACTIVE INTEGER;
DECLARE VARIABLE PASS_IS_VALIDE INTEGER;
begin

   people_is_active=0;
   card_is_active=0;
   pass_is_valide=0;
   analit_code=-1;
   card_for_delete=0;
   card_for_load=0;
   select p."ACTIVE" from people p where p.id_pep=:id_pep and p.id_db=1 into :people_is_active;
   select c.id_card, c."ACTIVE" from card c where c.id_pep=:id_pep and c.id_db=1 into :card,  :card_is_active;

   if (exists (
                select * from ss_accessuser ssa
                    join access ac on ssa.id_accessname=ac.id_accessname and ac.id_db=ssa.id_db
                    where ssa.id_pep=:id_pep and ac.id_dev=:id_dev and ssa.id_db=1
                )) then
                /*Пользователь может ходить через эту точку прохода*/
                          pass_is_valide=1;
                         else
                /*Пользователь НЕ имеет право прохода через эту точку прохода.';  */
                        pass_is_valide=0;
     /*Анализ возможных комбинаций*/
    if(:id_eventtype = 50) then  /*Анализ для события 50 - действительная карта*/
        begin
           /*Проверяю наличие карты в очереди на удаление*/
           if(exists(select * from cardindev cd where cd.id_dev=:id_dev and cd.id_card=:card and cd.operation=2 and cd.id_db=1)) then card_for_delete=1;
           /*Проверяю наличие метки Единый список у обрабатывающего контроллера*/
           select BITAND(d2.flag, 1) from device d
               join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
               where d.id_dev=:id_dev into :single_list;
               /*Если есть метка единого списка, то надо проверить возможность прохода через канал 1.*/
           /*Выполняю проверку всех возможных комбинаций*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=500;  /*Ошибка! Карта не должна ходить! Пользователь и карты не активны, проход запрещен.*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=501;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=502;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 1) ) then analit_code=503;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=504;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=505;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=506;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=0)) then analit_code=507; /*Ошибки нет, проход разрешен*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1) and (:single_list=0)) then analit_code=508; /*Ошибка СКУД. Такой комбинации быть не должно*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1) and (:single_list=1)) then analit_code=509; /*Ошибки нет, проход разрешена. Стоит метка Единый список*/
         /*14.03.2020 Добавлена обработка ситуаций, когда карат стоит в очереди на удаление*/
            if(:analit_code = 500 and :card_for_delete = 1) then analit_code=5001; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 501 and :card_for_delete = 1) then analit_code=5011; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 502 and :card_for_delete = 1) then analit_code=5021; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 503 and :card_for_delete = 1) then analit_code=5031; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 504 and :card_for_delete = 1) then analit_code=5041; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 505 and :card_for_delete = 1) then analit_code=5051; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 506 and :card_for_delete = 1) then analit_code=5061; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/


         end

     if(:id_eventtype = 65) then  /*Анализ для осбытия 65 - недействительная карта*/
        begin
           if(exists(select * from cardindev cd where cd.id_dev=:id_dev and cd.id_card=:card and cd.operation=1 and cd.id_db=1)) then card_for_load=1;
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=650;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=651;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=652;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 1) ) then analit_code=653;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=654;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=655;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=656;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=0)) then analit_code=657;  /*Ошибка! Карта должна ходить.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1)) then analit_code=658;  /*Переходное состояние! Надо дождаться загузки карты в контроллер.*/


        end

end
^

/* empty dependent procedure body */
/* Clear: EVENTS_GETLISTTIME for: EVENTS_GETLISTFROMID */
/* AssignEmptyBody proc */
ALTER PROCEDURE EVENTS_GETLISTTIME(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
 BEGIN EXIT; END
^

/* empty dependent procedure body */
/* Clear: EVENTS_GETLISTCOUNT for: EVENTS_GETLISTFROMID */
/* AssignEmptyBody proc */
ALTER PROCEDURE EVENTS_GETLISTCOUNT(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
 BEGIN EXIT; END
^

/* Alter (EVENTS_GETLISTFROMID) */
ALTER PROCEDURE EVENTS_GETLISTFROMID(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
ID_EVENTFROM INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
declare variable ID_EVENTTO integer;
declare variable ID_DEVGROUP integer;
declare variable ID_ORGGROUP integer;
declare variable DEV_ACCESS integer;
declare variable ORG_ACCESS integer;
declare variable CARD_TIME_END timestamp;
declare variable M_MONTH smallint;
declare variable P_MONTH varchar(12);
BEGIN
/* ID_EVENTTO - exclusive or null, ID_EVENTFROM - inclusive not null */
    /**/
    ID_EVENTTO = null;

     if(:id_eventfrom=1) then begin
         select gen_id(gen_event_id,0) from RDB$database into :ID_EVENTFROM;
      end

    if (:eventcount is not null) then begin
        if (:ID_EVENTFROM is null) then begin
            select distinct gen_id(gen_event_id,0) from RDB$DATABASE into :ID_EVENTFROM;
            ID_EVENTFROM = :ID_EVENTFROM - :eventcount + 1;
        end else begin
            ID_EVENTTO = :ID_EVENTFROM + :eventcount;
        end
    end
    if (:ID_EVENTFROM < 0) then ID_EVENTFROM = 0;
    /**/
    if (:ID_EVENTFROM is not null) then begin
        /* execute */

        /* Check for users groups */
        select max(p.id_devgroup), max(p.id_orgctrl)
        from people p
        where (p.id_db=:id_db) and (p.id_pep=:id_pep_cur)
        into :id_devgroup, :id_orggroup;
        /* if devgroupuser is empty then fill it for id_pep_cur child device */ 
        if (not exists (
            select id_devgroup from devgroupuser dgu
            where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur))) then
            begin
                insert into devgroupuser(id_pep, id_db, id_devgroup, id_dev,name)
                select :id_pep_cur, :id_db, id_devgroup, id_dev, name
                from devgroup_getchild(:id_db,:id_devgroup)
                where id_dev is not null;
            end
        
        /* if organizationuser is empty then fill it for id_pep_cur child people */
        if (not exists (
            select id_org from organizationuser ou
            where (ou.id_db=:id_db) and (ou.id_pep=:id_pep_cur))) then
            begin
                insert into organizationuser(id_pep, id_db, id_org, name)
                select :id_pep_cur, :id_db, id_org, name
                from organization_getchild(:id_db,:id_orggroup)
                where id_org is not null;
            end
        /* End Check for users groups */

        FOR SELECT distinct e.id_event, e.datetime, e.id_eventtype, et.name, e.id_dev, d.name,
            e.id_pep, e.note, e.id_plan, pl.name, e.id_video,
            e.id_card, e.ess1, e.ess2
        FROM (EVENTS e JOIN EVENTUSER eu ON (e.id_eventtype=eu.id_eventtype) and (eu.id_db=:id_db) and (eu.id_pep=:id_pep_cur) and (eu."ACTIVE"=1))
            LEFT JOIN EVENTTYPE et ON (e.id_eventtype=et.id_eventtype) and (e.id_db=:id_db)
            LEFT JOIN DEVICE d ON (e.id_dev=d.id_dev) and (e.id_db=:id_db)
            LEFT JOIN PLANS pl ON (d.id_plan=pl.id_plan) and (pl.id_db=:id_db)
            INNER JOIN devgroupuser dg2 on (dg2.id_pep=:id_pep_cur) and (dg2.id_dev=e.id_dev) and (dg2.id_db=:id_db)

        /* ID_EVENTTO - exclusive or null, ID_EVENTFROM - inclusive not null */
        WHERE (e.id_event >= :ID_EVENTFROM)
            and ( (:ID_EVENTTO is null) or (e.id_event < :ID_EVENTTO) )

        INTO :id_event, :datetime, :id_eventtype, :eventname, :id_dev, :devicename,
            :id_pep, :note, :id_plan, :planname, :id_video, :id_card, :ess1, :ess2
        DO BEGIN
            
            if (:id_eventtype in (1,2,5,6,7,8) ) then begin
                devicename = :note;
                note = '';
            end

                /* Check for user access */
            DEV_ACCESS = 1;
            /*
            if (
                ( :id_dev is not null )
                and ( not exists (
                    select dgu.id_devgroup from devgroupuser dgu 
                    where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur) and (dgu.id_dev=:id_dev)
                ))and ( not exists (
                    select dgu.id_devgroup from devgroupuser dgu   
                        join DEVICE_PARENTGROUPS dgp on ((dgu.id_devgroup=dgp.id_parent) and (dgp.id_dev = :id_dev ))
                    where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur)
                ))
            ) then DEV_ACCESS = 0;
            */
            ORG_ACCESS = 1;
            /* ess1 - id_pep of event */
            /* ess2 - id_org of event */
            /**/
            if ( :id_eventtype in (47,48,50,65, 81) ) then begin
               /* */if ( (:ess2 is null)
                    or (
                        (not exists (
                            select ou.id_org from organizationuser ou
                            where (ou.id_db = :id_db) and (ou.id_pep = :id_pep_cur) and (ou.id_org = :ess2)
                        )) and (not exists (
                            select ou.id_org from organizationuser ou
                                join ORGANIZATION_PARENTS op on ( (ou.id_org = op.id_parent) and (op.id_org = :ess2) )
                            where (ou.id_db = :id_db) and (ou.id_pep = :id_pep_cur)
                        ))
                    )
                ) then begin
                    ORG_ACCESS = 0;
                end else begin
                    select name from organization where id_org = :ess2 into :orgname;
                    if (:orgname <> '') then note=:note||' ['||:orgname||'] ('||:id_card||')';
                end

              select c.timeend from card c where c.id_pep=:ess1 and c.id_db = :id_db into :card_time_end;
              if (:card_time_end is  null) then begin
                    note=:note||', Срок действия карты не указан.';

               end else begin
                     m_month = extract(month from :card_time_end);
                     if(m_month < 10) then begin
                            p_month= '0'||cast(:m_month AS varchar(12));
                            end else begin
                            p_month = cast(:m_month AS varchar(12));
                            end

                     note=:note||', карта действительна до '
                     ||extract(day from :card_time_end)||'.'
                     ||:p_month||'.'
                     ||extract(year from :card_time_end);
                end
            end
            /*6.03.2018 restore analitic*/
            if ( :id_eventtype in (65) ) then begin
                note=:note||'analit:';
                if (exists (
                select * from ss_accessuser ssa
                    join access ac on ssa.id_accessname=ac.id_accessname and ac.id_db=ssa.id_db
                    where ssa.id_pep=:id_pep and ac.id_dev=:id_dev and ssa.id_db=1
                )) then
                note=:note||'Должна ходить!'; /*||' Возможно ошибка работы системы. Пользователь имеет право прохода через эту точку прохода. Обратитесь к администратору СКУД.';
                 */
                else
                /*note=:note||' Пользователь НЕ имеет право прохода через эту точку прохода.';  */
                note=:note||' Прохода нет!.';

                
            end
            /*end 6.03.2018*/
            /* End Check for user access */
/*6 декаря 2017 г. Обработка события 80 Проход незарегистрированного идентификатора  */
            if ( :id_eventtype in (80) ) then begin

                note=:note||' Проход незарегистрированного идентификатора.';
                
            end

            if ((:org_access=1) and (:dev_access=1)) then suspend;
        END

    end
END
^

/* empty dependent procedure body */
/* Clear: REPORT_GETEVENTLIST for: EVENTS_GETLISTTIME */
/* AssignEmptyBody proc */
ALTER PROCEDURE REPORT_GETEVENTLIST(IDDB INTEGER,
IDPEP INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_PEP INTEGER,
ID_DEV INTEGER,
ID_EVENTTYPE INTEGER,
DATETIME TIMESTAMP,
EVENTNAME VARCHAR(50),
SOURCE VARCHAR(50),
NOTE VARCHAR(152))
 AS
 BEGIN EXIT; END
^

/* Alter (EVENTS_GETLISTTIME) */
ALTER PROCEDURE EVENTS_GETLISTTIME(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
DECLARE VARIABLE ID_EVENT_START INTEGER;
DECLARE VARIABLE EVENTCOUNT INTEGER;
begin

select min(id_event), max(id_event) - min(id_event) + 1
from events where (datetime >= :timestart) and (datetime <= :timeend)
into :id_event_start, :eventcount ;

for select
        ID_EVENT ,
        DATETIME ,
        ID_EVENTTYPE ,
        EVENTNAME ,
        ID_DEV ,
        DEVICENAME ,
        ID_PEP ,
        NOTE ,
        ORGNAME ,
        ID_PLAN ,
        PLANNAME ,
        ID_VIDEO ,
        ID_CARD ,
        ESS1 ,
        ESS2
    from events_getlistfromid (:id_db, :id_pep_cur, :id_event_start, :eventcount)
    where (datetime>=:timestart) and (datetime<=:timeend)
    into
        :ID_EVENT ,
        :DATETIME ,
        :ID_EVENTTYPE ,
        :EVENTNAME ,
        :ID_DEV ,
        :DEVICENAME ,
        :ID_PEP ,
        :NOTE ,
        :ORGNAME ,
        :ID_PLAN ,
        :PLANNAME ,
        :ID_VIDEO ,
        :ID_CARD ,
        :ESS1 ,
        :ESS2
    do begin
        suspend;
    end
END
^

/* Alter (EVENTS_INSERT) */
ALTER PROCEDURE EVENTS_INSERT(ID_DB INTEGER,
ID_EVENTTYPE INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
NOTE VARCHAR(100),
"TIME" TIMESTAMP,
ID_VIDEO INTEGER,
ID_USER INTEGER,
ESS1 INTEGER,
ESS2 INTEGER)
 RETURNS(ID_EVENT INTEGER)
 AS
declare variable ID_DEV integer;
declare variable ID_PEP integer;
declare variable ID_PLAN integer;
declare variable ID_CARD varchar(32);
declare variable SURNAME varchar(50);
declare variable NAME varchar(50);
declare variable PATRONYMIC varchar(50);
declare variable FULLNAME varchar(152);
declare variable ID_ORG integer;
declare variable SOUND blob sub_type 0 segment size 80;
declare variable FLAG integer;
declare variable FLAGCARD integer;
declare variable GUESTNOTE varchar(250);
begin
 if (not exists (select * from
     eventtype et where (et.id_db=:id_db) and (et.id_eventtype=:id_eventtype)
     )) then begin
     id_event = 0;
     suspend;
     exit;
     end
 if (:id_eventtype in (0)) then
    begin
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end
 if (:id_eventtype in (17,18,19)) then
    begin
      SELECT P.SURNAME, P.NAME, P.PATRONYMIC
      FROM PEOPLE P
      WHERE (P.ID_PEP=:ess1) and (p.id_db=:id_db)
      INTO :surname, :name, :patronymic;
      note='<'||:note||'>';
      if (:surname is not null) then note=:note||' '||:surname;
      if (:name is not null) then note=:note||' '||:name;
      if (:patronymic is not null) then note=:note||' '||:patronymic;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end


 if (:id_eventtype in (9,10,11,12,13,14,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43)) then
    begin
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end
 IF (:id_eventtype IN (1,2)) THEN
    BEGIN
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :NOTE);
    END
 IF (:id_eventtype IN (5,6,7,8)) THEN
    BEGIN
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ESS1, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ESS1, :NOTE);
    END

/* Update 6.09.2007 
 IF (:id_eventtype IN (15,16,49,53,54,57,58)) THEN
*/
 IF (:id_eventtype IN (15,16)) THEN
/* Update 6.09.2007 */

    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN);
    END
 IF (:id_eventtype IN (44,45)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ID_DEV, :ID_PLAN, :NOTE);
    END


 IF (:id_eventtype IN (59,60)) THEN
/* Update 6.09.2007 */

    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ESS1)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :ESS1);
    END

 if (:id_eventtype IN (61,62,66)) then
    begin
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:ID_READER)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ID_DEV, :ID_PLAN);
    End

 if (:id_eventtype IN (63,64)) then
    begin
      SELECT MAX(ID_DEV) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV;
      SELECT C.ID_CARD, C.FLAG, P.ID_ORG, P.ID_PEP, P.SURNAME, P.NAME, P.PATRONYMIC, P.SOUND, P.ID_PLAN, P.FLAG, P.note
      FROM PEOPLE P
           INNER JOIN CARD C ON (ID_CARD STARTING WITH :NOTE) AND (P.ID_DB = C.ID_DB) AND (P.ID_PEP = C.ID_PEP)
      INTO :id_card, :FLAGCARD, :id_org, :id_pep, :surname, :name, :patronymic, :sound, :id_plan, :flag, :GUESTNOTE;

      IF (:surname IS NULL) THEN surname='';
      IF (:name IS NULL) THEN name='';
      IF (:patronymic IS NULL) THEN patronymic='';
      if (:id_pep is not null) then
         begin
           fullname = :surname||' '||:name||' '||:patronymic;
         end else fullname = '<'||:note||'> '||:surname||' '||:name||' '||:patronymic;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ID_CARD, ESS1, ESS2, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :NOTE, :ID_PEP, :ID_ORG, :fullname);
    end
select distinct gen_id(gen_event_id,0)
from RDB$DATABASE
into :id_event;
suspend;
/* 2018
post_event('EventMonitor');
*/

/* Update 6.09.2007
if ((:id_eventtype=50) and (:sound is not null)) then post_event('EventSound');
if ((:id_eventtype=50) and (bitAND(:flag,128)<>0)) then post_event('EventSpeak');
*/

END
^

/* Alter (HL_UPDATE_GARAGE_NAME) */
ALTER PROCEDURE HL_UPDATE_GARAGE_NAME(PLACENUM INTEGER,
NAME_GARAGE VARCHAR(250))
 AS
declare variable ID_GARAGENAME integer;
/*
  процедура обновляет название гаража, используя номер машиноместа и новое название
*/

begin
  select hlg.id_garagename from hl_garage hlg where hlg.id_place=:placenum into :id_garagename;
  update hl_garagename hlgn set hlgn.name=:name_garage
  where hlgn.id=:id_garagename;
  suspend;
end
^

/* Alter (INTTOHEX) */
ALTER PROCEDURE INTTOHEX(INPUTNUMBER BIGINT)
 RETURNS(OUTPUTNUMBER VARCHAR(8))
 AS
DECLARE VARIABLE Q BIGINT;
DECLARE VARIABLE R BIGINT;
DECLARE VARIABLE T BIGINT;
DECLARE VARIABLE H VARCHAR(1);
DECLARE VARIABLE S VARCHAR(6);
begin
  /* Max input value allowed is: 4294967295 */

  S = 'ABCDEF';

  Q = 1;
  OUTPUTNUMBER = '';
  T = INPUTNUMBER;
  WHILE (Q <> 0) DO
  BEGIN

    Q = T / 16;
    R = MOD(T, 16);
    T = Q;

    IF (R > 9) THEN

    -- H = SUBSTRING(S FROM R-9 FOR 1);
     H = SUBST2(S, R-9, R-9);

    ELSE
     H = R;

    OUTPUTNUMBER = H || OUTPUTNUMBER ;

  END


  SUSPEND;
end
^

/* Alter (ORGANIZATION_GETPARENT) */
ALTER PROCEDURE ORGANIZATION_GETPARENT(ID_DB INTEGER,
IDPARENT INTEGER)
 RETURNS(ID_ORG INTEGER,
NAME VARCHAR(50),
ID_PARENT INTEGER,
FLAG INTEGER)
 AS
begin
  id_org = 0;
  id_parent   = :idparent;

  WHILE (:id_org <> :id_parent) DO
    BEGIN 
      SELECT o.id_org, o.name, o.id_parent, o.flag
      FROM organization o
      WHERE (o.id_org=:id_parent) AND (o.id_db=:id_db)
      INTO :id_org, :name, :id_parent, :flag;
      SUSPEND;
    END
END
^

/* Alter (REGISTERPASS) */
ALTER PROCEDURE REGISTERPASS(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(RC INTEGER)
 AS
declare variable ID_PEP integer;
declare variable ID_EVENT integer;
declare variable ID_CTRL integer;
declare variable ID_READER integer;

declare variable ISENTER integer;
declare variable ID_ORG integer;
declare variable tmp integer;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
  select id_ctrl, id_reader from device where id_dev = :id_dev into :id_ctrl, :id_reader;
  execute procedure ValidatePass :id_dev, :id_card, :grz returning_values :RC, :id_pep;

  IF (:rc = 50) THEN BEGIN
    -- и начинаем корректировать список а/м на территории
    SELECT is_enter FROM carcount_gates WHERE id_dev = :id_dev INTO :isenter;
    -- если точка прохода есть КПП, то запросим ид организации проезжающего
    IF (:isenter IS NOT NULL) THEN
      SELECT ID_ORG FROM People WHERE ID_PEP = :id_pep INTO :id_org;

    IF (:ISENTER = 0) THEN BEGIN    /* это выезд */
      -- если ГРЗ указан, то посмотрим, имеется ли он в списке а/м на территории
      IF (:grz IS NOT NULL) THEN
          select count(*) from CARCOUNT_INSIDE where GRZ=:grz INTO :tmp;

      -- если есть на территории
      IF ((:grz IS NOT NULL) AND (:tmp > 0)) THEN BEGIN
        -- то удалим его из этого списка
        DELETE FROM CARCOUNT_INSIDE WHERE GRZ = :GRZ;
      END ELSE BEGIN
        -- ГРЗ не распознан
        -- ГРЗ распознан, но его нет на территории, возможно он был не распознан на въезде

        -- есть ли на территории а/м без ГРЗ, въехавшие по той же карте?
        select min(ID_CARCOUNT_INSIDE) 
            FROM CARCOUNT_INSIDE 
            WHERE ID_CARD = :id_card AND GRZ IS NULL 
            INTO :tmp;
        -- если таких нет, то смотрим, есть ли на территории а/м без ГРЗ той же организации
        IF (:tmp IS NULL) THEN
            select min(ID_CARCOUNT_INSIDE) 
                FROM CARCOUNT_INSIDE cci INNER JOIN People p ON cci.ID_PEP = p.ID_PEP
                WHERE p.ID_ORG = :id_org AND cci.GRZ IS NULL 
                INTO :tmp;
        -- если нашлась подходящая запись без распознанного ГРЗ, то удалим ее
        if (:tmp IS NOT NULL) THEN
            DELETE FROM CARCOUNT_INSIDE WHERE ID_CARCOUNT_INSIDE = :tmp;
      END
    END

    IF (:ISENTER <> 0) THEN BEGIN    /* это въезд */
      -- удалим, если они имеются, записи о нахождении внутри а/м с данным ГРЗ
      IF (:grz IS NOT NULL) THEN
          DELETE FROM CARCOUNT_INSIDE WHERE GRZ = :GRZ;
      -- добавим запись о нахождении на территории
      INSERT INTO CARCOUNT_INSIDE (ID_PEP, ID_CARD, GRZ, ENTERDEV) VALUES (:id_pep, :id_card, :grz, :id_dev);
    END
  END

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
  execute procedure events_insert(1, :rc, :id_ctrl, :id_reader, :id_card, 'now', null, null, :id_pep, null)
        returning_values :id_event;

  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  IF (:RC = 50) THEN RC = 0;
end
^

/* Alter (REGISTERPASS_HL) */
ALTER PROCEDURE REGISTERPASS_HL(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
DECLARE VARIABLE ID_ORG INTEGER;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
  rc=-1;
  -- определяю ID_ORG для ess2
  select p.id_org from card c
  join people p on p.id_pep=c.id_pep
  where c.id_card=:id_card into :id_org    ;

  --выполняю валидацию ГРЗ
  execute procedure validatepass_hl_parking :id_dev, :id_card, :grz returning_values :RC, :id_pep;

 -- фиксирую обращене к валидации
    INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
    VALUES (13, :id_card, :id_dev);

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
VALUES (1, :rc, :id_dev,  'now', :id_card, :id_card, 1, :id_pep, :id_org);

-- фиксирую результат валидации
 INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, ID_PEP)
    VALUES (:rc, :id_card, :id_dev, :id_pep);



  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end
^

/* Alter (REGISTERPASS_HL_2) */
ALTER PROCEDURE REGISTERPASS_HL_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
declare variable ID_ORG integer;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
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
VALUES (1, :rc, :id_dev,  'now', :id_card, :id_card, 1, :id_pep, :id_org);

  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end
^

/* Alter (REPORT_GETEVENTLIST) */
ALTER PROCEDURE REPORT_GETEVENTLIST(IDDB INTEGER,
IDPEP INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_PEP INTEGER,
ID_DEV INTEGER,
ID_EVENTTYPE INTEGER,
DATETIME TIMESTAMP,
EVENTNAME VARCHAR(50),
SOURCE VARCHAR(50),
NOTE VARCHAR(152))
 AS
begin
for select e_glt.ess1, e_glt.id_dev, e_glt.id_eventtype, e_glt.datetime,
           e_glt.eventname, e_glt.devicename, e_glt.note
    from events_getlisttime(:iddb,:idpep,:timestart,:timeend) e_glt
    into :id_pep, :id_dev, :id_eventtype, :datetime, :eventname, :source, :note
    do begin
       if ((id_eventtype<>50) and (id_eventtype<>65) and (id_eventtype<>80)) then id_pep=null;
       suspend;
    end
END
^

/* Alter (VALIDATEPASS) */
ALTER PROCEDURE VALIDATEPASS(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer;
declare variable TIMESTART timestamp;
declare variable TIMEEND timestamp;
declare variable ID_ORG integer;
declare variable ISENTER integer;
declare variable CURRENTCOUNT integer;
declare variable MAXCOUNT integer;
declare variable DEFAULT_LIMIT integer = 100000;
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
begin
  -- процедура выполняет проверку возможности прохода данного идентификатора
  -- через данную точку прохода
  -- результат выполнения: код события, который должен быть записан и
  -- идентификатор пользователя, если он был найден

  -- получаем данные идентификатора
  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
    event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;
  end else begin
    -- запрашиваем признак активности для сотрудника
    -- и код группы, который будет использован позже,
    -- при проверке количества автомобилей
    select "ACTIVE", id_org from people where id_pep = :id_pep into :is_active, :id_org;
    -- проверяем его
    if (:is_active <> 1) then begin
      event_type = :RC_DISABLEDUSER;
    end else if (not exists (select * from ss_accessuser au
                         join access on au.id_accessname = access.id_accessname
                         where au.id_pep = :id_pep and access.id_dev = :id_dev)) then begin
      event_type = :RC_ACCESSDENIED;
    end else begin
      -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход. для прочих точек прохода будет null
      SELECT is_enter FROM carcount_gates WHERE id_dev = :id_dev INTO :isenter;
      -- если это въезд
      if (:isenter <> 0) then begin
        -- получим максимальное количество машин
        SELECT carcount_limit."COUNT"
            FROM carcount_limit
            WHERE carcount_limit.id_org = :id_org
            INTO :maxcount;

        -- если явно лимит не назначен, то действует лимит по умолчанию
        if (:maxcount is null) then maxcount = :DEFAULT_LIMIT;

        -- если лимит есть 
        if (:maxcount > 0) then begin
          -- посчитаем количество а/м на территории
          SELECT Count(*) FROM carcount_inside
            inner join people on carcount_inside.id_pep = people.id_pep
            WHERE people.id_org = :id_org
            into :currentcount;

          -- если есть машины с тем же ГРЗ для этой организации, то уменьшим на единицу текущее количество
          -- поскольку при въезде прежняя запись о нахождении а/м на территории будет удалена
          if ((:GRZ IS NOT NULL) and
              (EXISTS (
                  SELECT * FROM CARCOUNT_INSIDE cci INNER JOIN People p ON cci.ID_PEP = p.ID_PEP
                    WHERE p.ID_ORG = :id_org AND cci.GRZ = :GRZ
              ))) then currentcount = :currentcount - 1;
        end

        -- проверим, достигнуто ли максимальное количество
        -- если одно из них или оба null, то результат будет false
        if (:currentcount < :maxcount) then begin
          -- все хорошо
          event_type = :RC_OK;
        end else begin
          -- превышен лимит количества а/м
          event_type = :RC_CARLIMITEXCEEDED;
        end
      end else begin    -- если это не въезд, то есть выезд или вообще не КПП, то проверка завершена, выезжать/выходить можно всем
        event_type = :RC_OK;
      end
    end
  end
end
^

/* Alter (VALIDATEPASS_HL_PARKING) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю id гаража и количество машиномест в гараже
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;
  end else begin
    -- запрашиваем признак активности для сотрудника
    -- и код группы, который будет использован позже,
    -- при проверке количества автомобилей
    select "ACTIVE", id_org from people where id_pep = :id_pep into :is_active, :id_org;
    -- проверяем его
    if (:is_active <> 1) then begin
      event_type = :RC_DISABLEDUSER;
    end else if (not exists (select * from ss_accessuser au
                         join access on au.id_accessname = access.id_accessname
                         where au.id_pep = :id_pep and access.id_dev = :id_dev)) then begin
      event_type = :RC_ACCESSDENIED;
    end else begin



      -- если нет парковки
      if(:id_parking is not null) then begin
          -- если это въезд
          if (:is_enter <> 0) then begin
    
            -- если нет гаража, то количество машиномест NULL
            IF (:cntcount IS NULL) THEN BEGIN
              event_type = :rc_accessdenied;
            END ELSE BEGIN
              --  а если есть гаража, то количество мест не NULL, и тогда считаю сколько машин уже стоит в гараже

              select count(*) from hl_inside hli
                join card c on c.id_card=hli.id_card
                join people p on p.id_pep=c.id_pep
                join hl_orgaccess hlo on hlo.id_org=p.id_org
                where hlo.id_garage=:id_garage INTO :currentcount;

                select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;
    
              -- проверим, достигнуто ли максимальное количество
              -- если одно из них или оба null, то результат будет false
              if ((:currentcount < :cntcount) OR (:checkplaceenable=0)) then begin
                -- все хорошо
                event_type = :RC_OK;
              end else begin
                -- превышен лимит количества а/м
                event_type = :RC_CARLIMITEXCEEDED;
              end
            END
          end else begin    -- если это не въезд, то есть выезд или вообще не КПП, то проверка завершена, выезжать/выходить можно всем
            event_type = :RC_OK;
          end
          end else begin
      event_type = :rc_accessdenied;    -- это если нет парковки

      end
    end
  end
 suspend;
end
^

/* Alter (VALIDATEPASS_HL_PARKING_2) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
declare variable GARAGEOLACECOUNTENABLE integer = 1; /* считать ли свободные места для выбранного гаража? 1 - НЕ считать, 0 - Считать. */
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю :id_garage гаража и :cntcount количество машиномест в гараже  с учетом паркинга (этажа).
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;

        --подсчет машиномест на парковке, куда пытается заехать ГРЗ
        select count(*) from hl_place hlp
        join hl_garage hlg on hlg.id_place=hlp.id
        where hlp.id_parking=:id_parking
        and hlg.id_garagename=:id_garage into :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;

     -- с ГРЗ все в порядке, начинаю следующие проверки
  end else begin


     -- если есть гараж, то проверяю наличие свободных мест.
    if(:id_garage is not null) then begin


                -- если это въезд
                  if (:is_enter <> 0) then
                        begin
                        --если въезд разрешен, то начинаю подсчет свободных мест
                         if(:cntcount >0) then begin
                        --определяю количество свободных мест
                            select count(*) from hl_inside hli
                            join card c on c.id_card=hli.id_card
                            join people p on p.id_pep=c.id_pep
                            join hl_orgaccess hlo on hlo.id_org=p.id_org
                            where hlo.id_garage=:id_garage
                            and hli.counterid=:id_parking
                            INTO :currentcount;
            
                            select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;

                            -- если ГРЗ в гараже, то надо разрешать въезд в любом случае.
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (select * from hl_inside hli where hli.id_card=:id_card))) then
                                        -- въезд разрешен
                                        event_type = :RC_OK;
                                  else
                                    -- превышен лимит количества а/м
                                    event_type = :RC_CARLIMITEXCEEDED;
                            end
                        else
                        --если въезд запрещен, то выдаяю отказ в проезде в явном виде
                            event_type = :rc_accessdenied;
                          end
        
                    else
                        --если это выезд
                       -- тут надо сделать проверка: со своего ли паркинга он выезжает? но пока этой проверки нет, выпускаем всех
                        event_type = :RC_OK;

                    -- обработка въезд-выезд завершена
                          -- а если нет гаража, то проверяю категории доступа
         end
        else begin

                    if (exists (select * from ss_accessuser au
                    join access on au.id_accessname = access.id_accessname
                    where au.id_pep = :id_pep and access.id_dev = :id_dev)) then
                    --если проезд разрешен
                        --если это въезд, то проверяю наличие ГРЗ на территории парковки
                        if (:is_enter <> 0) then
                            if(not exists(select * from hl_inside hli
                                where hli.id_card=COALESCE(:id_card, :grz))) then event_type = :RC_OK;  --ГРЗ нет на территории. Въезд разрешен.
                            else  event_type = :RC_CARLIMITEXCEEDED;  --ГРЗ есть на территории. Въезд запрещен (нет мест)

                        else
                            event_type = :RC_OK;  --а если это выезд, то выпускаем всех
                    else
                    event_type = :RC_ACCESSDENIED;

            end

        end

 suspend;
end
^

/* Alter (VALIDATEPASS_HL_PARKING_3) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING_3(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
declare variable GARAGEPLACECOUNTENABLE integer = 1; /* считать ли свободные места для выбранного гаража? 1 - НЕ считать, 0 - Считать. */
/*
ver 3
13.05.2023 добавлена обработка исключения из подсчета свободных мест для каждого гаража
*/
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю :id_garage гаража и :cntcount количество машиномест в гараже  с учетом паркинга (этажа).
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;

        --подсчет машиномест на парковке, куда пытается заехать ГРЗ
        select count(*) from hl_place hlp
        join hl_garage hlg on hlg.id_place=hlp.id
        where hlp.id_parking=:id_parking
        and hlg.id_garagename=:id_garage into :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;

     -- с ГРЗ все в порядке, начинаю следующие проверки
  end else begin


     -- если есть гараж...
    if(:id_garage is not null) then begin


                 select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;
                 select hlg.not_count from hl_garagename hlg where hlg.id=:id_garage into :garageplacecountenable;

                 -- если надо учитывать количество свободных мест
                 if((:checkplaceenable = 0) or (:garageplacecountenable = 0)) then begin
                -- если это въезд
                  if (:is_enter <> 0) then
                        begin
                        --если въезд разрешен, то начинаю подсчет свободных мест
                         if(:cntcount >0) then begin
                        --определяю количество свободных мест
                            select count(*) from hl_inside hli
                            join card c on c.id_card=hli.id_card
                            join people p on p.id_pep=c.id_pep
                            join hl_orgaccess hlo on hlo.id_org=p.id_org
                            where hlo.id_garage=:id_garage
                            and hli.counterid=:id_parking
                            INTO :currentcount;
            

                            -- надо ли учитывать количество свободных мест на всей паркове?
            
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0)) then
                                        -- въезд разрешен
                                        event_type = :RC_OK;
                                  else
                                    -- превышен лимит количества а/м
                                    event_type = :RC_CARLIMITEXCEEDED;
                            end
                        else
                        --если въезд запрещен, то выдаяю отказ в проезде в явном виде
                            event_type = :rc_accessdenied;
                    end

                     --если это выезд
                    else

                       -- тут надо сделать проверка: со своего ли паркинга он выезжает? но пока этой проверки нет, выпускаем всех
                        event_type = :RC_OK;

                    -- обработка въезд-выезд завершена

           end
             else  event_type = :RC_OK;
            -- обработка варианта НЕ СЧИТАТЬ завершена
          
            end

         -- а если нет гаража, то проверяю категории доступа
        else begin    -- это не гараж, и выполняется проверка по категориям доступа

                    if (exists (select * from ss_accessuser au
                    join access on au.id_accessname = access.id_accessname
                    where au.id_pep = :id_pep and access.id_dev = :id_dev)) then
                    --если проезд разрешен
                        --если это въезд, то проверяю наличие ГРЗ на территории парковки
                        if (:is_enter <> 0) then
                            if(not exists(select * from hl_inside hli
                                where hli.id_card=COALESCE(:id_card, :grz))) then event_type = :RC_OK;  --ГРЗ нет на территории. Въезд разрешен.
                            else  event_type = :RC_CARLIMITEXCEEDED;  --ГРЗ есть на территории. Въезд запрещен (нет мест)

                        else
                            event_type = :RC_OK;  --а если это выезд, то выпускаем всех
                    else
                    event_type = :RC_ACCESSDENIED;

            end

        end

 suspend;
end
^

/* Restore procedure body: EVENTS_GETLISTCOUNT */
ALTER PROCEDURE EVENTS_GETLISTCOUNT(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
begin
    for select
        ID_EVENT ,
        DATETIME ,
        ID_EVENTTYPE ,
        EVENTNAME ,
        ID_DEV ,
        DEVICENAME ,
        ID_PEP ,
        NOTE ,
        ORGNAME ,
        ID_PLAN ,
        PLANNAME ,
        ID_VIDEO ,
        ID_CARD ,
        ESS1 ,
        ESS2
    from events_getlistfromid (:id_db, :id_pep_cur, null, :eventcount) into
        :ID_EVENT ,
        :DATETIME ,
        :ID_EVENTTYPE ,
        :EVENTNAME ,
        :ID_DEV ,
        :DEVICENAME ,
        :ID_PEP ,
        :NOTE ,
        :ORGNAME ,
        :ID_PLAN ,
        :PLANNAME ,
        :ID_VIDEO ,
        :ID_CARD ,
        :ESS1 ,
        :ESS2
    do begin
        suspend;
    end

END
^

/* Create Trigger... */
CREATE TRIGGER EVENTS_PARKING_HL FOR EVENTS
ACTIVE AFTER INSERT POSITION 0 
AS
  DECLARE VARIABLE pID int;
  DECLARE VARIABLE IsEnter int;


begin
  /* Событие должно быть "действительная карта" и должен присутствовать ид пользователя*/
  if (new.id_eventtype = 50 and new.ess1 IS NOT NULL) then begin
    /* ищем, соответствует ли точке прохода какая-нибудь парковка */

        select hlp.id_parking, hlp.is_enter from  hl_param hlp
            where hlp.id_dev=new.id_dev into :pID, :IsEnter;


    if (pID IS NOT NULL) then begin   /* парковка найдена */
      if (IsEnter = 0) then begin     /* это выезд */

        DELETE FROM hl_inside  WHERE id_card = new.id_card;
      end else begin                  /* это въезд */
        -- удаляем запись о нахождении внутри (если она есть) в любом случае
       DELETE FROM hl_inside  WHERE counterid = :pID and id_card = new.id_card;
        -- а записываем только тех, у кого есть снимаемая категория

      INSERT INTO HL_inside  (ENTERTIME, id_card, counterid) VALUES (new.datetime, new.id_card, :pID );
      end
    end
  end
end
^

SET TERM ; ^

Update Rdb$Triggers set Rdb$Description =
'Заполение таблицы inside.'
where Rdb$Trigger_Name='EVENTS_PARKING_HL';

SET TERM ^ ;

CREATE TRIGGER HL_COUNTERS_BI FOR HL_COUNTERS
ACTIVE BEFORE INSERT POSITION 0 
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_HL_COUNTERS_id,1);
end
^

CREATE TRIGGER HL_EVENTS_BI FOR HL_EVENTS
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_EVENTS_ID,1);
END
^

CREATE TRIGGER HL_GARAGE_BI FOR HL_GARAGE
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_GARAGE_ID,1);
END
^

CREATE TRIGGER HL_GARAGENAME_BI FOR HL_GARAGENAME
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_GARAGENAME_ID,1);
   if (new.div_code is null) then
   new.div_code='garage_'||new.id  ;
END
^

CREATE TRIGGER HL_ORGACCESS_BI FOR HL_ORGACCESS
ACTIVE BEFORE INSERT POSITION 0 
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_hl_orgaccess_id,1);
end
^

CREATE TRIGGER HL_PARAM_BI FOR HL_PARAM
ACTIVE BEFORE INSERT POSITION 0 
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_hl_param_id,1);
end
^

CREATE TRIGGER HL_PARKING_BI FOR HL_PARKING
ACTIVE BEFORE INSERT POSITION 0 
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_hl_parking_id,1);
end
^

CREATE TRIGGER HL_PLACE_BI FOR HL_PLACE
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_PLACE_ID,1);
END
^

CREATE TRIGGER HL_PLACEGROUP_BI FOR HL_PLACEGROUP
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_PLACEGROUP_ID,1);
END
^

CREATE TRIGGER SERVERTYPE_BI FOR SERVERTYPE
ACTIVE BEFORE INSERT POSITION 0 
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_SERVERTYPE_ID,1);
END
^


/* Alter exist trigger... */
ALTER TRIGGER ACCESS_BD
AS
  declare variable idcard varchar(32);
  declare variable CardIdx integer;
BEGIN
  for select c.id_card
    /* from Card c left join ss_accessuser ss on c.id_db = ss.id_db and c.id_pep = ss.id_pep 2016*/
    from Card c join ss_accessuser ss on c.id_db = ss.id_db and c.id_pep = ss.id_pep
    where (c.id_db = old.id_db) and (ss.id_accessname = old.id_accessname)
    into :idcard
  do begin
    if (not exists (select * from access
                      where access.id_db = old.id_db and
                            access.id_accessname = old.id_accessname and
                            access.id_access <> old.id_access and
                            access.id_dev = old.id_dev)) then
      execute procedure cardidx_delete old.id_db, old.id_dev, :idcard returning_values :CardIdx; /* 08 Update 03.03.2008 */
  end
END
^

/* Alter exist trigger... */
ALTER TRIGGER CARDIDX_AD
AS
 declare variable door_is_active integer;
 declare variable id_pep integer;
 declare variable id_cardtype integer;
BEGIN

    execute procedure DEVICE_CHECKACTIVE old.id_dev returning_values :door_is_active;
    if (:door_is_active is not null) then begin
            select c.id_pep, c.id_cardtype from card c where c.id_card=old.id_card into :id_pep, :id_cardtype;
            insert into CardInDev(id_db, id_card, devidx, id_dev, operation, id_pep, id_cardtype)
                values (old.id_db,old.id_card,old.devidx,old.id_dev,2, :id_pep, :id_cardtype);

 end
           delete from CardInDev where ID_CARDINDEV = old.id_cardindev and ID_DB=old.id_db;
END
^

/* Alter exist trigger... */
ALTER TRIGGER CARDIDX_BI
AS
 declare variable door_is_active integer;   
 declare variable id_cardindev integer;
 declare variable id_pep integer;
 declare variable ID_CARDTYPE integer;

begin
/* 08 Update 03.03.2008 */
    /*select "ACTIVE" from device where id_db = new.id_db and id_dev = new.id_dev into :door_is_active; ** 03 Update 02.07.2007 */
    execute procedure DEVICE_CHECKACTIVE new.id_dev returning_values :door_is_active; 
/* 08 Update 03.03.2008 */
    if (:door_is_active <> 0) then
        id_cardindev = GEN_ID(GEN_CARDINDEV_ID,1);
        select c.id_pep, c.id_cardtype from card c where c.id_card=new.id_card into :id_pep, :id_cardtype;
        insert into CardInDev(id_cardindev, id_db, id_card, devidx, id_dev, operation, id_pep, ID_CARDTYPE)
        values (:id_cardindev, new.id_db,new.id_card,new.devidx,new.id_dev,1, :id_pep, :id_cardtype);
        new.id_cardindev = id_cardindev;
end
^

/* Alter exist trigger... */
ALTER TRIGGER CARDINDEV_AI
AS
begin
  /* Update 28.08.2007 Create trigger */
  delete from CARDINDEV c where
  c.id_db = NEW.id_db
  and c.id_dev = NEW.id_dev
  and c.devidx = NEW.devidx
  and c.id_card = NEW.id_card
  and c.ID_CARDINDEV <> NEW.ID_CARDINDEV;
end
^

ALTER TRIGGER CARDINDEV_AI ACTIVE
^

/* Alter exist trigger... */
ALTER TRIGGER DEVICE_AU
AS
begin
/* 08 Update 03.03.2008 Rename trigger DEVICE_BU*/ 

  if (old.id_reader is null ) then begin
      if (old.id_ctrl<>new.id_ctrl) then begin
         update device d
         set d.id_ctrl=new.id_ctrl
         where (d.id_db=old.id_db) and (d.id_ctrl=old.id_ctrl) and (d.id_reader is not null);
      end

/* Активность контроллера не должна автоматически влиять на активность считывателя,
*  т.к. Активность считывателей может участвовать в другой логике.
*/
  end
/* 08 update 03.03.2008 */
    if ( (old."ACTIVE" <> new."ACTIVE" and new."ACTIVE" <> 0) or
         ( (new.ID_SERVER<>old.ID_SERVER) or ((new.ID_SERVER is not null) and (old.ID_SERVER is null)) )
    ) then begin
        execute procedure CARDIDX_REFRESH old.id_dev;
    end
       if  (old."ACTIVE" <> new."ACTIVE" and new."ACTIVE" = 0)
       then begin
        /*comment 20.03.2020
        удаление данных из таблиц cardidx и cardindev
        */
        delete from cardidx cd where cd.id_dev=old.id_dev;
        delete from cardindev cdd where cdd.id_dev=old.id_dev;
    end
/* 08 update 03.03.2008 */
END
^

/* Alter exist trigger... */
ALTER TRIGGER EVENTS_GUEST INACTIVE
^

SET TERM ; ^

Update Rdb$Triggers set Rdb$Description =
'Проверка карты на вхождение в категорию Гости.
После вставки события карта должна быть удалена, т.к. покинула заданые периметр.'
where Rdb$Trigger_Name='EVENTS_GUEST';

/* Alter exist trigger... */
SET TERM ^ ;

ALTER TRIGGER EVENTS_GUESTCARD INACTIVE
^

/* Alter exist trigger... */
ALTER TRIGGER EVENTS_POST
AS

begin
--  if (new.id_eventtype in (12,13,14)) then POST_EVENT 'DevListChange';
  POST_EVENT('EventMonitor');

end
^

ALTER TRIGGER EVENTS_POST ACTIVE
^

/* Alter exist trigger... */
ALTER TRIGGER EVENTTYPE_CREATE_ID ACTIVE
^

/* Alter exist trigger... */
ALTER TRIGGER PEOPLE_CREATE_ID
AS
BEGIN
  IF (NEW.ID_PEP IS NULL) THEN
    begin
    NEW.ID_PEP = GEN_ID(GEN_PEOPLE_ID,1);
    end
  if ((NEW.login is null) or (NEW.login='')) then
    begin
      NEW.login = 'USER'||new.id_pep;
    end
  if (new.name is null) then new.name='';
  if (new.patronymic is null) then new.patronymic='';
  if (new.tabnum is null) then new.tabnum = 'tn_' || NEW.ID_PEP;
  if (new.login is null) then new.login = 'USER' || NEW.ID_PEP;
  if (new.pswd is null) then new.pswd = '';
  if (new.datedoc is null) then new.datedoc = 'now';
  if (new.datebirth is null) then new.datebirth = 'now';
END
^

/* Alter exist trigger... */
ALTER TRIGGER PEOPLE_LOGNEW
AS
declare variable id_event integer;
begin
select id_event
from events_insert(new.id_db,32,null,null,new.surname||' '||new.name||' '||new.patronymic,null,null,null,null,null)
into :id_event;
end
^

SET TERM ; ^

Update Rdb$Triggers set Rdb$Description =
''
where Rdb$Trigger_Name='PEOPLE_LOGNEW';

/* Drop Generators... */
/* Drop generator: GEN_OVER_SERVER_ID */
DELETE FROM RDB$GENERATORS WHERE RDB$GENERATOR_NAME='GEN_OVER_SERVER_ID';

/* Drop generator: GEN_PERIMETER_GATE_ID */
DELETE FROM RDB$GENERATORS WHERE RDB$GENERATOR_NAME='GEN_PERIMETER_GATE_ID';

/* Drop generator: GEN_PERIMETER_ID */
DELETE FROM RDB$GENERATORS WHERE RDB$GENERATOR_NAME='GEN_PERIMETER_ID';

/* Drop generator: GEN_TEST_ID */
DELETE FROM RDB$GENERATORS WHERE RDB$GENERATOR_NAME='GEN_TEST_ID';


/* Alter Procedure... */
/* Alter (ADD_ORG_PLACE) */
SET TERM ^ ;

ALTER PROCEDURE ADD_ORG_PLACE(DIV_CODE VARCHAR(50),
PLACENUM INTEGER)
 AS
declare variable ID_ORG integer;
declare variable ID_GARAGE integer;
begin
  select o.id_org from organization o
  where o.divcode=:div_code into :id_org;
  select hlg.id_garagename from hl_garage hlg
  where hlg.id_place=:placenum into :id_garage;
delete from hl_orgaccess hlo1 where hlo1.id_org=:id_org and hlo1.id_garage=:id_garage;
INSERT INTO HL_ORGACCESS (ID_ORG, ID_GARAGE, IS_ACTIVE) VALUES (:id_org, :id_garage, 1);
  suspend;
end
^

/* Alter (ADD_PARKING_PLACE_LINK) */
ALTER PROCEDURE ADD_PARKING_PLACE_LINK(PLACE INTEGER,
DIV VARCHAR(12),
HOUSE INTEGER)
 AS
DECLARE VARIABLE ID_GARAGE INTEGER;
DECLARE VARIABLE NAME_GARAGE VARCHAR(100) CHARACTER SET WIN1251;
begin
    name_garage= cast(:div as varchar(12))||'-'||cast(:house as varchar(5));
  select hlc.id from hl_counters hlc
  where hlc.name= :name_garage into :id_garage;
  update hl_place hlp
  set hlp.id_counters=:id_garage
  where hlp.placenumber=:place    ;
  suspend;
end
^

/* Alter (ADD_PEOPLE_WHITH_CARD_AND_TAB) */
ALTER PROCEDURE ADD_PEOPLE_WHITH_CARD_AND_TAB(CARD VARCHAR(32),
TABNUM VARCHAR(50),
NOTE VARCHAR(50),
CARDTYPE INTEGER)
 AS
DECLARE VARIABLE ID_PEP INTEGER;
begin
    select p.id_pep from people p where p.tabnum=:tabnum into :id_pep;

  /* Procedure Text */
  insert into card (ID_CARD, ID_DB, ID_PEP, TIMESTART, NOTE, STATUS, "ACTIVE", ID_CARDTYPE)
  values (
  :card, 1, :id_pep, 'now', :note,0, 1, :cardtype);
  /*
    cardtype 4 - GRZ

  */
  suspend;
end
^

/* Alter (ADD_PEOPLE_WHITH_DIV_ORG) */
ALTER PROCEDURE ADD_PEOPLE_WHITH_DIV_ORG(ID_DB INTEGER,
SURNAME VARCHAR(50),
NAME VARCHAR(50),
PATRONYMIC VARCHAR(50),
NOTE VARCHAR(250),
DIV_ORG VARCHAR(50),
TABNUM VARCHAR(50))
 AS
DECLARE VARIABLE ID_PEP INTEGER;
DECLARE VARIABLE ID_ORG INTEGER;
begin
    select o.id_org from organization o where o.divcode = :div_org into :id_org;
    INSERT INTO PEOPLE (
    ID_DB, ID_ORG, SURNAME, NAME, PATRONYMIC, WORKSTART, "ACTIVE", FLAG, NOTE, ID_AREA, TABNUM )
  VALUES (
    :ID_DB,  :ID_ORG, :SURNAME, :NAME, :PATRONYMIC, 'now', 1, 1, :NOTE, 0, :tabnum);
   suspend;
end
^

/* Alter (CARDIDX_REFRESH) */
ALTER PROCEDURE CARDIDX_REFRESH(ID_DEV INTEGER)
 AS
declare variable IS_ACTIVE integer;
declare variable IDCTRL integer;
declare variable IDREADER integer;
declare variable IDDEV integer;
declare variable IDDB integer;
declare variable CARD_DEL varchar(32);
declare variable CARD_INS varchar(32);
declare variable CARDIDX integer;
BEGIN
    /* Create procedure update 29.03.2016*/

    select d.ID_CTRL, d.ID_READER, d.ID_DB from device d where d.ID_DEV = :ID_DEV into :IDCTRL, :IDREADER, :IDDB;
    if (IDREADER is null) then begin
        for select d.ID_DEV from Device d where d.ID_CTRL = :IDCTRL and d.ID_READER is not null into :IDDEV
        do begin
            execute procedure CARDIDX_REFRESH :IDDEV ;

        end
    end

    execute procedure DEVICE_CHECKACTIVE :ID_DEV returning_values :IS_ACTIVE;
    if (:IS_ACTIVE is not null) then begin
        delete from CARDIDX where ID_DB = :IDDB AND ID_DEV = :ID_DEV;
        for select distinct c.id_card from access a
            join ss_accessuser su on su.id_accessname=a.id_accessname
             join card c on c.id_pep=su.id_pep and c."ACTIVE" <> 0 and (c.timeend is null or c.timeend > 'Now')
            where a.id_dev=:id_dev into :CARD_INS
        do begin
          /* 28.11.2017
          insert into CARDIDX (ID_DB, ID_DEV, ID_CARD) values (:IDDB, :ID_DEV, :CARD_INS);
          */
          execute procedure cardidx_insert :IDDB, :ID_DEV, :CARD_INS returning_values :CardIdx;
        end
      end

END
^

/* Alter (CARDINDEV_GETLIST) */
ALTER PROCEDURE CARDINDEV_GETLIST(IDDB INTEGER)
 RETURNS(ID_DEV INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
ID_CARD VARCHAR(32),
ID_PEP INTEGER,
DEVIDX INTEGER,
OPERATION INTEGER,
TIMEZONES INTEGER,
STATUS INTEGER,
ID_CARDINDEV INTEGER,
ATTEMPTS INTEGER)
 AS
begin
for select c.id_card, c.devidx, c.id_dev, c.operation, d.id_ctrl, d.id_reader, c.id_cardindev, c.ATTEMPTS, c.id_pep
     from CardInDev c
     join Device d  on (c.id_dev=d.id_dev) and (c.id_db=d.id_db)
     /*26.10.2017 Добавлена проверка на предмет того, что контроллер имеет тип 1 и 4 (работает с RFID и отпечатком пальца) и работает с идентификаторами вида 1 и 2 (RFID и отпечаток).
   Карты других типов АСервер не увидит */
    left join card cc on cc.id_card=c.id_card
    join device d2 on d2.id_ctrl=d.id_ctrl and (d2.id_devtype in (1,4)) and d2.id_reader is null
             join servertypelist stt on stt.id_server=d2.id_server and stt.id_type=1
    where (c.id_db=:iddb) and ( 0 <> (select IS_ACTIVE from DEVICE_CHECKACTIVE(d.id_dev)) ) and attempts < 2
/*28.10.2018 При удалении тип карты отсутствует поэтому проверка на тип карты не имеет смысла*/
   /* and (not exists (select * from cardindev where id_dev = c.id_dev and attempts > 3))     */
    order by c.id_cardindev
    into :id_card, :devidx, :id_dev, :operation, :id_ctrl, :id_reader, :id_cardindev, :attempts, :id_pep

/* Update 10.01.2015*/

do begin
   timezones=null;
   status=null;
   /* временное решение 12.04.2016. У всех будет временная зона 1 и статус 0. В дальнейшем надо будет сделать сборку временных масок  */
   timezones=1;
   status=0;

   if (operation=1) then
      begin
        select c_gp.timezones, c_gp.status
        from Card_GetParam4Dev(:iddb, :id_card) c_gp
        where (c_gp.id_dev=:id_dev)
        into :timezones, :status;

      end

    if(id_dev = 162) then
        begin
          -- id_card=INTTOHEX(cast(id_card as bigint));
           SELECT OUTPUTNUMBER FROM INTTOHEX(cast(:id_card as bigint)) into :id_card;
        end
   suspend;
   end
end
^

/* Alter (DELETE_UNKNOW_CARD) */
ALTER PROCEDURE DELETE_UNKNOW_CARD(CARD VARCHAR(32))
 AS
DECLARE VARIABLE ID_DEV INTEGER;
begin
  for
        select d.id_dev from device d
        join device d2 on d2.id_ctrl=d.id_ctrl
        where d2.id_reader is null
        and d.id_reader is not null
        and d."ACTIVE">0
        and d2."ACTIVE">0
        order by d.id_dev
     into :id_dev
    do begin
              INSERT INTO CARDINDEV (ID_DB,ID_CARD, DEVIDX, ID_PEP, ID_DEV,OPERATION,ATTEMPTS) VALUES (1,:card, 100, 0,:id_dev,2,0);

       end
end
^

SET TERM ; ^

Update Rdb$Procedures set Rdb$Description =
'Процедура выставляет указаннй код карты в таблицу cardindev для удаления из всех контроллеров.
29.05.2019 Бухаров.'
where Rdb$Procedure_Name='DELETE_UNKNOW_CARD';

/* Alter (DEVICEEVENTS_INSERT) */
SET TERM ^ ;

ALTER PROCEDURE DEVICEEVENTS_INSERT(ID_DB INTEGER,
ID_EVENTTYPE INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
NOTE VARCHAR(100),
"TIME" TIMESTAMP,
ID_VIDEO INTEGER,
ID_USER INTEGER,
ESS1 INTEGER,
ESS2 INTEGER,
IDSOURCE INTEGER,
IDSERVERTS INTEGER)
 RETURNS(ID_EVENT INTEGER)
 AS
DECLARE VARIABLE ID_DEV INTEGER;
DECLARE VARIABLE ID_PLAN INTEGER;
DECLARE VARIABLE FLAGCARD INTEGER;
DECLARE VARIABLE ID_ORG INTEGER;
DECLARE VARIABLE ID_PEP INTEGER;
DECLARE VARIABLE FLAG INTEGER;
DECLARE VARIABLE SURNAME VARCHAR(50);
DECLARE VARIABLE NAME BLOB SUB_TYPE 0 SEGMENT SIZE 80;
DECLARE VARIABLE PATRONYMIC VARCHAR(50);
DECLARE VARIABLE ID_CARD VARCHAR(32);
DECLARE VARIABLE GUESTNOTE VARCHAR(250);
DECLARE VARIABLE FULLNAME VARCHAR(410);
DECLARE VARIABLE SOUND BLOB SUB_TYPE 0 SEGMENT SIZE 80;
DECLARE VARIABLE DOOR_NAME VARCHAR(50);
DECLARE VARIABLE SERVER_NAME VARCHAR(50);
DECLARE VARIABLE ANALIT INTEGER;
DECLARE VARIABLE S_BEFOR TIMESTAMP;
DECLARE VARIABLE S_END TIMESTAMP;
DECLARE VARIABLE EXEC_TIME DOUBLE PRECISION;
Begin
s_befor='now';

select ID_DEV from device where  (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)  INTO :ID_DEV;
--INSERT INTO EVENTS2 (ID_DB,ID_DEV, ID_EVENTTYPE,ID_OBJECT,DATETIME,NOTE,DEVTIME,ID_SERVER,ID_EVENTTS) VALUES (1,:ID_DEV,:ID_EVENTTYPE,1,'NOW',:NOTE,:"TIME",:IDSOURCE,:IDSERVERTS);

IF (:id_eventtype IN (49,53,54,57,58, 90, 91)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else IF (:id_eventtype IN (46,47,48,50, 145, 81)) THEN
    BEGIN
      SELECT MAX(ID_DEV) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV;

      fullname = ' <'||coalesce(:note, '')||'>';
      id_pep = :ess1;                            
      id_org = :ess2;
      select C.FLAG, c.ID_PEP from card c where (c.ID_CARD STARTING WITH :NOTE)
      into :FLAGCARD, :id_pep;

      SELECT P.ID_ORG, P.SOUND, P.ID_PLAN, P.FLAG, P.note
        , coalesce(' '||p.surname,'')||coalesce(' '||p.name,'')||coalesce(' '||p.patronymic,'')
      FROM PEOPLE P where (P.ID_PEP = :id_pep)
      INTO :id_org, :sound, :id_plan, :flag, :GUESTNOTE
        , :fullname
      ;

      if ((:id_eventtype=46) and (coalesce(:id_pep, 0)<>0)) then
         id_eventtype=65;

         if ((:id_eventtype=50) and (:id_pep is null)) then
         id_eventtype=80;

      if ((:id_eventtype=50) and (bitAnd(:flagcard,1)<>0) ) then

         fullname = fullname||coalesce(' в '||:GUESTNOTE, '');



      if ((:id_eventtype=145) and (bitAnd(:flagcard,1)<>0) ) then
         fullname = fullname||coalesce(' в '||:GUESTNOTE, '');

       if((:id_eventtype=50) or (:id_eventtype=47) or (:id_eventtype=65)) then
        execute procedure event_analit :id_db, :ID_DEV, :ID_PEP, :id_eventtype returning_values :analit;

      fullname = substring(:fullname from 2 for 100) || ',';
       s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ID_CARD, ANALIT,  ESS1, ESS2, NOTE, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :NOTE, :analit ,:ID_PEP, :ID_ORG, :fullname, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else  IF (:id_eventtype IN (51,52,55,56)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      s_end= 'now';
        exec_time=s_end-s_befor;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ESS1, IDSOURCE, IDSERVERTS, EXEC_TIME)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :ESS1, :IDSOURCE, :IDSERVERTS,:exec_time);
    END

else begin   /*Неизвестное событие*/
        fullname='';
        door_name='no_device_name';
        server_name='no_server_name';
        select d.name, s.name from device d
        join server s on d.id_server=s.id_server
        where d.id_ctrl=:id_ctrl  and d.id_reader is null
         into :door_name, :server_name ;
        if(:id_ctrl is null) then fullname = 'no id_ctrl';
        if(:id_reader is null) then fullname = fullname||' no id_reader';
        fullname=fullname||', idserverts='||:idserverts;
        s_end= 'now';
        exec_time=s_end-s_befor;
        INSERT INTO events (ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, IDSOURCE, IDSERVERTS, NOTE, EXEC_TIME)
        VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :IDSOURCE, :IDSERVERTS,
        'Device event='||:note,:exec_time );
        /*
        ||', device="'||:door_name
        ||'", server="'||:server_name
        ||'", '||:fullname );
        */

    end

select distinct gen_id(gen_event_id,0)
    from RDB$DATABASE
    into :id_event;
    suspend;

post_event('EventMonitor');   
if ((:id_eventtype=50) and (:sound is not null)) then post_event('EventSound');
if ((:id_eventtype=50) and (bitAND(:flag,128)<>0)) then post_event('EventSpeak');


end
^

/* Alter (EVENT_ANALIT) */
ALTER PROCEDURE EVENT_ANALIT(ID_DB INTEGER,
ID_DEV INTEGER,
ID_PEP INTEGER,
ID_EVENTTYPE INTEGER)
 RETURNS(ANALIT_CODE INTEGER)
 AS
DECLARE VARIABLE SINGLE_LIST INTEGER;
DECLARE VARIABLE CARD VARCHAR(32);
DECLARE VARIABLE CARD_FOR_DELETE INTEGER;
DECLARE VARIABLE CARD_FOR_LOAD INTEGER;
DECLARE VARIABLE CARD_IS_ACTIVE INTEGER;
DECLARE VARIABLE PEOPLE_IS_ACTIVE INTEGER;
DECLARE VARIABLE PASS_IS_VALIDE INTEGER;
begin

   people_is_active=0;
   card_is_active=0;
   pass_is_valide=0;
   analit_code=-1;
   card_for_delete=0;
   card_for_load=0;
   select p."ACTIVE" from people p where p.id_pep=:id_pep and p.id_db=1 into :people_is_active;
   select c.id_card, c."ACTIVE" from card c where c.id_pep=:id_pep and c.id_db=1 into :card,  :card_is_active;

   if (exists (
                select * from ss_accessuser ssa
                    join access ac on ssa.id_accessname=ac.id_accessname and ac.id_db=ssa.id_db
                    where ssa.id_pep=:id_pep and ac.id_dev=:id_dev and ssa.id_db=1
                )) then
                /*Пользователь может ходить через эту точку прохода*/
                          pass_is_valide=1;
                         else
                /*Пользователь НЕ имеет право прохода через эту точку прохода.';  */
                        pass_is_valide=0;
     /*Анализ возможных комбинаций*/
    if(:id_eventtype = 50) then  /*Анализ для события 50 - действительная карта*/
        begin
           /*Проверяю наличие карты в очереди на удаление*/
           if(exists(select * from cardindev cd where cd.id_dev=:id_dev and cd.id_card=:card and cd.operation=2 and cd.id_db=1)) then card_for_delete=1;
           /*Проверяю наличие метки Единый список у обрабатывающего контроллера*/
           select BITAND(d2.flag, 1) from device d
               join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
               where d.id_dev=:id_dev into :single_list;
               /*Если есть метка единого списка, то надо проверить возможность прохода через канал 1.*/
           /*Выполняю проверку всех возможных комбинаций*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=500;  /*Ошибка! Карта не должна ходить! Пользователь и карты не активны, проход запрещен.*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=501;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=502;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 1) ) then analit_code=503;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=504;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=505;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=506;  /*Ошибка! Карта не должна ходить!*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=0)) then analit_code=507; /*Ошибки нет, проход разрешен*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1) and (:single_list=0)) then analit_code=508; /*Ошибка СКУД. Такой комбинации быть не должно*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1) and (:single_list=1)) then analit_code=509; /*Ошибки нет, проход разрешена. Стоит метка Единый список*/
         /*14.03.2020 Добавлена обработка ситуаций, когда карат стоит в очереди на удаление*/
            if(:analit_code = 500 and :card_for_delete = 1) then analit_code=5001; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 501 and :card_for_delete = 1) then analit_code=5011; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 502 and :card_for_delete = 1) then analit_code=5021; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 503 and :card_for_delete = 1) then analit_code=5031; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 504 and :card_for_delete = 1) then analit_code=5041; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 505 and :card_for_delete = 1) then analit_code=5051; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/
            if(:analit_code = 506 and :card_for_delete = 1) then analit_code=5061; /*14.03.2020 Переходной процесс. Карта стоит в очереди на удаление*/


         end

     if(:id_eventtype = 65) then  /*Анализ для осбытия 65 - недействительная карта*/
        begin
           if(exists(select * from cardindev cd where cd.id_dev=:id_dev and cd.id_card=:card and cd.operation=1 and cd.id_db=1)) then card_for_load=1;
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=650;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=651;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=652;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 0) and (:card_is_active = 1) and (:pass_is_valide = 1) ) then analit_code=653;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 0) ) then analit_code=654;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 0) and (:pass_is_valide = 1) ) then analit_code=655;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 0) ) then analit_code=656;   /*Отказ в проходе правильный.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=0)) then analit_code=657;  /*Ошибка! Карта должна ходить.*/
           if((:people_is_active = 1) and (:card_is_active = 1) and (:pass_is_valide = 1) and (:card_for_delete=1)) then analit_code=658;  /*Переходное состояние! Надо дождаться загузки карты в контроллер.*/


        end

end
^

/* Alter (EVENTS_GETLISTCOUNT) */
ALTER PROCEDURE EVENTS_GETLISTCOUNT(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
begin
    for select
        ID_EVENT ,
        DATETIME ,
        ID_EVENTTYPE ,
        EVENTNAME ,
        ID_DEV ,
        DEVICENAME ,
        ID_PEP ,
        NOTE ,
        ORGNAME ,
        ID_PLAN ,
        PLANNAME ,
        ID_VIDEO ,
        ID_CARD ,
        ESS1 ,
        ESS2
    from events_getlistfromid (:id_db, :id_pep_cur, null, :eventcount) into
        :ID_EVENT ,
        :DATETIME ,
        :ID_EVENTTYPE ,
        :EVENTNAME ,
        :ID_DEV ,
        :DEVICENAME ,
        :ID_PEP ,
        :NOTE ,
        :ORGNAME ,
        :ID_PLAN ,
        :PLANNAME ,
        :ID_VIDEO ,
        :ID_CARD ,
        :ESS1 ,
        :ESS2
    do begin
        suspend;
    end

END
^

/* Alter (EVENTS_GETLISTFROMID) */
ALTER PROCEDURE EVENTS_GETLISTFROMID(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
ID_EVENTFROM INTEGER,
EVENTCOUNT INTEGER)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
declare variable ID_EVENTTO integer;
declare variable ID_DEVGROUP integer;
declare variable ID_ORGGROUP integer;
declare variable DEV_ACCESS integer;
declare variable ORG_ACCESS integer;
declare variable CARD_TIME_END timestamp;
declare variable M_MONTH smallint;
declare variable P_MONTH varchar(12);
BEGIN
/* ID_EVENTTO - exclusive or null, ID_EVENTFROM - inclusive not null */
    /**/
    ID_EVENTTO = null;

     if(:id_eventfrom=1) then begin
         select gen_id(gen_event_id,0) from RDB$database into :ID_EVENTFROM;
      end

    if (:eventcount is not null) then begin
        if (:ID_EVENTFROM is null) then begin
            select distinct gen_id(gen_event_id,0) from RDB$DATABASE into :ID_EVENTFROM;
            ID_EVENTFROM = :ID_EVENTFROM - :eventcount + 1;
        end else begin
            ID_EVENTTO = :ID_EVENTFROM + :eventcount;
        end
    end
    if (:ID_EVENTFROM < 0) then ID_EVENTFROM = 0;
    /**/
    if (:ID_EVENTFROM is not null) then begin
        /* execute */

        /* Check for users groups */
        select max(p.id_devgroup), max(p.id_orgctrl)
        from people p
        where (p.id_db=:id_db) and (p.id_pep=:id_pep_cur)
        into :id_devgroup, :id_orggroup;
        /* if devgroupuser is empty then fill it for id_pep_cur child device */ 
        if (not exists (
            select id_devgroup from devgroupuser dgu
            where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur))) then
            begin
                insert into devgroupuser(id_pep, id_db, id_devgroup, id_dev,name)
                select :id_pep_cur, :id_db, id_devgroup, id_dev, name
                from devgroup_getchild(:id_db,:id_devgroup)
                where id_dev is not null;
            end
        
        /* if organizationuser is empty then fill it for id_pep_cur child people */
        if (not exists (
            select id_org from organizationuser ou
            where (ou.id_db=:id_db) and (ou.id_pep=:id_pep_cur))) then
            begin
                insert into organizationuser(id_pep, id_db, id_org, name)
                select :id_pep_cur, :id_db, id_org, name
                from organization_getchild(:id_db,:id_orggroup)
                where id_org is not null;
            end
        /* End Check for users groups */

        FOR SELECT distinct e.id_event, e.datetime, e.id_eventtype, et.name, e.id_dev, d.name,
            e.id_pep, e.note, e.id_plan, pl.name, e.id_video,
            e.id_card, e.ess1, e.ess2
        FROM (EVENTS e JOIN EVENTUSER eu ON (e.id_eventtype=eu.id_eventtype) and (eu.id_db=:id_db) and (eu.id_pep=:id_pep_cur) and (eu."ACTIVE"=1))
            LEFT JOIN EVENTTYPE et ON (e.id_eventtype=et.id_eventtype) and (e.id_db=:id_db)
            LEFT JOIN DEVICE d ON (e.id_dev=d.id_dev) and (e.id_db=:id_db)
            LEFT JOIN PLANS pl ON (d.id_plan=pl.id_plan) and (pl.id_db=:id_db)
            INNER JOIN devgroupuser dg2 on (dg2.id_pep=:id_pep_cur) and (dg2.id_dev=e.id_dev) and (dg2.id_db=:id_db)

        /* ID_EVENTTO - exclusive or null, ID_EVENTFROM - inclusive not null */
        WHERE (e.id_event >= :ID_EVENTFROM)
            and ( (:ID_EVENTTO is null) or (e.id_event < :ID_EVENTTO) )

        INTO :id_event, :datetime, :id_eventtype, :eventname, :id_dev, :devicename,
            :id_pep, :note, :id_plan, :planname, :id_video, :id_card, :ess1, :ess2
        DO BEGIN
            
            if (:id_eventtype in (1,2,5,6,7,8) ) then begin
                devicename = :note;
                note = '';
            end

                /* Check for user access */
            DEV_ACCESS = 1;
            /*
            if (
                ( :id_dev is not null )
                and ( not exists (
                    select dgu.id_devgroup from devgroupuser dgu 
                    where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur) and (dgu.id_dev=:id_dev)
                ))and ( not exists (
                    select dgu.id_devgroup from devgroupuser dgu   
                        join DEVICE_PARENTGROUPS dgp on ((dgu.id_devgroup=dgp.id_parent) and (dgp.id_dev = :id_dev ))
                    where (dgu.id_db=:id_db) and (dgu.id_pep=:id_pep_cur)
                ))
            ) then DEV_ACCESS = 0;
            */
            ORG_ACCESS = 1;
            /* ess1 - id_pep of event */
            /* ess2 - id_org of event */
            /**/
            if ( :id_eventtype in (47,48,50,65, 81) ) then begin
               /* */if ( (:ess2 is null)
                    or (
                        (not exists (
                            select ou.id_org from organizationuser ou
                            where (ou.id_db = :id_db) and (ou.id_pep = :id_pep_cur) and (ou.id_org = :ess2)
                        )) and (not exists (
                            select ou.id_org from organizationuser ou
                                join ORGANIZATION_PARENTS op on ( (ou.id_org = op.id_parent) and (op.id_org = :ess2) )
                            where (ou.id_db = :id_db) and (ou.id_pep = :id_pep_cur)
                        ))
                    )
                ) then begin
                    ORG_ACCESS = 0;
                end else begin
                    select name from organization where id_org = :ess2 into :orgname;
                    if (:orgname <> '') then note=:note||' ['||:orgname||'] ('||:id_card||')';
                end

              select c.timeend from card c where c.id_pep=:ess1 and c.id_db = :id_db into :card_time_end;
              if (:card_time_end is  null) then begin
                    note=:note||', Срок действия карты не указан.';

               end else begin
                     m_month = extract(month from :card_time_end);
                     if(m_month < 10) then begin
                            p_month= '0'||cast(:m_month AS varchar(12));
                            end else begin
                            p_month = cast(:m_month AS varchar(12));
                            end

                     note=:note||', карта действительна до '
                     ||extract(day from :card_time_end)||'.'
                     ||:p_month||'.'
                     ||extract(year from :card_time_end);
                end
            end
            /*6.03.2018 restore analitic*/
            if ( :id_eventtype in (65) ) then begin
                note=:note||'analit:';
                if (exists (
                select * from ss_accessuser ssa
                    join access ac on ssa.id_accessname=ac.id_accessname and ac.id_db=ssa.id_db
                    where ssa.id_pep=:id_pep and ac.id_dev=:id_dev and ssa.id_db=1
                )) then
                note=:note||'Должна ходить!'; /*||' Возможно ошибка работы системы. Пользователь имеет право прохода через эту точку прохода. Обратитесь к администратору СКУД.';
                 */
                else
                /*note=:note||' Пользователь НЕ имеет право прохода через эту точку прохода.';  */
                note=:note||' Прохода нет!.';

                
            end
            /*end 6.03.2018*/
            /* End Check for user access */
/*6 декаря 2017 г. Обработка события 80 Проход незарегистрированного идентификатора  */
            if ( :id_eventtype in (80) ) then begin

                note=:note||' Проход незарегистрированного идентификатора.';
                
            end

            if ((:org_access=1) and (:dev_access=1)) then suspend;
        END

    end
END
^

/* Alter (EVENTS_GETLISTTIME) */
ALTER PROCEDURE EVENTS_GETLISTTIME(ID_DB INTEGER,
ID_PEP_CUR INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_EVENT INTEGER,
DATETIME TIMESTAMP,
ID_EVENTTYPE INTEGER,
EVENTNAME VARCHAR(100),
ID_DEV INTEGER,
DEVICENAME VARCHAR(50),
ID_PEP INTEGER,
NOTE VARCHAR(152),
ORGNAME VARCHAR(50),
ID_PLAN INTEGER,
PLANNAME VARCHAR(100),
ID_VIDEO INTEGER,
ID_CARD VARCHAR(32),
ESS1 INTEGER,
ESS2 INTEGER)
 AS
DECLARE VARIABLE ID_EVENT_START INTEGER;
DECLARE VARIABLE EVENTCOUNT INTEGER;
begin

select min(id_event), max(id_event) - min(id_event) + 1
from events where (datetime >= :timestart) and (datetime <= :timeend)
into :id_event_start, :eventcount ;

for select
        ID_EVENT ,
        DATETIME ,
        ID_EVENTTYPE ,
        EVENTNAME ,
        ID_DEV ,
        DEVICENAME ,
        ID_PEP ,
        NOTE ,
        ORGNAME ,
        ID_PLAN ,
        PLANNAME ,
        ID_VIDEO ,
        ID_CARD ,
        ESS1 ,
        ESS2
    from events_getlistfromid (:id_db, :id_pep_cur, :id_event_start, :eventcount)
    where (datetime>=:timestart) and (datetime<=:timeend)
    into
        :ID_EVENT ,
        :DATETIME ,
        :ID_EVENTTYPE ,
        :EVENTNAME ,
        :ID_DEV ,
        :DEVICENAME ,
        :ID_PEP ,
        :NOTE ,
        :ORGNAME ,
        :ID_PLAN ,
        :PLANNAME ,
        :ID_VIDEO ,
        :ID_CARD ,
        :ESS1 ,
        :ESS2
    do begin
        suspend;
    end
END
^

/* Alter (EVENTS_INSERT) */
ALTER PROCEDURE EVENTS_INSERT(ID_DB INTEGER,
ID_EVENTTYPE INTEGER,
ID_CTRL INTEGER,
ID_READER INTEGER,
NOTE VARCHAR(100),
"TIME" TIMESTAMP,
ID_VIDEO INTEGER,
ID_USER INTEGER,
ESS1 INTEGER,
ESS2 INTEGER)
 RETURNS(ID_EVENT INTEGER)
 AS
declare variable ID_DEV integer;
declare variable ID_PEP integer;
declare variable ID_PLAN integer;
declare variable ID_CARD varchar(32);
declare variable SURNAME varchar(50);
declare variable NAME varchar(50);
declare variable PATRONYMIC varchar(50);
declare variable FULLNAME varchar(152);
declare variable ID_ORG integer;
declare variable SOUND blob sub_type 0 segment size 80;
declare variable FLAG integer;
declare variable FLAGCARD integer;
declare variable GUESTNOTE varchar(250);
begin
 if (not exists (select * from
     eventtype et where (et.id_db=:id_db) and (et.id_eventtype=:id_eventtype)
     )) then begin
     id_event = 0;
     suspend;
     exit;
     end
 if (:id_eventtype in (0)) then
    begin
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end
 if (:id_eventtype in (17,18,19)) then
    begin
      SELECT P.SURNAME, P.NAME, P.PATRONYMIC
      FROM PEOPLE P
      WHERE (P.ID_PEP=:ess1) and (p.id_db=:id_db)
      INTO :surname, :name, :patronymic;
      note='<'||:note||'>';
      if (:surname is not null) then note=:note||' '||:surname;
      if (:name is not null) then note=:note||' '||:name;
      if (:patronymic is not null) then note=:note||' '||:patronymic;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end


 if (:id_eventtype in (9,10,11,12,13,14,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43)) then
    begin
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_PEP, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', 1, :NOTE);
    end
 IF (:id_eventtype IN (1,2)) THEN
    BEGIN
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :NOTE);
    END
 IF (:id_eventtype IN (5,6,7,8)) THEN
    BEGIN
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ESS1, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ESS1, :NOTE);
    END

/* Update 6.09.2007 
 IF (:id_eventtype IN (15,16,49,53,54,57,58)) THEN
*/
 IF (:id_eventtype IN (15,16)) THEN
/* Update 6.09.2007 */

    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN);
    END
 IF (:id_eventtype IN (44,45)) THEN
    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ID_DEV, :ID_PLAN, :NOTE);
    END


 IF (:id_eventtype IN (59,60)) THEN
/* Update 6.09.2007 */

    BEGIN
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ESS1)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :ESS1);
    END

 if (:id_eventtype IN (61,62,66)) then
    begin
      SELECT MAX(ID_DEV), MAX(ID_PLAN) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:ID_READER)
      INTO :ID_DEV, :ID_PLAN;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN)
      VALUES (:ID_DB, :ID_EVENTTYPE, 'NOW', :ID_DEV, :ID_PLAN);
    End

 if (:id_eventtype IN (63,64)) then
    begin
      SELECT MAX(ID_DEV) FROM DEVICE WHERE (ID_DB=:ID_DB) AND (ID_CTRL=:ID_CTRL) AND (ID_READER=:id_reader)
      INTO :ID_DEV;
      SELECT C.ID_CARD, C.FLAG, P.ID_ORG, P.ID_PEP, P.SURNAME, P.NAME, P.PATRONYMIC, P.SOUND, P.ID_PLAN, P.FLAG, P.note
      FROM PEOPLE P
           INNER JOIN CARD C ON (ID_CARD STARTING WITH :NOTE) AND (P.ID_DB = C.ID_DB) AND (P.ID_PEP = C.ID_PEP)
      INTO :id_card, :FLAGCARD, :id_org, :id_pep, :surname, :name, :patronymic, :sound, :id_plan, :flag, :GUESTNOTE;

      IF (:surname IS NULL) THEN surname='';
      IF (:name IS NULL) THEN name='';
      IF (:patronymic IS NULL) THEN patronymic='';
      if (:id_pep is not null) then
         begin
           fullname = :surname||' '||:name||' '||:patronymic;
         end else fullname = '<'||:note||'> '||:surname||' '||:name||' '||:patronymic;
      INSERT INTO EVENTS(ID_DB, ID_EVENTTYPE, DATETIME, ID_DEV, ID_PLAN, ID_CARD, ESS1, ESS2, NOTE)
      VALUES (:ID_DB, :ID_EVENTTYPE, :"TIME", :ID_DEV, :ID_PLAN, :NOTE, :ID_PEP, :ID_ORG, :fullname);
    end
select distinct gen_id(gen_event_id,0)
from RDB$DATABASE
into :id_event;
suspend;
/* 2018
post_event('EventMonitor');
*/

/* Update 6.09.2007
if ((:id_eventtype=50) and (:sound is not null)) then post_event('EventSound');
if ((:id_eventtype=50) and (bitAND(:flag,128)<>0)) then post_event('EventSpeak');
*/

END
^

/* Alter (HL_UPDATE_GARAGE_NAME) */
ALTER PROCEDURE HL_UPDATE_GARAGE_NAME(PLACENUM INTEGER,
NAME_GARAGE VARCHAR(250))
 AS
declare variable ID_GARAGENAME integer;
/*
  процедура обновляет название гаража, используя номер машиноместа и новое название
*/

begin
  select hlg.id_garagename from hl_garage hlg where hlg.id_place=:placenum into :id_garagename;
  update hl_garagename hlgn set hlgn.name=:name_garage
  where hlgn.id=:id_garagename;
  suspend;
end
^

/* Alter (INTTOHEX) */
ALTER PROCEDURE INTTOHEX(INPUTNUMBER BIGINT)
 RETURNS(OUTPUTNUMBER VARCHAR(8))
 AS
DECLARE VARIABLE Q BIGINT;
DECLARE VARIABLE R BIGINT;
DECLARE VARIABLE T BIGINT;
DECLARE VARIABLE H VARCHAR(1);
DECLARE VARIABLE S VARCHAR(6);
begin
  /* Max input value allowed is: 4294967295 */

  S = 'ABCDEF';

  Q = 1;
  OUTPUTNUMBER = '';
  T = INPUTNUMBER;
  WHILE (Q <> 0) DO
  BEGIN

    Q = T / 16;
    R = MOD(T, 16);
    T = Q;

    IF (R > 9) THEN

    -- H = SUBSTRING(S FROM R-9 FOR 1);
     H = SUBST2(S, R-9, R-9);

    ELSE
     H = R;

    OUTPUTNUMBER = H || OUTPUTNUMBER ;

  END


  SUSPEND;
end
^

/* Alter (ORGANIZATION_GETPARENT) */
ALTER PROCEDURE ORGANIZATION_GETPARENT(ID_DB INTEGER,
IDPARENT INTEGER)
 RETURNS(ID_ORG INTEGER,
NAME VARCHAR(50),
ID_PARENT INTEGER,
FLAG INTEGER)
 AS
begin
  id_org = 0;
  id_parent   = :idparent;

  WHILE (:id_org <> :id_parent) DO
    BEGIN 
      SELECT o.id_org, o.name, o.id_parent, o.flag
      FROM organization o
      WHERE (o.id_org=:id_parent) AND (o.id_db=:id_db)
      INTO :id_org, :name, :id_parent, :flag;
      SUSPEND;
    END
END
^

/* Alter (REGISTERPASS) */
ALTER PROCEDURE REGISTERPASS(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(RC INTEGER)
 AS
declare variable ID_PEP integer;
declare variable ID_EVENT integer;
declare variable ID_CTRL integer;
declare variable ID_READER integer;

declare variable ISENTER integer;
declare variable ID_ORG integer;
declare variable tmp integer;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
  select id_ctrl, id_reader from device where id_dev = :id_dev into :id_ctrl, :id_reader;
  execute procedure ValidatePass :id_dev, :id_card, :grz returning_values :RC, :id_pep;

  IF (:rc = 50) THEN BEGIN
    -- и начинаем корректировать список а/м на территории
    SELECT is_enter FROM carcount_gates WHERE id_dev = :id_dev INTO :isenter;
    -- если точка прохода есть КПП, то запросим ид организации проезжающего
    IF (:isenter IS NOT NULL) THEN
      SELECT ID_ORG FROM People WHERE ID_PEP = :id_pep INTO :id_org;

    IF (:ISENTER = 0) THEN BEGIN    /* это выезд */
      -- если ГРЗ указан, то посмотрим, имеется ли он в списке а/м на территории
      IF (:grz IS NOT NULL) THEN
          select count(*) from CARCOUNT_INSIDE where GRZ=:grz INTO :tmp;

      -- если есть на территории
      IF ((:grz IS NOT NULL) AND (:tmp > 0)) THEN BEGIN
        -- то удалим его из этого списка
        DELETE FROM CARCOUNT_INSIDE WHERE GRZ = :GRZ;
      END ELSE BEGIN
        -- ГРЗ не распознан
        -- ГРЗ распознан, но его нет на территории, возможно он был не распознан на въезде

        -- есть ли на территории а/м без ГРЗ, въехавшие по той же карте?
        select min(ID_CARCOUNT_INSIDE) 
            FROM CARCOUNT_INSIDE 
            WHERE ID_CARD = :id_card AND GRZ IS NULL 
            INTO :tmp;
        -- если таких нет, то смотрим, есть ли на территории а/м без ГРЗ той же организации
        IF (:tmp IS NULL) THEN
            select min(ID_CARCOUNT_INSIDE) 
                FROM CARCOUNT_INSIDE cci INNER JOIN People p ON cci.ID_PEP = p.ID_PEP
                WHERE p.ID_ORG = :id_org AND cci.GRZ IS NULL 
                INTO :tmp;
        -- если нашлась подходящая запись без распознанного ГРЗ, то удалим ее
        if (:tmp IS NOT NULL) THEN
            DELETE FROM CARCOUNT_INSIDE WHERE ID_CARCOUNT_INSIDE = :tmp;
      END
    END

    IF (:ISENTER <> 0) THEN BEGIN    /* это въезд */
      -- удалим, если они имеются, записи о нахождении внутри а/м с данным ГРЗ
      IF (:grz IS NOT NULL) THEN
          DELETE FROM CARCOUNT_INSIDE WHERE GRZ = :GRZ;
      -- добавим запись о нахождении на территории
      INSERT INTO CARCOUNT_INSIDE (ID_PEP, ID_CARD, GRZ, ENTERDEV) VALUES (:id_pep, :id_card, :grz, :id_dev);
    END
  END

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
  execute procedure events_insert(1, :rc, :id_ctrl, :id_reader, :id_card, 'now', null, null, :id_pep, null)
        returning_values :id_event;

  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  IF (:RC = 50) THEN RC = 0;
end
^

/* Alter (REGISTERPASS_HL) */
ALTER PROCEDURE REGISTERPASS_HL(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
DECLARE VARIABLE ID_ORG INTEGER;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
  rc=-1;
  -- определяю ID_ORG для ess2
  select p.id_org from card c
  join people p on p.id_pep=c.id_pep
  where c.id_card=:id_card into :id_org    ;

  --выполняю валидацию ГРЗ
  execute procedure validatepass_hl_parking :id_dev, :id_card, :grz returning_values :RC, :id_pep;

 -- фиксирую обращене к валидации
    INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
    VALUES (13, :id_card, :id_dev);

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
VALUES (1, :rc, :id_dev,  'now', :id_card, :id_card, 1, :id_pep, :id_org);

-- фиксирую результат валидации
 INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, ID_PEP)
    VALUES (:rc, :id_card, :id_dev, :id_pep);



  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end
^

/* Alter (REGISTERPASS_HL_2) */
ALTER PROCEDURE REGISTERPASS_HL_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(ID_PEP INTEGER,
RC INTEGER)
 AS
declare variable ID_ORG integer;
begin
  -- процедура регистрирует попытку прохода по идентификатору
  -- результат записывается в журнал событий
  -- возвращает 0 про успешном проходе или
  -- код события, который будет записан в журнал событий при отказе

  if (:grz = '') then grz = null;
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
VALUES (1, :rc, :id_dev,  'now', :id_card, :id_card, 1, :id_pep, :id_org);

  -- при успешном прохода возращаем ноль, а не 50, чтобы драйвер не путался, какой код успеха, а какой нет
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end
^

/* Alter (REPORT_GETEVENTLIST) */
ALTER PROCEDURE REPORT_GETEVENTLIST(IDDB INTEGER,
IDPEP INTEGER,
TIMESTART TIMESTAMP,
TIMEEND TIMESTAMP)
 RETURNS(ID_PEP INTEGER,
ID_DEV INTEGER,
ID_EVENTTYPE INTEGER,
DATETIME TIMESTAMP,
EVENTNAME VARCHAR(50),
SOURCE VARCHAR(50),
NOTE VARCHAR(152))
 AS
begin
for select e_glt.ess1, e_glt.id_dev, e_glt.id_eventtype, e_glt.datetime,
           e_glt.eventname, e_glt.devicename, e_glt.note
    from events_getlisttime(:iddb,:idpep,:timestart,:timeend) e_glt
    into :id_pep, :id_dev, :id_eventtype, :datetime, :eventname, :source, :note
    do begin
       if ((id_eventtype<>50) and (id_eventtype<>65) and (id_eventtype<>80)) then id_pep=null;
       suspend;
    end
END
^

/* Alter (VALIDATEPASS) */
ALTER PROCEDURE VALIDATEPASS(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer;
declare variable TIMESTART timestamp;
declare variable TIMEEND timestamp;
declare variable ID_ORG integer;
declare variable ISENTER integer;
declare variable CURRENTCOUNT integer;
declare variable MAXCOUNT integer;
declare variable DEFAULT_LIMIT integer = 100000;
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
begin
  -- процедура выполняет проверку возможности прохода данного идентификатора
  -- через данную точку прохода
  -- результат выполнения: код события, который должен быть записан и
  -- идентификатор пользователя, если он был найден

  -- получаем данные идентификатора
  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
    event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;
  end else begin
    -- запрашиваем признак активности для сотрудника
    -- и код группы, который будет использован позже,
    -- при проверке количества автомобилей
    select "ACTIVE", id_org from people where id_pep = :id_pep into :is_active, :id_org;
    -- проверяем его
    if (:is_active <> 1) then begin
      event_type = :RC_DISABLEDUSER;
    end else if (not exists (select * from ss_accessuser au
                         join access on au.id_accessname = access.id_accessname
                         where au.id_pep = :id_pep and access.id_dev = :id_dev)) then begin
      event_type = :RC_ACCESSDENIED;
    end else begin
      -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход. для прочих точек прохода будет null
      SELECT is_enter FROM carcount_gates WHERE id_dev = :id_dev INTO :isenter;
      -- если это въезд
      if (:isenter <> 0) then begin
        -- получим максимальное количество машин
        SELECT carcount_limit."COUNT"
            FROM carcount_limit
            WHERE carcount_limit.id_org = :id_org
            INTO :maxcount;

        -- если явно лимит не назначен, то действует лимит по умолчанию
        if (:maxcount is null) then maxcount = :DEFAULT_LIMIT;

        -- если лимит есть 
        if (:maxcount > 0) then begin
          -- посчитаем количество а/м на территории
          SELECT Count(*) FROM carcount_inside
            inner join people on carcount_inside.id_pep = people.id_pep
            WHERE people.id_org = :id_org
            into :currentcount;

          -- если есть машины с тем же ГРЗ для этой организации, то уменьшим на единицу текущее количество
          -- поскольку при въезде прежняя запись о нахождении а/м на территории будет удалена
          if ((:GRZ IS NOT NULL) and
              (EXISTS (
                  SELECT * FROM CARCOUNT_INSIDE cci INNER JOIN People p ON cci.ID_PEP = p.ID_PEP
                    WHERE p.ID_ORG = :id_org AND cci.GRZ = :GRZ
              ))) then currentcount = :currentcount - 1;
        end

        -- проверим, достигнуто ли максимальное количество
        -- если одно из них или оба null, то результат будет false
        if (:currentcount < :maxcount) then begin
          -- все хорошо
          event_type = :RC_OK;
        end else begin
          -- превышен лимит количества а/м
          event_type = :RC_CARLIMITEXCEEDED;
        end
      end else begin    -- если это не въезд, то есть выезд или вообще не КПП, то проверка завершена, выезжать/выходить можно всем
        event_type = :RC_OK;
      end
    end
  end
end
^

/* Alter (VALIDATEPASS_HL_PARKING) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю id гаража и количество машиномест в гараже
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;
  end else begin
    -- запрашиваем признак активности для сотрудника
    -- и код группы, который будет использован позже,
    -- при проверке количества автомобилей
    select "ACTIVE", id_org from people where id_pep = :id_pep into :is_active, :id_org;
    -- проверяем его
    if (:is_active <> 1) then begin
      event_type = :RC_DISABLEDUSER;
    end else if (not exists (select * from ss_accessuser au
                         join access on au.id_accessname = access.id_accessname
                         where au.id_pep = :id_pep and access.id_dev = :id_dev)) then begin
      event_type = :RC_ACCESSDENIED;
    end else begin



      -- если нет парковки
      if(:id_parking is not null) then begin
          -- если это въезд
          if (:is_enter <> 0) then begin
    
            -- если нет гаража, то количество машиномест NULL
            IF (:cntcount IS NULL) THEN BEGIN
              event_type = :rc_accessdenied;
            END ELSE BEGIN
              --  а если есть гаража, то количество мест не NULL, и тогда считаю сколько машин уже стоит в гараже

              select count(*) from hl_inside hli
                join card c on c.id_card=hli.id_card
                join people p on p.id_pep=c.id_pep
                join hl_orgaccess hlo on hlo.id_org=p.id_org
                where hlo.id_garage=:id_garage INTO :currentcount;

                select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;
    
              -- проверим, достигнуто ли максимальное количество
              -- если одно из них или оба null, то результат будет false
              if ((:currentcount < :cntcount) OR (:checkplaceenable=0)) then begin
                -- все хорошо
                event_type = :RC_OK;
              end else begin
                -- превышен лимит количества а/м
                event_type = :RC_CARLIMITEXCEEDED;
              end
            END
          end else begin    -- если это не въезд, то есть выезд или вообще не КПП, то проверка завершена, выезжать/выходить можно всем
            event_type = :RC_OK;
          end
          end else begin
      event_type = :rc_accessdenied;    -- это если нет парковки

      end
    end
  end
 suspend;
end
^

/* Alter (VALIDATEPASS_HL_PARKING_2) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING_2(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
declare variable GARAGEOLACECOUNTENABLE integer = 1; /* считать ли свободные места для выбранного гаража? 1 - НЕ считать, 0 - Считать. */
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю :id_garage гаража и :cntcount количество машиномест в гараже  с учетом паркинга (этажа).
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;

        --подсчет машиномест на парковке, куда пытается заехать ГРЗ
        select count(*) from hl_place hlp
        join hl_garage hlg on hlg.id_place=hlp.id
        where hlp.id_parking=:id_parking
        and hlg.id_garagename=:id_garage into :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;

     -- с ГРЗ все в порядке, начинаю следующие проверки
  end else begin


     -- если есть гараж, то проверяю наличие свободных мест.
    if(:id_garage is not null) then begin


                -- если это въезд
                  if (:is_enter <> 0) then
                        begin
                        --если въезд разрешен, то начинаю подсчет свободных мест
                         if(:cntcount >0) then begin
                        --определяю количество свободных мест
                            select count(*) from hl_inside hli
                            join card c on c.id_card=hli.id_card
                            join people p on p.id_pep=c.id_pep
                            join hl_orgaccess hlo on hlo.id_org=p.id_org
                            where hlo.id_garage=:id_garage
                            and hli.counterid=:id_parking
                            INTO :currentcount;
            
                            select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;

                            -- если ГРЗ в гараже, то надо разрешать въезд в любом случае.
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (select * from hl_inside hli where hli.id_card=:id_card))) then
                                        -- въезд разрешен
                                        event_type = :RC_OK;
                                  else
                                    -- превышен лимит количества а/м
                                    event_type = :RC_CARLIMITEXCEEDED;
                            end
                        else
                        --если въезд запрещен, то выдаяю отказ в проезде в явном виде
                            event_type = :rc_accessdenied;
                          end
        
                    else
                        --если это выезд
                       -- тут надо сделать проверка: со своего ли паркинга он выезжает? но пока этой проверки нет, выпускаем всех
                        event_type = :RC_OK;

                    -- обработка въезд-выезд завершена
                          -- а если нет гаража, то проверяю категории доступа
         end
        else begin

                    if (exists (select * from ss_accessuser au
                    join access on au.id_accessname = access.id_accessname
                    where au.id_pep = :id_pep and access.id_dev = :id_dev)) then
                    --если проезд разрешен
                        --если это въезд, то проверяю наличие ГРЗ на территории парковки
                        if (:is_enter <> 0) then
                            if(not exists(select * from hl_inside hli
                                where hli.id_card=COALESCE(:id_card, :grz))) then event_type = :RC_OK;  --ГРЗ нет на территории. Въезд разрешен.
                            else  event_type = :RC_CARLIMITEXCEEDED;  --ГРЗ есть на территории. Въезд запрещен (нет мест)

                        else
                            event_type = :RC_OK;  --а если это выезд, то выпускаем всех
                    else
                    event_type = :RC_ACCESSDENIED;

            end

        end

 suspend;
end
^

/* Alter (VALIDATEPASS_HL_PARKING_3) */
ALTER PROCEDURE VALIDATEPASS_HL_PARKING_3(ID_DEV INTEGER,
ID_CARD VARCHAR(12),
GRZ VARCHAR(12))
 RETURNS(EVENT_TYPE INTEGER,
ID_PEP INTEGER)
 AS
declare variable IS_ACTIVE integer; /* Активность владельца ТС */
declare variable TIMESTART timestamp; /* Начало срока действия ГРЗ */
declare variable TIMEEND timestamp; /* Окончание срока действия ГРЗ */
declare variable ID_ORG integer; /* Организация, куда входит ГРЗ */
declare variable CNTCOUNT integer; /* количество мест в гараже, приписанному к ГРЗ */
declare variable CURRENTCOUNT integer; /* количество машин на стоянке */
declare variable RC_OK integer = 50; /* проверка успешна, проход разрешен */
declare variable RC_UNKNOWNCARD integer = 46; /* неизвестная карта */
declare variable RC_DISABLEDCARD integer = 65; /* карта неактивна */
declare variable RC_DISABLEDUSER integer = 65; /* юзер неактивен */
declare variable RC_CARDEXPIRED integer = 65; /* "сейчас" вне срока действия карты */
declare variable RC_ACCESSDENIED integer = 65; /* нет права доступа */
declare variable RC_CARLIMITEXCEEDED integer = 81; /* превышен лимит количества авто на территории */
declare variable ID_PARKING integer; /* ID парковки */
declare variable IS_ENTER integer; /* Въезд */
declare variable ID_GARAGE integer; /* ID гаража */
declare variable CHECKPLACEENABLE_KEY varchar(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
declare variable CHECKPLACEENABLE integer = 1; /* Значение: проверять (1) или НЕ проверят (0) */
declare variable GARAGEPLACECOUNTENABLE integer = 1; /* считать ли свободные места для выбранного гаража? 1 - НЕ считать, 0 - Считать. */
/*
ver 3
13.05.2023 добавлена обработка исключения из подсчета свободных мест для каждого гаража
*/
begin
    -- процедура выполняет проверку наличия свободных мест для указанного ГРЗ.
    -- результат выполнения: код события, который должен быть записан и
    -- идентификатор пользователя, если он был найден

     -- проверка допустимого количества машин на территории
      -- определим, въезд это или выход, получим ид точки прохода на въезде

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- определяю :id_garage гаража и :cntcount количество машиномест в гараже  с учетом паркинга (этажа).
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;

        --подсчет машиномест на парковке, куда пытается заехать ГРЗ
        select count(*) from hl_place hlp
        join hl_garage hlg on hlg.id_place=hlp.id
        where hlp.id_parking=:id_parking
        and hlg.id_garagename=:id_garage into :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- проверяем, найден ли идентификатор
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- стоит ли признак "карта активна"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- проверяем срок действия карты
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;

     -- с ГРЗ все в порядке, начинаю следующие проверки
  end else begin


     -- если есть гараж...
    if(:id_garage is not null) then begin


                 select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;
                 select hlg.not_count from hl_garagename hlg where hlg.id=:id_garage into :garageplacecountenable;

                 -- если надо учитывать количество свободных мест
                 if((:checkplaceenable = 0) or (:garageplacecountenable = 0)) then begin
                -- если это въезд
                  if (:is_enter <> 0) then
                        begin
                        --если въезд разрешен, то начинаю подсчет свободных мест
                         if(:cntcount >0) then begin
                        --определяю количество свободных мест
                            select count(*) from hl_inside hli
                            join card c on c.id_card=hli.id_card
                            join people p on p.id_pep=c.id_pep
                            join hl_orgaccess hlo on hlo.id_org=p.id_org
                            where hlo.id_garage=:id_garage
                            and hli.counterid=:id_parking
                            INTO :currentcount;
            

                            -- надо ли учитывать количество свободных мест на всей паркове?
            
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0)) then
                                        -- въезд разрешен
                                        event_type = :RC_OK;
                                  else
                                    -- превышен лимит количества а/м
                                    event_type = :RC_CARLIMITEXCEEDED;
                            end
                        else
                        --если въезд запрещен, то выдаяю отказ в проезде в явном виде
                            event_type = :rc_accessdenied;
                    end

                     --если это выезд
                    else

                       -- тут надо сделать проверка: со своего ли паркинга он выезжает? но пока этой проверки нет, выпускаем всех
                        event_type = :RC_OK;

                    -- обработка въезд-выезд завершена

           end
             else  event_type = :RC_OK;
            -- обработка варианта НЕ СЧИТАТЬ завершена
          
            end

         -- а если нет гаража, то проверяю категории доступа
        else begin    -- это не гараж, и выполняется проверка по категориям доступа

                    if (exists (select * from ss_accessuser au
                    join access on au.id_accessname = access.id_accessname
                    where au.id_pep = :id_pep and access.id_dev = :id_dev)) then
                    --если проезд разрешен
                        --если это въезд, то проверяю наличие ГРЗ на территории парковки
                        if (:is_enter <> 0) then
                            if(not exists(select * from hl_inside hli
                                where hli.id_card=COALESCE(:id_card, :grz))) then event_type = :RC_OK;  --ГРЗ нет на территории. Въезд разрешен.
                            else  event_type = :RC_CARLIMITEXCEEDED;  --ГРЗ есть на территории. Въезд запрещен (нет мест)

                        else
                            event_type = :RC_OK;  --а если это выезд, то выпускаем всех
                    else
                    event_type = :RC_ACCESSDENIED;

            end

        end

 suspend;
end
^

SET TERM ; ^

ALTER TABLE CARD ALTER COLUMN ID_CARD POSITION 1;

ALTER TABLE CARD ALTER COLUMN ID_DB POSITION 2;

ALTER TABLE CARD ALTER COLUMN ID_PEP POSITION 3;

ALTER TABLE CARD ALTER COLUMN ID_ACCESSNAME POSITION 4;

ALTER TABLE CARD ALTER COLUMN TIMESTART POSITION 5;

ALTER TABLE CARD ALTER COLUMN TIMEEND POSITION 6;

ALTER TABLE CARD ALTER COLUMN NOTE POSITION 7;

ALTER TABLE CARD ALTER COLUMN STATUS POSITION 8;

ALTER TABLE CARD ALTER COLUMN "ACTIVE" POSITION 9;

ALTER TABLE CARD ALTER COLUMN FLAG POSITION 10;

ALTER TABLE CARD ALTER COLUMN ID_CARDTYPE POSITION 11;

ALTER TABLE CARDTYPE ALTER COLUMN ID POSITION 1;

ALTER TABLE CARDTYPE ALTER COLUMN NAME POSITION 2;

ALTER TABLE CARDTYPE ALTER COLUMN DESCRIPTION POSITION 3;

ALTER TABLE PARKING_INSIDE ALTER COLUMN ID_PARKING POSITION 1;

ALTER TABLE PARKING_INSIDE ALTER COLUMN ID_DB POSITION 2;

ALTER TABLE PARKING_INSIDE ALTER COLUMN ID_PEP POSITION 3;

ALTER TABLE PARKING_INSIDE ALTER COLUMN ENTER_TIME POSITION 4;

ALTER TABLE PEOPLE ALTER COLUMN ID_PEP POSITION 1;

ALTER TABLE PEOPLE ALTER COLUMN ID_DB POSITION 2;

ALTER TABLE PEOPLE ALTER COLUMN ID_ORG POSITION 3;

ALTER TABLE PEOPLE ALTER COLUMN SURNAME POSITION 4;

ALTER TABLE PEOPLE ALTER COLUMN NAME POSITION 5;

ALTER TABLE PEOPLE ALTER COLUMN PATRONYMIC POSITION 6;

ALTER TABLE PEOPLE ALTER COLUMN DATEBIRTH POSITION 7;

ALTER TABLE PEOPLE ALTER COLUMN PLACELIFE POSITION 8;

ALTER TABLE PEOPLE ALTER COLUMN PLACEREG POSITION 9;

ALTER TABLE PEOPLE ALTER COLUMN PHONEHOME POSITION 10;

ALTER TABLE PEOPLE ALTER COLUMN PHONECELLULAR POSITION 11;

ALTER TABLE PEOPLE ALTER COLUMN PHONEWORK POSITION 12;

ALTER TABLE PEOPLE ALTER COLUMN NUMDOC POSITION 13;

ALTER TABLE PEOPLE ALTER COLUMN DATEDOC POSITION 14;

ALTER TABLE PEOPLE ALTER COLUMN PLACEDOC POSITION 15;

ALTER TABLE PEOPLE ALTER COLUMN PHOTO POSITION 16;

ALTER TABLE PEOPLE ALTER COLUMN WORKSTART POSITION 17;

ALTER TABLE PEOPLE ALTER COLUMN WORKEND POSITION 18;

ALTER TABLE PEOPLE ALTER COLUMN "ACTIVE" POSITION 19;

ALTER TABLE PEOPLE ALTER COLUMN FLAG POSITION 20;

ALTER TABLE PEOPLE ALTER COLUMN LOGIN POSITION 21;

ALTER TABLE PEOPLE ALTER COLUMN PSWD POSITION 22;

ALTER TABLE PEOPLE ALTER COLUMN ID_DEVGROUP POSITION 23;

ALTER TABLE PEOPLE ALTER COLUMN ID_ORGCTRL POSITION 24;

ALTER TABLE PEOPLE ALTER COLUMN PEPTYPE POSITION 25;

ALTER TABLE PEOPLE ALTER COLUMN POST POSITION 26;

ALTER TABLE PEOPLE ALTER COLUMN PLACEBIRTH POSITION 27;

ALTER TABLE PEOPLE ALTER COLUMN SOUND POSITION 28;

ALTER TABLE PEOPLE ALTER COLUMN ID_PLAN POSITION 29;

ALTER TABLE PEOPLE ALTER COLUMN PRESENT POSITION 30;

ALTER TABLE PEOPLE ALTER COLUMN NOTE POSITION 31;

ALTER TABLE PEOPLE ALTER COLUMN ID_AREA POSITION 32;

ALTER TABLE PEOPLE ALTER COLUMN SYSNOTE POSITION 33;

ALTER TABLE PEOPLE ALTER COLUMN TABNUM POSITION 34;

ALTER TABLE PEOPLE ALTER COLUMN TIME_STAMP POSITION 35;

ALTER TABLE SERVERTYPE ALTER COLUMN ID POSITION 1;

ALTER TABLE SERVERTYPE ALTER COLUMN SNAME POSITION 2;

ALTER TABLE SERVERTYPE ALTER COLUMN NAME POSITION 3;

ALTER TABLE SERVERTYPE ALTER COLUMN IS_ENABLED POSITION 4;

ALTER TABLE SERVERTYPE ALTER COLUMN DESCRIPTION POSITION 5;

ALTER TABLE SERVERTYPE ALTER COLUMN DATECREATED POSITION 6;

ALTER TABLE SERVERTYPE ALTER COLUMN DATECHANGE POSITION 7;

/* DROP: -- GRANT DELETE, INSERT, SELECT ON CARCOUNT_INSIDE TO PROCEDURE REGISTERPASS */
REVOKE DELETE, INSERT, SELECT ON CARCOUNT_INSIDE FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT DELETE, SELECT ON CARDIDX TO PROCEDURE CARDIDX_REFRESH */
REVOKE DELETE, SELECT ON CARDIDX FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT EXECUTE ON PROCEDURE CARDIDX_INSERT TO PROCEDURE CARDIDX_REFRESH */
REVOKE EXECUTE ON PROCEDURE CARDIDX_INSERT FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT EXECUTE ON PROCEDURE CARDIDX_REFRESH TO PROCEDURE CARDIDX_REFRESH */
REVOKE EXECUTE ON PROCEDURE CARDIDX_REFRESH FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT EXECUTE ON PROCEDURE DEVICE_CHECKACTIVE TO PROCEDURE CARDIDX_REFRESH */
REVOKE EXECUTE ON PROCEDURE DEVICE_CHECKACTIVE FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT EXECUTE ON PROCEDURE EVENT_ANALIT TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE EXECUTE ON PROCEDURE EVENT_ANALIT FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT EXECUTE ON PROCEDURE EVENTS_INSERT TO PROCEDURE REGISTERPASS */
REVOKE EXECUTE ON PROCEDURE EVENTS_INSERT FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT EXECUTE ON PROCEDURE VALIDATEPASS TO PROCEDURE REGISTERPASS */
REVOKE EXECUTE ON PROCEDURE VALIDATEPASS FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT INSERT ON CARD TO PROCEDURE ADD_CARD_FOR_VNII */
REVOKE INSERT ON CARD FROM PROCEDURE ADD_CARD_FOR_VNII;

/* DROP: -- GRANT INSERT ON EVENTS TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE INSERT ON EVENTS FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT INSERT ON EVENTS TO PROCEDURE EVENTS_INSERT_50 */
REVOKE INSERT ON EVENTS FROM PROCEDURE EVENTS_INSERT_50;

/* DROP: -- GRANT SELECT ON ACCESS TO PROCEDURE CARD_GETPARAM4DEV */
REVOKE SELECT ON ACCESS FROM PROCEDURE CARD_GETPARAM4DEV;

/* DROP: -- GRANT SELECT ON ACCESS TO PROCEDURE CARDIDX_REFRESH */
REVOKE SELECT ON ACCESS FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT SELECT ON ACCESS TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON ACCESS FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON ACCESS TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON ACCESS FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON ACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_3 */
REVOKE SELECT ON ACCESS FROM PROCEDURE VALIDATEPASS_HL_PARKING_3;

/* DROP: -- GRANT SELECT ON CARCOUNT_GATES TO PROCEDURE REGISTERPASS */
REVOKE SELECT ON CARCOUNT_GATES FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE CARD_GETPARAM4DEV */
REVOKE SELECT ON CARD FROM PROCEDURE CARD_GETPARAM4DEV;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE CARDIDX_REFRESH */
REVOKE SELECT ON CARD FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE SELECT ON CARD FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON CARD FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE EVENTS_INSERT_50 */
REVOKE SELECT ON CARD FROM PROCEDURE EVENTS_INSERT_50;

/* DROP: -- GRANT SELECT ON CARD TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON CARD FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON CARDINDEV TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON CARDINDEV FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE CARD_GETPARAM4DEV */
REVOKE SELECT ON DEVICE FROM PROCEDURE CARD_GETPARAM4DEV;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE CARDIDX_REFRESH */
REVOKE SELECT ON DEVICE FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE DEVICE_GETFREEIDCTRL */
REVOKE SELECT ON DEVICE FROM PROCEDURE DEVICE_GETFREEIDCTRL;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE SELECT ON DEVICE FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON DEVICE FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE EVENTS_INSERT_50 */
REVOKE SELECT ON DEVICE FROM PROCEDURE EVENTS_INSERT_50;

/* DROP: -- GRANT SELECT ON DEVICE TO PROCEDURE REGISTERPASS */
REVOKE SELECT ON DEVICE FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT SELECT ON ORGANIZATION TO PROCEDURE SET_ORG_FOR_VNII */
REVOKE SELECT ON ORGANIZATION FROM PROCEDURE SET_ORG_FOR_VNII;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE ADD_CARD_FOR_VNII */
REVOKE SELECT ON PEOPLE FROM PROCEDURE ADD_CARD_FOR_VNII;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE SELECT ON PEOPLE FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON PEOPLE FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE EVENTS_INSERT_50 */
REVOKE SELECT ON PEOPLE FROM PROCEDURE EVENTS_INSERT_50;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE REGISTERPASS */
REVOKE SELECT ON PEOPLE FROM PROCEDURE REGISTERPASS;

/* DROP: -- GRANT SELECT ON PEOPLE TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON PEOPLE FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON PERIMETER TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON PERIMETER FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON PERIMETER_GATE TO PROCEDURE VALIDATEPASS */
REVOKE SELECT ON PERIMETER_GATE FROM PROCEDURE VALIDATEPASS;

/* DROP: -- GRANT SELECT ON PERIMETER_GATE TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON PERIMETER_GATE FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON PERIMETER_INSIDE TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON PERIMETER_INSIDE FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON SERVER TO PROCEDURE DEVICEEVENTS_INSERT */
REVOKE SELECT ON SERVER FROM PROCEDURE DEVICEEVENTS_INSERT;

/* DROP: -- GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE CARDIDX_REFRESH */
REVOKE SELECT ON SS_ACCESSUSER FROM PROCEDURE CARDIDX_REFRESH;

/* DROP: -- GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE EVENT_ANALIT_MULTI */
REVOKE SELECT ON SS_ACCESSUSER FROM PROCEDURE EVENT_ANALIT_MULTI;

/* DROP: -- GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE VALIDATEPASS_APB */
REVOKE SELECT ON SS_ACCESSUSER FROM PROCEDURE VALIDATEPASS_APB;

/* DROP: -- GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE VALIDATEPASS_HL_PARKING_3 */
REVOKE SELECT ON SS_ACCESSUSER FROM PROCEDURE VALIDATEPASS_HL_PARKING_3;

/* DROP: -- GRANT SELECT, UPDATE ON PEOPLE TO PROCEDURE SET_ORG_FOR_VNII */
REVOKE SELECT, UPDATE ON PEOPLE FROM PROCEDURE SET_ORG_FOR_VNII;

/* Create(Add) Crant */
GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO PROCEDURE REGISTERPASS_HL;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO PROCEDURE REGISTERPASS_HL_2;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO PROCEDURE REGISTERPASS_HL;

GRANT INSERT ON EVENTS TO PROCEDURE REGISTERPASS_HL;

GRANT INSERT ON HL_EVENTS TO PROCEDURE REGISTERPASS_HL_2;

GRANT SELECT ON CARD TO PROCEDURE REGISTERPASS_HL;

GRANT SELECT ON HL_COUNTERS TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_3;

GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_3;

GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_3;

GRANT SELECT ON HL_PLACE TO PROCEDURE VALIDATEPASS_HL_PARKING_3;

GRANT SELECT ON HL_SETTING TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON PARKING_GATE TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON PEOPLE TO PROCEDURE REGISTERPASS_HL;


