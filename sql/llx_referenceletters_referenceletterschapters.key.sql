-- Copyright (C) 2022 SuperAdmin
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see https://www.gnu.org/licenses/.


-- BEGIN MODULEBUILDER INDEXES
ALTER TABLE llx_referenceletters_referenceletterschapters ADD INDEX idx_referenceletters_chapters_fk_referenceletters (fk_referenceletters);
ALTER TABLE llx_referenceletters_referenceletterschapters ADD CONSTRAINT ibfk_referenceletters_chapters_fk_referenceletters FOREIGN KEY (fk_referenceletters) REFERENCES llx_referenceletters (rowid);

-- END MODULEBUILDER INDEXES

--ALTER TABLE llx_referenceletters_referenceletterschapters ADD UNIQUE INDEX uk_referenceletters_referenceletterschapters_fieldxy(fieldx, fieldy);

--ALTER TABLE llx_referenceletters_referenceletterschapters ADD CONSTRAINT llx_referenceletters_referenceletterschapters_fk_field FOREIGN KEY (fk_field) REFERENCES llx_referenceletters_myotherobject(rowid);

