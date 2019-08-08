<?php


namespace Lqx;


/**
 * 获取服务器cpu、网络、内存等使用信息
 * Class Sys
 * @package Lqx
 * 获取系统线程使用情况 top
 * 获取磁盘使用情况 dh
 * 获取
 */
class Sys
{
    public function test()
    {
        echo "welcome";
    }

    public function getSys()
    {

    }

    public function getCpu()
    {
        //获取CPU使用率以及内存使用率
        $fp = popen('top -b -n 2 | grep -E "(Cpu\(s\))|(KiB Mem)"', "r");
        /*说明： 获取两次信息，因为只获取一次数据不准确，但是造成返回缓慢，建议做成异步处理*/
        $rs = fread($fp, 1024);
        $sys_info = explode("\n", $rs);
        $cpu_info = explode(",", $sys_info[2]);
        $cpu_usage = trim(trim($cpu_info[0], '%Cpu(s): '), 'us'); //百分比

        $mem_info = explode(",", $sys_info[3]); //内存占有量 数组
        $mem_total = trim(trim($mem_info[0], 'KiB Mem : '), ' total');
        $mem_used = trim(trim($mem_info[2], 'used'));
        $mem_usage = round(100 * intval($mem_used) / intval($mem_total), 2); //百分比
    }

    /**
     *
     */
    public function getDisk()
    {
        //获取磁盘占用率
        $fp = popen('df -lh | grep -E "^(/)"', "r");
        $rs = fread($fp, 1024);
        pclose($fp);
        $rs = preg_replace('/\s{2,}/', ' ', $rs);  //把多个空格换成 “_”
        $hd = explode(" ", $rs);

        $hd_avail = trim($hd[3], 'G'); //磁盘可用空间大小 单位G
        $hd_usage = trim($hd[4], '%'); //挂载点 百分比
    }
}