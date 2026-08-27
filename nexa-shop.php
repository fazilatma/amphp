<?php
/**
 * NEXA Shop — فروشگاه لوکس تک‌فایلی PHP
 * ووکامرس‌گونه + اعلان بله/تلگرام/روبیکا + چت آنلاین + نصب افزونه وردپرس
 *
 * نیازمندی: PHP 8.0+  sqlite3  json  (اختیاری: curl, zip)
 * اجرا: php -S 0.0.0.0:8080 nexa-shop.php
 * ورود مدیر: admin@nexa.shop / admin123
 */
declare(strict_types=1);
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Tehran');

define('NEXA', true);
define('ABSPATH', __DIR__ . '/');
define('WPINC', 'wp-includes');
define('NEXA_DB', __DIR__ . '/data/nexa.sqlite');
define('NEXA_PLUGINS', __DIR__ . '/plugins');
define('NEXA_UPLOADS', __DIR__ . '/uploads');

foreach ([dirname(NEXA_DB), NEXA_PLUGINS, NEXA_UPLOADS] as $d) {
    if (!is_dir($d)) @mkdir($d, 0775, true);
}

/* ───────── WordPress / WooCommerce compatibility layer ───────── */
class NexaHooks {
    public array $actions = [];
    public array $filters = [];
    public function add(string $type, string $hook, $cb, int $pri): void {
        $this->{$type}[$hook][$pri][] = $cb;
    }
    public function do_action(string $hook, array $args): void {
        if (empty($this->actions[$hook])) return;
        ksort($this->actions[$hook]);
        foreach ($this->actions[$hook] as $cbs) {
            foreach ($cbs as $cb) {
                if (is_callable($cb)) $cb(...$args);
            }
        }
    }
    public function apply(string $hook, $value, array $args) {
        if (empty($this->filters[$hook])) return $value;
        ksort($this->filters[$hook]);
        foreach ($this->filters[$hook] as $cbs) {
            foreach ($cbs as $cb) {
                if (is_callable($cb)) $value = $cb($value, ...$args);
            }
        }
        return $value;
    }
}
$GLOBALS['nexa_hooks'] = new NexaHooks();
$GLOBALS['nexa_shortcodes'] = [];
$GLOBALS['nexa_admin_pages'] = [];

if (!function_exists('add_action')) {
    function add_action($hook, $cb, $pri = 10, $accepted = 1) { $GLOBALS['nexa_hooks']->add('actions', $hook, $cb, (int)$pri); return true; }
    function add_filter($hook, $cb, $pri = 10, $accepted = 1) { $GLOBALS['nexa_hooks']->add('filters', $hook, $cb, (int)$pri); return true; }
    function do_action($hook, ...$args) { $GLOBALS['nexa_hooks']->do_action($hook, $args); }
    function apply_filters($hook, $value, ...$args) { return $GLOBALS['nexa_hooks']->apply($hook, $value, $args); }
    function add_shortcode($tag, $cb) { $GLOBALS['nexa_shortcodes'][$tag] = $cb; }
    function do_shortcode($content) {
        return preg_replace_callback('/\[(\w+)([^\]]*)\]/', function ($m) {
            $cb = $GLOBALS['nexa_shortcodes'][$m[1]] ?? null;
            return $cb ? (string)$cb([], $m[0]) : $m[0];
        }, (string)$content);
    }
    function __($s, $d = 'default') { return $s; }
    function _e($s, $d = 'default') { echo $s; }
    function esc_html($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
    function esc_attr($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
    function esc_url($s) { return filter_var($s, FILTER_SANITIZE_URL); }
    function sanitize_text_field($s) { return trim(strip_tags((string)$s)); }
    function wp_enqueue_script(...$a) {}
    function wp_enqueue_style(...$a) {}
    function wp_register_script(...$a) {}
    function wp_register_style(...$a) {}
    function add_menu_page($t, $m, $cap, $slug, $cb = null, $icon = '', $pos = null) { $GLOBALS['nexa_admin_pages'][$slug] = [$t, $cb]; }
    function add_submenu_page(...$a) {}
    function register_activation_hook($file, $cb) { if (is_callable($cb)) $cb(); }
    function register_deactivation_hook($file, $cb) {}
    function plugin_dir_path($f) { return rtrim(dirname($f), '/') . '/'; }
    function plugin_dir_url($f) { return '/plugins/' . basename(dirname($f)) . '/'; }
    function trailingslashit($s) { return rtrim($s, '/') . '/'; }
    function is_admin() { return (($GLOBALS['nexa_user']['role'] ?? '') === 'admin'); }
    function wp_get_current_user() { return (object)($GLOBALS['nexa_user'] ?? ['ID' => 0]); }
    function get_current_user_id() { return (int)($GLOBALS['nexa_user']['id'] ?? 0); }
    function current_user_can($cap) { return is_admin(); }
    function wp_die($m = '') { nexa_json(['ok' => false, 'error' => (string)$m], 400); }
    function maybe_unserialize($d) { $u = @unserialize($d); return $u === false && $d !== 'b:0;' ? $d : $u; }
    function maybe_serialize($d) { return is_array($d) || is_object($d) ? serialize($d) : $d; }
    function get_option($k, $d = false) {
        $v = nexa_setting($k, null);
        if ($v === null) {
            $row = nexa_pdo()->prepare('SELECT option_value FROM wp_options WHERE option_name=?');
            $row->execute([$k]); $r = $row->fetchColumn();
            return $r === false ? $d : maybe_unserialize($r);
        }
        return $v;
    }
    function update_option($k, $v) {
        nexa_set_setting($k, is_scalar($v) ? (string)$v : json_encode($v, JSON_UNESCAPED_UNICODE));
        $st = nexa_pdo()->prepare('INSERT INTO wp_options(option_name,option_value) VALUES(?,?) ON CONFLICT(option_name) DO UPDATE SET option_value=excluded.option_value');
        $st->execute([$k, maybe_serialize($v)]);
        return true;
    }
    function delete_option($k) { nexa_pdo()->prepare('DELETE FROM wp_options WHERE option_name=?')->execute([$k]); return true; }
    function add_option($k, $v) { return update_option($k, $v); }
}

class wpdb {
    public $prefix = 'wp_';
    public $insert_id = 0;
    public $last_error = '';
    public $posts = 'wp_posts';
    public $options = 'wp_options';
    public $users = 'wp_users';
    public function prepare($q, ...$args) {
        if (isset($args[0]) && is_array($args[0])) $args = $args[0];
        $q = str_replace(['%%'], ['%'], $q);
        $i = 0;
        return preg_replace_callback('/%[sdf]/', function () use (&$i, $args) {
            $v = $args[$i++] ?? '';
            return "'" . str_replace("'", "''", (string)$v) . "'";
        }, $q);
    }
    public function query($q) {
        try { nexa_pdo()->exec($q); return true; } catch (Throwable $e) { $this->last_error = $e->getMessage(); return false; }
    }
    public function get_var($q) { try { return nexa_pdo()->query($q)->fetchColumn(); } catch (Throwable $e) { return null; } }
    public function get_row($q, $out = OBJECT) {
        try { $r = nexa_pdo()->query($q)->fetch(PDO::FETCH_ASSOC); return $r ? (object)$r : null; } catch (Throwable $e) { return null; }
    }
    public function get_results($q, $out = OBJECT) {
        try { $r = nexa_pdo()->query($q)->fetchAll(PDO::FETCH_ASSOC); return array_map(fn($x) => (object)$x, $r); } catch (Throwable $e) { return []; }
    }
    public function insert($table, $data) {
        $cols = implode(',', array_keys($data));
        $qs = implode(',', array_fill(0, count($data), '?'));
        $st = nexa_pdo()->prepare("INSERT INTO $table ($cols) VALUES ($qs)");
        $st->execute(array_values($data));
        $this->insert_id = (int)nexa_pdo()->lastInsertId();
        return true;
    }
    public function update($table, $data, $where) {
        $set = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
        $w = implode(' AND ', array_map(fn($k) => "$k=?", array_keys($where)));
        $st = nexa_pdo()->prepare("UPDATE $table SET $set WHERE $w");
        $st->execute([...array_values($data), ...array_values($where)]);
        return true;
    }
}
$GLOBALS['wpdb'] = new wpdb();
if (!isset($wpdb)) { $wpdb = $GLOBALS['wpdb']; }
if (!defined('OBJECT')) define('OBJECT', 'OBJECT');
if (!defined('ARRAY_A')) define('ARRAY_A', 'ARRAY_A');

/* ───────── Database ───────── */
function nexa_pdo(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $pdo = new PDO('sqlite:' . NEXA_DB, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys=ON; PRAGMA journal_mode=WAL;');
    return $pdo;
}
function nexa_hash(string $pw): string {
    $salt = bin2hex(random_bytes(16));
    return 'pbkdf2:' . $salt . ':' . hash_pbkdf2('sha256', $pw, $salt, 120000);
}
function nexa_verify(string $pw, string $stored): bool {
    $p = explode(':', $stored);
    if (count($p) !== 3 || $p[0] !== 'pbkdf2') return false;
    return hash_equals($p[2], hash_pbkdf2('sha256', $pw, $p[1], 120000));
}
function nexa_now(): string { return date('Y-m-d H:i:s'); }

function nexa_init(): void {
    $db = nexa_pdo();
    $db->exec("
    CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY, name TEXT, email TEXT UNIQUE, phone TEXT, pass TEXT, role TEXT DEFAULT 'customer', created TEXT);
    CREATE TABLE IF NOT EXISTS sessions(id TEXT PRIMARY KEY, user_id INTEGER, data TEXT, expires INTEGER);
    CREATE TABLE IF NOT EXISTS categories(id INTEGER PRIMARY KEY, slug TEXT UNIQUE, name TEXT, icon TEXT);
    CREATE TABLE IF NOT EXISTS products(id INTEGER PRIMARY KEY, title TEXT, slug TEXT UNIQUE, sku TEXT, cat TEXT, price INTEGER, sale_price INTEGER DEFAULT 0, stock INTEGER, image TEXT, description TEXT, featured INTEGER DEFAULT 1, status TEXT DEFAULT 'publish', created TEXT);
    CREATE TABLE IF NOT EXISTS orders(id INTEGER PRIMARY KEY, number TEXT UNIQUE, user_id INTEGER, items TEXT, subtotal INTEGER, discount INTEGER, shipping INTEGER, total INTEGER, status TEXT, pay TEXT, address TEXT, note TEXT, coupon TEXT, created TEXT);
    CREATE TABLE IF NOT EXISTS coupons(id INTEGER PRIMARY KEY, code TEXT UNIQUE, type TEXT, amount INTEGER, min_total INTEGER DEFAULT 0, max_uses INTEGER DEFAULT 0, used INTEGER DEFAULT 0, active INTEGER DEFAULT 1);
    CREATE TABLE IF NOT EXISTS reviews(id INTEGER PRIMARY KEY, product_id INTEGER, user_id INTEGER, name TEXT, rating INTEGER, text TEXT, status TEXT DEFAULT 'approved', created TEXT);
    CREATE TABLE IF NOT EXISTS wishlist(user_id INTEGER, product_id INTEGER);
    CREATE TABLE IF NOT EXISTS chats(id INTEGER PRIMARY KEY, thread TEXT, name TEXT, role TEXT, message TEXT, created TEXT, seen INTEGER DEFAULT 0);
    CREATE TABLE IF NOT EXISTS settings(k TEXT PRIMARY KEY, v TEXT);
    CREATE TABLE IF NOT EXISTS plugins(id INTEGER PRIMARY KEY, slug TEXT UNIQUE, name TEXT, version TEXT, description TEXT, file TEXT, active INTEGER DEFAULT 0);
    CREATE TABLE IF NOT EXISTS wp_options(option_id INTEGER PRIMARY KEY, option_name TEXT UNIQUE, option_value TEXT, autoload TEXT DEFAULT 'yes');
    CREATE TABLE IF NOT EXISTS wp_posts(ID INTEGER PRIMARY KEY, post_author INTEGER, post_date TEXT, post_content TEXT, post_title TEXT, post_status TEXT, post_name TEXT, post_type TEXT DEFAULT 'post');
    CREATE TABLE IF NOT EXISTS wp_postmeta(meta_id INTEGER PRIMARY KEY, post_id INTEGER, meta_key TEXT, meta_value TEXT);
    CREATE TABLE IF NOT EXISTS wp_users(ID INTEGER PRIMARY KEY, user_login TEXT, user_pass TEXT, user_email TEXT, user_registered TEXT);
    CREATE TABLE IF NOT EXISTS wp_usermeta(umeta_id INTEGER PRIMARY KEY, user_id INTEGER, meta_key TEXT, meta_value TEXT);
    CREATE TABLE IF NOT EXISTS notify_log(id INTEGER PRIMARY KEY, channel TEXT, payload TEXT, ok INTEGER, created TEXT);
    ");
    if (!(int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn()) {
        $st = $db->prepare('INSERT INTO users(name,email,phone,pass,role,created) VALUES(?,?,?,?,?,?)');
        $st->execute(['مدیر نِکسا', 'admin@nexa.shop', '09120000000', nexa_hash('admin123'), 'admin', nexa_now()]);
        $st->execute(['سارا امینی', 'sara@nexa.shop', '09121112233', nexa_hash('sara123'), 'customer', nexa_now()]);
    }
    if (!(int)$db->query('SELECT COUNT(*) FROM categories')->fetchColumn()) {
        $st = $db->prepare('INSERT INTO categories(slug,name,icon) VALUES(?,?,?)');
        foreach ([['perfume','عطر و زیبایی','✦'],['fashion','مد و پوشاک','◈'],['tech','دیجیتال','◉'],['home','خانه و زندگی','◇'],['jewel','زیورآلات','✧']] as $c) $st->execute($c);
    }
    if (!(int)$db->query('SELECT COUNT(*) FROM products')->fetchColumn()) {
        $items = [
            ['عطر امپریال طلایی','imperial-gold','NX-P-01','perfume',2450000,2180000,18,'assets/p1.jpg','رایحه‌ای گرم از کهربا، زعفران و چوب عود. بطری کریستال با درپوش طلایی — امضای نِکسا.'],
            ['شال ابریشم شامپاین','silk-champagne','NX-F-02','fashion',1890000,0,12,'assets/p2.jpg','ابریشم خالص با بافت نرم و رنگ شامپاین. مناسب شب‌های رسمی و سفرهای آرام.'],
            ['ساعت هوشمند رزگلد','watch-rosegold','NX-T-03','tech',4200000,3890000,9,'assets/p3.jpg','بدنه رزگلد، بند چرم ایتالیایی، نمایشگر همیشه روشن و مقاومت در برابر آب.'],
            ['ست سرامیک دست‌ساز','ceramic-set','NX-H-04','home',1150000,0,20,'assets/p4.jpg','سرامیک کرم و طلا، پخت دو مرحله‌ای. مناسب پذیرایی‌های کم‌شمار و دقیق.'],
            ['هدفون پریمیوم نِکسا','nexa-audio','NX-T-05','tech',3750000,3490000,14,'assets/p5.jpg','صدای استودیویی، حذف نویز تطبیقی و طراحی مات مشکی با حلقه طلایی.'],
            ['گردنبند مروارید آرام','pearl-necklace','NX-J-06','jewel',5900000,0,6,'assets/p6.jpg','مروارید آب شیرین گرید A با زنجیر طلایی ۱۸ عیار. بست مخفی ایمنی.'],
            ['ست مراقبت پوست اِتوِل','skin-atelier','NX-P-07','perfume',2100000,1890000,22,'assets/p7.jpg','سرم، تونر و کرم شب با عصاره گل محمدی و نیاسینامید. ساخت محدود.'],
            ['کیف چرم دست‌دوز','leather-bag','NX-F-08','fashion',3280000,0,8,'assets/p8.jpg','چرم گیاه‌دباغی کنیاک، یراق طلایی و دوخت زینی. هر کیف شماره سریال دارد.'],
        ];
        $st = $db->prepare('INSERT INTO products(title,slug,sku,cat,price,sale_price,stock,image,description,featured,created) VALUES(?,?,?,?,?,?,?,?,?,1,?)');
        foreach ($items as $p) $st->execute([...$p, nexa_now()]);
        $db->prepare('INSERT INTO reviews(product_id,user_id,name,rating,text,created) VALUES(?,?,?,?,?,?)')->execute([1,2,'سارا امینی',5,'رایحه ماندگار و بسته‌بندی فوق‌العاده شیک.', nexa_now()]);
        $db->exec("INSERT INTO coupons(code,type,amount,active) VALUES('NEXA10','percent',10,1)");
        $db->exec("INSERT INTO coupons(code,type,amount,min_total,active) VALUES('WELCOME','fixed',150000,1000000,1)");
    }
    $defs = [
        'shop_name'=>'نِکسا','topbar'=>'ارسال رایگان سفارش‌های بالای ۲ میلیون تومان  ·  ضمانت اصالت کالا  ·  پشتیبانی در بله، تلگرام و روبیکا',
        'card_number'=>'6219-8619-0000-1234','tg_token'=>'','tg_chat'=>'','bale_token'=>'','bale_chat'=>'','rb_token'=>'','rb_chat'=>'',
        'support_welcome'=>'سلام، به پشتیبانی نِکسا خوش آمدید.',
    ];
    $st = $db->prepare('INSERT OR IGNORE INTO settings(k,v) VALUES(?,?)');
    foreach ($defs as $k=>$v) $st->execute([$k,$v]);
}

function nexa_setting(string $k, $default = '') {
    $st = nexa_pdo()->prepare('SELECT v FROM settings WHERE k=?'); $st->execute([$k]); $v = $st->fetchColumn();
    return $v === false ? $default : $v;
}
function nexa_set_setting(string $k, string $v): void {
    nexa_pdo()->prepare('INSERT INTO settings(k,v) VALUES(?,?) ON CONFLICT(k) DO UPDATE SET v=excluded.v')->execute([$k,$v]);
}
function nexa_settings(): array {
    $out = [];
    foreach (nexa_pdo()->query('SELECT k,v FROM settings') as $r) $out[$r['k']] = $r['v'];
    return $out;
}

/* ───────── Session / Auth ───────── */
function nexa_sid(): string {
    $sid = $_COOKIE['nexa_sid'] ?? '';
    if (!preg_match('/^[A-Za-z0-9_\-]{10,}$/', $sid)) {
        $sid = bin2hex(random_bytes(16));
        setcookie('nexa_sid', $sid, ['path'=>'/','httponly'=>true,'samesite'=>'Lax']);
        $_COOKIE['nexa_sid'] = $sid;
    }
    return $sid;
}
function nexa_sess(): array {
    $sid = nexa_sid();
    $st = nexa_pdo()->prepare('SELECT data FROM sessions WHERE id=? AND expires>?');
    $st->execute([$sid, time()]);
    $raw = $st->fetchColumn();
    $data = $raw ? json_decode($raw, true) : ['cart'=>[], 'user_id'=>null];
    return is_array($data) ? $data : ['cart'=>[], 'user_id'=>null];
}
function nexa_sess_save(array $data): void {
    nexa_pdo()->prepare('INSERT INTO sessions(id,user_id,data,expires) VALUES(?,?,?,?) ON CONFLICT(id) DO UPDATE SET data=excluded.data, user_id=excluded.user_id, expires=excluded.expires')
        ->execute([nexa_sid(), $data['user_id'] ?? 0, json_encode($data, JSON_UNESCAPED_UNICODE), time()+86400*14]);
}
function nexa_user(): ?array {
    $s = nexa_sess();
    if (empty($s['user_id'])) return null;
    $st = nexa_pdo()->prepare('SELECT id,name,email,phone,role FROM users WHERE id=?');
    $st->execute([$s['user_id']]);
    $u = $st->fetch();
    $GLOBALS['nexa_user'] = $u ?: null;
    return $u ?: null;
}
function nexa_admin(): ?array {
    $u = nexa_user();
    return ($u && $u['role']==='admin') ? $u : null;
}

/* ───────── Messengers: Telegram, Bale, Rubika ───────── */
function nexa_http_post(string $url, array $payload, array $headers = ['Content-Type: application/json']): array {
    $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_POST=>1, CURLOPT_POSTFIELDS=>$body, CURLOPT_HTTPHEADER=>$headers, CURLOPT_RETURNTRANSFER=>1, CURLOPT_TIMEOUT=>12, CURLOPT_SSL_VERIFYPEER=>true]);
        $r = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
        return [$r !== false, (string)($r !== false ? $r : $err)];
    }
    $ctx = stream_context_create(['http'=>['method'=>'POST','header'=>implode("\r\n",$headers),'content'=>$body,'timeout'=>12],'ssl'=>['verify_peer'=>true,'verify_peer_name'=>true]]);
    $r = @file_get_contents($url, false, $ctx);
    return [$r !== false, (string)($r !== false ? $r : 'http error')];
}
function nexa_notify(string $event, string $title, string $text): array {
    $s = nexa_settings();
    $msg = "✦ نِکسا | {$title}\n\n{$text}\n\nرویداد: {$event}\nزمان: " . nexa_now();
    $res = [];
    if (!empty($s['tg_token']) && !empty($s['tg_chat'])) {
        $res[] = ['telegram', ...nexa_http_post('https://api.telegram.org/bot'.$s['tg_token'].'/sendMessage', ['chat_id'=>$s['tg_chat'],'text'=>$msg])];
    }
    if (!empty($s['bale_token']) && !empty($s['bale_chat'])) {
        $ok = nexa_http_post('https://tapi.bale.ai/bot'.$s['bale_token'].'/sendMessage', ['chat_id'=>$s['bale_chat'],'text'=>$msg]);
        $res[] = ['bale', $ok[0], $ok[1]];
    }
    if (!empty($s['rb_token']) && !empty($s['rb_chat'])) {
        $res[] = ['rubika', ...nexa_http_post('https://botapi.rubika.ir/v3/'.$s['rb_token'].'/sendMessage', ['chat_id'=>$s['rb_chat'],'text'=>$msg])];
    }
    $st = nexa_pdo()->prepare('INSERT INTO notify_log(channel,payload,ok,created) VALUES(?,?,?,?)');
    if (!$res) $st->execute(['none', mb_substr($msg,0,2000), 0, nexa_now()]);
    foreach ($res as [$ch,$ok,$body]) $st->execute([$ch, mb_substr((string)$body,0,2000), $ok?1:0, nexa_now()]);
    do_action('nexa_notify', $event, $title, $text);
    return $res;
}

/* ───────── Catalog helpers ───────── */
function nexa_cats(): array {
    return ['perfume'=>'عطر و زیبایی','fashion'=>'مد و پوشاک','tech'=>'دیجیتال','home'=>'خانه و زندگی','jewel'=>'زیورآلات'];
}
function nexa_product(array $r): array {
    $r['cat_name'] = nexa_cats()[$r['cat'] ?? ''] ?? ($r['cat'] ?? '');
    $r['rating'] = 5;
    return $r;
}
function nexa_hydrate_cart(array $raw): array {
    $out = [];
    $st = nexa_pdo()->prepare('SELECT * FROM products WHERE id=?');
    foreach ($raw as $it) {
        $st->execute([(int)$it['id']]);
        $p = $st->fetch();
        if ($p) $out[] = ['id'=>$p['id'],'qty'=>(int)$it['qty'],'product'=>nexa_product($p)];
    }
    return $out;
}
function nexa_status_fa(string $s): string {
    return ['pending'=>'در انتظار پرداخت','processing'=>'در حال پردازش','shipped'=>'ارسال شده','completed'=>'تکمیل شده','cancelled'=>'لغو شده','refunded'=>'بازپرداخت'][$s] ?? $s;
}

function nexa_json($data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function nexa_input(): array {
    $raw = file_get_contents('php://input') ?: '';
    $j = json_decode($raw, true);
    return is_array($j) ? $j : $_POST;
}

function nexa_load_plugins(): void {
    $rows = nexa_pdo()->query('SELECT * FROM plugins WHERE active=1')->fetchAll();
    foreach ($rows as $p) {
        $file = NEXA_PLUGINS . '/' . ltrim($p['file'], '/');
        if (is_file($file) && str_ends_with(strtolower($file), '.php')) {
            try { include_once $file; } catch (Throwable $e) { error_log('plugin '.$p['slug'].': '.$e->getMessage()); }
        }
    }
    do_action('plugins_loaded');
    do_action('init');
}

function nexa_parse_plugin_header(string $src): array {
    $g = function ($k) use ($src) { return preg_match('/'.$k.':\s*(.+)/', $src, $m) ? trim($m[1]) : ''; };
    return ['name'=>$g('Plugin Name') ?: 'افزونه بدون نام', 'version'=>$g('Version') ?: '1.0', 'description'=>$g('Description'), 'author'=>$g('Author')];
}

/* ───────── Router ───────── */
nexa_init();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* static assets when using php built-in server */
if (preg_match('#^/(assets|uploads|plugins)/#', $path)) {
    $fp = realpath(__DIR__ . $path);
    if ($fp && str_starts_with($fp, realpath(__DIR__)) && is_file($fp)) {
        $mime = mime_content_type($fp) ?: 'application/octet-stream';
        header('Content-Type: '.$mime);
        header('Content-Length: '.filesize($fp));
        readfile($fp); exit;
    }
}

nexa_user();
nexa_load_plugins();
do_action('nexa_boot');

if (str_starts_with($path, '/hook/') && $method === 'POST') {
    $which = basename($path);
    $payload = nexa_input();
    $text = ''; $chatFrom = '';
    if (in_array($which, ['telegram','bale'], true)) {
        $msg = $payload['message'] ?? $payload['edited_message'] ?? [];
        $text = (string)($msg['text'] ?? '');
        $chatFrom = (string)($msg['chat']['id'] ?? '');
    } else {
        $text = (string)(($payload['message']['text'] ?? $payload['text'] ?? ''));
        $chatFrom = (string)($payload['chat_id'] ?? '');
    }
    if (preg_match('/^\/reply\s+(\S+)\s+([\s\S]+)/', trim($text), $m)) {
        nexa_pdo()->prepare('INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)')
            ->execute([$m[1], 'پشتیبانی', 'support', $m[2], nexa_now()]);
    } elseif ($text !== '') {
        nexa_pdo()->prepare('INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)')
            ->execute(['hook-'.$which, 'messenger', 'user', $text, nexa_now()]);
    }
    if ($which === 'telegram' && $chatFrom && !nexa_setting('tg_chat')) nexa_set_setting('tg_chat', $chatFrom);
    nexa_json(['ok'=>true]);
}

if (str_starts_with($path, '/api/')) {
    $sess = nexa_sess();
    $user = nexa_user();

    if ($path === '/api/state' && $method === 'GET') {
        $cats = nexa_pdo()->query('SELECT slug,name,icon FROM categories')->fetchAll();
        $featured = array_map('nexa_product', nexa_pdo()->query("SELECT * FROM products WHERE status='publish' ORDER BY id LIMIT 8")->fetchAll());
        $s = nexa_settings();
        $topbar = apply_filters('nexa_topbar', $s['topbar'] ?? '');
        $pub = ['shop_name'=>$s['shop_name']??'','topbar'=>$topbar,'card_number'=>$s['card_number']??''];
        if ($user && ($user['role']??'')==='admin') {
            foreach (['tg_token','tg_chat','bale_token','bale_chat','rb_token','rb_chat'] as $k) $pub[$k]=$s[$k]??'';
        }
        nexa_json(['ok'=>true,'user'=>$user,'cart'=>nexa_hydrate_cart($sess['cart']??[]),'categories'=>$cats,'featured'=>$featured,
            'settings'=>$pub,'csrf'=>nexa_sid()]);
    }
    if ($path === '/api/products' && $method === 'GET') {
        $sql = "SELECT * FROM products WHERE status='publish'"; $args=[];
        if (!empty($_GET['cat'])) { $sql.=' AND cat=?'; $args[]=$_GET['cat']; }
        if (!empty($_GET['q'])) { $sql.=' AND (title LIKE ? OR description LIKE ?)'; $q='%'.$_GET['q'].'%'; $args[]=$q; $args[]=$q; }
        if (!empty($_GET['featured'])) $sql.=' AND featured=1';
        $sort = $_GET['sort'] ?? '';
        $sql .= $sort==='price_asc' ? ' ORDER BY CASE WHEN sale_price>0 THEN sale_price ELSE price END ASC'
              : ($sort==='price_desc' ? ' ORDER BY CASE WHEN sale_price>0 THEN sale_price ELSE price END DESC'
              : ($sort==='sale' ? ' AND sale_price>0 ORDER BY id DESC' : ' ORDER BY id DESC'));
        $st = nexa_pdo()->prepare($sql); $st->execute($args);
        nexa_json(['ok'=>true,'items'=>array_map('nexa_product',$st->fetchAll())]);
    }
    if (preg_match('#^/api/product/([^/]+)$#', $path, $m) && $method==='GET') {
        $st = nexa_pdo()->prepare('SELECT * FROM products WHERE slug=? OR id=?'); $st->execute([$m[1], ctype_digit($m[1])?(int)$m[1]:-1]);
        $p = $st->fetch(); if (!$p) nexa_json(['ok'=>false,'error'=>'یافت نشد'],404);
        $p = apply_filters('woocommerce_product_get_name', $p, $p);
        $rs = nexa_pdo()->prepare("SELECT * FROM reviews WHERE product_id=? AND status='approved'"); $rs->execute([$p['id']]);
        $rel = nexa_pdo()->prepare('SELECT * FROM products WHERE cat=? AND id!=? LIMIT 4'); $rel->execute([$p['cat'],$p['id']]);
        nexa_json(['ok'=>true,'item'=>nexa_product($p),'reviews'=>$rs->fetchAll(),'related'=>array_map('nexa_product',$rel->fetchAll())]);
    }
    if ($path === '/api/auth/login' && $method==='POST') {
        $b = nexa_input();
        $st = nexa_pdo()->prepare('SELECT * FROM users WHERE email=?'); $st->execute([strtolower(trim($b['email']??''))]);
        $u = $st->fetch();
        if (!$u || !nexa_verify((string)($b['password']??''), $u['pass'])) nexa_json(['ok'=>false,'error'=>'ایمیل یا رمز نادرست است']);
        $sess['user_id']=$u['id']; nexa_sess_save($sess);
        nexa_json(['ok'=>true,'user'=>['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'phone'=>$u['phone'],'role'=>$u['role']]]);
    }
    if ($path === '/api/auth/register' && $method==='POST') {
        $b = nexa_input();
        $name=trim($b['name']??''); $email=strtolower(trim($b['email']??'')); $phone=$b['phone']??''; $pw=$b['password']??'';
        if ($name===''||$email===''||strlen($pw)<4) nexa_json(['ok'=>false,'error'=>'نام، ایمیل و رمز معتبر لازم است']);
        try {
            nexa_pdo()->prepare('INSERT INTO users(name,email,phone,pass,role,created) VALUES(?,?,?,?,?,?)')
                ->execute([$name,$email,$phone,nexa_hash($pw),'customer',nexa_now()]);
        } catch (Throwable $e) { nexa_json(['ok'=>false,'error'=>'این ایمیل قبلاً ثبت شده']); }
        $uid = (int)nexa_pdo()->lastInsertId(); $sess['user_id']=$uid; nexa_sess_save($sess);
        nexa_notify('register','عضو جدید',"$name\n$email\n$phone");
        do_action('user_register', $uid);
        nexa_json(['ok'=>true,'user'=>['id'=>$uid,'name'=>$name,'email'=>$email,'phone'=>$phone,'role'=>'customer']]);
    }
    if ($path === '/api/auth/logout' && $method==='POST') { $sess['user_id']=null; nexa_sess_save($sess); nexa_json(['ok'=>true]); }
    if ($path === '/api/cart' && $method==='POST') {
        $b = nexa_input(); $action=$b['action']??''; $pid=(int)($b['id']??0); $qty=(int)($b['qty']??1);
        $cart = $sess['cart'] ?? [];
        if ($action==='add') {
            $f=false; foreach ($cart as &$x) if ($x['id']===$pid) { $x['qty']+=max(1,$qty); $f=true; }
            if (!$f) $cart[]=['id'=>$pid,'qty'=>max(1,$qty)];
            $msg='به سبد افزوده شد'; do_action('woocommerce_add_to_cart',$pid,$qty);
        } elseif ($action==='set') {
            $n=[]; foreach ($cart as $x) { if ($x['id']===$pid) { if ($qty>0) { $x['qty']=max(1,$qty); $n[]=$x; } } else $n[]=$x; } $cart=$n; $msg='تعداد به‌روز شد';
        } elseif ($action==='remove') { $cart=array_values(array_filter($cart, fn($x)=>$x['id']!=$pid)); $msg='حذف شد'; }
        else $msg='سبد';
        $sess['cart']=$cart; nexa_sess_save($sess);
        nexa_json(['ok'=>true,'cart'=>nexa_hydrate_cart($cart),'message'=>$msg]);
    }
    if ($path === '/api/coupon' && $method==='POST') {
        $code = strtoupper(trim(nexa_input()['code']??''));
        $st=nexa_pdo()->prepare('SELECT * FROM coupons WHERE code=? AND active=1'); $st->execute([$code]); $c=$st->fetch();
        if (!$c) nexa_json(['ok'=>false,'error'=>'کوپن نامعتبر است']);
        $sess['coupon']=['code'=>$c['code'],'type'=>$c['type'],'amount'=>$c['amount']]; nexa_sess_save($sess);
        nexa_json(['ok'=>true,'coupon'=>$sess['coupon'],'message'=>'کوپن اعمال شد']);
    }
    if ($path === '/api/checkout' && $method==='POST') {
        $b = nexa_input(); $cart = nexa_hydrate_cart($sess['cart']??[]);
        if (!$cart) nexa_json(['ok'=>false,'error'=>'سبد خالی است']);
        foreach ($cart as $it) if ($it['qty']>$it['product']['stock']) nexa_json(['ok'=>false,'error'=>'موجودی «'.$it['product']['title'].'» کافی نیست']);
        $sub=0; foreach ($cart as $it) $sub += $it['qty'] * (($it['product']['sale_price']?:$it['product']['price']));
        $ship=45000; $coupon=$sess['coupon']??null;
        $disc = $coupon ? ($coupon['type']==='percent' ? (int)($sub*$coupon['amount']/100) : (int)$coupon['amount']) : 0;
        $total = max(0, $sub+$ship-$disc);
        $number = 'NXA-'.date('Ymd').'-'.random_int(1000,9999);
        $addr = json_encode(['name'=>$b['name']??'','phone'=>$b['phone']??'','city'=>$b['city']??'','address'=>$b['address']??'','zip'=>$b['zip']??''], JSON_UNESCAPED_UNICODE);
        $items = json_encode(array_map(fn($i)=>['id'=>$i['product']['id'],'title'=>$i['product']['title'],'qty'=>$i['qty'],'price'=>$i['product']['sale_price']?:$i['product']['price']], $cart), JSON_UNESCAPED_UNICODE);
        nexa_pdo()->prepare('INSERT INTO orders(number,user_id,items,subtotal,discount,shipping,total,status,pay,address,note,coupon,created) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$number, $user['id']??0, $items, $sub, $disc, $ship, $total, 'pending', $b['pay']??'cod', $addr, $b['note']??'', is_array($coupon) ? ($coupon['code'] ?? '') : '', nexa_now()]);
        $oid = (int)nexa_pdo()->lastInsertId();
        foreach ($cart as $it) {
            nexa_pdo()->prepare('UPDATE products SET stock=stock-? WHERE id=?')->execute([$it['qty'],$it['product']['id']]);
            $st=nexa_pdo()->prepare('SELECT title,stock FROM products WHERE id=?'); $st->execute([$it['product']['id']]); $row=$st->fetch();
            if ($row && (int)$row['stock']<=3) nexa_notify('low_stock','موجودی کم',$row['title']."\nباقی‌مانده: ".$row['stock']);
        }
        $lines=''; foreach ($cart as $i) $lines.='• '.$i['product']['title'].' × '.$i['qty']."\n";
        nexa_notify('order','سفارش جدید '.$number, ($b['name']??'')."\n".($b['phone']??'')."\n".($b['city']??'')."\n$lines جمع: ".number_format($total)." تومان\nپرداخت: ".($b['pay']??''));
        do_action('woocommerce_thankyou', $oid);
        do_action('nexa_after_order', ['id'=>$oid,'number'=>$number,'total'=>$total]);
        $sess['cart']=[]; $sess['coupon']=null; nexa_sess_save($sess);
        nexa_json(['ok'=>true,'number'=>$number,'total'=>$total,'pay'=>$b['pay']??'cod']);
    }
    if ($path === '/api/orders' && $method==='GET') {
        if (!$user) nexa_json(['ok'=>false,'error'=>'ورود لازم است'],401);
        $st=nexa_pdo()->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY id DESC'); $st->execute([$user['id']]);
        $items=array_map(function($o){$o['status_fa']=nexa_status_fa($o['status']); return $o;}, $st->fetchAll());
        nexa_json(['ok'=>true,'items'=>$items]);
    }
    if (preg_match('#^/api/track/(.+)$#', $path, $m) && $method==='GET') {
        $st=nexa_pdo()->prepare('SELECT * FROM orders WHERE number=?'); $st->execute([urldecode($m[1])]); $r=$st->fetch();
        if (!$r) nexa_json(['ok'=>false,'error'=>'سفارش پیدا نشد']);
        $r['status_fa']=nexa_status_fa($r['status']); nexa_json(['ok'=>true,'item'=>$r]);
    }
    if ($path === '/api/review' && $method==='POST') {
        if (!$user) nexa_json(['ok'=>false,'error'=>'ورود لازم است']);
        $b=nexa_input();
        nexa_pdo()->prepare('INSERT INTO reviews(product_id,user_id,name,rating,text,created) VALUES(?,?,?,?,?,?)')
            ->execute([(int)($b['product_id']??0),$user['id'],$user['name'],(int)($b['rating']??5),mb_substr($b['text']??'',0,800),nexa_now()]);
        nexa_notify('review','دیدگاه جدید',$user['name'].': '.($b['text']??''));
        nexa_json(['ok'=>true,'message'=>'دیدگاه ثبت شد']);
    }
    if ($path === '/api/wishlist' && $method==='POST') {
        if (!$user) nexa_json(['ok'=>false,'error'=>'برای علاقه‌مندی وارد شوید']);
        $pid=(int)(nexa_input()['id']??0);
        $st=nexa_pdo()->prepare('SELECT 1 FROM wishlist WHERE user_id=? AND product_id=?'); $st->execute([$user['id'],$pid]);
        if ($st->fetch()) { nexa_pdo()->prepare('DELETE FROM wishlist WHERE user_id=? AND product_id=?')->execute([$user['id'],$pid]); $msg='از علاقه‌مندی حذف شد'; }
        else { nexa_pdo()->prepare('INSERT INTO wishlist(user_id,product_id) VALUES(?,?)')->execute([$user['id'],$pid]); $msg='به علاقه‌مندی افزوده شد'; }
        nexa_json(['ok'=>true,'message'=>$msg]);
    }
    if ($path === '/api/chat' && $method==='GET') {
        $thread = $_GET['thread'] ?? '';
        $st=nexa_pdo()->prepare('SELECT * FROM chats WHERE thread=? ORDER BY id'); $st->execute([$thread]);
        nexa_json(['ok'=>true,'items'=>$st->fetchAll()]);
    }
    if ($path === '/api/chat' && $method==='POST') {
        $b=nexa_input(); $thread=$b['thread']??'guest'; $name=$b['name']??'مهمان'; $msg=trim($b['message']??'');
        if ($msg==='') nexa_json(['ok'=>false,'error'=>'پیام خالی']);
        $c=nexa_pdo()->prepare('SELECT COUNT(*) FROM chats WHERE thread=?'); $c->execute([$thread]);
        if (!(int)$c->fetchColumn()) {
            nexa_pdo()->prepare('INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)')
                ->execute([$thread,'نِکسا','support', nexa_setting('support_welcome','سلام، چطور کمک کنیم؟'), nexa_now()]);
        }
        nexa_pdo()->prepare('INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)')->execute([$thread,$name,'user',$msg,nexa_now()]);
        nexa_notify('chat','پیام پشتیبانی',"$name ($thread)\n$msg\nپاسخ: /reply $thread متن");
        nexa_json(['ok'=>true]);
    }

    /* admin */
    $need = function() { if (!nexa_admin()) nexa_json(['ok'=>false,'error'=>'ممنوع'],403); };
    if ($path === '/api/admin/stats' && $method==='GET') {
        $need();
        $o=nexa_pdo()->query('SELECT COUNT(*) c, COALESCE(SUM(total),0) t FROM orders')->fetch();
        $cu=(int)nexa_pdo()->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
        $ch=(int)nexa_pdo()->query('SELECT COUNT(DISTINCT thread) FROM chats')->fetchColumn();
        nexa_json(['ok'=>true,'orders'=>(int)$o['c'],'revenue'=>(int)$o['t'],'customers'=>$cu,'chats'=>$ch]);
    }
    if (in_array($path, ['/api/admin/products','/api/admin/orders','/api/admin/coupons','/api/admin/plugins'], true) && $method==='GET') {
        $need(); $kind=basename($path);
        $items=nexa_pdo()->query("SELECT * FROM $kind ORDER BY id DESC")->fetchAll();
        if ($kind==='orders') foreach ($items as &$it) $it['status_fa']=nexa_status_fa($it['status']);
        nexa_json(['ok'=>true,'items'=>$items]);
    }
    if ($path==='/api/admin/chats' && $method==='GET') {
        $need();
        $items=nexa_pdo()->query("SELECT thread,name,message as last,created FROM chats WHERE id IN (SELECT MAX(id) FROM chats GROUP BY thread) ORDER BY id DESC")->fetchAll();
        nexa_json(['ok'=>true,'items'=>$items]);
    }
    if ($path==='/api/admin/chat' && $method==='POST') {
        $need(); $b=nexa_input();
        nexa_pdo()->prepare('INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)')->execute([$b['thread']??'','پشتیبانی','support',$b['message']??'',nexa_now()]);
        nexa_json(['ok'=>true]);
    }
    if ($path==='/api/admin/products' && $method==='POST') {
        $need(); $b=nexa_input();
        if (!empty($b['id'])) {
            nexa_pdo()->prepare('UPDATE products SET title=?,price=?,sale_price=?,stock=?,cat=?,image=?,description=? WHERE id=?')
                ->execute([$b['title']??'',(int)($b['price']??0),(int)($b['sale_price']??0),(int)($b['stock']??0),$b['cat']??'home',$b['image']??'',$b['description']??'',(int)$b['id']]);
        } else {
            $slug = preg_replace('/[^a-z0-9\-]+/','-', strtolower($b['title']??'item')).'-'.(time()%10000);
            nexa_pdo()->prepare('INSERT INTO products(title,slug,sku,cat,price,sale_price,stock,image,description,featured,created) VALUES(?,?,?,?,?,?,?,?,?,1,?)')
                ->execute([$b['title']??'',$slug,'NX-'.bin2hex(random_bytes(3)),$b['cat']??'home',(int)($b['price']??0),(int)($b['sale_price']??0),(int)($b['stock']??0),$b['image']??'assets/p1.jpg',$b['description']??'',nexa_now()]);
        }
        nexa_json(['ok'=>true]);
    }
    if ($path==='/api/admin/orders' && $method==='POST') {
        $need(); $b=nexa_input();
        nexa_pdo()->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$b['status']??'pending',$b['id']??0]);
        $st=nexa_pdo()->prepare('SELECT number,status FROM orders WHERE id=?'); $st->execute([$b['id']??0]); $row=$st->fetch();
        if ($row) nexa_notify('order_status','وضعیت سفارش '.$row['number'], 'وضعیت جدید: '.nexa_status_fa($row['status']));
        do_action('woocommerce_order_status_changed', $b['id']??0, '', $b['status']??'');
        nexa_json(['ok'=>true]);
    }
    if ($path==='/api/admin/coupons' && $method==='POST') {
        $need(); $b=nexa_input();
        nexa_pdo()->prepare('INSERT OR REPLACE INTO coupons(code,type,amount,active) VALUES(?,?,?,1)')->execute([strtoupper($b['code']??''),$b['type']??'percent',(int)($b['amount']??0)]);
        nexa_json(['ok'=>true]);
    }
    if ($path==='/api/admin/settings' && $method==='POST') {
        $need(); foreach (nexa_input() as $k=>$v) nexa_set_setting((string)$k, (string)$v);
        nexa_json(['ok'=>true,'settings'=>nexa_settings()]);
    }
    if ($path==='/api/admin/test-notify' && $method==='POST') {
        $need(); $res=nexa_notify('test','پیام آزمایشی نِکسا','اگر این متن را می‌بینید، اتصال پیام‌رسان درست است.');
        if (!$res) nexa_json(['ok'=>false,'error'=>'توکن و Chat ID را ذخیره کنید']);
        $ok=0; foreach ($res as $r) if ($r[1]) $ok++;
        nexa_json(['ok'=>true,'message'=>"$ok از ".count($res).' پیام‌رسان ارسال شد']);
    }
    if ($path==='/api/admin/plugins' && $method==='POST') {
        $need(); $b=nexa_input();
        if (!empty($b['toggle'])) {
            $st=nexa_pdo()->prepare('SELECT active FROM plugins WHERE id=?'); $st->execute([$b['id']??0]); $row=$st->fetch();
            if ($row) nexa_pdo()->prepare('UPDATE plugins SET active=? WHERE id=?')->execute([$row['active']?0:1, $b['id']]);
        }
        nexa_json(['ok'=>true]);
    }
    if ($path==='/api/admin/plugins/upload' && $method==='POST') {
        $need();
        if (empty($_FILES['plugin']['tmp_name'])) nexa_json(['ok'=>false,'error'=>'فایل ارسال نشد']);
        $name = $_FILES['plugin']['name'];
        if (!preg_match('/\.(php|zip)$/i', $name)) nexa_json(['ok'=>false,'error'=>'فقط PHP یا ZIP']);
        $slug = preg_replace('/[^a-zA-Z0-9_\-]+/','-', pathinfo($name, PATHINFO_FILENAME));
        $dir = NEXA_PLUGINS.'/'.$slug; if (!is_dir($dir)) mkdir($dir,0775,true);
        $header = ['name'=>$name,'version'=>'1.0','description'=>''];
        $rel = $slug.'/'.basename($name);
        if (preg_match('/\.php$/i', $name)) {
            $src = file_get_contents($_FILES['plugin']['tmp_name']);
            $header = nexa_parse_plugin_header($src);
            file_put_contents($dir.'/'.basename($name), $src);
            $rel = $slug.'/'.basename($name);
        } else {
            $zipPath = $dir.'/'.basename($name);
            move_uploaded_file($_FILES['plugin']['tmp_name'], $zipPath);
            if (class_exists('ZipArchive')) {
                $z = new ZipArchive();
                if ($z->open($zipPath)===true) {
                    $z->extractTo($dir); $z->close();
                    foreach (glob($dir.'/*.php') ?: [] as $phpf) { $header = nexa_parse_plugin_header(file_get_contents($phpf)); $rel = $slug.'/'.basename($phpf); break; }
                    foreach (glob($dir.'/*/*.php') ?: [] as $phpf) { $header = nexa_parse_plugin_header(file_get_contents($phpf)); $rel = $slug.'/'.basename(dirname($phpf)).'/'.basename($phpf); break; }
                }
            } else $header['description'] = 'بسته ZIP — برای استخراج، php-zip لازم است';
        }
        nexa_pdo()->prepare('INSERT OR REPLACE INTO plugins(slug,name,version,description,file,active) VALUES(?,?,?,?,?,0)')
            ->execute([$slug,$header['name'],$header['version'],$header['description'],$rel]);
        nexa_notify('plugin','نصب افزونه',$header['name']);
        nexa_json(['ok'=>true,'message'=>'افزونه نصب شد. از فهرست فعال کنید.']);
    }
    if ($path==='/api/admin/plugins/samples' && $method==='POST') {
        $need();
        $samples = [
            'nexa-topbar/nexa-topbar.php' => "<?php\n/**\n * Plugin Name: نوار اعلان نِکسا\n * Description: نمایش پیام سفارشی در نوار بالای فروشگاه\n * Version: 1.0.0\n */\nif (!defined('ABSPATH')) exit;\nadd_filter('nexa_topbar', function (\$text) { return \$text . '  ·  افزونه نوار اعلان فعال است'; });\n",
            'nexa-order-sms/nexa-order-sms.php' => "<?php\n/**\n * Plugin Name: اعلام سفارش ووکامرس‌گونه\n * Description: هوک woocommerce_thankyou را می‌گیرد\n * Version: 1.0.0\n */\nif (!defined('ABSPATH')) exit;\nadd_action('woocommerce_thankyou', function (\$order_id) { update_option('nexa_last_order_plugin', (string)\$order_id); });\nadd_action('nexa_after_order', function (\$order) { update_option('nexa_last_order_plugin', \$order['number'] ?? ''); });\n",
        ];
        foreach ($samples as $rel=>$src) {
            $dest = NEXA_PLUGINS.'/'.$rel; if (!is_dir(dirname($dest))) mkdir(dirname($dest),0775,true);
            file_put_contents($dest, $src);
            $h = nexa_parse_plugin_header($src);
            $slug = explode('/',$rel)[0];
            nexa_pdo()->prepare('INSERT OR REPLACE INTO plugins(slug,name,version,description,file,active) VALUES(?,?,?,?,?,1)')
                ->execute([$slug,$h['name'],$h['version'],$h['description'],$rel]);
        }
        nexa_notify('plugin','افزونه‌های نمونه','نوار اعلان و هوک سفارش نصب شد');
        nexa_json(['ok'=>true,'message'=>'افزونه‌های نمونه نصب و فعال شدند']);
    }
    nexa_json(['ok'=>false,'error'=>'API یافت نشد'],404);
}

/* ───────── Storefront HTML ───────── */
header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: ALLOWALL');
$htmlFile = __DIR__ . '/app.html';
if (is_file($htmlFile)) { readfile($htmlFile); exit; }
echo nexa_embedded_ui();
exit;

function nexa_embedded_ui(): string {
    return <<<'HTML'
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>نِکسا | NEXA — بوتیک لوکس</title>
<meta name="description" content="فروشگاه لوکس نِکسا — عطر، مد، دیجیتال و زندگی. ارسال سریع، ضمانت اصالت، پشتیبانی آنلاین.">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap">
<style>
:root{
  --navy:#0b1220;--navy2:#121a2c;--ink:#16130f;--muted:#7a7268;--gold:#c6a46a;--gold2:#e8d5a8;
  --cream:#f3eee6;--paper:#fbf8f2;--white:#fff;--line:#e8dfd2;--rose:#c48978;--ok:#2c7a57;--err:#b33b3b;
  --shadow:0 24px 60px rgba(11,18,32,.14);--r:22px;--font:Vazirmatn,Tahoma,sans-serif;
}
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:var(--cream);color:var(--ink);font-family:var(--font);min-height:100%}
a{color:inherit;text-decoration:none}
button,input,select,textarea{font-family:inherit}
img{max-width:100%;display:block}
.serif{font-family:"Times New Roman",Georgia,serif;letter-spacing:.04em}
.wrap{width:min(1240px,calc(100% - 32px));margin-inline:auto}
.gold{color:var(--gold)}
.hide{display:none!important}
.row{display:flex;gap:12px;align-items:center}
.splash{position:fixed;inset:0;z-index:200;background:var(--navy);display:grid;place-items:center;transition:opacity .7s}
.splash.off{opacity:0;pointer-events:none}
.mark{width:78px;height:78px;border:1px solid var(--gold);border-radius:50%;display:grid;place-items:center;animation:pulse 1.8s infinite}
.mark span{color:var(--gold2);font-size:22px;font-weight:700}
@keyframes pulse{50%{transform:scale(1.06);box-shadow:0 0 0 12px rgba(198,164,106,.08)}}
.topbar{background:var(--navy);color:var(--gold2);font-size:13px;padding:9px 0;overflow:hidden}
.topbar .wrap{display:flex;justify-content:space-between;gap:16px}
.ticker{white-space:nowrap;opacity:.9}
header.nav{position:sticky;top:0;z-index:50;background:rgba(251,248,242,.86);backdrop-filter:blur(16px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;justify-content:space-between;padding:14px 0;gap:16px}
.logo{display:flex;align-items:center;gap:10px;cursor:pointer}
.logo-mark{width:42px;height:42px;border-radius:50%;border:1px solid var(--gold);display:grid;place-items:center;color:var(--gold);font-weight:800}
.logo b{display:block;font-size:20px;line-height:1}
.logo small{color:var(--muted);font-size:11px;letter-spacing:.18em}
.menu{display:flex;gap:22px;font-size:14.5px;font-weight:500}
.menu a{position:relative;opacity:.82}
.menu a:hover,.menu a.on{opacity:1}
.menu a.on:after{content:"";position:absolute;right:0;left:0;bottom:-8px;height:2px;background:var(--gold)}
.nav-act{display:flex;gap:8px}
.icon-btn{width:44px;height:44px;border:1px solid var(--line);background:#fff;border-radius:14px;display:grid;place-items:center;cursor:pointer;position:relative}
.icon-btn:hover{border-color:var(--gold)}
.badge{position:absolute;top:-6px;left:-6px;background:var(--navy);color:var(--gold2);font-size:10px;min-width:18px;height:18px;border-radius:99px;display:grid;place-items:center;padding:0 5px}
.hero{position:relative;min-height:78vh;border-radius:0 0 40px 40px;overflow:hidden;background:#0b1220 center/cover no-repeat}
.hero:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(11,18,32,.88) 8%,rgba(11,18,32,.28) 70%)}
.hero-in{position:relative;z-index:1;padding:90px 0 80px;color:#fff;width:min(1240px,calc(100% - 32px));margin:auto}
.kicker{color:var(--gold2);letter-spacing:.28em;font-size:12px}
.hero h1{font-size:clamp(36px,6vw,74px);line-height:1.15;margin:14px 0 18px;font-weight:800;font-family:var(--serif)}
.sec h2,.pdp h1,footer h4{font-family:var(--serif)}
.hero p{max-width:520px;color:#e8e0d4;line-height:1.9;font-size:16px}
.cta{display:flex;gap:12px;margin-top:28px;flex-wrap:wrap}
.btn{border:0;cursor:pointer;border-radius:999px;padding:13px 22px;font-weight:600;font-size:14px}
.btn-gold{background:linear-gradient(90deg,var(--gold),var(--gold2));color:var(--navy)}
.btn-ghost{background:transparent;color:#fff;border:1px solid rgba(255,255,255,.3)}
.btn-navy{background:var(--navy);color:var(--gold2)}
.btn-line{background:#fff;border:1px solid var(--line);color:var(--ink)}
.stats{display:flex;gap:28px;margin-top:48px;color:var(--gold2);font-size:13px}
.stats b{display:block;color:#fff;font-size:22px}
.sec{padding:72px 0}
.sec h2{font-size:32px;margin:0 0 8px}
.sub{color:var(--muted);margin:0 0 28px}
.cats{display:grid;grid-template-columns:repeat(5,1fr);gap:14px}
.cat{background:#fff;border-radius:20px;padding:22px 16px;text-align:center;border:1px solid var(--line);cursor:pointer;transition:.25s}
.cat:hover{transform:translateY(-6px);border-color:var(--gold);box-shadow:var(--shadow)}
.cat i{font-style:normal;font-size:26px;display:block;margin-bottom:8px}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.card{background:#fff;border-radius:var(--r);overflow:hidden;border:1px solid transparent;transition:.3s;position:relative}
.card:hover{transform:translateY(-8px);box-shadow:var(--shadow)}
.card .ph{aspect-ratio:1;background:#ddd center/cover;position:relative}
.card .wish{position:absolute;top:12px;left:12px;width:38px;height:38px;border:0;border-radius:50%;background:rgba(255,255,255,.92);cursor:pointer}
.sale{position:absolute;top:12px;right:12px;background:var(--rose);color:#fff;font-size:11px;padding:4px 8px;border-radius:999px}
.card .bd{padding:14px 16px 18px}
.card .c{color:var(--muted);font-size:12px}
.card h3{margin:6px 0 10px;font-size:16px;font-weight:650}
.price{display:flex;gap:8px;align-items:baseline}
.price b{color:var(--navy)}
.price s{color:#b3aaa0;font-size:12px}
.add{margin-top:12px;width:100%}
.banner{display:grid;grid-template-columns:1.3fr .7fr;gap:18px}
.banner>div{border-radius:28px;min-height:240px;padding:36px;color:#fff;background:var(--navy) center/cover;position:relative;overflow:hidden}
.banner h3{font-size:28px;margin:8px 0 12px}
.filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:22px}
.filters input,.filters select,.field{border:1px solid var(--line);background:#fff;border-radius:12px;padding:11px 12px;font-size:14px}
.pdp{display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:start}
.gallery{background:#fff;border-radius:28px;overflow:hidden;aspect-ratio:1;background-size:cover;background-position:center}
.stars{color:var(--gold);letter-spacing:2px}
.qty{display:flex;align-items:center;border:1px solid var(--line);border-radius:999px;overflow:hidden;width:max-content}
.qty button{width:42px;height:42px;border:0;background:#fff;cursor:pointer}
.qty span{min-width:36px;text-align:center}
.cart-table{width:100%;border-collapse:collapse;background:#fff;border-radius:20px;overflow:hidden}
.cart-table td,.cart-table th{padding:14px;border-bottom:1px solid var(--line);text-align:right}
.side{background:#fff;border-radius:24px;padding:22px;border:1px solid var(--line)}
.chk{display:grid;grid-template-columns:1.2fr .8fr;gap:22px}
.field{width:100%;margin:0 0 10px}
label.l{display:block;font-size:12px;color:var(--muted);margin:10px 0 6px}
.pay{display:flex;flex-direction:column;gap:8px}
.pay label{display:flex;gap:10px;align-items:center;background:var(--paper);border:1px solid var(--line);border-radius:14px;padding:12px;cursor:pointer}
.acc,.admin{display:grid;grid-template-columns:240px 1fr;gap:22px;min-height:60vh}
.sidemenu a,.sidemenu button{display:block;width:100%;text-align:right;background:transparent;border:0;padding:11px 14px;border-radius:12px;cursor:pointer;color:inherit;font-size:14px}
.sidemenu a.on,.sidemenu button.on,.sidemenu a:hover,.sidemenu button:hover{background:#fff}
.dark .sidemenu a,.dark .sidemenu button{color:#e8e0d4}
.dark .sidemenu a.on,.dark .sidemenu button.on{background:rgba(255,255,255,.06);color:var(--gold2)}
.admin{background:var(--navy);color:#e8e0d4;margin:18px auto 40px;border-radius:28px;padding:18px;width:min(1240px,calc(100% - 24px))}
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.kpi{background:rgba(255,255,255,.04);border:1px solid rgba(198,164,106,.2);border-radius:18px;padding:16px}
.kpi b{display:block;font-size:26px;color:var(--gold2);margin-top:6px}
table.data{width:100%;border-collapse:collapse;font-size:13px}
table.data th,table.data td{padding:10px;border-bottom:1px solid rgba(255,255,255,.08);text-align:right}
.modal{position:fixed;inset:0;background:rgba(11,18,32,.45);z-index:80;display:grid;place-items:center;padding:20px}
.modal .box{background:#fff;color:var(--ink);width:min(420px,100%);border-radius:24px;padding:26px}
.drawer{position:fixed;inset:0;background:rgba(11,18,32,.35);z-index:70;display:flex;justify-content:flex-start}
.drawer .panel{width:min(420px,100%);background:#fff;height:100%;padding:20px;overflow:auto}
.chat-fab{position:fixed;bottom:22px;left:22px;z-index:60;width:62px;height:62px;border-radius:50%;border:0;background:linear-gradient(180deg,var(--gold2),var(--gold));color:var(--navy);cursor:pointer;box-shadow:0 12px 30px rgba(198,164,106,.45);font-size:22px}
.chat-box{position:fixed;bottom:96px;left:22px;z-index:60;width:min(360px,calc(100% - 24px));height:480px;background:#fff;border-radius:24px;box-shadow:var(--shadow);display:flex;flex-direction:column;overflow:hidden;border:1px solid var(--line)}
.chat-h{background:var(--navy);color:#fff;padding:14px 16px;display:flex;justify-content:space-between;align-items:center}
.chat-m{flex:1;overflow:auto;padding:12px;background:var(--paper)}
.bubble{max-width:80%;padding:10px 12px;border-radius:16px;margin:8px 0;font-size:13px;line-height:1.7;white-space:pre-wrap}
.me{background:var(--navy);color:#fff;margin-right:auto;border-bottom-left-radius:4px}
.them{background:#fff;border:1px solid var(--line);margin-left:auto;border-bottom-right-radius:4px}
.chat-i{display:flex;gap:8px;padding:10px;border-top:1px solid var(--line)}
.chat-i input{flex:1;border:1px solid var(--line);border-radius:999px;padding:10px 14px}
.toast{position:fixed;top:18px;left:50%;transform:translateX(-50%);background:var(--navy);color:#fff;padding:10px 16px;border-radius:999px;z-index:120;font-size:13px}
footer{background:var(--navy);color:#d9d0c4;padding:48px 0 24px;margin-top:40px}
.ft{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:24px}
footer h4{color:var(--gold2);margin:0 0 12px}
.rev{background:#fff;border-radius:18px;padding:16px;border:1px solid var(--line);margin:10px 0}
.empty{text-align:center;padding:50px 16px;color:var(--muted)}
.searchbar{flex:1;max-width:360px;position:relative}
.searchbar input{width:100%;border:1px solid var(--line);border-radius:999px;padding:11px 16px;background:#fff}
@media(max-width:980px){
  .cats,.grid{grid-template-columns:repeat(2,1fr)}
  .pdp,.chk,.banner,.acc,.admin,.ft,.kpis{grid-template-columns:1fr}
  .menu{display:none}
  .hero{min-height:70vh;border-radius:0 0 24px 24px}
}
</style>
</head>
<body>
<div class="splash" id="splash"><div class="mark"><span>نِ</span></div></div>
<div class="topbar"><div class="wrap"><span class="ticker" id="ticker">ارسال رایگان سفارش‌های بالای ۲ میلیون تومان  ·  ضمانت اصالت کالا  ·  پشتیبانی ۲۴ ساعته در بله، تلگرام و روبیکا</span><span>تماس: ۰۲۱-۹۱۰۹۰۰۰۰</span></div></div>
<header class="nav">
  <div class="wrap nav-in">
    <div class="logo" data-go="/">
      <div class="logo-mark">N</div>
      <div><b>نِکسا</b><small>NEXA ATELIER</small></div>
    </div>
    <nav class="menu" id="menu">
      <a data-go="/" class="on">خانه</a>
      <a data-go="/shop">فروشگاه</a>
      <a data-go="/shop?cat=perfume">عطر</a>
      <a data-go="/shop?cat=fashion">مد</a>
      <a data-go="/shop?cat=tech">دیجیتال</a>
      <a data-go="/about">درباره</a>
    </nav>
    <div class="searchbar"><input id="q" placeholder="جستجوی محصول، برند، دسته..." onkeydown="if(event.key==='Enter')go('/shop?q='+encodeURIComponent(this.value))"></div>
    <div class="nav-act">
      <button class="icon-btn" title="حساب" onclick="go(S.user?'/account':'/auth')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.5-3.2 4-5 7-5s5.5 1.8 7 5"/></svg>
      </button>
      <button class="icon-btn" title="سبد" onclick="openCart()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 7h15l-1.5 9h-12z"/><path d="M6 7 5 4H2"/><circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/></svg>
        <span class="badge" id="cartBadge">0</span>
      </button>
    </div>
  </div>
</header>
<main id="app"></main>
<footer>
  <div class="wrap ft">
    <div>
      <div class="logo"><div class="logo-mark">N</div><div><b style="color:#fff">نِکسا</b><small>LUXURY BOUTIQUE</small></div></div>
      <p style="line-height:1.9;margin-top:14px">بوتیک نِکسا مجموعه‌ای از ابژه‌های منتخب زندگی است؛ از عطر و ابریشم تا فناوری آرام و زیورهای ماندگار.</p>
    </div>
    <div><h4>خرید</h4><div><a data-go="/shop">همه محصولات</a><br><a data-go="/track">پیگیری سفارش</a><br><a data-go="/account">حساب کاربری</a></div></div>
    <div><h4>خدمات</h4><div>ضمانت اصالت<br>بازگشت ۷ روزه<br>ارسال اکسپرس</div></div>
    <div><h4>ارتباط</h4><div>تهران، خیابان ولیعصر<br>پشتیبانی آنلاین در سایت<br>اعلان‌ها: بله · تلگرام · روبیکا</div></div>
  </div>
  <div class="wrap" style="opacity:.55;margin-top:28px;font-size:12px">© <span id="y"></span> NEXA Shop — تک‌فایل PHP با هسته ووکامرس‌گونه</div>
</footer>
<button class="chat-fab" id="chatFab" title="پشتیبانی آنلاین" onclick="toggleChat()">💬</button>
<div class="chat-box hide" id="chatBox">
  <div class="chat-h"><div>پشتیبانی نِکسا<br><small style="opacity:.7">آنلاین — پاسخگویی سریع</small></div><button class="btn btn-ghost" onclick="toggleChat()">بستن</button></div>
  <div class="chat-m" id="chatM"></div>
  <div class="chat-i"><input id="chatIn" placeholder="پیام شما..." onkeydown="if(event.key==='Enter')sendChat()"><button class="btn btn-gold" onclick="sendChat()">ارسال</button></div>
</div>
<div id="overlay"></div>
<script>
const $ = (s,el=document)=>el.querySelector(s);
const $$ = (s,el=document)=>[...el.querySelectorAll(s)];
function fa(n){const s=String(Math.round(+n||0)).replace(/\B(?=(\d{3})+(?!\d))/g,'٬');return s.replace(/\d/g,d=>'۰۱۲۳۴۵۶۷۸۹'[d])}
const money = n => fa(n)+' تومان';
const S = {user:null,cart:[],cats:[],settings:{},csrf:'',products:[],wish:[]};
document.getElementById('y').textContent = new Date().getFullYear();
function toast(t){const e=document.createElement('div');e.className='toast';e.textContent=t;document.body.appendChild(e);setTimeout(()=>e.remove(),2400)}
function path(){const h=location.hash.slice(1)||'/'; const [p,q]=h.split('?'); return {p:p||'/', q:Object.fromEntries(new URLSearchParams(q||''))} }
function go(u){location.hash=u; }
window.addEventListener('hashchange', render);
document.body.addEventListener('click',e=>{const a=e.target.closest('[data-go]'); if(a){e.preventDefault(); go(a.dataset.go)}});
async function api(url, opt={}){
  const r = await fetch(url, {credentials:'include', headers:{'Content-Type':'application/json',...(opt.headers||{})}, ...opt, body: opt.body && typeof opt.body!=='string' ? JSON.stringify(opt.body): opt.body});
  const j = await r.json().catch(()=>({ok:false,error:'پاسخ نامعتبر'}));
  if(!r.ok && !j.error) j.error='خطا';
  return j;
}
async function boot(){
  const st = await api('/api/state');
  Object.assign(S, st);
  $('#cartBadge').textContent = fa(S.cart.reduce((a,c)=>a+c.qty,0));
  $('#splash').classList.add('off');
  if(S.settings.topbar) $('#ticker').textContent = S.settings.topbar;
  render();
  pollChat();
}
function openCart(){
  const items = S.cart;
  const total = items.reduce((a,c)=>a+c.qty*(c.product.sale_price||c.product.price),0);
  $('#overlay').innerHTML = `<div class="drawer" onclick="if(event.target===this)closeOv()"><div class="panel">
    <div class="row" style="justify-content:space-between"><h3>سبد خرید</h3><button class="btn btn-line" onclick="closeOv()">بستن</button></div>
    ${items.length? items.map(i=>`<div class="row" style="margin:14px 0">
      <div style="width:64px;height:64px;border-radius:12px;background:#eee center/cover url('${i.product.image}')"></div>
      <div style="flex:1"><b>${i.product.title}</b><div class="sub">${money(i.product.sale_price||i.product.price)} × ${fa(i.qty)}</div></div>
      <button class="btn btn-line" onclick="cart('remove',${i.product.id})">حذف</button>
    </div>`).join('') : '<div class="empty">سبد خالی است</div>'}
    <div class="side" style="margin-top:16px"><div class="row" style="justify-content:space-between"><span>جمع</span><b>${money(total)}</b></div>
    <button class="btn btn-gold" style="width:100%;margin-top:12px" onclick="closeOv();go('/checkout')">تسویه حساب</button>
    <button class="btn btn-line" style="width:100%;margin-top:8px" onclick="closeOv();go('/cart')">مشاهده سبد</button></div>
  </div></div>`;
}
function closeOv(){$('#overlay').innerHTML=''}
async function cart(action,id,qty=1){
  const j = await api('/api/cart',{method:'POST',body:{action,id,qty}});
  if(j.ok){S.cart=j.cart; $('#cartBadge').textContent=fa(S.cart.reduce((a,c)=>a+c.qty,0)); toast(j.message||'سبد به‌روز شد'); if($('.drawer')) openCart(); if(path().p==='/cart'||path().p==='/checkout') render();}
  else toast(j.error||'خطا');
}
async function render(){
  const {p,q} = path();
  $$('#menu a').forEach(a=>a.classList.toggle('on', a.getAttribute('data-go')===p || (p==='/'&&a.getAttribute('data-go')==='/')));
  const app = $('#app');
  if(p==='/') app.innerHTML = await viewHome();
  else if(p==='/shop') app.innerHTML = await viewShop(q);
  else if(p.startsWith('/product/')) app.innerHTML = await viewProduct(p.split('/')[2]);
  else if(p==='/cart') app.innerHTML = viewCart();
  else if(p==='/checkout') app.innerHTML = viewCheckout();
  else if(p==='/auth') app.innerHTML = viewAuth();
  else if(p==='/account') app.innerHTML = await viewAccount(q);
  else if(p==='/admin') app.innerHTML = await viewAdmin(q);
  else if(p==='/track') app.innerHTML = viewTrack();
  else if(p==='/about') app.innerHTML = viewAbout();
  else app.innerHTML = `<div class="wrap empty"><h2>صفحه پیدا نشد</h2><button class="btn btn-gold" data-go="/">بازگشت</button></div>`;
  window.scrollTo({top:0,behavior:'smooth'});
}
async function viewHome(){
  const feat = (S.featured||S.products||[]).slice(0,8);
  const prods = feat.length? feat : (await api('/api/products?featured=1')).items||[];
  return `<section class="hero" style="background-image:url('assets/hero.jpg')">
    <div class="hero-in">
      <div class="kicker">NEXA ATELIER  ·  EST. ۱۴۰۳</div>
      <h1>زیبایی،<br>با دقت انتخاب شده.</h1>
      <p>مجموعه‌ای محدود از عطر، ابریشم، فناوری و زیور؛ برای کسانی که کیفیت را با سکوت تشخیص می‌دهند.</p>
      <div class="cta"><button class="btn btn-gold" data-go="/shop">ورود به فروشگاه</button><button class="btn btn-ghost" data-go="/shop?cat=perfume">عطرهای امضا</button></div>
      <div class="stats"><div><b>+${fa(1200)}</b>مشتری راضی</div><div><b>${fa(48)}</b>ساعت ارسال</div><div><b>${fa(7)}</b>روز ضمانت بازگشت</div></div>
    </div></section>
    <section class="sec"><div class="wrap">
      <h2>دسته‌بندی‌ها</h2><p class="sub">آنچه برای یک زندگی آرام و لوکس لازم است</p>
      <div class="cats">${(S.categories||[]).map(c=>`<div class="cat" data-go="/shop?cat=${c.slug}"><i>${c.icon||'◆'}</i><b>${c.name}</b></div>`).join('')}</div>
    </div></section>
    <section class="sec" style="padding-top:0"><div class="wrap">
      <h2>منتخب سردبیر</h2><p class="sub">آثار و ابژه‌هایی که این فصل در نِکسا می‌درخشند</p>
      <div class="grid">${prods.map(card).join('')}</div>
    </div></section>
    <section class="sec" style="padding-top:0"><div class="wrap banner">
      <div style="background-image:linear-gradient(180deg,rgba(11,18,32,.45),rgba(11,18,32,.7)),url('assets/p2.jpg')">
        <div class="kicker">SILK EDIT</div><h3>ابریشم شامپاین</h3><p>لمس نور، بافت سکوت.</p><button class="btn btn-gold" data-go="/shop?cat=fashion">مشاهده کالکشن</button>
      </div>
      <div style="background-image:linear-gradient(180deg,rgba(11,18,32,.5),rgba(11,18,32,.75)),url('assets/p1.jpg')">
        <div class="kicker">SIGNATURE</div><h3>عطر امپریال</h3><button class="btn btn-ghost" data-go="/shop?cat=perfume">کشف عطر</button>
      </div>
    </div></section>`;
}
function card(p){
  const price = p.sale_price||p.price;
  const off = p.sale_price ? Math.round(100-(p.sale_price/p.price)*100) : 0;
  return `<article class="card">
    <div class="ph" style="background-image:url('${p.image}')" data-go="/product/${p.slug}">
      ${off?`<span class="sale">${fa(off)}٪</span>`:''}
      <button class="wish" onclick="event.stopPropagation();wish(${p.id})">♡</button>
    </div>
    <div class="bd">
      <div class="c">${p.cat_name||''}</div>
      <h3 data-go="/product/${p.slug}">${p.title}</h3>
      <div class="price"><b>${money(price)}</b>${p.sale_price?`<s>${money(p.price)}</s>`:''}</div>
      <button class="btn btn-navy add" onclick="cart('add',${p.id},1)">افزودن به سبد</button>
    </div></article>`;
}
async function viewShop(q){
  const qs = new URLSearchParams(q).toString();
  const d = await api('/api/products?'+qs);
  const items = d.items||[];
  return `<div class="wrap sec">
    <h2>${q.cat ? (S.categories.find(c=>c.slug===q.cat)||{}).name || 'فروشگاه' : (q.q? 'نتایج «'+q.q+'»':'فروشگاه')}</h2>
    <p class="sub">${fa(items.length)} محصول</p>
    <div class="filters">
      <input value="${q.q||''}" placeholder="جستجو" onchange="go('/shop?q='+encodeURIComponent(this.value))">
      <select onchange="go('/shop?cat='+this.value)">
        <option value="">همه دسته‌ها</option>
        ${(S.categories||[]).map(c=>`<option value="${c.slug}" ${q.cat===c.slug?'selected':''}>${c.name}</option>`).join('')}
      </select>
      <select onchange="go('/shop?sort='+this.value+'&cat=${q.cat||''}')">
        <option value="">جدیدترین</option>
        <option value="price_asc" ${q.sort==='price_asc'?'selected':''}>ارزان‌ترین</option>
        <option value="price_desc" ${q.sort==='price_desc'?'selected':''}>گران‌ترین</option>
        <option value="sale" ${q.sort==='sale'?'selected':''}>تخفیف‌دار</option>
      </select>
    </div>
    <div class="grid">${items.map(card).join('')||'<div class="empty">محصولی یافت نشد</div>'}</div>
  </div>`;
}
async function viewProduct(slug){
  const d = await api('/api/product/'+slug);
  if(!d.ok) return `<div class="wrap empty">محصول یافت نشد</div>`;
  const p=d.item, revs=d.reviews||[], rel=d.related||[];
  window._qty=1; window._pid=p.id;
  return `<div class="wrap sec pdp">
    <div class="gallery" style="background-image:url('${p.image}')"></div>
    <div>
      <div class="c">${p.cat_name} · SKU ${p.sku}</div>
      <h1 style="font-size:36px;margin:8px 0">${p.title}</h1>
      <div class="stars">${'★'.repeat(Math.round(p.rating||5))}${'☆'.repeat(5-Math.round(p.rating||5))} <span class="c">${fa(revs.length)} دیدگاه</span></div>
      <div class="price" style="margin:18px 0;font-size:22px"><b>${money(p.sale_price||p.price)}</b>${p.sale_price?`<s>${money(p.price)}</s>`:''}</div>
      <p style="line-height:2;color:#4a453f">${p.description}</p>
      <div class="c">موجودی: ${fa(p.stock)} عدد</div>
      <div class="row" style="margin:18px 0">
        <div class="qty"><button onclick="_qty=Math.max(1,_qty-1);$('#qv').textContent=_qty">−</button><span id="qv">1</span><button onclick="_qty++;$('#qv').textContent=_qty">+</button></div>
        <button class="btn btn-gold" onclick="cart('add',_pid,_qty)">افزودن به سبد</button>
        <button class="btn btn-line" onclick="wish(_pid)">علاقه‌مندی</button>
      </div>
      <div class="side" style="margin-top:18px;font-size:13px;line-height:2">ارسال ۲۴ تا ۴۸ ساعته · پرداخت در محل یا کارت‌به‌کارت · ضمانت اصالت نِکسا</div>
      <h3 style="margin-top:28px">دیدگاه‌ها</h3>
      ${revs.map(r=>`<div class="rev"><b>${r.name}</b> <span class="stars">${'★'.repeat(r.rating)}</span><p>${r.text}</p></div>`).join('')||'<p class="c">هنوز دیدگاهی نیست.</p>'}
      ${S.user?`<div class="side" style="margin-top:12px"><label class="l">نظر شما</label><textarea id="rv" class="field" rows="3"></textarea>
        <select id="rr" class="field"><option value="5">۵ ستاره</option><option value="4">۴</option><option value="3">۳</option></select>
        <button class="btn btn-navy" onclick="sendReview(${p.id})">ثبت دیدگاه</button></div>`:'<p class="c">برای ثبت نظر وارد شوید.</p>'}
    </div></div>
    <div class="wrap"><h2>ممکن است بپسندید</h2><div class="grid">${rel.map(card).join('')}</div></div>`;
}
function viewCart(){
  const items=S.cart; const sub=items.reduce((a,c)=>a+c.qty*(c.product.sale_price||c.product.price),0);
  return `<div class="wrap sec"><h2>سبد خرید</h2>
    ${items.length? `<table class="cart-table"><thead><tr><th>کالا</th><th>قیمت</th><th>تعداد</th><th></th></tr></thead><tbody>
    ${items.map(i=>`<tr><td><b>${i.product.title}</b></td><td>${money(i.product.sale_price||i.product.price)}</td>
    <td><div class="qty"><button onclick="cart('set',${i.product.id},${i.qty-1})">−</button><span>${fa(i.qty)}</span><button onclick="cart('set',${i.product.id},${i.qty+1})">+</button></div></td>
    <td><button class="btn btn-line" onclick="cart('remove',${i.product.id})">حذف</button></td></tr>`).join('')}
    </tbody></table>
    <div class="side" style="max-width:360px;margin-top:18px">
      <div class="row" style="justify-content:space-between"><span>جمع جزء</span><b>${money(sub)}</b></div>
      <input class="field" id="cpn" placeholder="کد تخفیف">
      <button class="btn btn-line" onclick="applyCpn()">اعمال کوپن</button>
      <button class="btn btn-gold" style="width:100%;margin-top:10px" data-go="/checkout">ادامه تسویه</button>
    </div>` : '<div class="empty">سبد شما خالی است<br><button class="btn btn-gold" data-go="/shop">فروشگاه</button></div>'}</div>`;
}
async function applyCpn(){const j=await api('/api/coupon',{method:'POST',body:{code:$('#cpn').value}}); toast(j.message||j.error); if(j.ok){S.coupon=j.coupon;}}
function viewCheckout(){
  if(!S.cart.length) return `<div class="wrap empty">سبد خالی است</div>`;
  const sub=S.cart.reduce((a,c)=>a+c.qty*(c.product.sale_price||c.product.price),0);
  const ship=45000; const disc=S.coupon? (S.coupon.type==='percent'? sub*S.coupon.amount/100 : S.coupon.amount):0;
  const total=Math.max(0,sub+ship-disc);
  return `<div class="wrap sec"><h2>تسویه حساب</h2><div class="chk">
    <div class="side">
      <h3>اطلاعات ارسال</h3>
      <label class="l">نام و نام خانوادگی</label><input class="field" id="nm" value="${S.user?S.user.name:''}">
      <label class="l">موبایل</label><input class="field" id="ph" value="${S.user?S.user.phone||'':''}">
      <label class="l">استان / شهر</label><input class="field" id="ct" placeholder="تهران، تهران">
      <label class="l">آدرس</label><textarea class="field" id="ad" rows="3"></textarea>
      <label class="l">کد پستی</label><input class="field" id="zp">
      <label class="l">یادداشت سفارش</label><textarea class="field" id="nt" rows="2"></textarea>
      <h3>پرداخت</h3>
      <div class="pay">
        <label><input type="radio" name="pay" value="cod" checked> پرداخت در محل</label>
        <label><input type="radio" name="pay" value="card"> کارت به کارت — ${S.settings.card_number||'۶۲۱۹-****'}</label>
        <label><input type="radio" name="pay" value="online"> درگاه آنلاین (زرین‌پال / آزمایشی)</label>
      </div>
    </div>
    <div class="side">
      <h3>خلاصه سفارش</h3>
      ${S.cart.map(i=>`<div class="row" style="justify-content:space-between;margin:8px 0"><span>${i.product.title} × ${fa(i.qty)}</span><span>${money((i.product.sale_price||i.product.price)*i.qty)}</span></div>`).join('')}
      <hr style="border:0;border-top:1px solid var(--line)">
      <div class="row" style="justify-content:space-between"><span>ارسال</span><span>${money(ship)}</span></div>
      <div class="row" style="justify-content:space-between"><span>تخفیف</span><span>${money(disc)}</span></div>
      <div class="row" style="justify-content:space-between;font-size:18px"><b>قابل پرداخت</b><b>${money(total)}</b></div>
      <button class="btn btn-gold" style="width:100%;margin-top:16px" onclick="placeOrder()">ثبت سفارش</button>
      <p class="c" style="margin-top:10px">با ثبت سفارش، رویداد به بله، تلگرام و روبیکا ارسال می‌شود.</p>
    </div>
  </div></div>`;
}
async function placeOrder(){
  const pay=$$('[name=pay]').find(x=>x.checked).value;
  const j=await api('/api/checkout',{method:'POST',body:{name:$('#nm').value,phone:$('#ph').value,city:$('#ct').value,address:$('#ad').value,zip:$('#zp').value,note:$('#nt').value,pay,coupon:S.coupon&&S.coupon.code}});
  if(!j.ok) return toast(j.error||'خطا در ثبت');
  S.cart=[]; $('#cartBadge').textContent='۰';
  $('#app').innerHTML = `<div class="wrap sec" style="text-align:center"><div class="side" style="max-width:520px;margin:auto">
    <div class="stars">✦</div><h2>سفارش ثبت شد</h2><p>شماره سفارش: <b>${j.number}</b></p>
    <p>مبلغ: ${money(j.total)}</p>
    ${j.pay==='card'?`<p>مبلغ را به کارت <b dir="ltr">${S.settings.card_number||''}</b> واریز و فیش را در چت پشتیبانی بفرستید.</p>`:''}
    <button class="btn btn-gold" data-go="/track">پیگیری</button>
  </div></div>`;
}
function viewAuth(){
  return `<div class="wrap sec" style="max-width:480px"><div class="side">
    <h2>ورود / عضویت</h2>
    <div class="row"><button class="btn btn-navy" onclick="$('#lg').classList.remove('hide');$('#rg').classList.add('hide')">ورود</button>
    <button class="btn btn-line" onclick="$('#rg').classList.remove('hide');$('#lg').classList.add('hide')">ثبت‌نام</button></div>
    <div id="lg">
      <label class="l">ایمیل</label><input class="field" id="le">
      <label class="l">رمز</label><input class="field" id="lp" type="password">
      <button class="btn btn-gold" style="width:100%" onclick="login()">ورود</button>
      <p class="c">مدیر نمونه: admin@nexa.shop / admin123</p>
    </div>
    <div id="rg" class="hide">
      <label class="l">نام</label><input class="field" id="rn">
      <label class="l">ایمیل</label><input class="field" id="re">
      <label class="l">موبایل</label><input class="field" id="rp">
      <label class="l">رمز</label><input class="field" id="rw" type="password">
      <button class="btn btn-gold" style="width:100%" onclick="register()">ایجاد حساب</button>
    </div>
  </div></div>`;
}
async function login(){const j=await api('/api/auth/login',{method:'POST',body:{email:$('#le').value,password:$('#lp').value}}); if(!j.ok) return toast(j.error); S.user=j.user; toast('خوش آمدید'); go(j.user.role==='admin'?'/admin':'/account')}
async function register(){const j=await api('/api/auth/register',{method:'POST',body:{name:$('#rn').value,email:$('#re').value,phone:$('#rp').value,password:$('#rw').value}}); if(!j.ok) return toast(j.error); S.user=j.user; go('/account')}
async function viewAccount(){
  if(!S.user) return viewAuth();
  const d=await api('/api/orders');
  return `<div class="wrap sec acc">
    <div class="side sidemenu">
      <h3>${S.user.name}</h3>
      <a class="on">سفارش‌ها</a>
      ${S.user.role==='admin'?`<a data-go="/admin">پنل مدیریت</a>`:''}
      <button onclick="logout()">خروج</button>
    </div>
    <div>${(d.items||[]).map(o=>`<div class="side" style="margin-bottom:12px"><b>${o.number}</b> · ${o.status_fa}<div class="c">${money(o.total)} · ${o.created}</div></div>`).join('')||'<div class="empty">سفارشی ندارید</div>'}</div>
  </div>`;
}
async function logout(){await api('/api/auth/logout',{method:'POST',body:{}}); S.user=null; go('/');}
function viewTrack(){return `<div class="wrap sec" style="max-width:520px"><div class="side"><h2>پیگیری سفارش</h2>
  <input class="field" id="tn" placeholder="شماره سفارش مثل NXA-1403-1001">
  <button class="btn btn-gold" onclick="doTrack()">پیگیری</button><div id="tr"></div></div></div>`}
async function doTrack(){const j=await api('/api/track/'+encodeURIComponent($('#tn').value)); $('#tr').innerHTML=j.ok?`<p><b>${j.item.number}</b> — ${j.item.status_fa}<br>${money(j.item.total)}</p>`:`<p class="c">${j.error}</p>`}
function viewAbout(){return `<div class="wrap sec"><h2>درباره نِکسا</h2><p class="sub" style="max-width:640px;line-height:2">نِکسا یک بوتیک انتخاب‌گر است. هر رویداد فروشگاه — از سفارش تا پیام پشتیبانی — به‌صورت زنده به پیام‌رسان‌های بله، تلگرام و روبیکا فرستاده می‌شود. هسته فروشگاه ووکامرس‌گونه است و افزونه‌های وردپرس با لایه سازگاری قابل نصب‌اند.</p></div>`}
async function viewAdmin(q){
  if(!S.user||S.user.role!=='admin') return viewAuth();
  const tab=q.tab||'dash';
  const nav=(id,t)=>`<button class="${tab===id?'on':''}" onclick="go('/admin?tab=${id}')">${t}</button>`;
  let body='';
  if(tab==='dash'){const s=await api('/api/admin/stats'); body=`<div class="kpis">
    <div class="kpi">سفارش‌ها<b>${fa(s.orders)}</b></div><div class="kpi">فروش<b>${money(s.revenue)}</b></div>
    <div class="kpi">مشتری<b>${fa(s.customers)}</b></div><div class="kpi">چت باز<b>${fa(s.chats)}</b></div></div>
    <h3 style="margin-top:22px">سفارش‌های اخیر</h3><div id="ao"></div>`; setTimeout(()=>adminTable('orders'),0);}
  if(tab==='products'){body=`<div class="row" style="justify-content:space-between"><h3>محصولات</h3><button class="btn btn-gold" onclick="editProduct()">محصول جدید</button></div><div id="ao"></div>`; setTimeout(()=>adminTable('products'),0);}
  if(tab==='orders'){body=`<h3>سفارش‌ها</h3><div id="ao"></div>`; setTimeout(()=>adminTable('orders'),0);}
  if(tab==='coupons'){body=`<div class="row" style="justify-content:space-between"><h3>کوپن‌ها</h3><button class="btn btn-gold" onclick="newCoupon()">کوپن جدید</button></div><div id="ao"></div>`; setTimeout(()=>adminTable('coupons'),0);}
  if(tab==='chat'){body=`<h3>صندوق پشتیبانی</h3><div id="ao"></div>`; setTimeout(()=>adminChat(),0);}
  if(tab==='plugins'){body=`<h3>افزونه‌های وردپرس</h3>
    <p class="c">فایل PHP یا ZIP افزونه وردپرس را نصب کنید. لایه سازگاری شامل add_action، add_filter، $wpdb، options و هوک‌های ووکامرس است.</p>
    <input type="file" id="plf" accept=".php,.zip">
    <button class="btn btn-gold" onclick="upPlugin()">نصب افزونه</button>
    <button class="btn btn-line" onclick="installSamples()">نصب افزونه‌های نمونه نِکسا</button>
    <div id="ao"></div>`; setTimeout(()=>adminTable('plugins'),0);}
  if(tab==='settings'){const s=S.settings; body=`<h3>تنظیمات و پیام‌رسان‌ها</h3>
    <div class="chk">
      <div>
        <label class="l">نام فروشگاه</label><input class="field" id="s_shop_name" value="${s.shop_name||'نِکسا'}">
        <label class="l">نوار بالا</label><input class="field" id="s_topbar" value="${s.topbar||''}">
        <label class="l">شماره کارت</label><input class="field" id="s_card_number" value="${s.card_number||''}">
        <label class="l">تلگرام Bot Token</label><input class="field" id="s_tg_token" value="${s.tg_token||''}" dir="ltr">
        <label class="l">تلگرام Chat ID</label><input class="field" id="s_tg_chat" value="${s.tg_chat||''}" dir="ltr">
        <label class="l">بله Bot Token</label><input class="field" id="s_bale_token" value="${s.bale_token||''}" dir="ltr">
        <label class="l">بله Chat ID</label><input class="field" id="s_bale_chat" value="${s.bale_chat||''}" dir="ltr">
        <label class="l">روبیکا Bot Token</label><input class="field" id="s_rb_token" value="${s.rb_token||''}" dir="ltr">
        <label class="l">روبیکا Chat ID</label><input class="field" id="s_rb_chat" value="${s.rb_chat||''}" dir="ltr">
        <button class="btn btn-gold" onclick="saveSet()">ذخیره</button>
        <button class="btn btn-line" onclick="testMsg()">ارسال پیام آزمایشی</button>
      </div>
      <div class="side" style="background:rgba(255,255,255,.03);color:#e8e0d4">
        <h4 style="color:var(--gold2)">وب‌هوک‌ها</h4>
        <p class="c" style="color:#cfc6b8">این آدرس‌ها را در ربات‌ها setWebhook کنید تا پاسخ چت از پیام‌رسان به سایت برگردد:</p>
        <code style="display:block;direction:ltr;font-size:12px;line-height:1.8" id="hooks"></code>
        <p class="c" style="color:#cfc6b8;margin-top:12px">رویدادهای ارسالی: سفارش جدید، ثبت‌نام، پیام چت، دیدگاه، موجودی کم، نصب افزونه، تغییر وضعیت سفارش.</p>
      </div>
    </div>`; setTimeout(()=>{$('#hooks').textContent = location.origin+'/hook/telegram\n'+location.origin+'/hook/bale\n'+location.origin+'/hook/rubika'},0);}
  return `<div class="admin">
    <div class="sidemenu">${nav('dash','داشبورد')}${nav('products','محصولات')}${nav('orders','سفارش‌ها')}${nav('coupons','کوپن')}${nav('chat','چت پشتیبانی')}${nav('plugins','افزونه‌ها')}${nav('settings','پیام‌رسان و تنظیمات')}<button data-go="/">بازگشت به فروشگاه</button></div>
    <div>${body}</div>
  </div>`;
}
async function adminTable(kind){
  const d=await api('/api/admin/'+kind);
  const el=$('#ao'); if(!el) return;
  if(kind==='products') el.innerHTML=`<table class="data"><tr><th>کالا</th><th>قیمت</th><th>موجودی</th><th></th></tr>${(d.items||[]).map(p=>`<tr><td>${p.title}</td><td>${money(p.price)}</td><td>${fa(p.stock)}</td><td><button class="btn btn-line" onclick='editProduct(${JSON.stringify(p).replace(/'/g,"&#39;")})'>ویرایش</button></td></tr>`).join('')}</table>`;
  if(kind==='orders') el.innerHTML=`<table class="data"><tr><th>شماره</th><th>مبلغ</th><th>وضعیت</th><th></th></tr>${(d.items||[]).map(o=>`<tr><td>${o.number}</td><td>${money(o.total)}</td><td>${o.status_fa}</td><td>
    <select onchange="setOrder(${o.id},this.value)">
      ${['pending','processing','shipped','completed','cancelled'].map(s=>`<option value="${s}" ${o.status===s?'selected':''}>${s}</option>`).join('')}
    </select></td></tr>`).join('')}</table>`;
  if(kind==='coupons') el.innerHTML=`<table class="data"><tr><th>کد</th><th>مقدار</th><th>فعال</th></tr>${(d.items||[]).map(c=>`<tr><td>${c.code}</td><td>${c.type==='percent'?c.amount+'٪':money(c.amount)}</td><td>${c.active?'بله':'خیر'}</td></tr>`).join('')}</table>`;
  if(kind==='plugins') el.innerHTML=`<table class="data"><tr><th>افزونه</th><th>نسخه</th><th></th></tr>${(d.items||[]).map(p=>`<tr><td><b>${p.name}</b><div class="c">${p.description||''}</div></td><td>${p.version}</td><td>
    <button class="btn ${p.active?'btn-line':'btn-gold'}" onclick="togglePl(${p.id})">${p.active?'غیرفعال':'فعال'}</button>
  </td></tr>`).join('')||'<p>افزونه‌ای نصب نشده</p>'}</table>`;
}
async function adminChat(){
  const d=await api('/api/admin/chats');
  $('#ao').innerHTML = (d.items||[]).map(t=>`<div class="side" style="background:rgba(255,255,255,.04);margin-bottom:10px">
    <b>${t.name}</b> · ${t.thread}<div class="c">${t.last}</div>
    <input class="field" id="r${t.thread}" placeholder="پاسخ"><button class="btn btn-gold" onclick="adminReply('${t.thread}')">ارسال</button>
  </div>`).join('')||'<p>گفتگویی نیست</p>';
}
async function adminReply(th){const t=$('#r'+th).value; await api('/api/admin/chat',{method:'POST',body:{thread:th,message:t}}); toast('ارسال شد'); adminChat();}
async function setOrder(id,status){await api('/api/admin/orders',{method:'POST',body:{id,status}}); toast('وضعیت به‌روز شد');}
function editProduct(p={}){
  $('#overlay').innerHTML=`<div class="modal" onclick="if(event.target===this)closeOv()"><div class="box" style="width:min(560px,100%)">
    <h3>${p.id?'ویرایش':'محصول جدید'}</h3>
    <input class="field" id="pt" placeholder="عنوان" value="${p.title||''}">
    <input class="field" id="pp" placeholder="قیمت" value="${p.price||''}">
    <input class="field" id="ps" placeholder="قیمت فروش" value="${p.sale_price||''}">
    <input class="field" id="pk" placeholder="موجودی" value="${p.stock||10}">
    <input class="field" id="pc" placeholder="دسته slug مثل perfume" value="${p.cat||''}">
    <input class="field" id="pi" placeholder="آدرس تصویر" value="${p.image||'assets/p1.jpg'}">
    <textarea class="field" id="pd" rows="4" placeholder="توضیح">${p.description||''}</textarea>
    <button class="btn btn-gold" onclick="saveProd(${p.id||0})">ذخیره</button>
  </div></div>`;
}
async function saveProd(id){const j=await api('/api/admin/products',{method:'POST',body:{id,title:$('#pt').value,price:+$('#pp').value,sale_price:+$('#ps').value||0,stock:+$('#pk').value,cat:$('#pc').value,image:$('#pi').value,description:$('#pd').value}}); toast(j.ok?'ذخیره شد':j.error); closeOv(); render();}
function newCoupon(){
  $('#overlay').innerHTML=`<div class="modal" onclick="if(event.target===this)closeOv()"><div class="box">
    <h3>کوپن</h3><input class="field" id="cc" placeholder="کد مثل NEXA10">
    <select class="field" id="cty"><option value="percent">درصد</option><option value="fixed">مبلغی</option></select>
    <input class="field" id="ca" placeholder="مقدار">
    <button class="btn btn-gold" onclick="saveCpn()">ذخیره</button></div></div>`;
}
async function saveCpn(){await api('/api/admin/coupons',{method:'POST',body:{code:$('#cc').value,type:$('#cty').value,amount:+$('#ca').value}}); closeOv(); render();}
async function saveSet(){
  const body={};
  $$('[id^=s_]').forEach(i=>body[i.id.slice(2)]=i.value);
  const j=await api('/api/admin/settings',{method:'POST',body}); if(j.ok){S.settings=j.settings; toast('ذخیره شد')}
}
async function testMsg(){const j=await api('/api/admin/test-notify',{method:'POST',body:{}}); toast(j.message||j.error)}
async function upPlugin(){
  const f=$('#plf').files[0]; if(!f) return toast('فایل را انتخاب کنید');
  const fd=new FormData(); fd.append('plugin', f);
  const r=await fetch('/api/admin/plugins/upload',{method:'POST',body:fd,credentials:'include'});
  const j=await r.json(); toast(j.message||j.error); render();
}
async function installSamples(){const j=await api('/api/admin/plugins/samples',{method:'POST',body:{}}); toast(j.message||j.error); render();}
async function togglePl(id){await api('/api/admin/plugins',{method:'POST',body:{id,toggle:1}}); render();}
async function wish(id){const j=await api('/api/wishlist',{method:'POST',body:{id}}); toast(j.message||j.error||'افزوده شد')}
async function sendReview(id){const j=await api('/api/review',{method:'POST',body:{product_id:id,text:$('#rv').value,rating:+$('#rr').value}}); toast(j.message||j.error); if(j.ok) render();}
let chatOpen=false, chatThread=localStorage.getItem('nexa_thread')||('t'+Math.random().toString(36).slice(2,10));
localStorage.setItem('nexa_thread', chatThread);
function toggleChat(){chatOpen=!chatOpen; $('#chatBox').classList.toggle('hide', !chatOpen); if(chatOpen) loadChat()}
async function loadChat(){
  const d=await api('/api/chat?thread='+chatThread);
  $('#chatM').innerHTML=(d.items||[]).map(m=>`<div class="bubble ${m.role==='user'?'me':'them'}">${m.message}<div style="opacity:.5;font-size:10px">${m.name||''}</div></div>`).join('')||'<div class="c">سلام، چطور کمک کنیم؟</div>';
  $('#chatM').scrollTop=9999;
}
async function sendChat(){
  const t=$('#chatIn').value.trim(); if(!t) return;
  $('#chatIn').value='';
  await api('/api/chat',{method:'POST',body:{thread:chatThread,message:t,name:S.user?S.user.name:'مهمان'}});
  loadChat();
}
async function pollChat(){ if(chatOpen) await loadChat(); setTimeout(pollChat, 4000); }
boot();
</script>
</body>
</html>

HTML;
}
