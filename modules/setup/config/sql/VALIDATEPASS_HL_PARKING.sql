SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING (
    ID_DEV INTEGER,
    ID_CARD VARCHAR(12),
    GRZ VARCHAR(12))
RETURNS (
    EVENT_TYPE INTEGER,
    ID_PEP INTEGER)
AS
DECLARE VARIABLE IS_ACTIVE INTEGER; /* Активность владельца ТС */
DECLARE VARIABLE TIMESTART TIMESTAMP; /* Начало срока действия ГРЗ */
DECLARE VARIABLE TIMEEND TIMESTAMP; /* Окончание срока действия ГРЗ */
DECLARE VARIABLE ID_ORG INTEGER; /* Организация, куда входит ГРЗ */
DECLARE VARIABLE CNTCOUNT INTEGER; /* количество мест в гараже, приписанному к ГРЗ */
DECLARE VARIABLE CURRENTCOUNT INTEGER; /* количество машин на стоянке */
DECLARE VARIABLE RC_OK INTEGER = 50; /* проверка успешна, проход разрешен */
DECLARE VARIABLE RC_UNKNOWNCARD INTEGER = 46; /* неизвестная карта */
DECLARE VARIABLE RC_DISABLEDCARD INTEGER = 65; /* карта неактивна */
DECLARE VARIABLE RC_DISABLEDUSER INTEGER = 65; /* юзер неактивен */
DECLARE VARIABLE RC_CARDEXPIRED INTEGER = 65; /* "сейчас" вне срока действия карты */
DECLARE VARIABLE RC_ACCESSDENIED INTEGER = 65; /* нет права доступа */
DECLARE VARIABLE RC_CARLIMITEXCEEDED INTEGER = 81; /* превышен лимит количества авто на территории */
DECLARE VARIABLE ID_PARKING INTEGER; /* ID парковки */
DECLARE VARIABLE IS_ENTER INTEGER; /* Въезд */
DECLARE VARIABLE ID_GARAGE INTEGER; /* ID гаража */
DECLARE VARIABLE CHECKPLACEENABLE_KEY VARCHAR(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
DECLARE VARIABLE CHECKPLACEENABLE INTEGER = 1; /* Значение: проверять (1) или НЕ проверят (0) */
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

SET TERM ; ^

DESCRIBE PROCEDURE VALIDATEPASS_HL_PARKING
'Производит проверку наличия свободных мест при въезде на парковку';

GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON CARD TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON PEOPLE TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON ACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT SELECT ON HL_SETTING TO PROCEDURE VALIDATEPASS_HL_PARKING;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO PROCEDURE REGISTERPASS_HL;
GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO PROCEDURE REGISTERPASS_HL_2;
GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO SYSDBA;