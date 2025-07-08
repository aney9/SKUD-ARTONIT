SET TERM ^ ;

CREATE PROCEDURE REGISTERPASS_HL_2 (
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
        --delete from hl_inside hli2 where hli2.id_card=:id_card;
        delete from hl_inside hli2 where hli2.id_pep=:id_pep;
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

SET TERM ; ^

GRANT SELECT ON CARD TO PROCEDURE REGISTERPASS_HL_2;

GRANT SELECT ON PEOPLE TO PROCEDURE REGISTERPASS_HL_2;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO PROCEDURE REGISTERPASS_HL_2;

GRANT SELECT,DELETE ON HL_INSIDE TO PROCEDURE REGISTERPASS_HL_2;

GRANT SELECT ON HL_PARAM TO PROCEDURE REGISTERPASS_HL_2;

GRANT INSERT ON HL_EVENTS TO PROCEDURE REGISTERPASS_HL_2;

GRANT INSERT ON EVENTS TO PROCEDURE REGISTERPASS_HL_2;

GRANT EXECUTE ON PROCEDURE REGISTERPASS_HL_2 TO SYSDBA;