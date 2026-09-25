<?php
//bugun kullanicinin dogum gunuyse konfeti patlat + kutlama mesaji goster
//dashboard.php ve my-diary.php icinden cagriliyor
if (isset($im2alone_user['birthday']) && $im2alone_user['birthday'] != "") {
  $bday_check = substr($im2alone_user['birthday'], 0, 5); //dd/mm kismi
  $bday_now = date('d/m');
  if ($bday_check == $bday_now) {
    $bday_username = htmlspecialchars($im2alone_user['username'], ENT_QUOTES);
    echo <<<BDAY
<canvas id="bday-confetti"></canvas>
<div id="bday-greet">&#127874; Happy Birthday, $bday_username! &#127881;</div>
<style>
#bday-confetti{position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:99998;}
#bday-greet{position:fixed;top:16%;left:50%;transform:translateX(-50%) scale(.5);opacity:0;
  background:#fff;color:#333;font-size:22px;font-weight:600;padding:14px 28px;border-radius:50px;
  box-shadow:0 8px 30px rgba(0,0,0,.18);z-index:99999;pointer-events:none;white-space:nowrap;
  animation:bdayPop .6s .3s ease forwards, bdayFade .8s 5.3s ease forwards;}
@keyframes bdayPop{to{transform:translateX(-50%) scale(1);opacity:1;}}
@keyframes bdayFade{to{transform:translateX(-50%) scale(.8);opacity:0;}}
@media (max-width:600px){#bday-greet{font-size:16px;padding:10px 18px;}}
</style>
<script>
(function(){
  var canvas = document.getElementById('bday-confetti');
  var ctx = canvas.getContext('2d');
  function resize(){ canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
  resize();
  window.addEventListener('resize', resize);

  var colors = ['#f44336','#e91e63','#9c27b0','#3f51b5','#03a9f4','#009688','#4caf50','#ffeb3b','#ff9800','#ff5722'];
  var parts = [];

  //x,y: patlama noktasi, dir: firlatma acisi (radyan), count: parcacik sayisi
  function burst(x, y, dir, count){
    for(var i = 0; i < count; i++){
      var angle = dir + (Math.random() - .5) * 1.2;
      var power = 8 + Math.random() * 9;
      parts.push({
        x: x, y: y,
        vx: Math.cos(angle) * power,
        vy: Math.sin(angle) * power,
        w: 6 + Math.random() * 6,
        h: 4 + Math.random() * 4,
        rot: Math.random() * Math.PI,
        vrot: (Math.random() - .5) * .3,
        color: colors[Math.floor(Math.random() * colors.length)],
        life: 0
      });
    }
  }

  //sure gercek zamana bagli: her ekranda (60Hz/144Hz) ayni hizda akar, toplam ~5.8sn
  var t0 = null, last = null, TOTAL = 5800, FADE_START = 5000;
  function frame(ts){
    if(t0 === null){ t0 = ts; last = ts; }
    var dt = Math.min((ts - last) / 16.67, 3); //60fps'e normalize adim
    last = ts;
    var elapsed = ts - t0;
    var globalFade = elapsed > FADE_START ? Math.max(0, 1 - (elapsed - FADE_START) / (TOTAL - FADE_START)) : 1;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for(var i = parts.length - 1; i >= 0; i--){
      var p = parts[i];
      p.vy += 0.06 * dt;  //yercekimi (yavas, konfeti havada sursun)
      p.vx *= Math.pow(0.99, dt); //hava surtunmesi
      p.x += p.vx * 0.6 * dt;     //agir cekim hissi
      p.y += p.vy * 0.6 * dt;
      p.rot += p.vrot * dt;
      if(p.y > canvas.height + 30){ parts.splice(i, 1); continue; }
      ctx.save();
      ctx.translate(p.x, p.y);
      ctx.rotate(p.rot);
      ctx.globalAlpha = globalFade;
      ctx.fillStyle = p.color;
      ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
      ctx.restore();
    }
    if(parts.length > 0 && elapsed < TOTAL){
      requestAnimationFrame(frame);
    } else {
      canvas.parentNode && canvas.parentNode.removeChild(canvas);
    }
  }

  //sol alt + sag alt koselerden capraz, ortadan yukari - patlamalar ~2.5sn'ye yayilmis dalgalar
  burst(0, canvas.height, -Math.PI / 4, 70);
  burst(canvas.width, canvas.height, -3 * Math.PI / 4, 70);
  setTimeout(function(){ burst(canvas.width / 2, canvas.height, -Math.PI / 2, 80); }, 700);
  setTimeout(function(){
    burst(canvas.width * .25, canvas.height, -Math.PI / 2.5, 50);
    burst(canvas.width * .75, canvas.height, -Math.PI / 1.7, 50);
  }, 1500);
  setTimeout(function(){
    burst(0, canvas.height, -Math.PI / 4, 40);
    burst(canvas.width, canvas.height, -3 * Math.PI / 4, 40);
  }, 2300);
  requestAnimationFrame(frame);

  var greet = document.getElementById('bday-greet');
  setTimeout(function(){ greet.parentNode && greet.parentNode.removeChild(greet); }, 6800);
})();
</script>
BDAY;
  }
}
?>
