SET TERM ^ ;

CREATE PROCEDURE HL_UPDATE_GARAGE_NAME (
    PLACENUM INTEGER,
    NAME_GARAGE VARCHAR(250))
AS
DECLARE VARIABLE ID_GARAGENAME INTEGER;
begin
  select hlg.id_garagename from hl_garage hlg where hlg.id_place=:placenum into :id_garagename;
  update hl_garagename hlgn set hlgn.name=:name_garage
  where hlgn.id=:id_garagename;
  suspend;
end
^

SET TERM ; ^

GRANT SELECT ON HL_GARAGE TO PROCEDURE HL_UPDATE_GARAGE_NAME;

GRANT SELECT,UPDATE ON HL_GARAGENAME TO PROCEDURE HL_UPDATE_GARAGE_NAME;

GRANT EXECUTE ON PROCEDURE HL_UPDATE_GARAGE_NAME TO SYSDBA;
