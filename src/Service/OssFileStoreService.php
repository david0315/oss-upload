<?php
declare(strict_types=1);

namespace David\OssUpload\Service;

use Hyperf\HttpMessage\Upload\UploadedFile;
use OSS\OssClient;
use OSS\Core\OssException;

class OssFileStoreService implements FileUploadInterface
{
    private $config;
    /**
     * @var OssClient
     */
    private $client = null;
    public function __construct()
    {
        $this->config = config('filestore.aliyun');
        if( $this->client == null)  $this->initClient();
    }

    # 初始化
    public function initClient()
    {
        try {
            $this->client = new OssClient($this->config['access_id'], $this->config['access_key'], $this->config['endpoint']);
            $this->client->setTimeout( $this->config['socket_timeout']);
            $this->client->setConnectTimeout( $this->config['connection_timeout']);
        } catch (OssException $e) {
            throw new \RuntimeException('链接失败: '.$e->getMessage());
        }
    }

    public function fileExists($path)
    {
        return $this->getClient()->doesObjectExist($this->config['bucket'], $path) ? true : false;
    }

    public function store(?UploadedFile $file, ?string $folder = '' )
    {
        //作用：（1）判断一个对象是否是某个类的实例，（2）判断一个对象是否实现了某个接口。
        if(! $file instanceof  UploadedFile)  throw new \RuntimeException('文件必须是 Hyperf\HttpMessage\Upload\UploadedFile');

        $saveFilePath = $this->addPrefix($folder, $this->hashFileName($file));

        //检查文件是否存在
        if( $this->fileExists($saveFilePath)) return $saveFilePath;

        //上传
        try{
            $this->getClient()->uploadFile($this->config['bucket'], $saveFilePath, $file->getPathname());
            return $saveFilePath;//返回
        } catch(OssException $e) {
            return;
        }
    }

    public function url($path)
    {
//        return $this->getClient()->signUrl($this->config['bucket'],$path,3600);
        return $this->config['url'] . $path;
    }

    public function delete($keys)
    {
        //删除
        if(is_array($keys)){//如果是数组
            try{
                return $this->client->deleteObjects($this->config['bucket'], $keys);
            } catch(OssException $e) {
                return false;
            }
        }elseif(is_string($keys)){//如果是字符串
            try{
                return $this->client->deleteObject($this->config['bucket'], $keys);
            } catch(OssException $e) {
                return false;
            }
        }
        //默认返回
        return false;
    }

    public function put($filename, $folder, $content, $ext = 'jpg')
    {
        try{
            $uidMd = md5($content);
            $prefix = substr($uidMd, 0, 2);
            $path = "{$folder}/{$prefix}/$uidMd.$ext";

            $this->getClient()->putObject($this->config['bucket'],$this->config['save_path'] . $path, $content);
            return $this->config['save_path'] . $filename;
        } catch(\Exception  $e) {
            throw new \RuntimeException("put fail " . $e->getMessage());
        }
    }

    /**
     * @return OssClient|null
     */
    public function getClient():?OssClient
    {
        return $this->client;
    }

    //增加云存储文件路径
    protected function addPrefix($folder, $filename)
    {
        return  $this->config['save_path']. '/' .$folder. '/' .$filename;
    }

    // 根据文件的md5生成名称
    protected function hashFileName(UploadedFile $file)
    {
//        return md5_file($file->getPathname()).'.'.$file->getExtension();
        $md = md5_file($file->getPathname());
        $prefix = substr($md, 0, 2);
        return "{$prefix}/$md.{$file->getExtension()}";
    }

    //获取文件名+扩展名
    private function savePath(UploadedFile $file)
    {
        //md5_file 计算文件的 MD5 散列,作用是可检测是否已被更改
        $filename = md5_file($file->getPathname()).'.'.$file->getExtension();
        return  $this->config['save_path'].$filename;
    }


}