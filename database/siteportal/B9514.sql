-- Author: Jay Stoffle
-- Bug: 9514
-- Description: SHJ Type Update

UPDATE css_networking_device_type set uses_snmp = 0 where id = 2423;
