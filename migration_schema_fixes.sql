-- Sema duzeltmeleri (25 Eyl 2026) - canliya cikarken calistirilmali.
-- ONEMLI: once canlida su kontrolu yap:
--   SHOW COLUMNS FROM users LIKE 'token';
-- Kolon ZATEN VARSA asagidaki ilk ALTER satirini ATLA (MySQL 8.0'da
-- ADD COLUMN IF NOT EXISTS yok, iki kez calistirirsan hata verir).

-- 1) Mobil API login/register ve web ban aksiyonu (handle_report.php) bu kolonu
--    kullaniyor ama hicbir migration'da tanimi yoktu. Temiz kurulumda site patliyordu.
ALTER TABLE users ADD COLUMN token VARCHAR(255) NULL DEFAULT NULL;

-- 2) tokens tablosu hem email dogrulama (confirm.php) hem sifre yenileme
--    (recovery.php) icin ortakti; bir akisin token'i digerinde kullanilabiliyordu.
--    Mevcut satirlar forgot akisindan kaldigi icin default 'recovery'.
ALTER TABLE tokens ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT 'recovery';

-- 3) bannedlist tablosu hicbir kod tarafindan kullanilmiyor (ban sistemi artik
--    users.is_banned uzerinden). Silmek istersen asagidaki satiri ac:
-- DROP TABLE IF EXISTS bannedlist;
