<?php
function csrfToken()
{
  if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrfTokenValid($token)
{
  if (!is_string($token) || $token === '') {
    return false;
  }
  if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
    return false;
  }
  return hash_equals($_SESSION['csrf_token'], $token);
}
