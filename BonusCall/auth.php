<?php
header('Content-Type: application/json');

// 1. 读取密码文件（路径需根据实际调整）
$password_file = './private/.hidden_password.txt';
$stored_password = trim(file_get_contents($password_file));

// 2. 获取用户输入
$input_password = $_POST['password'] ?? '';

// 3. 验证（安全比较防止时序攻击）
function secure_compare($a, $b) {
    return hash_equals($a, $b);
}

$is_valid = secure_compare($input_password, $stored_password);

// 4. 返回JSON响应
echo json_encode([
    'success' => $is_valid,
    'message' => $is_valid ? '验证成功' : '密码错误'
]);
?>
