<?php
function labEscape($value) { return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8'); }
function labPost($key, $default = '') { return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : $default; }
function labToken()
{
    if (empty($_SESSION['lab_token'])) $_SESSION['lab_token'] = bin2hex(random_bytes(32));
    return $_SESSION['lab_token'];
}
function labCheckPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método no permitido.'); }
    if (!hash_equals(labToken(), labPost('csrf'))) { http_response_code(403); exit('Formulario vencido. Recargue la página e intente nuevamente.'); }
}
function labId($value)
{
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
    if ($id === false) { http_response_code(400); exit('Código inválido.'); }
    return $id;
}
function labRedirect($path) { header('Location: ' . urlRoot . $path); exit; }
