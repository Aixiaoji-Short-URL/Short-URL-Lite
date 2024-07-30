<?php
include("./Configs/Main/WebMainConfig.php");
$UseLanguage = ".".$ConfigMain["Language"];
include($UseLanguage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $LanguageV1["title_index"]; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            color: #333;
        }
    </style>
</head>
<body>

<h1><?php echo htmlspecialchars($LanguageV1["h1_index"]); ?></h1>

<form method="POST" action="">
    <label for="original_url"><?php echo htmlspecialchars($LanguageV1["CC-original_url_index"]); ?></label>
    <input type="text" name="original_url" required>

    <input type="submit" value="<?php echo htmlspecialchars($LanguageV1["Create-submit_index"]); ?>">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 处理表单提交

    $original_url = isset($_POST["original_url"]) ? htmlspecialchars($_POST["original_url"]) : '';

    // 获取当前网站域名
    $current_domain = $_SERVER['HTTP_HOST'];

    // 确定协议
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

    // 构建API请求URL
    $api_url = $protocol . $current_domain . "/addurl_api.php";
    $api_url .= "?original_url=" . urlencode($original_url);

    // 使用cURL发送GET请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    
    // 检查cURL错误
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }

    curl_close($ch);

    // 解析JSON响应
    $result = json_decode($response, true);

    // 处理响应
    if (isset($result["code"]) && $result["code"] == 1) {
        echo $LanguageV1["Create-OK_index"] . "<p><a href='" . $protocol . $current_domain . "/i?i={$result["diy_url"]}'>" . $protocol . $current_domain . "/i?i={$result["diy_url"]}</a></p>";
    } else {
        echo $LanguageV1["Create-ERROR_index"] . "<p>" . htmlspecialchars($result["msg"] ?? 'Unknown error') . "</p>";
    }
}
?>

</body>
</html>
