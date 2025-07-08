delete from hl_resident hlr where hlr.id=0;
INSERT INTO HL_RESIDENT (ID, NAME, IS_ACTIVE, CREATED, MODIFY) VALUES (0, 'Все', 1, 'now', NULL);
