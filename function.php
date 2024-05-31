<?php
if (file_exists('config.php')) {
	require_once('config.php');
} else if (file_exists('../config.php')) {
	require_once('../config.php');
} else if (file_exists('../../config.php')) {
	require_once('../../config.php');
} else {
	require_once('../../../config.php');
}
session_start();
//session_destroy();

try {
	$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
} catch( PDOException $Exception ) {
	echo 'Error on connection.';
}
$tiempo = date('Y-m-d h:i:s');
$user_ip = getUserIp();

function getUserIp() {

    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
} 

function viewCountry($ip) {
    $country_api = json_decode(@file_get_contents('https://api.country.is/'. $ip));
    
    if ($country_api && isset($country_api->country)) {
        $country_dec = $country_api->country;
        
        $variable = array("A", "S", "D", "F", "G", "H", "J", "K", "L", "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "Z", "X", "C", "V", "B", "N", "M");
        $str_variable = array("a", "s", "d", "f", "g", "h", "j", "k", "l", "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "z", "x", "c", "v", "b", "n", "m");
        $country = str_replace($variable, $str_variable, $country_dec);
        
        return $country;
    } else {
        return "us";
    }
}


function langList() {
	$lang_dir = __DIR__ . '/languages/';

	$files_lang = glob($lang_dir . '*.php');

	$lang_list = [];

	foreach ($files_lang as $file) {
		include $file;
		
		if (isset($messages)) {
			$lang_list[] = [
				'data' => basename($file, '.php'),
				'name' => $messages['location']['name'],
				'flag' => $messages['location']['flag'],
				'display' => $messages['display']
			];
		}
	}
	return $lang_list;
}
if (!isset($_SESSION['lang']) AND !isset($_COOKIE['lang'])) {
    $default_lang_set = false;
    
    foreach (langList() as $list_lang) {
        if (in_array(viewCountry($user_ip), $list_lang['display'])) {
            $_SESSION['lang'] = $list_lang['data'];
			$lang_name = $list_lang['name'];
            setcookie('lang', $list_lang['data'], time() + (86400 * 30), '/');
            $default_lang_set = true;
            break;
        }
    }
    
    if (!$default_lang_set) {
        $_SESSION['lang'] = DEFAULT_LANG;
        setcookie('lang', DEFAULT_LANG, time() + (86400 * 30), '/');
    }
}

if (isset($_SESSION['lang']) AND !isset($_COOKIE['lang'])) {
	setcookie('lang', $_SESSION['lang'], time() + (86400 * 30), '/');
} else if (!isset($_SESSION['lang']) AND isset($_COOKIE['lang'])) {
	$_SESSION['lang'] = $_COOKIE['lang'];
}

$lang = $_SESSION['lang'];
if (file_exists(__DIR__.'/languages/')) {
	if (!file_exists(__DIR__.'/languages/' . $lang . '.php')) $lang = DEFAULT_LANG;
	require_once __DIR__.'/languages/' . $lang . '.php';
} else {
	if (!file_exists(__DIR__.'/../languages/' . $lang . '.php')) $lang = DEFAULT_LANG;
	require_once __DIR__.'/../languages/' . $lang . '.php';
}


function lang($lang, $page, $section, $sec2 = '', $sec3 = '', $sec4 = '', $sec5 = '', $sec6 = '', $sec7 = '', $sec8 = '', $sec9 = '') {
    require_once('config.php');
	$msg = 'Not found message.';
	$lang = $lang[$page][$section];
    if (!isset($lang) AND empty($sec2) AND empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2]) AND !empty($sec2) AND empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3]) AND !empty($sec2) AND !empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4][$sec5]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4][$sec5][$sec6]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7][$sec8]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND !empty($sec8) AND empty($sec9)) return $msg;
    if (!isset($lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7][$sec8][$sec9]) AND !empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND !empty($sec8) AND !empty($sec9)) return $msg;

    if (empty($sec2) AND empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang;
    if (!empty($sec2) AND empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2];
    if (!empty($sec2) AND !empty($sec3) AND empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3][$sec4];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3][$sec4][$sec5];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3][$sec4][$sec5][$sec6];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND !empty($sec8) AND empty($sec9)) $var = $lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7][$sec8];
    if (!empty($sec2) AND !empty($sec3) AND !empty($sec4) AND !empty($sec5) AND !empty($sec6) AND !empty($sec7) AND !empty($sec8) AND !empty($sec9)) $var = $lang[$sec2][$sec3][$sec4][$sec5][$sec6][$sec7][$sec8][$sec9];
    return $var;
}


function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function clearText($text, $quant) {
    $palabras = explode(' ', $text);
    
    if (count($palabras) <= $quant) {
        return $text;
    }
    
    $texta = implode(' ', array_slice($palabras, 0, $quant)) . '...';
    
    return $texta;
}
function table($sql, $text, $type = '', $info = '') {
    require_once('config.php');
    $connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
	
	
    if (!empty($info)) $searchSQL = $connx->prepare("SELECT * FROM `$sql` WHERE `$type` = ?");
    if (empty($info)) $searchSQL = $connx->prepare("SELECT * FROM `$sql`");
    if (!empty($info)) $searchSQL->execute([$info]);
    if (empty($info)) $searchSQL->execute();

    $result = $searchSQL->fetch(PDO::FETCH_ASSOC);
    
    return $result[$text];
}
function discount($descuento, $precio) {
	$montoDescuento = ($descuento / 100) * $precio;
	$precioConDescuento = $precio - $montoDescuento;
	return $precioConDescuento;
}
function acortarNombre($nombre) {
    // Divide el nombre en palabras
    $palabras = explode(' ', $nombre);
    
    // Obtiene la primera palabra
    $primeraPalabra = $palabras[0];
    
    // Verifica si hay más de una palabra en el nombre
    if (count($palabras) > 1) {
        // Obtiene la primera letra de cada palabra excepto la primera
        $iniciales = array_map(function($palabra) {
            return $palabra[0];
        }, array_slice($palabras, 1));
        
        // Concatena las iniciales
        $inicialesTexto = implode('', $iniciales);
        
        return $primeraPalabra . ' ' . $inicialesTexto;
    } else {
        // Si solo hay una palabra en el nombre, devuelve el nombre original
        return $nombre;
    }
}
function userInfo($user_id, $info) {
	require_once 'config.php';
	$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
	$usuario = $connx->prepare("SELECT * FROM `usuarios` WHERE `id` = ?");
	$usuario->bindParam(1, $user_id);
	$usuario->execute();
	$users = $usuario->fetch(PDO::FETCH_ASSOC);
	$userinfo = $users[$info];
	return $userinfo;
}
function simplyText($text) {
    $text = strtoupper(preg_replace('/[^A-Za-z0-9\s]/', '', $text));
    
    $palabras = explode(' ', $text);
    
    $iniciales = '';

    foreach ($palabras as $palabra) {
        $iniciales .= substr($palabra, 0, 1);

        if (strlen($iniciales) >= 3) {
            break;
        }
    }

    return $iniciales;
}



function counttime($date, $dates = 'datetime') {
    global $messages;
	
	if ($dates == 'datetime') {
		$timestamp = strtotime($date);
	} else {
		$timestamp = $date;
	}
	
	$strTime=array(lang($messages, 'counttime', 'second'), 
	lang($messages, 'counttime', 'minute'), 
	lang($messages, 'counttime', 'hour'), 
	lang($messages, 'counttime', 'day'), 
	lang($messages, 'counttime', 'month'), 
	lang($messages, 'counttime', 'year'));
	
	$strTimes=array(lang($messages, 'counttime', 'seconds'), 
	lang($messages, 'counttime', 'minutes'), 
	lang($messages, 'counttime', 'hours'), 
	lang($messages, 'counttime', 'days'), 
	lang($messages, 'counttime', 'months'), 
	lang($messages, 'counttime', 'years'));
	
	$length=array("60","60","24","30","12","10");
	$currentTime=time();
	if($currentTime >= $timestamp) { 
		$diff = time()- $timestamp; 
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) { 
			$diff = $diff / $length[$i]; 
		} 
		
		$diff = round($diff); 
		if ($diff > 1) { 
			$timeName = $strTimes[$i]; 
		} else { 
			$timeName = $strTime[$i]; 
		} 
		
		$type_lang = lang($messages, 'counttime', 'ago-type');
		if ($type_lang == 1) {
			return lang($messages, 'counttime', 'ago') . " ".$diff. " " .$timeName;
		} else if ($type_lang == 2) {
			return $diff." ".$timeName . " " . lang($messages, 'counttime', 'ago');
		}
	}
}
function counttimedown($timing, $msg, $date = 'time') {
    global $messages;
	
	if ($date == 'time') {
		$info = date('Y-m-d H:i:s', $timing);
	} else {
		$info = $timing;
	}
	
	$end_time = new DateTime($info);
	$current_time = new DateTime();
	$interval = $current_time->diff($end_time);
	
	$textand = lang($messages, 'counttime', 'separator');

	if ($interval->format("%a") == '0') {
		$timers = $interval->format("%h h, %i m " . $textand . " %s s.");
	} else if ($interval->format("%h") == '0') {
		$timers = $interval->format("%i m " . $textand . " %s s.");
	} else if ($interval->format("%i") == '0') {
		$timers = $interval->format("%s s.");
	} else {
		$timers = $interval->format("%a d, %h h, %i m " . $textand . " %s s.");
	}
	
	if ($interval->invert) {
		$text = $msg;
	} else {
		$text = $timers;
	}
	
	return $text;
}


function countSince($date, $year = '') {
    global $messages;
	$monthNames = array(
		lang($messages, 'months', 'low', 'jan'),
		lang($messages, 'months', 'low', 'feb'),
		lang($messages, 'months', 'low', 'mar'),
		lang($messages, 'months', 'low', 'apr'),
		lang($messages, 'months', 'low', 'may'),
		lang($messages, 'months', 'low', 'jun'),
		lang($messages, 'months', 'low', 'jul'),
		lang($messages, 'months', 'low', 'aug'),
		lang($messages, 'months', 'low', 'sep'),
		lang($messages, 'months', 'low', 'oct'),
		lang($messages, 'months', 'low', 'nov'),
		lang($messages, 'months', 'low', 'dec'),
	);
	
    $timestamp = strtotime($date);
    if (empty($year)) $formattedDate = date('M j, Y', $timestamp);
    if (!empty($year)) $formattedDate = date('M j', $timestamp);
    $monthIndex = date('n', $timestamp) - 1;
    $formattedDate = str_replace(date('M', $timestamp), $monthNames[$monthIndex], $formattedDate);
    return $formattedDate;
}

function linkSimplyText($text) {
    $text = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $text));
    $text = preg_replace('/-+/', '-', $text);
    return $text;
}


function randomCodes($length = 10, $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $characters = $chars;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function customChar($length = 10, $chart = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $characters = $chart;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function paginationButtons($TotalRegistro, $compag, $total, $action = 'updatePage') {
    $IncrimentNum = (($compag + 1) <= $TotalRegistro) ? ($compag + 1) : 1;
    $DecrementNum = (($compag - 1)) < 1 ? 1 : ($compag - 1);

    if (empty($action)) $action = 'updatePage'; else $action = $action;
	$prevClass = ($compag == 1 || $TotalRegistro < $total) ? 'disabled' : '';
	$nextClass = ($compag == $TotalRegistro || $TotalRegistro < $total) ? 'disabled' : '';
	echo '<li class="page-item ' . $prevClass . '">';
	echo '<a class="page-link" href="#" onclick="' . $action . '(\'' . $DecrementNum . '\'); event.preventDefault();">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>';
	echo '</a>';
	echo '</li>';

    $Desde = max(1, $compag - floor($total / 2));
    $Hasta = min($TotalRegistro, $Desde + $total - 1);

    $maxButtons = 10;
    $halfMaxButtons = $maxButtons / 2;

    if ($TotalRegistro > $maxButtons) {
        $Offset = $compag - $halfMaxButtons;
        $Offset = max(1, $Offset);
        $Offset = min($Offset, $TotalRegistro - $maxButtons + 1);

        $Desde = $Offset;
        $Hasta = min($TotalRegistro, $Offset + $maxButtons - 1);
    }

    for ($i = $Desde; $i <= $Hasta; $i++) {
        $activeClass = ($i == $compag) ? 'active' : '';
		echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="#" onclick="' . $action . '(\'' . $i . '\'); event.preventDefault();">' . $i . '</a></li>';
    }

    $NextSet = min($TotalRegistro, $Hasta + 1 + $total);
	$nextPosition = $compag + 1;
    if ($NextSet <= $TotalRegistro) {
		
		echo '<li class="page-item ' . $nextClass . '">
				<a class="page-link" href="#" onclick="' . $action . '(\'' . $nextPosition . '\'); event.preventDefault();">
					
					<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
				</a>
			</li>';
	}
}
function whileList($sql, $text, $opt = '', $error = 'Unknown') {
    require_once('config.php');
    global $connx;
    if (!empty($opt)) $sopt = 'ORDER BY ' . $opt . ' DESC';
    $seasonList = $connx->prepare("SELECT * FROM `$sql`" . $sopt);
    $seasonList->execute();

    $seasonCount = $connx->prepare("SELECT COUNT(id) AS totals FROM `$sql`");
    $seasonCount->execute();
    $seasonc = $seasonCount->fetch(PDO::FETCH_ASSOC);

    $options = '';

    while ($season = $seasonList->fetch(PDO::FETCH_ASSOC)) {
        $rowData = '';
        foreach ($season as $column => $value) {
            $rowData .= ":$column: => $value, ";
        }

        $rowData = rtrim($rowData, ', ');

        $data = array_merge(['totals' => $seasonc['totals']], $season);

        $var = replaceVariables($text, $data);

        $options .= $var;
    }
    return $options ? $options : $error;
}

function replaceVariables($text, $data) {
    while (preg_match('/:(\w+):/', $text, $matches)) {
        $variableName = $matches[1];
        if (isset($data[$variableName])) {
            $text = str_replace(":$variableName:", $data[$variableName], $text);
        } else {
            break;
        }
    }
    return $text;
}
function truncateText($text, $length, $suffix = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        // Busca la última posición del espacio en blanco dentro del texto truncado
        $lastSpace = strrpos($text, ' ');
        if ($lastSpace !== false) {
            // Trunca el texto en el último espacio en blanco encontrado
            $text = substr($text, 0, $lastSpace);
        }
        // Agrega el sufijo
        $text .= $suffix;
    }
    return $text;
}


$page_not_found = '<div class="page page-center">
      <div class="container-tight py-4">
        <div class="empty">
          <div class="empty-header">404</div>
          <p class="empty-title">Oops… You just found an error page</p>
          <p class="empty-subtitle text-secondary">
            We are sorry but the page you are looking for was not found
          </p>
          <div class="empty-action">
            <a href="./." class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l14 0"></path><path d="M5 12l6 6"></path><path d="M5 12l6 -6"></path></svg>
              Take me home
            </a>
          </div>
        </div>
      </div>
    </div>';
?>
