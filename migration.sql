-- See all affected rows with the new timestamp
SELECT `meta_value`, CAST(UNIX_TIMESTAMP(CONVERT_TZ(FROM_UNIXTIME(`meta_value`), @@session.time_zone, '+00:00')) AS SIGNED) AS `utc_datetime` FROM `TABLE_NAME` WHERE `meta_key` = 'tecal_events_begin' OR `meta_key` = 'tecal_events_end'
-- Migrate timestamps to UTC
UPDATE `TABLE_NAME` SET `meta_value` = CAST(UNIX_TIMESTAMP(CONVERT_TZ(FROM_UNIXTIME(`meta_value`), @@session.time_zone, '+00:00')) AS SIGNED) WHERE `meta_key` = 'tecal_events_begin' OR `meta_key` = 'tecal_events_end'