<?php
include("../Configs/Main/WebMainConfig.php");
$UseLanguage = "..".$ConfigMain["Language"];
include($UseLanguage);
?>

<?php
header('Content-Type: text/html; charset=utf-8');

// 读取JSON文件
// Read JSON file
function readJSON($file_path) {
    if (!file_exists($file_path)) {
        return array(); // 返回空数组如果文件不存在
    }
    $json_content = file_get_contents($file_path);
    $data = json_decode($json_content, true);
    return $data;
}

// 重定向到原始URL
// Redirect to original URL
function redirectToOriginalURL($original_url) {
    header("Location: {$original_url}");
    exit(); // 确保脚本停止执行
}

// 显示错误信息
// Display error message
function displayError($message) {
    echo "<p style='color: red;'>{$message}</p>";
}

$i = isset($_GET['i']) ? $_GET['i'] : '';

if (empty($i)) {
    displayError('Error: Parameter "i" is not specified');
} else {
    $cans_data = readJSON('../url.json');

    $matched = false;
    foreach ($cans_data as $row) {
        if ($row['d'] == $i) { // 使用 'd' 作为 DIY URL 的键
            $matched = true;
            redirectToOriginalURL($row['o']); // 使用 'o' 作为 original_url 的键
            break;
        }
    }

    if (!$matched) {
        displayError('404: The specified URL does not exist');
    }
}
?>
