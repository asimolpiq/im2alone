<?php
//diary html cleaning, default deny tokenizer. the old regex delete approach could be
//bypassed (attribute splicing, security review 24 sep 2026). here every tag is either
//rebuilt from scratch by us or dropped, so no user written attribute ever survives.
function sanitizeDiaryHtml($html)
{
  if (!is_string($html) || trim($html) === '') {
    return '';
  }

  //tags we rebuild. void = no closing tag needed
  $paired_tags = array('p', 'b', 'strong', 'i', 'em', 'u', 'ul', 'ol', 'li', 'span', 'a');
  $void_tags = array('br', 'img');

  preg_match_all('/<[^>]*>|[^<]+|</su', $html, $matches);
  $out = '';
  $stack = array();

  foreach ($matches[0] as $token) {
    if ($token === '' ) {
      continue;
    }
    if ($token[0] !== '<' || substr($token, -1) !== '>') {
      //plain text (or a stray "<"), escape it. double_encode false keeps old &amp; entities intact
      $out .= htmlspecialchars($token, ENT_QUOTES, 'UTF-8', false);
      continue;
    }

    //closing tag?
    if (preg_match('/^<\/\s*([a-z0-9]+)\s*>$/i', $token, $m)) {
      $tag = strtolower($m[1]);
      if (in_array($tag, $paired_tags, true) && in_array($tag, $stack, true)) {
        //close everything opened after it too, keeps the html balanced
        while (count($stack) > 0) {
          $top = array_pop($stack);
          $out .= '</' . $top . '>';
          if ($top === $tag) {
            break;
          }
        }
      }
      continue; //unknown or unopened closing tag: dropped
    }

    //opening tag?
    if (!preg_match('/^<\s*([a-z0-9]+)/i', $token, $m)) {
      continue; //comment, doctype, garbage: dropped
    }
    $tag = strtolower($m[1]);

    if ($tag === 'img') {
      //only our own upload folder, tag rebuilt from scratch
      if (preg_match('/\ssrc\s*=\s*("|\')?(dist\/diary_images\/[A-Za-z0-9._\-]+)/i', $token, $src)) {
        $out .= '<img src="' . htmlspecialchars($src[2], ENT_QUOTES) . '" alt="diary image">';
      }
      continue;
    }

    if ($tag === 'a') {
      $href = '#';
      if (preg_match('/\shref\s*=\s*("([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $token, $h)) {
        $value = isset($h[4]) && $h[4] !== '' ? $h[4] : (isset($h[3]) && $h[3] !== '' ? $h[3] : $h[2]);
        if (isSafeUrlValue($value)) {
          $href = $value;
        }
      }
      $out .= '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '">';
      $stack[] = 'a';
      continue;
    }

    if (in_array($tag, $void_tags, true)) {
      $out .= '<' . $tag . '>';
      continue;
    }

    if (in_array($tag, $paired_tags, true)) {
      $out .= '<' . $tag . '>'; //attributes never copied over
      $stack[] = $tag;
    }
    //anything else (script, div, style, whatever): dropped, only its inner text remains
  }

  //close whatever the user forgot to close
  while (count($stack) > 0) {
    $out .= '</' . array_pop($stack) . '>';
  }

  return $out;
}

function isSafeUrlValue($value)
{
  $normalised = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
  $normalised = preg_replace('/[\x00-\x20\x7F]+/', '', $normalised);
  if ($normalised === null || $normalised === '') {
    return true;
  }
  if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $normalised, $scheme)) {
    return in_array(strtolower($scheme[1]), array('http', 'https', 'mailto'), true);
  }
  return true;
}
