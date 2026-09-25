-- Bildirim okundu takibi (navbar zil simgesi)
-- Canliya cikarken bu dosya prod DB'de calistirilmali.

ALTER TABLE friend_request ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0;
