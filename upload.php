<?php
// 手动引入 AWS SDK
require '/aws-autoloader.php'; // 根据实际路径修改

// 引入需要的 AWS 类
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// R2 配置
$bucketName = 'your-bucket-name';  // 替换为您的 R2 存储桶名称
$accessKey = 'your-access-key';    // 替换为您的 R2 访问密钥
$secretKey = 'your-secret-key';    // 替换为您的 R2 秘密密钥
$endpoint = 'https://pub-5622b6ddd38644f88f44859bd09a5e02.r2.dev'; // 替换为您的 R2 终端节点

// 设置 S3 客户端
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'auto',  // R2 使用 'auto'
    'endpoint' => $endpoint,
    'credentials' => [
        'key'    => $accessKey,
        'secret' => $secretKey,
    ],
]);

// 处理文件上传
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = uniqid('image_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $filePath = $file['tmp_name'];

    try {
        // 上传文件到 R2 存储桶
        $result = $s3Client->putObject([
            'Bucket' => $bucketName,
            'Key'    => $fileName,
            'SourceFile' => $filePath,
            'ACL'    => 'public-read',  // 设置文件为公开可读
        ]);

        // 返回文件的公开 URL
        $fileUrl = $result['ObjectURL'];

        echo json_encode(['url' => $fileUrl]);
    } catch (AwsException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
