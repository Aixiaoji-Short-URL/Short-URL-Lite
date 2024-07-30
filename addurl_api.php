<?php
header('Content-Type: application/json');

// 生成随机字符串
function generateRandomString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// 检查 DIY URL 是否重复
function isDuplicateDiyURL($diy_url, $existing_data) {
    foreach ($existing_data as $data) {
        if ($data['d'] === $diy_url) {
            return true;
        }
    }
    return false;
}

$original_url = isset($_GET['original_url']) ? $_GET['original_url'] : '';
$diy_url = generateRandomString(rand(5, 10)); // 始终生成随机的 DIY URL

if (empty($original_url)) {
    $response = array('code' => -100, 'msg' => 'Error: no original_url');
    echo json_encode($response);
} else {
    // 检查 URL 是否包含协议头
    if (!preg_match('/^(http|https):\/\//', $original_url)) {
        $response = array('code' => -103, 'msg' => 'Error: original_url missing protocol header (http:// or https://)');
        echo json_encode($response);
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // 加载现有数据
        $file_path = 'url.json';
        $file_content = file_exists($file_path) ? file_get_contents($file_path) : '[]';
        $existing_data = json_decode($file_content, true);

        // 检查 DIY URL 是否重复
        while (isDuplicateDiyURL($diy_url, $existing_data)) {
            $diy_url = generateRandomString(rand(5, 10)); // 重新生成DIY URL
        }

        // 构造 JSON 数据
        $json_data = array(
            'o' => $original_url,
            'd' => $diy_url,
            'ip' => $ip_address,
            'time' => time()
        );

        // 将数据追加到现有数据中
        $existing_data[] = $json_data;

        // 将 JSON 数据写入文件
        file_put_contents($file_path, json_encode($existing_data, JSON_PRETTY_PRINT));

        // 准备响应 JSON
        $response = array(
            'code' => 1,
            'msg' => 'OK !!!',
            'original_url' => $original_url,
            'diy_url' => $diy_url
        );

        echo json_encode($response);
    }
}
?>
