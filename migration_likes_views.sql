-- Gunluk begeni + goruntulenme (25 Eyl 2026) - canliya cikarken calistirilmali.

CREATE TABLE IF NOT EXISTS feed_likes (
  id INT NOT NULL AUTO_INCREMENT,
  feed_id INT NOT NULL,
  user_id INT NOT NULL,
  date VARCHAR(25) NOT NULL,
  PRIMARY KEY(id),
  UNIQUE KEY uniq_like (feed_id, user_id)
);

CREATE TABLE IF NOT EXISTS feed_views (
  id INT NOT NULL AUTO_INCREMENT,
  feed_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY(id),
  UNIQUE KEY uniq_view (feed_id, user_id)
);
