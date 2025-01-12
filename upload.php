<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    // 获取上传文件的信息
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmp = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];

    // 设置上传目录
    $uploadDir = 'uploads/';
    
    // 检查上传目录是否存在，不存在则创建
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // 生成一个唯一的文件名
    $newImageName = uniqid('', true) . '.' . pathinfo($imageName, PATHINFO_EXTENSION);

    // 设置文件保存路径
    $uploadPath = $uploadDir . $newImageName;

    // 处理上传
    if ($imageError === 0) {
        if ($imageSize < 5000000) {  // 限制上传文件大小为 5MB
            // 移动文件到目标目录
            if (move_uploaded_file($imageTmp, $uploadPath)) {
                echo "<h2>上传成功！</h2>";
                echo "<p>图片已上传，点击以下链接查看：</p>";
                echo "<div class='image-link'><a href='https://r2.110902.xyz/" . $uploadPath . "' target='_blank'>查看图片</a></div>";
            } else {
                echo "<h2>上传失败！</h2>";
            }
        } else {
            echo "<h2>文件过大！</h2><p>最大允许文件大小为 5MB。</p>";
        }
    } else {
        echo "<h2>上传错误！</h2><p>错误代码：{$imageError}</p>";
    }
} else {
    echo "<h2>请上传图片！</h2>";
}
?>
