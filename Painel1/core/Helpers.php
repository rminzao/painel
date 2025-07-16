<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= 8 && mb_strlen($password) <= 40)) {
        return true;
    }

    return false;
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_DEFAULT);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(
        ["-----", "----", "---", "--"],
        "-",
        str_replace(
            " ",
            "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(
        " ",
        "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $text
 * @return string
 */
function str_textarea(string $text): string
{
    $text = htmlspecialchars($text);
    $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;"];
    return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "...", $filter = true): string
{
    if ($filter) {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    }

    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * @param string $price
 * @return string
 */
function str_price(?string $price): string
{
    return number_format((!empty($price) ? $price : 0), 2, ",", ".");
}

/**
 * @param int|null $length
 * @return string
 */
function str_hash(?int $length): string
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, $length);
}

/**
 * Generate 16 bytes of random data, set the version to 0100 and the bits 6-7 to 10, and output the 36
 * character UUID
 *
 * @return UUID (Universally Unique Identifier)
 */
function uuid()
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * @param string|null $search
 * @return string
 */
function str_search(?string $search): string
{
    if (!$search) {
        return "all";
    }

    $search = preg_replace("/[^a-z0-9A-Z\@\ ]/", "", $search);
    return (!empty($search) ? $search : "all");
}

/**
 * @param string $email
 * @return string
 */
function str_obfuscate_email(string $email): string
{
    $em   = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len  = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

/**
 * @param array $array
 * @param string $key
 * @return array
 */
function arr_sort(&$array, $key)
{
    $sorter = [];
    $ret = [];
    $nulled = [];

    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $key => $value) {
        if (!$value) {
            $nulled[] = $key;
            unset($sorter[$key]);
        }
    }

    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }

    foreach ($nulled as $ii) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(?string $path = null): ?string
{
    if ($path) {
        return $_ENV['APP_URL'] . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return $_ENV['APP_URL'];
}

/**
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url = '/'): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

/**
 * @return \Source\Models\User|null
 */
function user(): ?\App\Models\User
{
    return \App\Models\Auth::user();
}

/**
 * @return \Source\Core\Session
 */
function session(): \Core\Session
{
    return new \Core\Session();
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(?string $image, int $width, ?int $height = null): ?string
{
    if ($image) {
        if (strpos($image, 'storage/blog/') !== false) {
            $file = explode('storage/blog/', $image)[1];
            return image('blog/' . $file, $width, $height);
        }
        if (strpos($image, '.gif')) {
            return url() . "/storage?path=images/" . $image;
        }
        return url() . "/storage?path=" . (new \Core\Utils\Thumb())->make($image, $width, $height);
    }

    return null;
}

/**
 * @param string|null $photo
 * @param integer|null $width
 * @param integer|null $height
 * @return string
 */
function image_avatar(?string $photo, ?int $width, ?int $height = null): string
{
    if (strpos($photo, 'storage/avatar/') !== false) {
        $file = explode('storage/avatar/', $photo)[1];
        return image('avatar/' . $file, $width, $height);
    }

    if (strpos($photo, 'avatars/') !== false) {
        return url('assets/media/' . $photo);
    }

    return url('assets/media/avatars/blank.png');
}

/**
 * @param int|string $id
 * @param string $db
 * @param boolean $equip
 * @return string|null
 */
function image_item(int|string $id, string $db, bool $equip = false, ?string $res = null): ?string
{
    if (empty($id) || is_null($id)) {
        return null;
    }

    $specialList = [
        10001 => '2x',
        10002 => '1x',
        10003 => 'x3',
        10004 => '50',
        10005 => '40',
        10006 => '30',
        10007 => '20',
        10008 => '10',
        10009 => 'life300',
        10010 => 'hidden',
        10011 => 'hiddenTeam',
        10012 => 'life500',
        10013 => 'changeWind',
        10015 => 'ice',
        10016 => 'fly',
        10017 => 'missile',
        10018 => 'anger',
        10020 => 'piercing',
        10021 => 'avoidHole',
        10022 => 'atomic',
        10024 => '10024',
        10025 => 'fly',
    ];

    if (array_key_exists($id, $specialList)) {
        return url('assets/media/game/battle/' . $specialList[$id] . '.png');
    }

    $model = (new App\Models\ShopGoods($db));
    $data = $model->where('TemplateID', $id)->first();
    if (!$data) {
        return null;
    }

    $sex = $data->NeedSex;
    $cat = (int) $data->CategoryID;
    $pic = $data->Pic;

    $resUrl = $res ?? $_ENV['APP_RESOURCE'];

    $sexChang = match ($sex) {
        '1' => 'm',
        '2' => 'f',
        default => 'f',
    };

    if ($equip) {
        $img = match ($cat) {
            1 => "equip/$sexChang/head/$pic/1/show.png",
            3 => "equip/$sexChang/hair/$pic/1/b/show.png",
            4 => "equip/$sexChang/eff/$pic/1/show.png",
            5 => "equip/$sexChang/cloth/$pic/1/show.png",
            6 => "equip/$sexChang/face/$pic/1/show.png",
            7 => "arm/$pic/1/0/show.png",
            13 => "equip/$sexChang/suits/$pic/1/show.png",
            default => false,
        };
    }

    if (!$equip) {
        $img = match ($cat) {
            1 => "equip/$sexChang/head/$pic/icon_1.png",
            2 => "equip/$sexChang/glass/$pic/icon_1.png",
            3 => "equip/$sexChang/hair/$pic/icon_1.png",
            4 => "equip/$sexChang/eff/$pic/icon_1.png",
            5 => "equip/$sexChang/cloth/$pic/icon_1.png",
            6 => "equip/$sexChang/face/$pic/icon_1.png",
            7 => "arm/$pic/00.png",
            8, 28 => "equip/armlet/$pic/icon.png",
            9, 29 => "equip/ring/$pic/icon.png",
            11 => "unfrightprop/$pic/icon.png",
            12 => "task/$pic/icon.png",
            13 => "equip/$sexChang/suits/$pic/icon_1.png",
            14 => "equip/necklace/$pic/icon.png",
            15 => "equip/wing/$pic/icon.png",
            16 => "specialprop/chatBall/$pic/icon.png",
            17 => "equip/offhand/$pic/icon.png",
            18 => "cardbox/$pic/icon.png",
            19 => "equip/recover/$pic/icon.png",
            20 => "unfrightprop/$pic/icon.png",
            23 => "unfrightprop/$pic/icon.png",
            25 => "gift/$pic/icon.png",
            26 => "card/$pic/icon.jpg",
            27 => "arm/$pic/00.png",
            30 => "unfrightprop/$pic/icon.png",
            31 => "equip/offhand/$pic/icon.png",
            32 => "farm/Crops/$pic/seed.png",
            33 => "farm/fertilizer/$pic/icon.png",
            34 => "unfrightprop/$pic/icon.png",
            35 => "unfrightprop/$pic/icon.png",
            36 => "unfrightprop/$pic/icon.png",
            40,180 => "unfrightprop/$pic/icon.png",
            50 => "petequip/arm/$pic/icon.png",
            51 => "petequip/hat/$pic/icon.png",
            52 => "petequip/cloth/$pic/icon.png",
            64 => "arm/$pic/00.png",
            70 => "equip/amulet/$pic/1/icon.png",
            63, 73 => "prop/$pic/icon.png",
            66 => "cardbox/$pic/icon.png",
            74 => "rune/$pic.png",
            87 => "farm/prop/$pic.png",
            24, 37, 60, 61, 53, 78, 79, 71, 23, 30, 40, 39, 84, 85, 86, 89, 88, 65, 68, 69, 72, 43, 62, 83, 82 => "unfrightprop/$pic/icon.png",
            default => false,
        };
    }

    if (!$img) {
        return url('assets/media/icons/original.png');
    }

    return "$resUrl/image/$img";
}

function image_equipment(string $style, string $db, string $sex = 'm')
{
    $ids = explode(',', $style);
    $ids = array_map(fn ($id) => explode('|', $id), $ids);
    $ids = array_map(fn ($id) => $id[0], $ids);

    $equip = [
        'head' => $ids[0],
        'glass' => $ids[1],
        'hair' => $ids[2],
        'eff' => $ids[3],
        'cloth' => $ids[4],
        'face' => $ids[5],
        'arm' => $ids[6],
        'suit' => $ids[7],
    ];

    $template = [
        "head"  => "equip/{sexChang}/head/{pic}/1/show.png",
        "glass" => "equip/{sexChang}/glass/{pic}/1/show.png",
        "hair"  => "equip/{sexChang}/hair/{pic}/1/b/show.png",
        "eff"   => "equip/{sexChang}/eff/{pic}/1/show.png",
        "cloth" => "equip/{sexChang}/cloth/{pic}/1/show.png",
        "face"  => "equip/{sexChang}/face/{pic}/1/show.png",
        "arm"   => "arm/{pic}/1/0/show.png",
        "suit"  => "equip/{sexChang}/suits/{pic}/1/show.png",
    ];

    $equipList = [];
    foreach ($equip as $type => $id) {
        $item = (new App\Models\ShopGoods())
            ->setTable($db . '.dbo.Shop_Goods')
            ->where('TemplateID', $id)
            ->first();

        $pic = $item?->Pic ?? ($type == 'arm' ? 'axe' : 'default');

        $sexChang = match ($item?->NeedSex) {
            '1' => 'm',
            '2' => 'f',
            default => $sex,
        };

        $image = str_replace(
            ['{sexChang}', '{pic}'],
            [$sexChang, $pic],
            $template[$type]
        );

        $equipList[$type] = $image;
    }

    $resUrl = $_ENV['APP_RESOURCE'];

    //add resource url to start of image path
    $equipList = array_map(fn ($image) => "$resUrl/image/$image", $equipList);

    return $equipList;
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(?string $date, string $format = "d/m/Y H\hi"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_br(?string $date): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format('d/m/Y H:i:s');
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_app(?string $date): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format('d/m/Y H:i:s');
}

/**
 * @param string|null $date
 * @return string|null
 */
function date_fmt_back(?string $date): ?string
{
    if (!$date) {
        return null;
    }

    if (strpos($date, " ")) {
        $date = explode(" ", $date);
        return implode("-", array_reverse(explode("/", $date[0]))) . " " . $date[1];
    }

    return implode("-", array_reverse(explode("/", $date)));
}

function date_fmt_ago($time)
{
    $diff = time() - strtotime($time);

    if ($diff < 1) {
        return 'menos de 1 segundo atrás';
    }

    $time_names = [
        'y' => ['ano', 'anos'],
        'm' => ['mês', 'meses'],
        'd' => ['dia', 'dias'],
        'h' => ['hr', 'hrs'],
        'min' => ['min', 'mins'],
        's' => ['seg', 'segs'],
    ];

    $time_rules = [
        12 * 30 * 24 * 60 * 60  => 'y',
        30 * 24 * 60 * 60       => 'm',
        24 * 60 * 60            => 'd',
        60 * 60                 => 'h',
        60                      => 'min',
        1                       => 's'
    ];

    foreach ($time_rules as $secs => $str) {
        $div = $diff / $secs;

        if ($div >= 1) {
            $t = round($div);
            return $t . ' ' . ($t > 1 ? $time_names[$str][1] : $time_names[$str][0]) . ' atrás';
        }
    }
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, PASSWORD_DEFAULT, ["cost" => 10]);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, PASSWORD_DEFAULT, ["cost" => 10]);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string|null
 */
function flash(): ?string
{
    $session = new \Core\Session();
    if ($flash = $session->flash()) {
        return $flash;
    }
    return null;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60): bool
{
    $session = new \Core\Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;
}

/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_repeat(string $field, string $value): bool
{
    $session = new \Core\Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }

    $session->set($field, $value);
    return false;
}

/**
 * ###################
 * ###   OTHERS    ###
 * ###################
 */

/**
 * @param  string|null  $view
 * @param  array  $data
 * @return string
 */
function view($view = null, $data = [], $merge = []): string
{
    $blade = new Core\View\View();
    $blade->init($merge);

    return $blade->render($view, $data);
}

/**
 * @param string $message
 * @param array $attempts
 * @return string
 */
function __($message, $attempts = []): string
{
    $locale = $_ENV['APP_LOCALE'];

    if ($message == '') {
        return false;
    }

    $structure = explode('.', $message);

    $lang = getLanguage($message, $locale);

    if (!$lang) {
        return false;
    }

    $message = $lang[$structure[sizeof($structure) - 1]] ?? null;

    if (!empty($attempts)) {
        foreach ($attempts as $key => $value) {
            $message = str_replace(":$key", $value, $message);
        }
    }

    return $message;
}

/**
 * @param string $locale
 * @param string $message
 * @return array
 */
function getLanguage(string $message, ?string $locale = ''): array
{
    if (!$locale) {
        $locale = $_ENV['APP_LOCALE'];
    }

    $structure = explode('.', $message);
    if (sizeof($structure) <= 1) {
        return false;
    }

    $path = __DIR__ . '/../resources/lang/' . $locale . '/';

    for ($x = 0; $x < sizeof($structure) - 2; $x++) {
        $path .= $structure[$x] . '/';
    }

    $path .= $structure[sizeof($structure) - 2] . '.php';
    if (!file_exists($path)) {
        return false;
    }

    return require $path;
}

/**
 * @param string $line
 * @param array $args
 * @param string|null $locale
 * @return void
 */
function lang(string $line, array $args = [], ?string $locale = null)
{
    //$locale != null ? $locale : $_ENV['APP_LOCALE']
    //return $line;
}

/**
 * @param mixed $var
 * @return mixed
 */
function dd($var): mixed
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit;
}

/**
 * @param integer $admin
 * @param integer|null $uid
 * @param string $content
 * @param string|null $route
 * @return void
 */
function log_system(?int $uid = null, string $content, ?string $route = null, string $type = 'generic'): void
{
    $log = new App\Models\Log();
    $log->uid = $uid;
    $log->content = $content;
    $log->route = $route;
    //$log->type = $type;

    if (!$log->save()) {
        dd('erro pra salvar log');
    }

    return;
}

function log_webhook($message)
{
    try {
        $webhookUrl = 'https://discord.com/api/webhooks/1386867084188979272/BEOv68oV8bA6mDS5-fDiQb8sIBvaCwLxrQtYp_BhYnHjpFQpVK0wMSXFqIMRsP_9zl0C';

        $payload = json_encode(['content' => $message]);

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('cURL error: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new Exception('Discord webhook retornou HTTP ' . $httpCode . ': ' . $response);
        }

        curl_close($ch);
    } catch (Exception $e) {
        echo "Erro ao enviar webhook: " . $e->getMessage();
    }
}

/**
 * ################
 * ###   HTML   ###
 * ################
 */

function getSvgIcon($path, $iconClass = "", $svgClass = "")
{
    $path = 'assets/media/icons/duotune/' . $path;

    if (!file_exists($path)) {
        return "<!-- SVG file not found: " . $path . " -->";
    }

    $svg_content = file_get_contents($path);

    $dom = new DOMDocument();
    $dom->loadXML($svg_content);

    // remove unwanted comments
    $xpath = new DOMXPath($dom);
    foreach ($xpath->query("//comment()") as $comment) {
        $comment->parentNode->removeChild($comment);
    }

    // add class to svg
    if (!empty($svgClass)) {
        foreach ($dom->getElementsByTagName("svg") as $element) {
            $element->setAttribute("class", $svgClass);
        }
    }

    // remove unwanted tags
    $title = $dom->getElementsByTagName("title");
    if ($title["length"]) {
        $dom->documentElement->removeChild($title[0]);
    }

    $desc = $dom->getElementsByTagName("desc");
    if ($desc["length"]) {
        $dom->documentElement->removeChild($desc[0]);
    }

    $defs = $dom->getElementsByTagName("defs");
    if ($defs["length"]) {
        $dom->documentElement->removeChild($defs[0]);
    }

    // remove unwanted id attribute in g tag
    $g =  $dom->getElementsByTagName("g");
    foreach ($g as $el) {
        $el->removeAttribute("id");
    }

    $mask =  $dom->getElementsByTagName("mask");
    foreach ($mask as $el) {
        $el->removeAttribute("id");
    }

    $rect =  $dom->getElementsByTagName("rect");
    foreach ($rect as $el) {
        $el->removeAttribute("id");
    }

    $path =  $dom->getElementsByTagName("path");
    foreach ($path as $el) {
        $el->removeAttribute("id");
    }

    $circle =  $dom->getElementsByTagName("circle");
    foreach ($circle as $el) {
        $el->removeAttribute("id");
    }

    $use =  $dom->getElementsByTagName("use");
    foreach ($use as $el) {
        $el->removeAttribute("id");
    }

    $polygon =  $dom->getElementsByTagName("polygon");
    foreach ($polygon as $el) {
        $el->removeAttribute("id");
    }

    $ellipse =  $dom->getElementsByTagName("ellipse");
    foreach ($ellipse as $el) {
        $el->removeAttribute("id");
    }

    $string = $dom->saveXML($dom->documentElement);

    // remove empty lines
    $string = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);

    $cls = array("svg-icon");

    if (!empty($iconClass)) {
        $cls = array_merge($cls, explode(" ", $iconClass));
    }
    return "<span class=\" " . implode(" ", $cls) . "\">{$string}</span>";
}

function is_mobile()
{
    return preg_match(
        "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
        $_SERVER["HTTP_USER_AGENT"]
    );
}

function builder_form_version(array $list)
{
    if (blank($list)) {
        return;
    }

    $html = '';
    foreach ($list as $key => $version) {
        $html .= "<div id=\"version_{$key}\">";
        $html_master = '';
        foreach ($version as $master) {
            $html_master .= "<div " .
                ($master['class'] ? "class=\"{$master['class']}\"" : '') .
                ($master['id'] ? "id=\"{$master['id']}\"" : '') . " >";

            $html_child = '';

            foreach ($master['childs'] as $child) {
                $field = explode(':', $child['type']);
                $html_child .= "<div " . ($master['class_child'] ?? false ? "class=\"{$master['class_child']}\"" : '') . ">";
                $html_child .= $child['label'] ?? false ? "<label class=\"fs-6 form-label mb-2\">{$child['label']}</label>" : '';

                if ($field[0] == 'hidden') {
                    $html_child .= "<input " .
                        ($child['type'] ?? false ? "type=\"hidden\"" : '') .
                        ($child['name'] ?? false ? "name=\"{$child['name']}\"" : '') .
                        ($child['default'] ?? false ? "value=\"{$child['default']}\"" : '') . " />";
                }

                if ($field[0] == 'input') {
                    $html_child .= "<input " .
                        ($child['type'] ?? false ? "type=\"{$field[1]}\"" : '') .
                        ($child['name'] ?? false ? "name=\"{$child['name']}\"" : '') .
                        ($child['class'] ?? false ? "class=\"{$child['class']}\"" : '') .
                        ($child['id'] ?? false ? "id=\"{$child['id']}\"" : '') . " />";
                }

                if ($field[0] == 'select') {
                    $html_child .= "<select " .
                        ($child['name'] ?? false ? "name=\"{$child['name']}\"" : '') .
                        ($child['class'] ?? false ? "class=\"{$child['class']}\"" : '') .
                        ($child['attributes'] ?? false ? $child['attributes'] : '') .
                        ($child['id'] ?? false ? "id=\"{$child['id']}\"" : '') . " />";
                    $html_child .= "</select>";
                }

                if ($field[0] == 'textarea') {
                    $html_child .= "<textarea " .
                        ($child['name'] ?? false ? "name=\"{$child['name']}\"" : '') .
                        ($child['class'] ?? false ? "class=\"{$child['class']}\"" : '') .
                        ($child['attributes'] ?? false ? $child['attributes'] : '') .
                        ($child['id'] ?? false ? "id=\"{$child['id']}\"" : '') . " />";
                    $html_child .= "</textarea>";
                }
                $html_child .= "</div>";
            }
            $html_master .= $html_child . "</div>";
        }

        $html .= $html_master . "</div>";
    }

    return $html;
}

function get_client_ip()
{
    return $_SERVER['HTTP_CLIENT_IP']
    ?? $_SERVER["HTTP_CF_CONNECTING_IP"]
    ?? $_SERVER['HTTP_X_FORWARDED']
    ?? $_SERVER['HTTP_X_FORWARDED_FOR']
    ?? $_SERVER['HTTP_FORWARDED']
    ?? $_SERVER['HTTP_FORWARDED_FOR']
    ?? $_SERVER['REMOTE_ADDR']
    ?? '0.0.0.0';
}
