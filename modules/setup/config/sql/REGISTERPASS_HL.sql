SET TERM ^ ;

CREATE PROCEDURE REGISTERPASS_HL (
    ID_DEV INTEGER,
    ID_CARD VARCHAR(12),
    GRZ VARCHAR(12))
RETURNS (
    ID_PEP INTEGER,
    RC INTEGER)
AS
DECLARE VARIABLE ID_ORG INTEGER;
begin
  -- ��������� ������������ ������� ������� �� ��������������
  -- ��������� ������������ � ������ �������
  -- ���������� 0 ��� �������� ������� ���
  -- ��� �������, ������� ����� ������� � ������ ������� ��� ������

  if (:grz = '') then grz = null;
  rc=-1;
  -- ��������� ID_ORG ��� ess2
  select p.id_org from card c
  join people p on p.id_pep=c.id_pep
  where c.id_card=:id_card into :id_org    ;

  --�������� ��������� ���
  execute procedure validatepass_hl_parking :id_dev, :id_card, :grz returning_values :RC, :id_pep;

 -- �������� �������� � ���������
    INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
    VALUES (13, :id_card, :id_dev);

  -- ������� ��������� � ��
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

-- �������� ��������� ���������
 INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, ID_PEP)
    VALUES (:rc, :id_card, :id_dev, :id_pep);



  -- ��� �������� ������� ��������� ����, � �� 50, ����� ������� �� �������, ����� ��� ������, � ����� ���
  --IF (:RC = 50) THEN RC = 0;
  suspend;
end
^

SET TERM ; ^

DESCRIBE PROCEDURE REGISTERPASS_HL
'�������� ������������ ������� + ������ ������� � ������';

GRANT SELECT ON CARD TO PROCEDURE REGISTERPASS_HL;

GRANT SELECT ON PEOPLE TO PROCEDURE REGISTERPASS_HL;

GRANT EXECUTE ON PROCEDURE VALIDATEPASS_HL_PARKING TO PROCEDURE REGISTERPASS_HL;

GRANT INSERT ON HL_EVENTS TO PROCEDURE REGISTERPASS_HL;

GRANT INSERT ON EVENTS TO PROCEDURE REGISTERPASS_HL;

GRANT EXECUTE ON PROCEDURE REGISTERPASS_HL TO SYSDBA;