<?php
namespace LaneWeChat\Core;
include 'lanewechat.php';
/**
 * 扫码关注获取客户关系
 * User: lane
 * Date: 14-10-31
 * Time: 下午4:15
 * E-mail: 
 * WebSite: 
 */
class Scanin {
    /*****************************
     * 在扫码关注事件中取得二维码带的场景值$dataNO
     *
     * @param $type char $sceneid
     * @param $type char $oenpid
     * 
     * @return $dataNO char
     */
    public static function getSceneid($sceneid){
            $dataNO = ltrim($sceneid,"qrscene_");
            return $dataNO;

    }
    

     /*****************************
     * 创建扫码关注用户和场景值dataNO间的一一对应关系,并写入表名为noXXX的表中
     *
     * @param $type char $sceneid
     * @param $type char $oenpid
     * 
     * @return int 或者 array
     */
    public static function dealwithTable($sceneid,$openid){
            //连接数据库
            $dsn = PDO_DSN;//使用config.php 配置赋值 
            $user = PDO_USER;
            $pass = PDO_PASS;

            try{
                //用建立一个新PDO类的方法连接数据库
                $dbh = new \PDO($dsn,$user,$pass);
                //echo 'ok!数据库连接成功<br>';测试语句
            }catch(PDOException $e){
                    //echo '数据库连接失败:<br>'.$e->getMessage();测试语句
                exit;
            }
            
            $dataNO = ltrim($sceneid,"qrscene_");//why self::getSceneid($secneid)不行？奇怪
            $openid = $openid;
            $time =time();
            $tablename = 'no'.$dataNO;
                //var_dump($tablename);测试表名使用
            $query4 = "INSERT INTO {$tablename} (open_ID,followtime) VALUES ('{$openid}','{$time}')";
            $stmt = $dbh->exec($query4);
            if($stmt){
                //echo 'ok!数据写入成功<br>';测试时打开
                $e = $stmt;//返回受影响的行数
                return $e;
            }else{
                //echo '数据写入失败<br>';测试时打开
                $e = $dbh->errorInfo();//获得错误信息
                return $e;

            }


    }

     /*****************************
     * 统计功能
     *
     * @param $type char $oenpid
     * @param $type int  $filter
     * 
     * @return bool
     */
    public static function queryUsers($openid,$filter){


    }
    


}    
            