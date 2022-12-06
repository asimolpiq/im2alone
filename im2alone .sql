-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 18 Haz 2022, 23:19:16
-- Sunucu sürümü: 10.4.22-MariaDB
-- PHP Sürümü: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `im2alone`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_room`
--

CREATE TABLE `admin_room` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `date` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `admin_room`
--

INSERT INTO `admin_room` (`id`, `username`, `message`, `date`) VALUES
(1, 'asimolpiq', 'sa', '12.04.2022 09:20:43'),
(2, 'root', 'sa', '12.04.2022 09:20:51'),
(3, 'portakalikoru', 'as', '12.04.2022 19:39:41'),
(4, 'root', 'seni mi kırıcam', '12.04.2022 19:39:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bannedlist`
--

CREATE TABLE `bannedlist` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blockeduser`
--

CREATE TABLE `blockeduser` (
  `id` int(11) NOT NULL,
  `userid1` int(11) NOT NULL,
  `userid2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat_online`
--

CREATE TABLE `chat_online` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `feeds`
--

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `date` varchar(60) NOT NULL,
  `privacy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `feeds`
--

INSERT INTO `feeds` (`id`, `user_id`, `content`, `link`, `date`, `privacy`) VALUES
(12, 2, '<p>Arkadaşlık sistemini &ccedil;alıştırmayı başardım</p>\r\n', 'https://open.spotify.com/track/4rp6FKiFRBOu06i3pIFg9k?si=3b3ff18e732741bb', 'Friday 25th of March 2022 03:24:41 AM', 2),
(13, 2, '<p>bakalım bozulacak mı</p>\r\n', 'https://open.spotify.com/track/7liWX1fWlrWRTqvLVSKC2T?si=30ee48cb522a421d', 'Friday 25th of March 2022 03:26:38 AM', 2),
(14, 2, '<p>sanırım bozulmadı amk</p>\r\n', 'https://open.spotify.com/track/7liWX1fWlrWRTqvLVSKC2T?si=30ee48cb522a421d', 'Friday 25th of March 2022 03:26:57 AM', 2),
(16, 2, '<p>JERKİNG</p>\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Friday 25th of March 2022 09:22:29 PM', 2),
(20, 2, '<p>Agacım karışık be</p>\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Sunday 3rd of April 2022 01:31:40 AM', 1),
(21, 2, '<p>T&uuml;rkiye veya resm&icirc; adıyla T&uuml;rkiye Cumhuriyeti, topraklarının b&uuml;y&uuml;k b&ouml;l&uuml;m&uuml; Anadolu&#39;da, k&uuml;&ccedil;&uuml;k bir b&ouml;l&uuml;m&uuml; ise Balkan Yarımadası&#39;nın g&uuml;neydoğu uzantısı olan Trakya&#39;da yer alan bir &uuml;lkedir. Kuzeybatıda Bulgaristan, batıda Yunanistan, kuzeydoğuda G&uuml;rcistan, doğuda Ermenistan, İran ve Azerbaycan&#39;ın ekslav toprağı Nah&ccedil;ıvan, g&uuml;neydoğuda ise Irak ve Suriye ile komşudur. G&uuml;neyini Kıbrıs Adası ve Akdeniz, batısını Ege Denizi ve kuzeyini Karadeniz &ccedil;evreler. Marmara Denizi ise İstanbul Boğazı ve &Ccedil;anakkale Boğazı ile birlikte Anadolu&#39;yu Trakya&#39;dan, yani Asya&#39;yı Avrupa&#39;dan ayırır. T&uuml;rkiye, Avrupa ve Asya kıtalarının kavşak noktasında yer alması nedeniyle &ouml;nemli bir jeostratejik g&uuml;ce sahiptir.[6]</p>\r\n\r\n<p>T&uuml;rkiye toprakları &uuml;zerindeki ilk yerleşmeler Yontma Taş Devri&#39;nde başlar.[7][8][9][10] Doğu Trakya&#39;da Traklar olmak &uuml;zere, Hititler, Frigler, Lidyalılar ve Dor istilası sonucu Yunanistan&#39;dan ka&ccedil;an Akalar tarafından kurulan İyon medeniyeti gibi &ccedil;eşitli eski Anadolu medeniyetlerinin ardından, Makedonya kralı B&uuml;y&uuml;k İskender&#39;in egemenliğiyle ve fetihleriyle birlikte Helenistik D&ouml;nem başladı. Daha sonra, sırasıyla Roma İmparatorluğu ve Anadolu&#39;nun Hristiyanlaştığı Bizans d&ouml;nemleri yaşandı.[9][11] Sel&ccedil;uklu T&uuml;rklerinin 1071 yılında Bizans&#39;a karşı kazandığı Malazgirt Meydan Muharebesi ile Anadolu&#39;daki Bizans &uuml;st&uuml;nl&uuml;ğ&uuml; b&uuml;y&uuml;k &ouml;l&ccedil;&uuml;de kırılarak Anadolu, kısa s&uuml;re i&ccedil;erisinde Sel&ccedil;uklulara bağlı T&uuml;rk beyleri tarafından ele ge&ccedil;irildi ve Anadolu toprakları &uuml;zerinde İslamlaşma ve T&uuml;rkleşme faaliyetleri başladı.[12] Kısa s&uuml;rede Anadolu&#39;daki diğer T&uuml;rk beyliklerinin &uuml;zerinde hakimiyet kuran Konya merkezli Anadolu Sel&ccedil;uklu Sultanlığı, 1243 yılındaki Moğollara karşı kaybedilen K&ouml;sedağ Muharebesi&#39;ne kadar Anadolu&#39;yu y&ouml;netti.[13] Anadolu&#39;daki Moğol istilalarından sonra zayıf duruma d&uuml;şen Anadolu Sel&ccedil;uklu Devleti, Anadolu&#39;da yerini yeni T&uuml;rk beyliklerine bıraktı.</p>\r\n\r\n<p>13. y&uuml;zyılın sonlarından itibaren Batı Anadolu&#39;daki T&uuml;rk beyliklerinden biri olarak &ouml;n plana &ccedil;ıkan ve bağımsızlık kazanan Osmanlılar, 14. y&uuml;zyılda Balkan topraklarında ger&ccedil;ekleştirdiği fetihlerle b&uuml;y&uuml;k bir g&uuml;&ccedil; haline geldi ve Anadolu&#39;daki diğer T&uuml;rk beylikleri &uuml;zerinde de hakimiyet kurdu. Osmanlılar, 1453 yılında II. Mehmed&#39;in İstanbul&#39;u fethederek Bizans İmparatorluğu&#39;na son vermesiyle b&uuml;y&uuml;k bir imparatorluk haline geldi. İmparatorluk, zirvesini 16. y&uuml;zyılda, &ouml;zelikle I. S&uuml;leyman d&ouml;neminde yaşadı. 1683 yılındaki II. Viyana Kuşatması sonrasında gelen bozgun ve 15 sene s&uuml;ren Kutsal İttifak Savaşları sonucunda Osmanlı İmparatorluğu&#39;nun Avrupa&#39;ya karşı &uuml;st&uuml;nl&uuml;ğ&uuml; sona erdi.</p>\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 01:15:40 AM', 1),
(23, 2, 'T&uuml;rkiye&nbsp;veya resm&icirc; adıyla&nbsp;T&uuml;rkiye Cumhuriyeti, topraklarının b&uuml;y&uuml;k b&ouml;l&uuml;m&uuml;&nbsp;Anadolu&#39;da, k&uuml;&ccedil;&uuml;k bir b&ouml;l&uuml;m&uuml; ise&nbsp;Balkan Yarımadası&#39;nın g&uuml;neydoğu uzantısı olan&nbsp;Trakya&#39;da yer alan bir &uuml;lkedir. Kuzeybatıda&nbsp;Bulgaristan, batıda&nbsp;Yunanistan, kuzeydoğuda&nbsp;G&uuml;rcistan, doğuda&nbsp;Ermenistan,&nbsp;İran&nbsp;ve&nbsp;Azerbaycan&#39;ın&nbsp;ekslav&nbsp;toprağı&nbsp;Nah&ccedil;ıvan, g&uuml;neydoğuda ise&nbsp;Irak&nbsp;ve&nbsp;Suriye&nbsp;ile komşudur. G&uuml;neyini&nbsp;Kıbrıs Adası&nbsp;ve&nbsp;Akdeniz, batısını&nbsp;Ege Denizi&nbsp;ve kuzeyini&nbsp;Karadeniz&nbsp;&ccedil;evreler.&nbsp;Marmara Denizi&nbsp;ise&nbsp;İstanbul Boğazı&nbsp;ve&nbsp;&Ccedil;anakkale Boğazı&nbsp;ile birlikte&nbsp;Anadolu&#39;yu&nbsp;Trakya&#39;dan, yani&nbsp;Asya&#39;yı&nbsp;Avrupa&#39;dan ayırır. T&uuml;rkiye,&nbsp;Avrupa&nbsp;ve&nbsp;Asya&nbsp;kıtalarının kavşak noktasında yer alması nedeniyle&nbsp;&ouml;nemli bir jeostratejik g&uuml;ce&nbsp;sahiptir.[6]\r\n\r\nT&uuml;rkiye toprakları &uuml;zerindeki ilk yerleşmeler&nbsp;Yontma Taş Devri&#39;nde başlar.[7][8][9][10]&nbsp;Doğu Trakya&#39;da&nbsp;Traklar&nbsp;olmak &uuml;zere,&nbsp;Hititler,&nbsp;Frigler,&nbsp;Lidyalılar&nbsp;ve&nbsp;Dor istilası&nbsp;sonucu&nbsp;Yunanistan&#39;dan ka&ccedil;an&nbsp;Akalar&nbsp;tarafından kurulan&nbsp;İyon&nbsp;medeniyeti gibi &ccedil;eşitli eski&nbsp;Anadolu medeniyetlerinin&nbsp;ardından,&nbsp;Makedonya&nbsp;kralı&nbsp;B&uuml;y&uuml;k İskender&#39;in egemenliğiyle ve fetihleriyle birlikte&nbsp;Helenistik D&ouml;nem&nbsp;başladı. Daha sonra, sırasıyla&nbsp;Roma İmparatorluğu&nbsp;ve&nbsp;Anadolu&#39;nun&nbsp;Hristiyanlaştığı&nbsp;Bizans&nbsp;d&ouml;nemleri yaşandı.[9][11]&nbsp;Sel&ccedil;uklu T&uuml;rklerinin&nbsp;1071 yılında Bizans&#39;a karşı kazandığı&nbsp;Malazgirt Meydan Muharebesi&nbsp;ile Anadolu&#39;daki Bizans &uuml;st&uuml;nl&uuml;ğ&uuml; b&uuml;y&uuml;k &ouml;l&ccedil;&uuml;de kırılarak&nbsp;Anadolu, kısa s&uuml;re i&ccedil;erisinde&nbsp;Sel&ccedil;uklulara&nbsp;bağlı T&uuml;rk beyleri tarafından ele ge&ccedil;irildi ve&nbsp;Anadolu&nbsp;toprakları &uuml;zerinde&nbsp;İslamlaşma&nbsp;ve&nbsp;T&uuml;rkleşme&nbsp;faaliyetleri başladı.[12]&nbsp;Kısa s&uuml;rede&nbsp;Anadolu&#39;daki diğer T&uuml;rk beyliklerinin &uuml;zerinde hakimiyet kuran&nbsp;Konya&nbsp;merkezli&nbsp;Anadolu Sel&ccedil;uklu Sultanlığı, 1243 yılındaki&nbsp;Moğollara&nbsp;karşı kaybedilen&nbsp;K&ouml;sedağ Muharebesi&#39;ne kadar&nbsp;Anadolu&#39;yu y&ouml;netti.[13]&nbsp;Anadolu&#39;daki&nbsp;Moğol istilalarından&nbsp;sonra zayıf duruma d&uuml;şen Anadolu Sel&ccedil;uklu Devleti, Anadolu&#39;da yerini yeni T&uuml;rk beyliklerine bıraktı.\r\n\r\n13. y&uuml;zyılın sonlarından itibaren&nbsp;Batı Anadolu&#39;daki T&uuml;rk beyliklerinden biri olarak &ouml;n plana &ccedil;ıkan ve bağımsızlık kazanan&nbsp;Osmanlılar, 14. y&uuml;zyılda&nbsp;Balkan&nbsp;topraklarında ger&ccedil;ekleştirdiği fetihlerle b&uuml;y&uuml;k bir g&uuml;&ccedil; haline geldi ve&nbsp;Anadolu&#39;daki diğer T&uuml;rk beylikleri &uuml;zerinde de hakimiyet kurdu. Osmanlılar, 1453 yılında&nbsp;II. Mehmed&#39;in&nbsp;İstanbul&#39;u fethederek&nbsp;Bizans İmparatorluğu&#39;na son vermesiyle b&uuml;y&uuml;k bir&nbsp;imparatorluk&nbsp;haline geldi. İmparatorluk, zirvesini 16. y&uuml;zyılda, &ouml;zelikle&nbsp;I. S&uuml;leyman&nbsp;d&ouml;neminde yaşadı. 1683 yılındaki&nbsp;II. Viyana Kuşatması&nbsp;sonrasında gelen bozgun ve 15 sene s&uuml;ren&nbsp;Kutsal İttifak Savaşları&nbsp;sonucunda&nbsp;Osmanlı İmparatorluğu&#39;nun&nbsp;Avrupa&#39;ya karşı &uuml;st&uuml;nl&uuml;ğ&uuml; sona erdi.\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 01:19:08 AM', 1),
(24, 2, '&nbsp;\r\n\r\nT&uuml;rkiye&nbsp;veya resm&icirc; adıyla&nbsp;T&uuml;rkiye Cumhuriyeti, topraklarının b&uuml;y&uuml;k b&ouml;l&uuml;m&uuml;&nbsp;Anadolu&#39;da, k&uuml;&ccedil;&uuml;k bir b&ouml;l&uuml;m&uuml; ise&nbsp;Balkan Yarımadası&#39;nın g&uuml;neydoğu uzantısı olan&nbsp;Trakya&#39;da yer alan bir &uuml;lkedir. Kuzeybatıda&nbsp;Bulgaristan, batıda&nbsp;Yunanistan, kuzeydoğuda&nbsp;G&uuml;rcistan, doğuda&nbsp;Ermenistan,&nbsp;İran&nbsp;ve&nbsp;Azerbaycan&#39;ın&nbsp;ekslav&nbsp;toprağı&nbsp;Nah&ccedil;ıvan, g&uuml;neydoğuda ise&nbsp;Irak&nbsp;ve&nbsp;Suriye&nbsp;ile komşudur. G&uuml;neyini&nbsp;Kıbrıs Adası&nbsp;ve&nbsp;Akdeniz, batısını&nbsp;Ege Denizi&nbsp;ve kuzeyini&nbsp;Karadeniz&nbsp;&ccedil;evreler.&nbsp;Marmara Denizi&nbsp;ise&nbsp;İstanbul Boğazı&nbsp;ve&nbsp;&Ccedil;anakkale Boğazı&nbsp;ile birlikte&nbsp;Anadolu&#39;yu&nbsp;Trakya&#39;dan, yani&nbsp;Asya&#39;yı&nbsp;Avrupa&#39;dan ayırır. T&uuml;rkiye,&nbsp;Avrupa&nbsp;ve&nbsp;Asya&nbsp;kıtalarının kavşak noktasında yer alması nedeniyle&nbsp;&ouml;nemli bir jeostratejik g&uuml;ce&nbsp;sahiptir.[6]\r\n\r\n&nbsp;\r\n\r\n&nbsp;\r\n\r\n&nbsp;\r\n\r\nT&uuml;rkiye toprakları &uuml;zerindeki ilk yerleşmeler&nbsp;Yontma Taş Devri&#39;nde başlar.[7][8][9][10]&nbsp;Doğu Trakya&#39;da&nbsp;Traklar&nbsp;olmak &uuml;zere,&nbsp;Hititler,&nbsp;Frigler,&nbsp;Lidyalılar&nbsp;ve&nbsp;Dor istilası&nbsp;sonucu&nbsp;Yunanistan&#39;dan ka&ccedil;an&nbsp;Akalar&nbsp;tarafından kurulan&nbsp;İyon&nbsp;medeniyeti gibi &ccedil;eşitli eski&nbsp;Anadolu medeniyetlerinin&nbsp;ardından,&nbsp;Makedonya&nbsp;kralı&nbsp;B&uuml;y&uuml;k İskender&#39;in egemenliğiyle ve fetihleriyle birlikte&nbsp;Helenistik D&ouml;nem&nbsp;başladı. Daha sonra, sırasıyla&nbsp;Roma İmparatorluğu&nbsp;ve&nbsp;Anadolu&#39;nun&nbsp;Hristiyanlaştığı&nbsp;Bizans&nbsp;d&ouml;nemleri yaşandı.[9][11]&nbsp;Sel&ccedil;uklu T&uuml;rklerinin&nbsp;1071 yılında Bizans&#39;a karşı kazandığı&nbsp;Malazgirt Meydan Muharebesi&nbsp;ile Anadolu&#39;daki Bizans &uuml;st&uuml;nl&uuml;ğ&uuml; b&uuml;y&uuml;k &ouml;l&ccedil;&uuml;de kırılarak&nbsp;Anadolu, kısa s&uuml;re i&ccedil;erisinde&nbsp;Sel&ccedil;uklulara&nbsp;bağlı T&uuml;rk beyleri tarafından ele ge&ccedil;irildi ve&nbsp;Anadolu&nbsp;toprakları &uuml;zerinde&nbsp;İslamlaşma&nbsp;ve&nbsp;T&uuml;rkleşme&nbsp;faaliyetleri başladı.[12]&nbsp;Kısa s&uuml;rede&nbsp;Anadolu&#39;daki diğer T&uuml;rk beyliklerinin &uuml;zerinde hakimiyet kuran&nbsp;Konya&nbsp;merkezli&nbsp;Anadolu Sel&ccedil;uklu Sultanlığı, 1243 yılındaki&nbsp;Moğollara&nbsp;karşı kaybedilen&nbsp;K&ouml;sedağ Muharebesi&#39;ne kadar&nbsp;Anadolu&#39;yu y&ouml;netti.[13]&nbsp;Anadolu&#39;daki&nbsp;Moğol istilalarından&nbsp;sonra zayıf duruma d&uuml;şen Anadolu Sel&ccedil;uklu Devleti, Anadolu&#39;da yerini yeni T&uuml;rk beyliklerine bıraktı.\r\n\r\n&nbsp;\r\n\r\n&nbsp;\r\n\r\n&nbsp;\r\n\r\n13. y&uuml;zyılın sonlarından itibaren&nbsp;Batı Anadolu&#39;daki T&uuml;rk beyliklerinden biri olarak &ouml;n plana &ccedil;ıkan ve bağımsızlık kazanan&nbsp;Osmanlılar, 14. y&uuml;zyılda&nbsp;Balkan&nbsp;topraklarında ger&ccedil;ekleştirdiği fetihlerle b&uuml;y&uuml;k bir g&uuml;&ccedil; haline geldi ve&nbsp;Anadolu&#39;daki diğer T&uuml;rk beylikleri &uuml;zerinde de hakimiyet kurdu. Osmanlılar, 1453 yılında&nbsp;II. Mehmed&#39;in&nbsp;İstanbul&#39;u fethederek&nbsp;Bizans İmparatorluğu&#39;na son vermesiyle b&uuml;y&uuml;k bir&nbsp;imparatorluk&nbsp;haline geldi. İmparatorluk, zirvesini 16. y&uuml;zyılda, &ouml;zelikle&nbsp;I. S&uuml;leyman&nbsp;d&ouml;neminde yaşadı. 1683 yılındaki&nbsp;II. Viyana Kuşatması&nbsp;sonrasında gelen bozgun ve 15 sene s&uuml;ren&nbsp;Kutsal İttifak Savaşları&nbsp;sonucunda&nbsp;Osmanlı İmparatorluğu&#39;nun&nbsp;Avrupa&#39;ya karşı &uuml;st&uuml;nl&uuml;ğ&uuml; sona erdi.\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 01:22:29 AM', 1),
(25, 2, 'Liserjik asit dietilamid, kısaca&nbsp;LSD&nbsp;ya da&nbsp;LSD-25, veya halk arasında bilinen ismi ile&nbsp;asit, yarısentetik&nbsp;psikoaktif&nbsp;bir&nbsp;hal&uuml;sinojendir.. İlk olarak 1936-1943 yılları arasında&nbsp;Albert Hoffman&nbsp;tarafından&nbsp;&ccedil;avdar mahmuzunda&nbsp;bulunan&nbsp;ergotaminden&nbsp;sentezlenmiştir. G&uuml;n&uuml;m&uuml;zde ve tarih boyunca genellikle&nbsp;keyif verici&nbsp;olarak veya&nbsp;ruhani ama&ccedil;lar&nbsp;i&ccedil;in kullanılmıştır. 1960&#39;ların&nbsp;karşı k&uuml;lt&uuml;r&uuml;ndeki&nbsp;yeri sebebiyle &ccedil;ok yaygın olarak bilinir.\r\n\r\nA&ccedil;ık ve kapalı g&ouml;z&nbsp;hal&uuml;sinasyonları, değişen boyutsal zaman algısı,&nbsp;sinestezi&nbsp;etkisi, ruhani deneyimler ve değişen d&uuml;ş&uuml;nce s&uuml;reci gibi&nbsp;psikedelik&nbsp;etkileri vardır. Ayrıca&nbsp;g&ouml;z bebeklerinin&nbsp;b&uuml;y&uuml;mesi,&nbsp;taşikardi,&nbsp;y&uuml;ksek tansiyon&nbsp;ve v&uuml;cut ısısının artması, terleme, iştah kaybı, ağız kuruması gibi fiziksel etkilere neden olur. Bilim ve tıp d&uuml;nyasının g&ouml;r&uuml;ş&uuml;ne g&ouml;re bağımlılık yapma potansiyeline sahip değildir.[1]\r\n\r\n&Ouml;n beyinde&nbsp;5-HT2A&nbsp;ve diğer alakalı&nbsp;resept&ouml;rlerinin&nbsp;doğrudan&nbsp;agonistidir&nbsp;ve bu&nbsp;serotonejenik&nbsp;etkiye yol a&ccedil;ar.[2]&nbsp;D2&nbsp;resept&ouml;rlerinde de benzer etkileri olması LSD&#39;nin aynı zamanda&nbsp;dopaminerjik&nbsp;&ouml;zelliklerine sebep olur.[3]&nbsp;LSD;&nbsp;oksijen,&nbsp;mor&ouml;tesi&nbsp;ışık ve &ccedil;&ouml;zelti i&ccedil;inde&nbsp;klora&nbsp;karşı duyarlıdır ve ışık ve nemden uzak tutulursa uzun yıllar dayanabilir. Saf haliyle kokusuz, renksiz ve hafif acı bir tada sahip&nbsp;kristal&nbsp;yapılı bir molek&uuml;ld&uuml;r.\r\n\r\nLSD yaygın olarak emici kurutma kağıdı, jel tabletler, şeker k&uuml;p&uuml; veya&nbsp;jelatin&nbsp;&uuml;zerine d&ouml;k&uuml;lerek satılır ve dil altı veya ağız yoluyla alınır.&nbsp;Eşik dozu&nbsp;20-30 mikrogram olan LSD&#39;nin, alınan doza g&ouml;re bakıldığında en g&uuml;&ccedil;l&uuml; hal&uuml;sinasyon g&ouml;rd&uuml;ren maddelerden biri olduğu kabul edilmektedir.&nbsp;Hal&uuml;sinasyon g&ouml;rd&uuml;ren mantarlardan&nbsp;100 kat,&nbsp;Meskalin&#39;den 4000 kat daha g&uuml;&ccedil;l&uuml;d&uuml;r.[4]&nbsp;Ancak LSD &ccedil;ok d&uuml;ş&uuml;k dozlarda, bu maddeler ise LSD&#39;ye kıyasla &ccedil;ok daha y&uuml;ksek dozlarda alındıklarından &ouml;t&uuml;r&uuml; etkileri birbirlerine benzerdir.\r\n\r\nLSD d&uuml;nyanın &ccedil;oğu &uuml;lkesinde&nbsp;yasaklı&nbsp;bir maddedir, ancak denetleyici yasalar &uuml;lkeden &uuml;lkeye farklılıklar g&ouml;sterir. Aynı zamanda LSD&#39;nin tıbbi kullanımı bazı &uuml;lkelerde yasal olup, madde hakkında bilimsel araştırmaların y&uuml;r&uuml;t&uuml;lmesine karşı &ccedil;oğu &uuml;lkede engeller de yoktur.[5]\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 02:00:35 AM', 1),
(28, 2, 'T&uuml;rkiye veya resm&icirc; adıyla T&uuml;rkiye Cumhuriyeti, topraklarının b&uuml;y&uuml;k b&ouml;l&uuml;m&uuml; Anadolu&#39;da, k&uuml;&ccedil;&uuml;k bir b&ouml;l&uuml;m&uuml; ise Balkan Yarımadası&#39;nın g&uuml;neydoğu uzantısı olan Trakya&#39;da yer alan bir &uuml;lkedir. Kuzeybatıda Bulgaristan, batıda Yunanistan, kuzeydoğuda G&uuml;rcistan, doğuda Ermenistan, İran ve Azerbaycan&#39;ın ekslav toprağı Nah&ccedil;ıvan, g&uuml;neydoğuda ise Irak ve Suriye ile komşudur. G&uuml;neyini Kıbrıs Adası ve Akdeniz, batısını Ege Denizi ve kuzeyini Karadeniz &ccedil;evreler. Marmara Denizi ise İstanbul Boğazı ve &Ccedil;anakkale Boğazı ile birlikte Anadolu&#39;yu Trakya&#39;dan, yani Asya&#39;yı Avrupa&#39;dan ayırır. T&uuml;rkiye, Avrupa ve Asya kıtalarının kavşak noktasında yer alması nedeniyle &ouml;nemli bir jeostratejik g&uuml;ce sahiptir.[6]\r\n\r\nT&uuml;rkiye toprakları &uuml;zerindeki ilk yerleşmeler Yontma Taş Devri&#39;nde başlar.[7][8][9][10] Doğu Trakya&#39;da Traklar olmak &uuml;zere, Hititler, Frigler, Lidyalılar ve Dor istilası sonucu Yunanistan&#39;dan ka&ccedil;an Akalar tarafından kurulan İyon medeniyeti gibi &ccedil;eşitli eski Anadolu medeniyetlerinin ardından, Makedonya kralı B&uuml;y&uuml;k İskender&#39;in egemenliğiyle ve fetihleriyle birlikte Helenistik D&ouml;nem başladı. Daha sonra, sırasıyla Roma İmparatorluğu ve Anadolu&#39;nun Hristiyanlaştığı Bizans d&ouml;nemleri yaşandı.[9][11] Sel&ccedil;uklu T&uuml;rklerinin 1071 yılında Bizans&#39;a karşı kazandığı Malazgirt Meydan Muharebesi ile Anadolu&#39;daki Bizans &uuml;st&uuml;nl&uuml;ğ&uuml; b&uuml;y&uuml;k &ouml;l&ccedil;&uuml;de kırılarak Anadolu, kısa s&uuml;re i&ccedil;erisinde Sel&ccedil;uklulara bağlı T&uuml;rk beyleri tarafından ele ge&ccedil;irildi ve Anadolu toprakları &uuml;zerinde İslamlaşma ve T&uuml;rkleşme faaliyetleri başladı.[12] Kısa s&uuml;rede Anadolu&#39;daki diğer T&uuml;rk beyliklerinin &uuml;zerinde hakimiyet kuran Konya merkezli Anadolu Sel&ccedil;uklu Sultanlığı, 1243 yılındaki Moğollara karşı kaybedilen K&ouml;sedağ Muharebesi&#39;ne kadar Anadolu&#39;yu y&ouml;netti.[13] Anadolu&#39;daki Moğol istilalarından sonra zayıf duruma d&uuml;şen Anadolu Sel&ccedil;uklu Devleti, Anadolu&#39;da yerini yeni T&uuml;rk beyliklerine bıraktı.\r\n\r\n13. y&uuml;zyılın sonlarından itibaren Batı Anadolu&#39;daki T&uuml;rk beyliklerinden biri olarak &ouml;n plana &ccedil;ıkan ve bağımsızlık kazanan Osmanlılar, 14. y&uuml;zyılda Balkan topraklarında ger&ccedil;ekleştirdiği fetihlerle b&uuml;y&uuml;k bir g&uuml;&ccedil; haline geldi ve Anadolu&#39;daki diğer T&uuml;rk beylikleri &uuml;zerinde de hakimiyet kurdu. Osmanlılar, 1453 yılında II. Mehmed&#39;in İstanbul&#39;u fethederek Bizans İmparatorluğu&#39;na son vermesiyle b&uuml;y&uuml;k bir imparatorluk haline geldi. İmparatorluk, zirvesini 16. y&uuml;zyılda, &ouml;zelikle I. S&uuml;leyman d&ouml;neminde yaşadı. 1683 yılındaki II. Viyana Kuşatması sonrasında gelen bozgun ve 15 sene s&uuml;ren Kutsal İttifak Savaşları sonucunda Osmanlı İmparatorluğu&#39;nun Avrupa&#39;ya karşı &uuml;st&uuml;nl&uuml;ğ&uuml; sona erdi.\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 02:11:30 AM', 0),
(30, 2, 'T&uuml;rkiye veya resm&icirc; adıyla T&uuml;rkiye Cumhuriyeti, topraklarının b&uuml;y&uuml;k b&ouml;l&uuml;m&uuml; Anadolu&#39;da, k&uuml;&ccedil;&uuml;k bir b&ouml;l&uuml;m&uuml; ise Balkan Yarımadası&#39;nın g&uuml;neydoğu uzantısı olan Trakya&#39;da yer alan bir &uuml;lkedir. Kuzeybatıda Bulgaristan, batıda Yunanistan, kuzeydoğuda G&uuml;rcistan, doğuda Ermenistan, İran ve Azerbaycan&#39;ın ekslav toprağı Nah&ccedil;ıvan, g&uuml;neydoğuda ise Irak ve Suriye ile komşudur. G&uuml;neyini Kıbrıs Adası ve Akdeniz, batısını Ege Denizi ve kuzeyini Karadeniz &ccedil;evreler. Marmara Denizi ise İstanbul Boğazı ve &Ccedil;anakkale Boğazı ile birlikte Anadolu&#39;yu Trakya&#39;dan, yani Asya&#39;yı Avrupa&#39;dan ayırır. T&uuml;rkiye, Avrupa ve Asya kıtalarının kavşak noktasında yer alması nedeniyle &ouml;nemli bir jeostratejik g&uuml;ce sahiptir.[6]\r\n\r\nT&uuml;rkiye toprakları &uuml;zerindeki ilk yerleşmeler Yontma Taş Devri&#39;nde başlar.[7][8][9][10] Doğu Trakya&#39;da Traklar olmak &uuml;zere, Hititler, Frigler, Lidyalılar ve Dor istilası sonucu Yunanistan&#39;dan ka&ccedil;an Akalar tarafından kurulan İyon medeniyeti gibi &ccedil;eşitli eski Anadolu medeniyetlerinin ardından, Makedonya kralı B&uuml;y&uuml;k İskender&#39;in egemenliğiyle ve fetihleriyle birlikte Helenistik D&ouml;nem başladı. Daha sonra, sırasıyla Roma İmparatorluğu ve Anadolu&#39;nun Hristiyanlaştığı Bizans d&ouml;nemleri yaşandı.[9][11] Sel&ccedil;uklu T&uuml;rklerinin 1071 yılında Bizans&#39;a karşı kazandığı Malazgirt Meydan Muharebesi ile Anadolu&#39;daki Bizans &uuml;st&uuml;nl&uuml;ğ&uuml; b&uuml;y&uuml;k &ouml;l&ccedil;&uuml;de kırılarak Anadolu, kısa s&uuml;re i&ccedil;erisinde Sel&ccedil;uklulara bağlı T&uuml;rk beyleri tarafından ele ge&ccedil;irildi ve Anadolu toprakları &uuml;zerinde İslamlaşma ve T&uuml;rkleşme faaliyetleri başladı.[12] Kısa s&uuml;rede Anadolu&#39;daki diğer T&uuml;rk beyliklerinin &uuml;zerinde hakimiyet kuran Konya merkezli Anadolu Sel&ccedil;uklu Sultanlığı, 1243 yılındaki Moğollara karşı kaybedilen K&ouml;sedağ Muharebesi&#39;ne kadar Anadolu&#39;yu y&ouml;netti.[13] Anadolu&#39;daki Moğol istilalarından sonra zayıf duruma d&uuml;şen Anadolu Sel&ccedil;uklu Devleti, Anadolu&#39;da yerini yeni T&uuml;rk beyliklerine bıraktı.\r\n\r\n13. y&uuml;zyılın sonlarından itibaren Batı Anadolu&#39;daki T&uuml;rk beyliklerinden biri olarak &ouml;n plana &ccedil;ıkan ve bağımsızlık kazanan Osmanlılar, 14. y&uuml;zyılda Balkan topraklarında ger&ccedil;ekleştirdiği fetihlerle b&uuml;y&uuml;k bir g&uuml;&ccedil; haline geldi ve Anadolu&#39;daki diğer T&uuml;rk beylikleri &uuml;zerinde de hakimiyet kurdu. Osmanlılar, 1453 yılında II. Mehmed&#39;in İstanbul&#39;u fethederek Bizans İmparatorluğu&#39;na son vermesiyle b&uuml;y&uuml;k bir imparatorluk haline geldi. İmparatorluk, zirvesini 16. y&uuml;zyılda, &ouml;zelikle I. S&uuml;leyman d&ouml;neminde yaşadı. 1683 yılındaki II. Viyana Kuşatması sonrasında gelen bozgun ve 15 sene s&uuml;ren Kutsal İttifak Savaşları sonucunda Osmanlı İmparatorluğu&#39;nun Avrupa&#39;ya karşı &uuml;st&uuml;nl&uuml;ğ&uuml; sona erdi.\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Thursday 7th of April 2022 02:13:29 AM', 1),
(37, 3, '<p>ahlanabi</p>\r\n', 'https://open.spotify.com/track/2VlLbhGLVJgdOW7kKdWWFc?si=be0f87347f2443bf', 'Friday 8th of April 2022 04:11:08 AM', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `userid1` int(11) NOT NULL,
  `userid2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `friends`
--

INSERT INTO `friends` (`id`, `userid1`, `userid2`) VALUES
(8, 1, 3),
(9, 3, 4),
(13, 1, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `friend_request`
--

CREATE TABLE `friend_request` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `friend_request`
--

INSERT INTO `friend_request` (`id`, `sender`, `receiver`) VALUES
(19, 1, 21);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date` varchar(70) NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `log`
--

INSERT INTO `log` (`id`, `userid`, `date`, `ip`) VALUES
(1, 4, '2022/04/09', '192.168.1.125'),
(2, 5, '2022/04/18', '::1'),
(3, 6, '2022/04/18', '::1'),
(4, 7, '2022/04/18', '::1'),
(5, 8, '2022/04/18', '::1'),
(6, 9, '2022/04/22', '::1'),
(7, 10, '2022/04/30', '::1'),
(8, 11, '2022/04/30', '::1'),
(9, 12, '2022/04/30', '::1'),
(10, 13, '2022/04/30', '::1'),
(11, 14, '2022/04/30', '::1'),
(12, 15, '2022/04/30', '::1'),
(13, 16, '2022/04/30', '::1'),
(14, 17, '2022/05/03', '::1'),
(15, 18, '2022/05/03', '::1'),
(16, 19, '2022/05/03', '::1'),
(17, 20, '2022/05/03', '::1'),
(18, 21, '2022/05/07', '::1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `public_room`
--

CREATE TABLE `public_room` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `date` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `public_room`
--

INSERT INTO `public_room` (`id`, `username`, `message`, `date`) VALUES
(1, 'portakalikoru', 'agacım?', '12.04.2022 19:48:26'),
(2, 'root', 'efendim?', '12.04.2022 19:48:48'),
(3, 'portakalikoru', ':)', '12.04.2022 20:11:18'),
(4, 'portakalikoru', '?', '12.04.2022 20:11:33');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `gender` int(11) NOT NULL,
  `birthday` varchar(25) NOT NULL,
  `bio` text NOT NULL,
  `pp` varchar(255) DEFAULT NULL,
  `interested` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `online` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `realname`, `password`, `email`, `gender`, `birthday`, `bio`, `pp`, `interested`, `permission`, `status`, `online`) VALUES
(1, 'root', 'Alpay Güroğlu', 'fcfc585186a9d31656ec3036e120dae3', 'alpayguroglu@gmail.com', 0, '30/09/1999', 'Aynen Gardeş', 'dist/profile_pictures/pp-root.jpg', 0, 1, 1, 1),
(2, 'asimolpiq', 'Alpay Güroğlu', 'fcfc585186a9d31656ec3036e120dae3', '', 0, '', 'Aynen Gardeş', 'dist/profile_pictures/pp-asimolpiq.jpg', 0, 0, 1, 0),
(3, 'portakalikoru', 'Emirhan Dikilitaş', 'fcfc585186a9d31656ec3036e120dae3', 'byhackerman2072@gmail.com', 0, '', '', NULL, 0, 0, 1, 1),


--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin_room`
--
ALTER TABLE `admin_room`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `bannedlist`
--
ALTER TABLE `bannedlist`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `blockeduser`
--
ALTER TABLE `blockeduser`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `chat_online`
--
ALTER TABLE `chat_online`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `friend_request`
--
ALTER TABLE `friend_request`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `public_room`
--
ALTER TABLE `public_room`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin_room`
--
ALTER TABLE `admin_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `bannedlist`
--
ALTER TABLE `bannedlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `blockeduser`
--
ALTER TABLE `blockeduser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `chat_online`
--
ALTER TABLE `chat_online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Tablo için AUTO_INCREMENT değeri `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `public_room`
--
ALTER TABLE `public_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
