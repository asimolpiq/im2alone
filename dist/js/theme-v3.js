// tema v3: sidebar'da gerçekten açık olan sayfayı işaretler.
// left_menu.php'deki her <li> hard-coded "active" olduğundan o class kullanılamıyor;
// bunun yerine URL'e bakıp ilgili linke .is-current ekliyoruz (CSS theme-v3.css'te).
document.addEventListener('DOMContentLoaded', function () {
  var current = (location.pathname.split('/').pop() || 'dashboard.php').toLowerCase();
  var links = document.querySelectorAll('.sidebar-menu a[href]');
  for (var i = 0; i < links.length; i++) {
    var href = (links[i].getAttribute('href') || '').split('?')[0].toLowerCase();
    if (href !== '' && href !== '#' && href === current) {
      links[i].classList.add('is-current');
    }
  }

  // treeview'lar (Admin Settings) her sayfada otomatik açılmasın:
  // niche.js window.load'da .treeview.active olanları açıyor; alt menüsünde
  // aktif sayfa yoksa "active" class'ını kaldır ki kapalı başlasın.
  var trees = document.querySelectorAll('.sidebar-menu li.treeview');
  for (var t = 0; t < trees.length; t++) {
    if (!trees[t].querySelector('.treeview-menu a.is-current')) {
      trees[t].classList.remove('active');
    }
  }
});

// Navbar dropdown'larını (bildirim/kullanıcı menüsü) güvenilir şekilde kapat.
function themeV3CloseDropdowns(except) {
  var opens = document.querySelectorAll('.dropdown.show, .dropdown.open');
  for (var i = 0; i < opens.length; i++) {
    if (!except || !opens[i].contains(except)) {
      opens[i].classList.remove('show');
      opens[i].classList.remove('open');
    }
  }
}
// 1) Sayfada menü dışı bir yere tıklanınca kapat (capture: bir script
//    stopPropagation yapsa bile yakalar).
document.addEventListener('click', function (e) {
  themeV3CloseDropdowns(e.target);
}, true);
// 2) Iframe'e tıklama (Spotify embed, CKEditor) ana dokümana click üretmez;
//    window blur + activeElement iframe kontrolüyle yakala.
window.addEventListener('blur', function () {
  setTimeout(function () {
    if (document.activeElement && document.activeElement.tagName === 'IFRAME') {
      themeV3CloseDropdowns(null);
    }
  }, 0);
});
// 3) ESC ile de kapansın.
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') { themeV3CloseDropdowns(null); }
});

// Feed beğeni butonu (dashboard/user_detail/post_detail) — sayfa yenilenmeden toggle.
document.addEventListener('click', function (e) {
  var btn = e.target.closest ? e.target.closest('.feed-like-btn') : null;
  if (!btn) { return; }
  var tokenEl = document.getElementById('app-csrf-token');
  if (!tokenEl || btn.dataset.busy === '1') { return; }
  btn.dataset.busy = '1';
  var fd = new FormData();
  fd.append('feed_id', btn.getAttribute('data-feed'));
  fd.append('csrf_token', tokenEl.value);
  fetch('like_post.php', { method: 'POST', body: fd })
    .then(function (r) { return r.json(); })
    .then(function (res) {
      if (res.success) {
        btn.classList.toggle('is-liked', res.liked);
        var icon = btn.querySelector('i');
        if (icon) { icon.className = res.liked ? 'fa fa-heart' : 'fa fa-heart-o'; }
        var count = btn.querySelector('.like-count');
        if (count) { count.textContent = res.count; }
      }
    })
    .catch(function () {})
    .finally(function () { btn.dataset.busy = ''; });
});
