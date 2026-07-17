<?php
/*
 * Local-only router for PHP's built-in dev server (php -S), replicating the
 * subset of .htaccess mod_rewrite rules needed to run LCWO without Apache.
 * Not used in production (Apache serves via .htaccess there).
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($uri, '/');

// Let the built-in server serve existing static files (css, js, images...) directly.
$file = __DIR__ . '/' . $path;
if ($path !== '' && is_file($file)) {
    return false;
}

$rules = [
    '#^robots\.txt$#' => '/robots.php',
    '#^atom\.xml$#' => '/api/atom.php',
    '#^forumatom\.xml$#' => '/api/forumatom.php',
    '#^changelogatom\.xml$#' => '/api/changelogatom.php',
    '#^forum/edit/([0-9]+)$#' => ['p' => 'forum', 'e' => '$1'],
    '#^forum/showall$#' => ['p' => 'forum', 'showall' => '1'],
    '#^forum/([0-9]+)/?(.+)?$#' => ['p' => 'forum', 't' => '$1'],
    '#^users/member/([0-9]+)/([0-9]+)/([a-z]+)/([a-z]+)/?$#' => ['p' => 'users', 'member' => '$1', 'l' => '$2', 'o' => '$3', 'd' => '$4'],
    '#^users/member/([0-9]+)/([0-9]+)/?$#' => ['p' => 'users', 'member' => '$1', 'l' => '$2'],
    '#^users/member/([0-9]+)/?$#' => ['p' => 'users', 'member' => '$1'],
    '#^users/([0-9]+)/([a-z]+)/([a-z]+)/?$#' => ['p' => 'users', 'l' => '$1', 'o' => '$2', 'd' => '$3'],
    '#^users/([0-9]+)/?$#' => ['p' => 'users', 'l' => '$1'],
    '#^highscores/group/([0-9]+)/([a-z]+)/([0-9]+)/?$#' => ['p' => 'highscores', 'group' => '$1', '$2' => '$3'],
    '#^highscores/group/([0-9]+)/?$#' => ['p' => 'highscores', 'group' => '$1'],
    '#^highscores/([a-z]+)/([0-9]+)/?$#' => ['p' => 'highscores', '$1' => '$2'],
    '#^usergroups/([0-9]+)/uploadpic/ok$#' => ['p' => 'usergroups', 'group' => '$1', 'uploadpic' => '2'],
    '#^usergroups/([0-9]+)/uploadpic$#' => ['p' => 'usergroups', 'group' => '$1', 'uploadpic' => '1'],
    '#^usergroups/new$#' => ['p' => 'usergroups', 'new' => '1'],
    '#^usergroups/join/([0-9]+)$#' => ['p' => 'usergroups', 'join' => '$1'],
    '#^usergroups/map/([0-9]+)$#' => ['p' => 'usergroups', 'map' => 'true', 'gid' => '$1'],
    '#^usergroups/map$#' => ['p' => 'usergroups', 'map' => 'true'],
    '#^usergroups/subscribe$#' => ['p' => 'usergroups', 'subscribe' => 'show'],
    '#^usergroups/subscribe/((-)?[0-9]+)?$#' => ['p' => 'usergroups', 'subscribe' => '$1'],
    '#^usergroups/leave/([0-9]+)$#' => ['p' => 'usergroups', 'leave' => '$1'],
    '#^usergroups/([0-9]+)/approve/([0-9]+)/([01])$#' => ['p' => 'usergroups', 'group' => '$1', 'approve' => '$2', 'ok' => '$3'],
    '#^usergroups/([0-9]+)/edit/ok$#' => ['p' => 'usergroups', 'group' => '$1', 'edit' => '2'],
    '#^usergroups/([0-9]+)/edit/?$#' => ['p' => 'usergroups', 'group' => '$1', 'edit' => '1'],
    '#^usergroups/([0-9]+)/showall$#' => ['p' => 'usergroups', 'group' => '$1', 'showall' => '1'],
    '#^usergroups/([0-9]+)/?$#' => ['p' => 'usergroups', 'group' => '$1'],
    '#^profile/newaboutme$#' => ['p' => 'profile', 'newaboutme' => '1'],
    '#^profile/newimage$#' => ['p' => 'profile', 'newimage' => '1'],
    '#^profile/([A-Za-z0-9]+)$#' => ['p' => 'profile', 'u' => '$1'],
    '#^delete/([a-z]+)/([0-9]+)$#' => ['p' => 'delete', 'type' => '$1', 'nr' => '$2'],
    '#^pmsg/([a-z]+)/([a-zA-Z0-9]+)$#' => ['p' => 'pmsg', 'action' => '$1', 'id' => '$2'],
    '#^main/([a-z])$#' => ['p' => 'main', '$1' => '1'],
    '#^news/all$#' => ['p' => 'news', 'all' => '1'],
    '#^news/delete/([0-9]+)$#' => ['p' => 'news', 'delete' => '$1'],
    '#^lostpassword/([a-z0-9]+)/([a-zA-Z0-9]+)$#' => ['p' => 'lostpassword', 'h' => '$1', 'u' => '$2'],
    '#^([a-z0-9]{2,2})/([a-z0-9]+)/?$#' => ['p' => '$2', 'hl' => '$1'],
    '#^([a-z0-9]{3,20})/?$#' => ['p' => '$1'],
];

foreach ($rules as $pattern => $target) {
    if (preg_match($pattern, $path, $m)) {
        if (is_string($target)) {
            require __DIR__ . $target;
            return true;
        }
        foreach ($target as $key => $value) {
            $key = preg_replace_callback('/\$(\d+)/', fn($mm) => $m[$mm[1]] ?? '', $key);
            $value = preg_replace_callback('/\$(\d+)/', fn($mm) => $m[$mm[1]] ?? '', $value);
            $_GET[$key] = $value;
        }
        $_SERVER['DOCUMENT_ROOT'] = __DIR__;
        chdir(__DIR__);
        require __DIR__ . '/index.php';
        return true;
    }
}

if ($path === '' || $path === 'index.php') {
    $_SERVER['DOCUMENT_ROOT'] = __DIR__;
    chdir(__DIR__);
    require __DIR__ . '/index.php';
    return true;
}

return false;
