<?php

namespace David\OssUpload\Service;

use Hyperf\HttpMessage\Upload\UploadedFile;

interface FileUploadInterface
{
    //初始化配置
    public function __construct();

    /*
     *文件是否存在
     * @return bool
     */
    public function fileExists($path);

    /*
     * 根据文件的md5保存
     *@return string 保存文件的路径
    */
    public function store(?UploadedFile $file);

    /*
     * 域名 + 文件保存路径
     * @return string 返回文件的完整路径
    */
    public function url($path);

    /*
     * 删除
     * @return bool
    */
    public function delete($keys);

    /*
     * @param $filename 文件名
     * @param $folder
     * @param $content 要写入的字符串
     * @param $ext 后缀
	 * @return 路径
     */
    public function put($filename, $folder, $content, $ext = 'jpg');

}