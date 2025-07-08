SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING_2 (
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
DECLARE VARIABLE RC_CARDEXPIRED INTEGER = 47; /* "сейчас" вне срока действия карты */
DECLARE VARIABLE RC_ACCESSDENIED INTEGER = 65; /* нет права доступа */
DECLARE VARIABLE RC_CARLIMITEXCEEDED INTEGER = 81; /* превышен лимит количества авто на территории */
DECLARE VARIABLE ID_PARKING INTEGER; /* ID парковки */
DECLARE VARIABLE IS_ENTER INTEGER; /* Въезд */
DECLARE VARIABLE ID_GARAGE INTEGER; /* ID гаража */
DECLARE VARIABLE CHECKPLACEENABLE_KEY VARCHAR(20) = 'CHECKPLACEENABLE'; /* Имя параметра в настройках hl_setting */
DECLARE VARIABLE CHECKPLACEENABLE INTEGER = 1; /* Значение: проверять (1) или НЕ проверят (0) */
DECLARE VARIABLE GARAGEOLACECOUNTENABLE INTEGER = 1; /* считать ли свободные места для выбранного гаража? 1 - НЕ считать, 0 - Считать. */
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
                            -- если ГРЗ уже в гараже, то все равно разрешаю въезд
                            --   if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (select * from hl_inside hli where hli.id_card=:id_card))) then
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (
                               select * from hl_inside hli
                                join card c on c.id_card=hli.id_card
                                join card c2 on c2.id_pep=c.id_pep
                                where c2.id_card=:id_card
                                ))) then
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

SET TERM ; ^

GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON CARD TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON PEOPLE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_ORGACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_PLACE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_INSIDE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON HL_SETTING TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON SS_ACCESSUSER TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT SELECT ON ACCESS TO PROCEDURE VALIDATEPASS_HL_PARKING_2;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO PROCEDURE REGISTERPASS_HL;
GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO PROCEDURE REGISTERPASS_HL_2;
GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING_2 TO SYSDBA;