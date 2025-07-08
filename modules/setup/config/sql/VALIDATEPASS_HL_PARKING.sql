SET TERM ^ ;

CREATE PROCEDURE VALIDATEPASS_HL_PARKING (
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
DECLARE VARIABLE RC_CARDEXPIRED INTEGER = 65; /* "������" ��� ����� �������� ����� */
DECLARE VARIABLE RC_ACCESSDENIED INTEGER = 65; /* ��� ����� ������� */
DECLARE VARIABLE RC_CARLIMITEXCEEDED INTEGER = 81; /* �������� ����� ���������� ���� �� ���������� */
DECLARE VARIABLE ID_PARKING INTEGER; /* ID �������� */
DECLARE VARIABLE IS_ENTER INTEGER; /* ����� */
DECLARE VARIABLE ID_GARAGE INTEGER; /* ID ������ */
DECLARE VARIABLE CHECKPLACEENABLE_KEY VARCHAR(20) = 'CHECKPLACEENABLE'; /* ��� ��������� � ���������� hl_setting */
DECLARE VARIABLE CHECKPLACEENABLE INTEGER = 1; /* ��������: ��������� (1) ��� �� �������� (0) */
begin
    -- ��������� ��������� �������� ������� ��������� ���� ��� ���������� ���.
    -- ��������� ����������: ��� �������, ������� ������ ���� ������� �
    -- ������������� ������������, ���� �� ��� ������

     -- �������� ����������� ���������� ����� �� ����������
      -- ���������, ����� ��� ��� �����, ������� �� ����� ������� �� ������

        select hlp.id_parking, hlp.is_enter from hl_param hlp
        where hlp.id_dev=:id_dev into :id_parking, :is_enter;

        -- ��������� id ������ � ���������� ���������� � ������
        select hlg.id_garagename, count(*) from card c
        join people p on p.id_pep=c.id_pep
        join hl_orgaccess hlo on hlo.id_org=p.id_org
        join hl_garage hlg on hlo.id_garage=hlg.id_garagename
        where c.id_card=COALESCE(:id_card, :grz)
        group by hlg.id_garagename  into :id_garage, :cntcount;


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
  end else begin
    -- ����������� ������� ���������� ��� ����������
    -- � ��� ������, ������� ����� ����������� �����,
    -- ��� �������� ���������� �����������
    select "ACTIVE", id_org from people where id_pep = :id_pep into :is_active, :id_org;
    -- ��������� ���
    if (:is_active <> 1) then begin
      event_type = :RC_DISABLEDUSER;
    end else if (not exists (select * from ss_accessuser au
                         join access on au.id_accessname = access.id_accessname
                         where au.id_pep = :id_pep and access.id_dev = :id_dev)) then begin
      event_type = :RC_ACCESSDENIED;
    end else begin



      -- ���� ��� ��������
      if(:id_parking is not null) then begin
          -- ���� ��� �����
          if (:is_enter <> 0) then begin
    
            -- ���� ��� ������, �� ���������� ���������� NULL
            IF (:cntcount IS NULL) THEN BEGIN
              event_type = :rc_accessdenied;
            END ELSE BEGIN
              --  � ���� ���� ������, �� ���������� ���� �� NULL, � ����� ������ ������� ����� ��� ����� � ������

              select count(*) from hl_inside hli
                join card c on c.id_card=hli.id_card
                join people p on p.id_pep=c.id_pep
                join hl_orgaccess hlo on hlo.id_org=p.id_org
                where hlo.id_garage=:id_garage INTO :currentcount;

                select hlt.value_int from hl_setting hlt where hlt.name=:checkplaceenable_key into :checkplaceenable;
    
              -- ��������, ���������� �� ������������ ����������
              -- ���� ���� �� ��� ��� ��� null, �� ��������� ����� false
              if ((:currentcount < :cntcount) OR (:checkplaceenable=0)) then begin
                -- ��� ������
                event_type = :RC_OK;
              end else begin
                -- �������� ����� ���������� �/�
                event_type = :RC_CARLIMITEXCEEDED;
              end
            END
          end else begin    -- ���� ��� �� �����, �� ���� ����� ��� ������ �� ���, �� �������� ���������, ��������/�������� ����� ����
            event_type = :RC_OK;
          end
          end else begin
      event_type = :rc_accessdenied;    -- ��� ���� ��� ��������

      end
    end
  end
 suspend;
end
^

SET TERM ; ^

DESCRIBE PROCEDURE VALIDATEPASS_HL_PARKING
'���������� �������� ������� ��������� ���� ��� ������ �� ��������';

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