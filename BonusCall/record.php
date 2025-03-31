<?php
// 设置时区为北京时间
date_default_timezone_set('Asia/Shanghai');

// 获取参数
$name = $_POST['name'] ?? '';
$id = $_POST['id'] ?? '';
$color = $_POST['color'] ?? '';
$course = $_POST['course'] ?? '1';

// 验证参数
if (empty($name) || empty($id) || !in_array($color, ['red', 'green']) || !in_array($course, ['1', '2'])) {
    http_response_code(400);
    die("参数错误");
}

// 记录数据准备
$timestamp = date('Y-m-d H:i:s');
$status = ($color === 'red') ? '缺席' : '出席';  // 修改状态显示文字

// 统一文件名（按课程区分）
$csvFilename = "records/attendance_{$course}.csv";  // 修改为统一文件名

try {
    // 自动创建目录（如果不存在）
    if (!is_dir('records')) {
        mkdir('records', 0755, true);
    }

    // 如果CSV文件不存在，先写入UTF-8 BOM头和标题行
    if (!file_exists($csvFilename)) {
        $header = "\xEF\xBB\xBF学生姓名,学号,状态,记录时间\n"; // UTF-8 BOM
        file_put_contents($csvFilename, $header);
    }
    
    // 准备新记录行（自动处理特殊字符）
    $record = sprintf(
        '%s,%s,%s,%s%s',
        escapeCsvValue($name),
        escapeCsvValue($id),
        escapeCsvValue($status),  // 使用修改后的状态文字
        escapeCsvValue($timestamp),
        PHP_EOL
    );
    
    // 追加记录到CSV文件
    file_put_contents($csvFilename, $record, FILE_APPEND | LOCK_EX);
    
    echo "记录成功";
    
} catch (Exception $e) {
    error_log("CSV记录失败: ".$e->getMessage());
    http_response_code(500);
    echo "记录失败";
}

// CSV值转义函数（处理包含逗号/引号/换行符的情况）
function escapeCsvValue($value) {
    if (preg_match('/[,"\n]/', $value)) {
        return '"' . str_replace('"', '""', $value) . '"';
    }
    return $value;
}
?>
