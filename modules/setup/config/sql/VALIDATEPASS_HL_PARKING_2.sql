SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING_2 (
    ID_DEV INTEGER,
    ID_CARD VARCHAR(12),
    GRZ VARCHAR(12))
RETURNS (
    EVENT_TYPE INTEGER,
    ID_PEP INTEGER)
AS
DECLARE VARIABLE IS_ACTIVE INTEGER; /* ���������� ��������� �� */
DECLARE VARIABLE TIMESTART TIMESTAMP; /* ������ ����� �������� ��� */
DECLARE VARIABLE TIMEEND TIMESTAMP; /* ��������� ����� �������� ��� */
DECLARE VARIABLE ID_ORG INTEGER; /* �����������, ���� ������ ��� */
DECLARE VARIABLE CNTCOUNT INTEGER; /* ���������� ���� � ������, ������������ � ��� */
DECLARE VARIABLE CURRENTCOUNT INTEGER; /* ���������� ����� �� ������� */
DECLARE VARIABLE RC_OK INTEGER = 50; /* �������� �������, ������ �������� */
DECLARE VARIABLE RC_UNKNOWNCARD INTEGER = 46; /* ����������� ����� */
DECLARE VARIABLE RC_DISABLEDCARD INTEGER = 65; /* ����� ��������� */
DECLARE VARIABLE RC_DISABLEDUSER INTEGER = 65; /* ���� ��������� */
DECLARE VARIABLE RC_CARDEXPIRED INTEGER = 47; /* "������" ��� ����� �������� ����� */
DECLARE VARIABLE RC_ACCESSDENIED INTEGER = 65; /* ��� ����� ������� */
DECLARE VARIABLE RC_CARLIMITEXCEEDED INTEGER = 81; /* �������� ����� ���������� ���� �� ���������� */
DECLARE VARIABLE ID_PARKING INTEGER; /* ID �������� */
DECLARE VARIABLE IS_ENTER INTEGER; /* ����� */
DECLARE VARIABLE ID_GARAGE INTEGER; /* ID ������ */
DECLARE VARIABLE CHECKPLACEENABLE_KEY VARCHAR(20) = 'CHECKPLACEENABLE'; /* ��� ��������� � ���������� hl_setting */
DECLARE VARIABLE CHECKPLACEENABLE INTEGER = 1; /* ��������: ��������� (1) ��� �� �������� (0) */
DECLARE VARIABLE GARAGEOLACECOUNTENABLE INTEGER = 1; /* ������� �� ��������� ����� ��� ���������� ������? 1 - �� �������, 0 - �������. */
begin
    -- ��������� ��������� �������� ������� ��������� ���� ��� ���������� ���.
    -- ��������� ����������: ��� �������, ������� ������ ���� ������� �
    -- ������������� ������������, ���� �� ��� ������

     -- �������� ����������� ���������� ����� �� ����������
      -- ���������, ����� ��� ��� �����, ������� �� ����� ������� �� ������

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- ��������� :id_garage ������ � :cntcount ���������� ���������� � ������  � ������ �������� (�����).
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;

        --������� ���������� �� ��������, ���� �������� ������� ���
        select count(*) from hl_place hlp
        join hl_garage hlg on hlg.id_place=hlp.id
        where hlp.id_parking=:id_parking
        and hlg.id_garagename=:id_garage into :cntcount;


  select id_pep, "ACTIVE", timestart, timeend from card where id_card = :id_card
    into :id_pep, :is_active, :timestart, :timeend;

  -- ���������, ������ �� �������������
  if (:id_pep is null) then begin
    event_type = :RC_UNKNOWNCARD;
  -- ����� �� ������� "����� �������"
  end else if (:is_active <> 1) then begin
        event_type = :RC_DISABLEDCARD;
  -- ��������� ���� �������� �����
  end else if (('now' < :timestart) or ((:timeend is not null) and ('now' > :timeend))) then begin
    event_type = :RC_CARDEXPIRED;

     -- � ��� ��� � �������, ������� ��������� ��������
  end else begin


     -- ���� ���� �����, �� �������� ������� ��������� ����.
    if(:id_garage is not null) then begin


                -- ���� ��� �����
                  if (:is_enter <> 0) then
                        begin
                        --���� ����� ��������, �� ������� ������� ��������� ����
                         if(:cntcount >0) then begin
                        --��������� ���������� ��������� ����
                            select count(*) from hl_inside hli
                            join card c on c.id_card=hli.id_card
                            join people p on p.id_pep=c.id_pep
                            join hl_orgaccess hlo on hlo.id_org=p.id_org
                            where hlo.id_garage=:id_garage
                            and hli.counterid=:id_parking
                            INTO :currentcount;
            
                            select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;

                            -- ���� ��� � ������, �� ���� ��������� ����� � ����� ������.
                            -- ���� ��� ��� � ������, �� ��� ����� �������� �����
                            --   if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (select * from hl_inside hli where hli.id_card=:id_card))) then
                               if ((:currentcount < :cntcount) OR (:checkplaceenable=0) OR (exists (
                               select * from hl_inside hli
                                join card c on c.id_card=hli.id_card
                                join card c2 on c2.id_pep=c.id_pep
                                where c2.id_card=:id_card
                                ))) then
                                        -- ����� ��������
                                        event_type = :RC_OK;
                                  else
                                    -- �������� ����� ���������� �/�
                                    event_type = :RC_CARLIMITEXCEEDED;
                            end
                        else
                        --���� ����� ��������, �� ������ ����� � ������� � ����� ����
                            event_type = :rc_accessdenied;
                          end
        
                    else
                        --���� ��� �����
                       -- ��� ���� ������� ��������: �� ������ �� �������� �� ��������? �� ���� ���� �������� ���, ��������� ����
                        event_type = :RC_OK;

                    -- ��������� �����-����� ���������
                          -- � ���� ��� ������, �� �������� ��������� �������
         end
        else begin

                    if (exists (select * from ss_accessuser au
                    join access on au.id_accessname = access.id_accessname
                    where au.id_pep = :id_pep and access.id_dev = :id_dev)) then
                    --���� ������ ��������
                        --���� ��� �����, �� �������� ������� ��� �� ���������� ��������
                        if (:is_enter <> 0) then
                            if(not exists(select * from hl_inside hli
                                where hli.id_card=COALESCE(:id_card, :grz))) then event_type = :RC_OK;  --��� ��� �� ����������. ����� ��������.
                            else  event_type = :RC_CARLIMITEXCEEDED;  --��� ���� �� ����������. ����� �������� (��� ����)

                        else
                            event_type = :RC_OK;  --� ���� ��� �����, �� ��������� ����
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