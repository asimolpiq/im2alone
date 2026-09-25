# Yapılacaklar — im2alone (Apple Guideline 1.2 / UGC uyumluluk çalışması)

## 🚀 DEPLOY DURUMU (25 Eyl 2026) — canlı klasör hazırlandı

Tüm değişiklikler `C:\Users\nolur\Desktop\repo` klasörüne uygulandı:
- [x] Değişen/yeni tüm PHP + tema + Quill dosyaları kopyalandı (canlı drift'ler korundu:
      db_connect gerçek bilgiler, api register permission=1, api write-diary'nin canlı hali).
- [x] SMTP şifresi 3 dosyada dolduruldu (forgot.php, includes/mailer.php, moderation_functions.php).
- [x] Kök .htaccess'e güvenlik blokları eklendi (cPanel bölümlerine dokunulmadı).
- [x] `alpayguroglu_im2alone.sql`: tüm şema güncellemeleri sona eklendi (is_banned, eula_accepted_at,
      reports, tokens.type, friend_request.is_read, feed_likes, feed_views — users.token canlıda zaten
      vardı, eklenmedi), her tabloya DROP TABLE IF EXISTS kondu, bozuk `bio` TEXT default'u temizlendi.
      Lokalde 2 senaryoda test edildi: boş DB'ye ve üzerine import — ikisi de hatasız.
- [x] Deploy öncesi yedek: `Desktop\repo-backup-deploy-oncesi.tgz`

SANA KALAN:
- [ ] repo klasörünü FTP ile yükle
- [ ] alpayguroglu_im2alone.sql'i phpMyAdmin'den import et (DİKKAT: dump bugünkü veriyle, mevcut
      tabloları düşürüp yeniden kurar — import öncesi canlıda yeni kayıt oluştuysa onlar gider)
- [ ] Sunucudaki `ckeditor/` klasörünü FTP'DEN SİL (iki lokal klasörden de kaldırıldı ama FTP upload
      sunucudaki mevcut klasörü silmez — elle silinmeli; samples/posteddata.php risk)
- [ ] Sunucuda `dist/diary_images/` klasörü FTP ile oluşmazsa elle oluştur (.htaccess ile birlikte geliyor)

Mevcut çalışma alanındaki commit edilmemiş değişiklikler ve yeni dosyalar incelenerek çıkarıldı.
Devam eden iş: kullanıcı engelleme (block), şikayet (report), banlama, EULA onayı ve admin şikayet paneli.

## Kritik / Önce Yapılmalı

- [ ] **`migration_moderation.sql` henüz production DB'de çalıştırılmamış görünüyor.**
      `users.is_banned`, `users.eula_accepted_at` ve `reports` tablosu kodda (auth_functions.php,
      register.php, moderation_functions.php, reports.php, handle_report.php) zaten kullanılıyor.
      Migration çalıştırılmadan bu kod prod'da SQL hatası verir.
- [ ] `api/v1/includes/moderation/moderation_functions.php` içindeki PHPMailer şifresi placeholder:
      `"MAİL PASSWORD"`. `forgot.php`'deki gerçek SMTP bilgisiyle doldurulmalı, .env/config'e taşınması
      da düşünülebilir (şu an tüm DB/SMTP bilgileri kod içinde plaintext).
- [ ] Yeni/değişen dosyalar henüz git'e eklenmemiş (untracked): `api/v1/block-user.php`,
      `api/v1/get-blocked-users.php`, `api/v1/includes/moderation/`, `api/v1/report-content.php`,
      `api/v1/unblock-user.php`, `handle_report.php`, `migration_moderation.sql`, `reports.php`,
      `support.php`, `terms.php` → commit edilmeli.

## Gözden Geçirilmeli

- [ ] `reports.php` / `handle_report.php`: state değiştiren aksiyonlar (`ban`, `delete_content`,
      `dismiss`) GET isteğiyle yapılıyor, koruma sadece JS `confirm()`. CSRF token yok — link paylaşılırsa
      istemsiz banlama/silme riski var.
- [ ] Yeni block/report sorgularının çoğu (`isBlocked`, `blockUser`, `unblockUser`, `getBlockedUsers`,
      diaries/general_functions'daki blockeduser kontrolleri) ham string concatenation kullanıyor;
      `createReport` ise prepared statement kullanıyor. Tutarlılık için hepsi prepared statement'a
      taşınabilir (öncelik değil, mevcut kod tabanı zaten genel olarak bu şekilde).
- [ ] Engelleme kontrolü şu an sadece diary feed (`getAllFeed`) ve arama (`getUserSearch`) için eklendi.
      Diğer temas noktaları kontrol edilmeli: `api/v1/get-user.php`, arkadaşlık istekleri/bildirimleri,
      profil görüntüleme (`user_detail.php` zaten eski blockeduser mantığını kullanıyor — mobil API ile
      tutarlı mı bakılmalı), oda/mesajlaşma (`room.php`, `rooms.php`) blocklu kullanıcıyı gösteriyor mu.
- [ ] `terms.php` ve `support.php` şu an sadece `register.php` içinden linkleniyor. Apple review için
      login ekranı / footer / uygulama içi ayarlar menüsünden de erişilebilir olması faydalı olur.
- [ ] Web admin girişi (`index.php`) `is_banned` kontrolü yapmıyor — kasıtlı mı (admin/staff hesapları
      `permission`/`status` ile ayrı yönetiliyor gibi görünüyor) yoksa eksik mi, teyit edilmeli.

## Editör / Fotoğraf Yükleme (Quill geçişi — 24 Eyl 2026)

- [x] CKEditor kaldırıldı → Quill 2.0.3 (self-hosted, `dist/plugins/quill/`), sadece izinli formatlar:
      bold, italic, underline, liste, fotoğraf. Yapıştırılan HTML de bu formatlara indirgeniyor.
- [x] `upload_diary_image.php` eklendi: session + CSRF kontrolü, class.upload ile max 1200px resize,
      her şey jpg'ye re-encode (gizli payload'ları öldürür), 5MB limit, `dist/diary_images/` klasörüne.
- [x] `includes/html_sanitizer.php`: `<img>` artık izinli ama SADECE `dist/diary_images/` src'li olanlar;
      dış URL'li img komple siliniyor, tag sıfırdan kuruluyor (onerror vb. attribute kalamaz).
- [x] `write-diary.php`: kayıt anında da `sanitizeDiaryHtml()` (editör bypass edilip direkt POST
      atılsa bile temiz kayıt). Test edildi: script/onclick/onerror/dış img hepsi temizleniyor.
- [x] `dist/diary_images/.htaccess`: klasörde PHP çalıştırma kapalı + dizin listeleme kapalı.
- [ ] **Deploy (canlıya çıkarken):** `dist/plugins/quill/`, `dist/diary_images/` (.htaccess ile birlikte),
      `upload_diary_image.php`, güncel `write-diary.php` + `includes/html_sanitizer.php` yüklenmeli.
- [ ] **Canlıdaki `ckeditor/` klasörü silinmeli** — artık kullanılmıyor ve `ckeditor/samples/` içindeki
      `posteddata.php` dosyaları başlı başına risk.
- [ ] Fotoğraf içerik denetimi: pornografik görsel otomatik engellenemiyor; fotoğraflar artık kendi
      sunucumuzda olduğu için report → admin panel (reports.php) akışıyla silinebilir. `delete_content`
      aksiyonunun içerikteki `dist/diary_images/` dosyalarını da diskten silmesi eklenebilir.
- [ ] Post silinince (`my-diary.php?sil=`) içindeki fotoğraflar diskte kalıyor — orphan temizliği
      düşünülebilir (öncelik değil).

### Güvenlik denetimi sonrası düzeltmeler (security-reviewer, 24 Eyl 2026)

- [x] **KRİTİK: upload bypass kapatıldı** — class.upload, GD'nin tanımadığı `image/*` mimeleri
      (ico başlıklı .html, .svg) re-encode etmeden ham kopyalıyordu → stored XSS. Artık class.upload'dan
      ÖNCE `getimagesize` ile gerçek tip kontrolü (sadece jpeg/png/gif/webp/bmp), explicit mime listesi,
      işlem sonrası uzantı doğrulaması. PoC'ler test edildi: ico+script ve svg reddediliyor.
- [x] **KRİTİK: sanitizer bypass kapatıldı** — eski regex-silme yaklaşımı attribute splicing ile
      aşılabiliyordu (silme işlemi yeni onmouseover üretiyordu). `html_sanitizer.php` sıfırdan yazıldı:
      default-deny tokenizer, her tag ya bizim sabit çıktımızla yeniden kurulur ya düşer; style attribute
      tamamen yasak, tag'ler otomatik balance ediliyor. 20 testlik saldırı paketi + idempotentlik geçti.
      (Not: DOMDocument bu lokal PHP'de segfault ettiği için bilerek DOM'suz çözüldü.)
- [x] Sıkıştırma: WebP %80 kalite (GD desteklemezse jpg %75), 1200px sınır. 426KB → 47KB ölçüldü.
- [x] Decompression bomb koruması: 25MP üstü piksel reddediliyor (küçük dosya dev boyut iddia edip RAM yiyemez).
- [x] Saat başı 20 upload limiti (session bazlı) — disk doldurma önlemi.
- [x] `write-diary.php` kayıt formuna CSRF eklendi (upload'da vardı, asıl kayıtta yoktu — test edildi).
- [x] Profil fotoğrafı yüklemesi de aynı şekilde sıkılaştırıldı (getimagesize ön kontrol + jpg re-encode
      + 5MB limit); `unlink` artık yeni fotoğraf başarıyla kaydedildikten SONRA ve sadece
      `dist/profile_pictures/` yolu için çalışıyor. `dist/profile_pictures/.htaccess` eklendi.
- [x] Her iki upload klasörünün .htaccess'i deny-all-except-images yapıldı + nosniff.
- [x] API (`diaries_functions.php`) artık ham HTML döndürmüyor — `strip_tags` ile düz yazı
      (mobil WebView XSS riski kapandı; web'den eklenen fotoğraflar mobilde görünmez, bilinen sınırlama).
- [x] Dosya adları `uniqid()` yerine `random_bytes` (tahmin edilemez).
- [x] `dashboard.php` truncate `mb_substr` oldu (UTF-8 karakter ortadan bölünmesin).

Açık kalanlar (düşük öncelik):
- [ ] `includes/db_connect.php`'de `mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)` yoksa
      write-diary'deki try/catch hiç tetiklenmez, başarısız insert "Save Successful" der — kontrol edilmeli.
- [ ] Site geneli CSP header'ı yok (inline script'ler yüzünden şimdilik eklenmedi).
- [ ] Session cookie flag'leri (`SameSite=Lax`, `HttpOnly`, `Secure`) site genelinde ayarlanabilir —
      canlıda HTTPS varken eklenmeli, lokalde Secure flag'i bozar.
- [ ] Upload rate limit session bazlı; çıkış yapıp girince sıfırlanır (kabul edilebilir sınırlama).

## Bildirim Sistemi (24 Eyl 2026)

- [x] Navbar zil simgesi artık sadece okunmamış bildirim varken animasyonlu + kırmızı noktalı;
      bildirim yoksa sabit ve noktasız. `friend_request` tablosuna `is_read` kolonu eklendi.
- [x] Bildirim dropdown'ına "Mark all as read" eklendi — sayfa yenilenmeden çalışıyor
      (`mark_notifications_read.php`, CSRF korumalı). İstekler okundu işaretlense de listede kalıyor
      (kabul/red hâlâ yapılabilir).
- [ ] **Deploy:** `migration_notifications.sql` prod DB'de çalıştırılmalı, `mark_notifications_read.php`
      ve güncel `includes/navbar.php` yüklenmeli. Migration çalıştırılmadan navbar SQL hatası verir!

## E-posta Doğrulama & Login Senaryosu (24 Eyl 2026)

İstek: mail servisi başarısız olursa kayıt bozulmasın, doğrulama sonraya ertelensin.

- [x] `register.php`: kullanıcı artık `status=0` (doğrulanmamış) oluşturuluyor, confirm token üretiliyor.
      Mail gönderimi `includes/mailer.php` içindeki `sendConfirmationMail()` ile yapılıyor; **mail
      başarısız olsa bile kayıt tamamlanıyor** ("hesabın oluştu, sonra doğrulayabilirsin" uyarısıyla).
      Mail linki artık doğru şekilde `confirm.php`'ye gidiyor (eskiden forgot.php recovery.php'ye
      gidiyordu, register maili yanlış hedefe gidebiliyordu).
- [x] `index.php` login: `status=0` kullanıcı **engellenmiyor**, sadece "email not confirmed" uyarısı
      gösteriliyor. Doğrulanmış (`status=1`) kullanıcıda uyarı yok.
- [x] `includes/navbar.php`: doğrulanmamış kullanıcıya her app sayfasında sarı bir hatırlatma bandı
      (`verify-banner`) + "Resend confirmation email" butonu gösteriliyor. Fresh DB sorgusu ile
      kontrol ediliyor (session bayatsa bile doğru), kullanıcı doğrulayınca band kayboluyor.
- [x] `resend_verification.php`: banddan mail tekrar gönderme (CSRF korumalı, 2 dk spam guard,
      mevcut token'ı reuse eder). SMTP yoksa uygun hata mesajı döner.
- [x] `confirm.php`: token ile `status` 0→1, token siliniyor. curl ile uçtan uca test edildi.
- [x] `migration_notifications.sql`'e `tokens` yoksa oluşturma NOT'u — zaten mevcut, dokunulmadı.
- [ ] **Deploy:** `includes/mailer.php`, `resend_verification.php`, güncel `register.php` / `index.php`
      / `includes/navbar.php` yüklenmeli. `mailer.php`'deki `"MAİL PASSWORD"` placeholder gerçek SMTP
      şifresiyle doldurulmalı (forgot.php'deki bilgiyle). **Not:** forgot.php hâlâ eski inline mail
      kodunu kullanıyor; istenirse o da `mailer.php`'ye taşınabilir (öncelik değil).
- [ ] Bilinen edge case: `tokens` tablosu hem email-confirm hem password-recovery için ortak. Kullanıcı
      doğrulanmadan (confirm token dururken) şifresini unutursa forgot.php "zaten token var" diyebilir.
      Nadir; ileride token'lara `type` kolonu eklenebilir.

## SQL/Fonksiyon Taraması Düzeltmeleri (25 Eyl 2026)

Tüm site tarandı (157 sorgu, 38 dosya), bulunan bozukluklar düzeltildi:

- [x] **`users.token` kolonu** → `migration_schema_fixes.sql` yazıldı ve lokalde uygulandı. Ban aksiyonu
      (handle_report.php), API register/login ve tokenLoginControl artık temiz kurulumda da çalışıyor
      (lokalde uçtan uca test edildi). **Deploy:** canlıda önce `SHOW COLUMNS FROM users LIKE 'token';`
      kontrol et — kolon zaten varsa migration'daki ilk ALTER satırını atla.
- [x] **user_detail yanlış profil** → `LIKE '%..%'` iki yerde de `username = ?` (prepared) oldu;
      "sqlfixtes" araması artık "sqlfixtest"i açmıyor, User not found veriyor (test edildi).
- [x] **register username kontrolü** → `&&` → `||`, pattern'ler anchored (`/^[0-9A-Za-z_]+$/` username,
      `/^[\p{L}0-9_ ]+$/u` realname — Türkçe karakter destekli). "kotu-isim!!" artık reddediliyor (test edildi).
- [x] **IP loglama** → register (web + API) artık log satırı açıyor; login (web + API) satır yoksa
      INSERT fallback yapıyor. Test edildi: silinen log satırı login'de yeniden oluştu.
- [x] **confirm/recovery token karışıklığı** → `tokens.type` kolonu ('confirm'/'recovery');
      confirm.php sadece confirm, recovery.php sadece recovery token kabul ediyor (çapraz test edildi:
      confirm token'ı recovery'de reddedildi). forgot.php "zaten token var" kontrolü de type'a bakıyor.
- [x] **FOLLOWERS/FOLLOWING** → arkadaşlık yönsüz olduğu için web'de tek **FRIENDS** sayısına çevrildi
      (user_detail + search, 2 kutulu görünüm). Mobil API'nin follower/following alanlarına DOKUNULMADI
      (uygulama sözleşmesi bozulmasın) — istenirse app güncellemesiyle birlikte değiştirilir.
- [x] **Silinmiş kullanıcının chat avatarı** → room.php + refresh_msg.php'de artık default resme düşüyor.
- [x] API login'de IP artık `REMOTE_ADDR`'dan yazılıyor (eskiden hep boş string'di).
- [ ] `bannedlist` tablosu ölü (hiçbir kod kullanmıyor) — migration'da DROP satırı YORUMLU bekliyor,
      silmek istersen aç.
- [ ] Not: API login response'u password hash'ini de döndürüyor — mobil sözleşme olduğu için dokunulmadı,
      app güncellemesinde alan response'tan çıkarılmalı.

## Beğeni + Görüntülenme (25 Eyl 2026)

- [x] `migration_likes_views.sql`: `feed_likes` ve `feed_views` tabloları (unique key ile kişi başı
      1 beğeni / 1 görüntülenme). Lokalde uygulandı.
- [x] `like_post.php`: AJAX beğeni toggle — CSRF korumalı; gizlilik kuralları post_detail ile aynı
      (privacy=2 herkes, privacy=1 sadece arkadaş, privacy=0 sadece sahibi; bloklu kullanıcı beğenemez).
      Test edildi: toggle, gizli post reddi, arkadaş-dışı herkese açık kabul, CSRF'siz red.
- [x] `includes/feed_stats.php`: recordFeedView / getFeedStats / feedActionsHtml yardımcıları.
- [x] Kart altı aksiyon barı: dashboard + user_detail + post_detail'de kalp butonu (sayfa yenilenmeden
      toggle, theme-v3.js delegation) + göz ikonu ile görüntülenme sayısı. my-diary'de kendi postlarında
      buton yerine statik sayaçlar. Kendi görüntülemen sayılmaz.
- [x] Privacy rozeti ikonu ti-eye → ti-world/ti-user (göz artık görüntülenmenin ikonu).
- [x] Post silinince (web my-diary, API deleteDiary, admin handle_report) beğeni/görüntülenme
      kayıtları da siliniyor.
- [ ] **Deploy:** `migration_likes_views.sql` prod DB'de çalıştırılmalı; `like_post.php`,
      `includes/feed_stats.php` + güncel dashboard/user_detail/post_detail/my-diary/navbar/handle_report,
      theme-v3.css/js yüklenmeli.
- [ ] Mobil API feed response'larına likes/views alanları EKLENMEDİ (app güncellemesi gerektirir);
      istenirse `getAllFeed`/`getMyDiaries`e alan eklemek app'i bozmaz, beğeni endpoint'i ayrıca yazılır.
- [x] Arkadaşlık isteğini geri çekme: `cancel_request.php` (CSRF'li, sadece kendi gönderdiğin istek
      silinir) + user_detail "Awaiting Response" yanına "Cancel Request" butonu. Geri çekilince karşı
      tarafın navbar bildirimi de otomatik düşüyor. **Deploy:** cancel_request.php + güncel user_detail.php.
      (Mobil API'de cancel yok — app güncellemesiyle eklenebilir.)

## Profil Ayarları Düzeltmesi (25 Eyl 2026)

- [x] "Boş bırakılan alan boş kaydediliyor" bugu kökten çözüldü: form artık mevcut değerlerle DOLU
      geliyor (username/realname/email/gender selected/birthday/bio) ve backend'de boş gelen her alan
      eski değerini koruyor (eski `elseif` zinciri sadece ilk boş alanı koruyordu).
- [x] Bio artık gerçekten kaydediliyor (eski `value="emptys"` textarea hilesi hiç çalışmıyordu,
      bio her update'te boşalıyordu).
- [x] Şifre artık zorunlu değil: boş bırakılırsa mevcut şifre AYNEN korunuyor, doldurulursa
      confirm eşleşmesiyle değişiyor (iki yönlü login testiyle doğrulandı).
- [x] Update sonrası session tazeleniyor (navbar/form eski değer göstermiyor).
- [x] Forma CSRF eklendi (bu formla email+şifre değiştirilebiliyordu, korumasızdı).
- [x] Bozuk username/realname regex'i burada da düzeltildi (register'daki fixin aynısı);
      username/email çakışma hatası artık register.php'ye YÖNLENDİRMİYOR, sayfada gösteriliyor.
- [ ] **Deploy:** güncel profile-page.php.

## Navbar Search Ortalama (25 Eyl 2026)

- [x] Arama kutusu navbar'ın ortasına alındı (theme-v3.css, absolute + translate); 768px altında
      eski sol akışına dönüyor (mobilde test edildi). **Deploy:** güncel theme-v3.css yeterli.

## Küçük UI İşleri (25 Eyl 2026)

- [x] Navbar user menüsünden ölü "Inbox" linki kaldırıldı.
- [x] Avatar değiştirme pop-up'a taşındı: profil kartının altında "Change Profile Picture" butonu →
      koyu temalı modal içinde yuvarlak canlı önizleme (hover'da kamera ikonu), dosya seçilince
      anında önizleme + dosya adı + aktifleşen Upload. Upload formuna CSRF eklendi (CSRF'siz upload
      işlenmiyor — test edildi). Eski ayrı upload kartı kaldırıldı (layout kaymasına sebep oluyordu).
      **Deploy:** güncel navbar.php + profile-page.php + theme-v3.css.

## Mobil / Responsive (25 Eyl 2026)

- [x] **Kök sorun:** Bootstrap 4 alpha'da `col-lg-*` dar ekranda %100 olmuyordu — kartlar 240px'e
      çöküyor, feed başlığındaki isim/tarih kayboluyordu. theme-v3.css'e ≤991px'te col-lg'leri tam
      genişliğe zorlayan kural eklendi (tüm sayfaları düzeltti).
- [x] Navbar mobilde tek satır (bootstrap alpha column'a çeviriyordu): hamburger + arama + zil + avatar.
- [x] Mobilde kart iç boşlukları daraltıldı, dropdown'lar ekrandan taşmıyor, profil sayfasındaki
      büyük avatar 180px'e sınırlandı.
- [x] Login/register/forgot sayfaları (librarys.php'ye media query): kutu mobilde tam genişlik +
      düzgün iç boşluk (masaüstü görünüm değişmedi — doğrulandı).
- [x] 390px (iPhone) emülasyonuyla gezilen sayfalar: dashboard, rooms, room (chat), social_settings,
      write-diary (Quill), my-diary, user_detail, profile-page, login, register — yatay taşma yok,
      sidebar hamburger ile açılıp kapanıyor.
- [ ] **Deploy:** güncel theme-v3.css + includes/librarys.php.

## Kullanıcı Sözleşmesi Görünürlüğü + Hesap Silme (25 Eyl 2026)

- [x] terms.php ve support.php artık her yerden erişilebilir: tüm app sayfalarının footer'ında
      + login sayfasında "Terms of Service · Support" linkleri (Apple review şartı sağlandı).
- [x] **Hesap silme (Apple 5.1.1(v) zorunluluğu):**
      - `includes/account_delete.php`: merkezi `deleteUserAccount()` — kullanıcının HER ŞEYİNİ siler:
        profil, postlar (+fotoğraf dosyaları diskten), beğeniler/görüntülenmeler (iki yönlü),
        arkadaşlıklar, istekler, bloklar, tokenlar, chat mesajları (tüm *_room tabloları),
        chat_online, log, raporlar, profil fotoğrafı dosyası.
      - Web: Account Settings'te "Danger Zone" kartı → şifre onaylı modal → `delete_account.php`
        (CSRF + şifre doğrulama) → session düşer → login'de "hesabın silindi" mesajı.
      - Mobil: `api/v1/delete-account.php` (Authorization token + şifre onayı, mevcut API kalıbında).
      - Test edildi: yanlış şifre reddi, CSRF, tam temizlik (11 tablo + disk dosyaları), başkasının
        verisinin korunması, API iki yönlü.
- [x] Canlı repo klasörüne kopyalandı (DB değişikliği gerektirmiyor).
- [ ] Mobil app'e "Delete Account" ekranı eklenmeli (endpoint hazır) — app güncellemesi.
- [x] **Apple'a verilecek public hesap silme linki:** `https://im2alone.com/account-deletion.php` —
      login gerektirmez, kullanıcı adı + şifre + onay kutusuyla doğrulayıp hesabı kalıcı siler
      (aynı deleteUserAccount fonksiyonu). support.php'deki "email at..." maddesi de bu sayfaya
      bağlandı (Apple email-only silmeyi kabul etmiyor). App Store Connect'te "Account deletion" /
      privacy alanına bu URL yazılacak. Canlı klasöre kopyalandı.

## LOKAL ORTAM NOTU (canlıyı etkilemez)

- [ ] **Session ayarı:** PHP built-in server Windows'ta `session.save_path` boşken session'ları
      güvenilmez yazıyordu (login sonrası oturum kayboluyordu). Server artık şu ek parametreyle
      başlatılmalı: `-d session.save_path="C:/Users/nolur/Desktop/im2alone/php-sessions"`.
      PC yeniden başladığında MySQL + PHP server'ı başlatırken bu parametreyi unutma.
      Canlı sunucuda (cPanel/Apache) session.save_path zaten tanımlı, orada sorun yok.

## Test Edilmeli

- [ ] `block-user.php`, `unblock-user.php`, `get-blocked-users.php`, `report-content.php` uçları Postman
      ile manuel test edilmeli; başarılı olursa `im2alone.com.postman_collection.json` koleksiyonuna da
      eklenmeli (repoda mevcut pattern böyle).
- [ ] `register.php` (API) → `eulaAccepted` zorunluluğu eklendi; mobil istemcinin bu alanı gönderdiğinden
      emin olunmalı (bu repoda değil, mobil app tarafında).
- [ ] Banlı kullanıcı ile giriş denemesi: `login.php` (API) → `"banned" => true"` durumunda doğru mesaj
      dönüyor mu, token login (`tokenLoginControl`) banlı kullanıcıyı gerçekten reddediyor mu.
- [ ] `handle_report.php` → `ban` aksiyonu aynı feed'e ait diğer bekleyen raporları da `actioned` yapıyor;
      aynı *kullanıcıya* ait başka bekleyen raporlar (farklı feed/feed'siz) kapatılmıyor — istenen davranış
      bu mu kontrol edilmeli.
