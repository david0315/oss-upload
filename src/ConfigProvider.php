<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace David\OssUpload;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'publish' => [
                [
                    'id' => 'oss-upload',
                    'description' => 'hyperf-oss-upload',
                    'source' => __DIR__ . '/../publish/filestore.php',
                    'destination' => BASE_PATH . '/config/autoload/filestore.php',
                ],
            ],
        ];
    }
}
