<<<<<<< HEAD
﻿<?php
=======
<?php
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
declare(strict_types=1);
require_once __DIR__ . '/../app/Bootstrap.php';
require_once __DIR__ . '/../app/Db.php';
use App\Db;
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['uid'])) {
  http_response_code(401);
  echo json_encode(['ok'=>false,'error'=>'UNAUTH']);
  exit;
}
/* ---------- ENV & Debug ---------- */
$botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? '';
$announceChat = $_ENV['TELEGRAM_ANNOUNCE_CHAT_ID'] ?? '';
$appDebug = filter_var($_ENV['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN)
                || (isset($_GET['debug']) && $_GET['debug']==='1');
<<<<<<< HEAD
$LOG_FILE = '/www/wwwroot/ethnix.net/storage/logs/buy_plan.log';
=======
$LOG_FILE = '/www/wwwroot/babachecker.com/storage/logs/buy_plan.log';
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
@is_dir(dirname($LOG_FILE)) || @mkdir(dirname($LOG_FILE), 0775, true);
function logerr(string $m){ global $LOG_FILE; @file_put_contents($LOG_FILE,'['.date('c')."] $m\n",FILE_APPEND); }
/* ---------- UTF-8 safe helpers (mbstring fallback) ---------- */
function utf8_first_char(string $s): string { if (function_exists('mb_substr')) return mb_substr($s, 0, 1, 'UTF-8'); if (preg_match('/^./u', $s, $m)) return $m[0]; return substr($s, 0, 1); }
function utf8_upper(string $s): string { if (function_exists('mb_strtoupper')) return mb_strtoupper($s, 'UTF-8'); return strtoupper($s); }
/* ---------- tiny utils ---------- */
function tg_send(string $token,string $chat,string $txt,string $parse='HTML'):bool{
  if($token===''||$chat==='') return false;
  if(!function_exists('curl_init')){ logerr('curl missing for telegram'); return false; }
  $ch=curl_init("https://api.telegram.org/bot{$token}/sendMessage");
  curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>http_build_query([
    'chat_id'=>$chat,'text'=>$txt,'parse_mode'=>$parse,'disable_web_page_preview'=>'true'
  ]),CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>10]);
  curl_exec($ch); $ok=(curl_errno($ch)===0)&&(curl_getinfo($ch,CURLINFO_HTTP_CODE)===200);
  if(!$ok) logerr('tg_send fail: '.curl_error($ch).' http='.curl_getinfo($ch,CURLINFO_HTTP_CODE));
  curl_close($ch); return $ok;
}
function mask_public_name(string $who): string { $name = trim(ltrim($who,'@')); if($name==='') return 'U***'; $f = utf8_first_char($name); return utf8_upper($f).'***'; }
function make_receipt_num(int $len=10):string{ $s=(string)random_int(1,9); for($i=1;$i<$len;$i++) $s.=(string)random_int(0,9); return $s; }
function mask_receipt_numeric(string $num):string{ $n=preg_replace('/\D+/','',$num); $L=strlen($n); if($L<=3) return $n; return substr($n,0,1).str_repeat('X',max(0,$L-3)).substr($n,-2); }
function fmt_human_date(string $ymd):string{ try{$d=new DateTime($ymd);return $d->format('d-m-Y');}catch(Throwable){return $ymd;} }
function plain_plan_name(string $label):string{ $s=preg_replace('/[^\p{L}\p{N}\s\-]/u','',$label); return trim(preg_replace('/\s+/',' ',$s)); }
<<<<<<< HEAD
/** à¦•à¦²à¦¾à¦® à¦†à¦›à§‡ à¦•à¦¿à¦¨à¦¾â€”information_schema à¦›à¦¾à§œà¦¾à¦‡ à¦¸à§‡à¦«à¦­à¦¾à¦¬à§‡ */
=======
/** কলাম আছে কিনা—information_schema ছাড়াই সেফভাবে */
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
function columnExists(PDO $pdo, string $table, string $column): bool {
  $col = $pdo->quote($column);
  $stmt = $pdo->query("SHOW COLUMNS FROM `{$table}` LIKE {$col}");
  return ($stmt && $stmt->fetch(PDO::FETCH_ASSOC)) ? true : false;
}
/* ---------- Plans (ONLY 4, updated price & credits) ---------- */
$PLANS = [
<<<<<<< HEAD
  'silver'   => ['label'=>'Silver ðŸ¥ˆ',   'price'=>10, 'credits'=>800,   'bonus'=>1, 'days'=>7],
  'gold'     => ['label'=>'Gold ðŸ¥‡',     'price'=>20, 'credits'=>1500,  'bonus'=>2, 'days'=>15],
  'platinum' => ['label'=>'Platinum ðŸ…', 'price'=>30, 'credits'=>3000,  'bonus'=>3, 'days'=>30],
  'diamond'  => ['label'=>'Diamond ðŸ’Ž',  'price'=>70, 'credits'=>10000, 'bonus'=>5, 'days'=>90],
];
/* ---------- Credit Packs ---------- */
$CREDIT_PACKS = [
  'c1' => ['label'=>'1 XCoin â†’ 100 credits', 'price'=>1, 'credits'=>100, 'days'=>30],
  'c5' => ['label'=>'5 XCoin â†’ 600 credits', 'price'=>5, 'credits'=>600, 'days'=>30],
  'c10'=> ['label'=>'10 XCoin â†’ 1300 credits','price'=>10, 'credits'=>1300, 'days'=>30],
  'c15'=> ['label'=>'15 XCoin â†’ 2000 credits','price'=>15, 'credits'=>2000, 'days'=>30],
  'c30'=> ['label'=>'30 XCoin â†’ 4000 credits','price'=>30, 'credits'=>4000, 'days'=>30],
  'c50'=> ['label'=>'50 XCoin â†’ 7000 credits','price'=>50, 'credits'=>7000, 'days'=>30],
];
/* ---------- Killer Credit Packs ---------- */
$KILLER_PACKS = [
  'k1' => ['label'=>'1 XCoin â†’ 50 kcoin', 'price'=>1, 'kcoin'=>50, 'days'=>1],
  'k5' => ['label'=>'5 XCoin â†’ 300 kcoin', 'price'=>5, 'kcoin'=>300, 'days'=>4],
  'k10' => ['label'=>'10 XCoin â†’ 650 kcoin', 'price'=>10, 'kcoin'=>650, 'days'=>7],
  'k15' => ['label'=>'15 XCoin â†’ 1000 kcoin', 'price'=>15, 'kcoin'=>1000, 'days'=>10],
  'k30' => ['label'=>'30 XCoin â†’ 2000 kcoin', 'price'=>30, 'kcoin'=>2000, 'days'=>15],
  'k50' => ['label'=>'50 XCoin â†’ 3500 kcoin', 'price'=>50, 'kcoin'=>3500, 'days'=>30],
=======
  'silver'   => ['label'=>'Silver 🥈',   'price'=>10, 'credits'=>800,   'bonus'=>1, 'days'=>7],
  'gold'     => ['label'=>'Gold 🥇',     'price'=>20, 'credits'=>1500,  'bonus'=>2, 'days'=>15],
  'platinum' => ['label'=>'Platinum 🏅', 'price'=>30, 'credits'=>3000,  'bonus'=>3, 'days'=>30],
  'diamond'  => ['label'=>'Diamond 💎',  'price'=>70, 'credits'=>10000, 'bonus'=>5, 'days'=>90],
];
/* ---------- Credit Packs ---------- */
$CREDIT_PACKS = [
  'c1' => ['label'=>'1 XCoin → 100 credits', 'price'=>1, 'credits'=>100, 'days'=>30],
  'c5' => ['label'=>'5 XCoin → 600 credits', 'price'=>5, 'credits'=>600, 'days'=>30],
  'c10'=> ['label'=>'10 XCoin → 1300 credits','price'=>10, 'credits'=>1300, 'days'=>30],
  'c15'=> ['label'=>'15 XCoin → 2000 credits','price'=>15, 'credits'=>2000, 'days'=>30],
  'c30'=> ['label'=>'30 XCoin → 4000 credits','price'=>30, 'credits'=>4000, 'days'=>30],
  'c50'=> ['label'=>'50 XCoin → 7000 credits','price'=>50, 'credits'=>7000, 'days'=>30],
];
/* ---------- Killer Credit Packs ---------- */
$KILLER_PACKS = [
  'k1' => ['label'=>'1 XCoin → 50 kcoin', 'price'=>1, 'kcoin'=>50, 'days'=>1],
  'k5' => ['label'=>'5 XCoin → 300 kcoin', 'price'=>5, 'kcoin'=>300, 'days'=>4],
  'k10' => ['label'=>'10 XCoin → 650 kcoin', 'price'=>10, 'kcoin'=>650, 'days'=>7],
  'k15' => ['label'=>'15 XCoin → 1000 kcoin', 'price'=>15, 'kcoin'=>1000, 'days'=>10],
  'k30' => ['label'=>'30 XCoin → 2000 kcoin', 'price'=>30, 'kcoin'=>2000, 'days'=>15],
  'k50' => ['label'=>'50 XCoin → 3500 kcoin', 'price'=>50, 'kcoin'=>3500, 'days'=>30],
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
];
$pdo = Db::pdo();
$uid = (int)$_SESSION['uid'];
try {
  // detect columns: prefer xcoin; fall back old names for compatibility
  $hasX = columnExists($pdo,'users','xcoin');
  $hasK = columnExists($pdo,'users','kcoin');
  $hasCreditsExpiry = columnExists($pdo,'users','credits_expiry');
  $hasKcoinExpiry = columnExists($pdo,'users','kcoin_expiry');
  $cashCol = $hasX ? 'xcoin' : ($hasK ? 'kcoin' : 'cash');
  // load user
  $st = $pdo->prepare("SELECT id, telegram_id, username, first_name, last_name, {$cashCol} AS xbal, credits, status, expiry_date".
                      ($hasK?", kcoin":"").
                      ($hasCreditsExpiry?", credits_expiry":"").
                      ($hasKcoinExpiry?", kcoin_expiry":"").
                      " FROM users WHERE id=:id LIMIT 1");
  $st->execute([':id'=>$uid]);
  $u = $st->fetch(PDO::FETCH_ASSOC);
  if (!$u) throw new RuntimeException('USER_NOT_FOUND');
  $action = isset($_POST['action']) ? strtolower(trim((string)$_POST['action'])) : '';
  // Telegram user info
  $tgId = (string)($u['telegram_id'] ?? '');
  $first = trim((string)($u['first_name'] ?? ''));
  $last = trim((string)($u['last_name'] ?? ''));
  $uname = trim((string)($u['username'] ?? ''));
  $who = $first || $last ? trim($first.' '.$last) : ($uname!=='' ? '@'.$uname : 'Friend');
  $receiptNum = make_receipt_num(10);
  $receiptFull = 'BABACHECKER-'.$receiptNum;
  $receiptPub = 'BABACHECKER-'.mask_receipt_numeric($receiptNum);
  $purchaseDate = (new DateTime('now'))->format('d-m-Y');
  // ---------------- PLAN FLOW (default) ----------------
  if ($action==='' || $action==='plan') {
    $planKey = isset($_POST['plan']) ? strtolower(trim((string)$_POST['plan'])) : '';
    if ($planKey==='' || !isset($PLANS[$planKey])) { echo json_encode(['ok'=>false,'error'=>'NO_PLAN']); exit; }
    $plan = $PLANS[$planKey];
    $planLabel = $plan['label'];
    $planPlain = plain_plan_name($planLabel);
    $price = (int)$plan['price'];
    $bonusCoin = (int)$plan['bonus'];
    $addCred = (int)$plan['credits'];
    $days = (int)$plan['days'];
    $haveX = (int)$u['xbal'];
    if ($haveX < $price) { echo json_encode(['ok'=>false,'error'=>'INSUFFICIENT']); exit; }
    // expiry compute
    $now = new DateTime('today');
    if (!empty($u['expiry_date'])) {
      try { $cur = new DateTime((string)$u['expiry_date']); $base = ($cur > $now) ? $cur : $now; }
      catch(Throwable) { $base = $now; }
    } else { $base = $now; }
    $base->setTime(23,59,59);
    $base->modify("+{$days} days");
    $newExpiry = $base->format('Y-m-d');
    // balances
    $newX = $haveX - $price + $bonusCoin;
    $newCredits = (int)$u['credits'] + $addCred;
    $newStatus = 'premium';
    // update
    $set = "{$cashCol}=:x, credits=:c, status=:s, expiry_date=:e";
    if (columnExists($pdo,'users','plan_name')) $set .= ", plan_name=:p";
    $up = $pdo->prepare("UPDATE users SET {$set} WHERE id=:id");
    $params = [':x'=>$newX, ':c'=>$newCredits, ':s'=>$newStatus, ':e'=>$newExpiry, ':id'=>$uid];
    if (strpos($set,'plan_name')!==false) $params[':p'] = $planPlain;
    $up->execute($params);
    // Telegram receipts
    $expiryHuman = fmt_human_date($newExpiry);
    $validity = "{$days} Days";
    $priceStr = $price.'$';
    if ($botToken!=='' && $tgId!=='') {
<<<<<<< HEAD
$dm = "Thanks For Purchasing Our {$planLabel} âœ…
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
ID âžœ {$tgId}
Plan âžœ {$planLabel}
Price âžœ {$priceStr}
Purchase Date âžœ {$purchaseDate}
Expiry âžœ {$expiryHuman}
Validity âžœ {$validity}
Status âžœ Paid â˜‘ï¸
Payment Method âžœ CRYPTO.
Receipt ID âžœ {$receiptFull}
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
This is a receipt for your plan. Save it in a secure place. This will help you if anything goes wrong with your plan purchases.
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
Have a Good Day.
âžœ ethnix.net";
=======
$dm = "Thanks For Purchasing Our {$planLabel} ✅
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
ID ➜ {$tgId}
Plan ➜ {$planLabel}
Price ➜ {$priceStr}
Purchase Date ➜ {$purchaseDate}
Expiry ➜ {$expiryHuman}
Validity ➜ {$validity}
Status ➜ Paid ☑️
Payment Method ➜ CRYPTO.
Receipt ID ➜ {$receiptFull}
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
This is a receipt for your plan. Save it in a secure place. This will help you if anything goes wrong with your plan purchases.
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
Have a Good Day.
➜ BabaChecker.com";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $tgId, $dm, 'HTML');
    }
    if ($botToken!=='' && $announceChat!=='') {
      $pubName = mask_public_name($who);
<<<<<<< HEAD
      $short = "ðŸ§¾ <b>New Purchase</b>\n".
               "<b>User</b> âžœ {$pubName}\n".
               "<b>Plan</b> âžœ <b>{$planLabel}</b>\n".
               "<b>Price</b> âžœ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> âžœ <code>{$receiptPub}</code>";
=======
      $short = "🧾 <b>New Purchase</b>\n".
               "<b>User</b> ➜ {$pubName}\n".
               "<b>Plan</b> ➜ <b>{$planLabel}</b>\n".
               "<b>Price</b> ➜ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> ➜ <code>{$receiptPub}</code>";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $announceChat, $short, 'HTML');
    }
    echo json_encode([
      'ok'=>true,
      'data'=>[
        'kind' => 'plan',
        'plan' => $planKey,
        'plan_label' => $planLabel,
        'plan_name' => $planPlain,
        'price' => $price,
        'bonus_added' => $bonusCoin,
        'credits_added' => $addCred,
        'new_xcoin' => $newX,
        'new_credits' => $newCredits,
        'new_status' => $newStatus,
        'new_expiry' => $newExpiry,
        'receipt_id' => $receiptFull,
        'receipt_public' => $receiptPub,
        'cash_column' => $cashCol,
        'summary' => "Plan <b>{$planLabel}</b> applied: +<b>{$addCred}</b> credits, +<b>{$bonusCoin}</b> XCoin. Premium till <b>{$expiryHuman}</b>."
      ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }
  // ---------------- CREDIT PACKS ----------------
  if ($action==='credits') {
    if (!$hasCreditsExpiry) { echo json_encode(['ok'=>false,'error'=>'UNSUPPORTED']); exit; }
    $packKey = isset($_POST['pack']) ? strtolower(trim((string)$_POST['pack'])) : '';
    if ($packKey==='' || !isset($CREDIT_PACKS[$packKey])) { echo json_encode(['ok'=>false,'error'=>'NO_PACK']); exit; }
    $pack = $CREDIT_PACKS[$packKey];
    $packLabel = $pack['label'];
    $price = (int)$pack['price'];
    $addC = (int)$pack['credits'];
    $days = (int)$pack['days'];
    $haveX = (int)$u['xbal'];
    if ($haveX < $price) { echo json_encode(['ok'=>false,'error'=>'INSUFFICIENT']); exit; }
    // expiry compute
    $now = new DateTime('today');
    $newCreditsExpiry = $now->setTime(23,59,59)->modify("+{$days} days")->format('Y-m-d');
    if ($hasCreditsExpiry && !empty($u['credits_expiry'])) {
      try {
        $cur = new DateTime((string)$u['credits_expiry']);
        if ($cur > $now) $newCreditsExpiry = max($newCreditsExpiry, $u['credits_expiry']);
      } catch(Throwable) {}
    }
    // balances
    $newX = $haveX - $price;
    $newCre = (int)$u['credits'] + $addC;
    // update
    $set = "{$cashCol}=:x, credits=:c, credits_expiry=:ce";
    $up = $pdo->prepare("UPDATE users SET {$set} WHERE id=:id");
    $params = [':x'=>$newX, ':c'=>$newCre, ':ce'=>$newCreditsExpiry, ':id'=>$uid];
    $up->execute($params);
    // Telegram receipts
    $priceStr = $price.'$';
    $expiryHuman = fmt_human_date($newCreditsExpiry);
    $validity = "{$days} Days";
    if ($botToken!=='' && $tgId!=='') {
<<<<<<< HEAD
$dm = "Thanks For Purchasing Credits Pack {$packLabel} âœ…
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
ID âžœ {$tgId}
Pack âžœ {$packLabel}
Price âžœ {$priceStr}
Credits Added âžœ {$addC}
Purchase Date âžœ {$purchaseDate}
Expiry âžœ {$expiryHuman}
Validity âžœ {$validity}
Status âžœ Paid â˜‘ï¸
Payment Method âžœ CRYPTO.
Receipt ID âžœ {$receiptFull}
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
This is a receipt for your credits purchase. Save it in a secure place. This will help you if anything goes wrong with your purchase.
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
Have a Good Day.
âžœ ethnix.net";
=======
$dm = "Thanks For Purchasing Credits Pack {$packLabel} ✅
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
ID ➜ {$tgId}
Pack ➜ {$packLabel}
Price ➜ {$priceStr}
Credits Added ➜ {$addC}
Purchase Date ➜ {$purchaseDate}
Expiry ➜ {$expiryHuman}
Validity ➜ {$validity}
Status ➜ Paid ☑️
Payment Method ➜ CRYPTO.
Receipt ID ➜ {$receiptFull}
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
This is a receipt for your credits purchase. Save it in a secure place. This will help you if anything goes wrong with your purchase.
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
Have a Good Day.
➜ BabaChecker.com";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $tgId, $dm, 'HTML');
    }
    if ($botToken!=='' && $announceChat!=='') {
      $pubName = mask_public_name($who);
<<<<<<< HEAD
      $short = "ðŸ§¾ <b>New Credits Purchase</b>\n".
               "<b>User</b> âžœ {$pubName}\n".
               "<b>Pack</b> âžœ <b>{$packLabel}</b>\n".
               "<b>Price</b> âžœ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> âžœ <code>{$receiptPub}</code>";
=======
      $short = "🧾 <b>New Credits Purchase</b>\n".
               "<b>User</b> ➜ {$pubName}\n".
               "<b>Pack</b> ➜ <b>{$packLabel}</b>\n".
               "<b>Price</b> ➜ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> ➜ <code>{$receiptPub}</code>";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $announceChat, $short, 'HTML');
    }
    echo json_encode([
      'ok'=>true,
      'data'=>[
        'kind' => 'credits_pack',
        'pack' => $packKey,
        'pack_label' => $packLabel,
        'price' => $price,
        'credits_add' => $addC,
        'new_xcoin' => $newX,
        'new_credits' => $newCre,
        'credits_expiry' => $hasCreditsExpiry ? $newCreditsExpiry : null,
        'receipt_id' => $receiptFull,
        'receipt_public' => $receiptPub,
        'summary' => "+<b>{$addC}</b> credits added. Credits expire on <b>{$expiryHuman}</b>."
      ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }
  // ---------------- KILLER CREDIT PACKS ----------------
  if ($action==='kcoin') {
    if (!$hasK || !$hasKcoinExpiry) { echo json_encode(['ok'=>false,'error'=>'UNSUPPORTED']); exit; }
    $packKey = isset($_POST['pack']) ? strtolower(trim((string)$_POST['pack'])) : '';
    if ($packKey==='' || !isset($KILLER_PACKS[$packKey])) { echo json_encode(['ok'=>false,'error'=>'NO_PACK']); exit; }
    $pack = $KILLER_PACKS[$packKey];
    $packLabel = $pack['label'];
    $price = (int)$pack['price'];
    $addK = (int)$pack['kcoin'];
    $days = (int)$pack['days'];
    $haveX = (int)$u['xbal'];
    if ($haveX < $price) { echo json_encode(['ok'=>false,'error'=>'INSUFFICIENT']); exit; }
    // expiry compute
    $now = new DateTime('today');
    $newKcoinExpiry = $now->setTime(23,59,59)->modify("+{$days} days")->format('Y-m-d');
    if (!empty($u['kcoin_expiry'])) {
      try {
        $cur = new DateTime((string)$u['kcoin_expiry']);
        if ($cur > $now) $newKcoinExpiry = max($newKcoinExpiry, $u['kcoin_expiry']);
      } catch(Throwable) {}
    }
    // balances
    $newX = $haveX - $price;
    $newKcoin = (int)($u['kcoin'] ?? 0) + $addK;
    // update
    $set = "{$cashCol}=:x, kcoin=:k, kcoin_expiry=:ke";
    $up = $pdo->prepare("UPDATE users SET {$set} WHERE id=:id");
    $params = [':x'=>$newX, ':k'=>$newKcoin, ':ke'=>$newKcoinExpiry, ':id'=>$uid];
    $up->execute($params);
    // Telegram receipts
    $priceStr = $price.'$';
    $expiryHuman = fmt_human_date($newKcoinExpiry);
    $validity = "{$days} Days";
    if ($botToken!=='' && $tgId!=='') {
<<<<<<< HEAD
$dm = "Thanks For Purchasing Killer Credits Pack {$packLabel} âœ…
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
ID âžœ {$tgId}
Pack âžœ {$packLabel}
Price âžœ {$priceStr}
Killer Credits Added âžœ {$addK}
Purchase Date âžœ {$purchaseDate}
Expiry âžœ {$expiryHuman}
Validity âžœ {$validity}
Status âžœ Paid â˜‘ï¸
Payment Method âžœ CRYPTO.
Receipt ID âžœ {$receiptFull}
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
This is a receipt for your killer credits purchase. Save it in a secure place. This will help you if anything goes wrong with your purchase.
â” â” â” â” â” â” â” â” â” â” â” â” â” â”
Have a Good Day.
âžœ ethnix.net";
=======
$dm = "Thanks For Purchasing Killer Credits Pack {$packLabel} ✅
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
ID ➜ {$tgId}
Pack ➜ {$packLabel}
Price ➜ {$priceStr}
Killer Credits Added ➜ {$addK}
Purchase Date ➜ {$purchaseDate}
Expiry ➜ {$expiryHuman}
Validity ➜ {$validity}
Status ➜ Paid ☑️
Payment Method ➜ CRYPTO.
Receipt ID ➜ {$receiptFull}
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
This is a receipt for your killer credits purchase. Save it in a secure place. This will help you if anything goes wrong with your purchase.
━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━ ━
Have a Good Day.
➜ BabaChecker.com";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $tgId, $dm, 'HTML');
    }
    if ($botToken!=='' && $announceChat!=='') {
      $pubName = mask_public_name($who);
<<<<<<< HEAD
      $short = "ðŸ§¾ <b>New Killer Credits Purchase</b>\n".
               "<b>User</b> âžœ {$pubName}\n".
               "<b>Pack</b> âžœ <b>{$packLabel}</b>\n".
               "<b>Price</b> âžœ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> âžœ <code>{$receiptPub}</code>";
=======
      $short = "🧾 <b>New Killer Credits Purchase</b>\n".
               "<b>User</b> ➜ {$pubName}\n".
               "<b>Pack</b> ➜ <b>{$packLabel}</b>\n".
               "<b>Price</b> ➜ <b>{$priceStr}</b>\n".
               "<b>Receipt</b> ➜ <code>{$receiptPub}</code>";
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
      tg_send($botToken, $announceChat, $short, 'HTML');
    }
    echo json_encode([
      'ok'=>true,
      'data'=>[
        'kind' => 'kcoin_pack',
        'pack' => $packKey,
        'pack_label' => $packLabel,
        'price' => $price,
        'kcoin_add' => $addK,
        'new_xcoin' => $newX,
        'new_kcoin' => $newKcoin,
        'kcoin_expiry' => $hasKcoinExpiry ? $newKcoinExpiry : null,
        'receipt_id' => $receiptFull,
        'receipt_public' => $receiptPub,
        'summary' => "+<b>{$addK}</b> kcoin added. Killer Credits expire on <b>{$expiryHuman}</b>."
      ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }
  // ---------------- Unsupported ----------------
  echo json_encode(['ok'=>false,'error'=>'UNSUPPORTED']);
} catch (Throwable $e) {
  logerr('BUY_PLAN ERROR: '.$e->getMessage().' @'.$e->getFile().':'.$e->getLine());
  echo json_encode(['ok'=>false,'error'=>'SERVER'] + ($appDebug?['debug'=>$e->getMessage()]:[]));
}
<<<<<<< HEAD

=======
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
