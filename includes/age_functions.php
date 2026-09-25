<?php
//App Store yas siniflandirmasi: sosyal medya / mesajlasma ozellikleri 13 yas alti icin kapali.
//im2alone'un tamami sosyal oldugu icin 13 yas alti kayit olamaz ve giris yapamaz.
//users.birthday kolonu 'd/m/Y' string olarak tutuluyor (or. 30/09/1999)
define('IM2ALONE_MIN_AGE', 13);

//'d/m/Y' string'ini dogrular, normalize edilmis 'd/m/Y' dondurur (1/1/2000 -> 01/01/2000).
//gecersiz, kaymali (31/02/2020) veya ileri tarihli ise null
function normalizeBirthday($birthday)
{
  if (!is_string($birthday) || trim($birthday) == "") {
    return null;
  }
  $birthday = trim($birthday);
  $date = DateTime::createFromFormat('!d/m/Y', $birthday);
  if ($date === false) {
    return null;
  }
  $errors = DateTime::getLastErrors(); //PHP 8.2+ hata yoksa false doner
  if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
    return null;
  }
  $today = new DateTime('today');
  if ($date > $today) {
    return null;
  }
  return $date->format('d/m/Y');
}

//birthday'den yas hesaplar, tarih gecersiz/bos ise null
function ageFromBirthday($birthday)
{
  $normalized = normalizeBirthday($birthday);
  if ($normalized === null) {
    return null;
  }
  $date = DateTime::createFromFormat('!d/m/Y', $normalized);
  $today = new DateTime('today');
  return (int) $date->diff($today)->y;
}

//13 yasindan kucuk mu? dogum tarihi bilinmiyorsa (eski kayitlar, bos birthday) false doner
function isUnderMinAge($birthday)
{
  $age = ageFromBirthday($birthday);
  if ($age === null) {
    return false;
  }
  return $age < IM2ALONE_MIN_AGE;
}
?>
