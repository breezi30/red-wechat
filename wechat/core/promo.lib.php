<?php
namespace LaneWeChat\Core;
include 'lanewechat.php';
/**
 * 推广分享
 * User: lane
 * Date: 14-10-31
 * Time: 下午4:15
 * E-mail: 
 * WebSite: 
 */
class Promo {
    /*****************************
     * 点击菜单生成带参数的二维码，参数值为openid。
     *
     * @param $type char $openid
     * 
     * @return ????????
     */
    public static function createQrcode($openid){
            
            //1.根据openid到数据库qrcode中查询是否已有可用的临时二维码，查询函数为checkQrcode(),
            $check = self::checkQrcode($openid);
            if ($check[3]) {
            //url有效，直接将url交微信前段展示
                $url = $check[1];
                return $url;
            
            }else{//2.url失效，创建新二维码，并将新的url、生成时间、写进库中对应字段,调用updateziprul()方法
                $sceneId = $check[2];//用唯一的、和openiD一一对应的int数dataNO作为场景值以定位产生二维码用户
                if ($sceneID == NULL) {//dataNO场景值为空无效，再执行一次checkQrcode得到dataNO
                    $check2 = self::checkQrcode($openid);
                    $sceneID = $check2[2];
                }
                //dataNO 场景值有效，用其产生二维码
                    $ticket = Popularize::createTicket(1, 604800, $sceneID);
                    $ticket = $ticket['ticket'];
                    $queryUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
                    $url = Popularize::long2short($queryUrl);
                    $url = $url['short_url'];
                    $up = self::updateZipurl($openid,$url);//写入数据库qrcode表中对应字段
                    return $url;
                }
        }
            
    
   

     /******************
     *库查询功能
     *
     * @param $type char $openid 根据传入的openid查询，，查到就放入相关数据，如果发现表中没有就用openid新建一行数据。
     *
     * @return array() 返回是否失效标志[3]、codetime[0]、zipurl[1]、dataNO[2]
     *
     */
    public static function checkQrcode($openid){
        $dsn = PDO_DSN;//使用config.php 配置  数据库连接准备 
        $user = PDO_USER;
        $pass = PDO_PASS;
        try{
            //用建立一个新PDO类的方法连接数据库
            $dbh = new \PDO($dsn,$user,$pass);// "\PDO" 名字空间中调用全局PDO命名
                //echo 'ok!数据库连接成功<br>';
        }catch(PDOException $e){
                //echo '数据库连接失败:'.$e->getMessage();
            exit;
        }

        $openid = $openid;
        $query1 = "SELECT codetime,zipurl,dataNO FROM qrcode WHERE open_ID = '{$openid}'";
        try{
            $pdostatement = $dbh->query($query1);//用PDO的query()方法执行SQL查询产生一个PDOstatement类
            $result1 = $pdostatement->fetch(\PDO::FETCH_NUM);//使用PDOstatement类的fetch()获得结果集
            //echo "用openid查到失效时间是：".$result[0]."<br>";
            //echo "用openid查到二维码地址是：".$result[1]."<br>";
            //echo "用openid查到dataNO是：".$result[2]."<br>";
        }catch(PDOException $e){
                //echo '数据库连接失败:'.$e->getMessage();
            exit;
        }
        
        if (!$result1) {
            //根据openid没有查到任何的记录，在表中新建一条openid、dataNO记录，zipurl、codetime为空，并返回失效标志。
            $openid = $openid;
            $query4 = "INSERT INTO qrcode (open_ID) VALUES ('{$openid}')";
            $stmt = $dbh->exec($query4);

            //此刻在数据库中新建一张以no+dataNO为名的数据表，字段为open_ID和followtime，准备记录被推荐用户信息。
            $query5 = "SELECT dataNO FROM qrcode WHERE open_ID = '{$openid}'";
            try{
                $pdostatement = $dbh->query($query5);//用PDO的query()方法执行SQL查询产生一个PDOstatement类
                $result2 = $pdostatement->fetch(\PDO::FETCH_NUM);//使用PDOstatement类的fetch()获得结果集
                                //echo "用openid查到dataNO是：".$result[0]."<br>";
            }catch(PDOException $e){
                    //echo '数据库连接失败:'.$e->getMessage();
                exit;
            }
            $tablename = 'no'.$result2[0];//,注意$tablename必须是一个字符串，并在字符串前增加一个随意选择的字母no前缀满足阿里云mysql表名规则
            $query6 = "create table $tablename (open_ID varchar(255),followtime int);";
            $pdostatement_table = $dbh->query($query6);

            $checkresult = array();
            $checkresult[0] = NULL;
            $checkresult[1] = NULL;
            $checkresult[2] = $result2[0];
            $checkresult[3] = FALSE;
            return $checkresult;

        }else{//在数据表中查到了相关记录，但仍然需要进一步判断时间有效性
           
            $time = \time()-$result1[0];
            if($time < 604500) {//codetime 仍然在有效时间之内
            //codetime有效，返回有效标志
                $checkresult = array();
                $checkresult[0] = $result1[0];
                $checkresult[1] = $result1[1];
                $checkresult[2] = $result1[2];
                $checkresult[3] = TURE;
                return $checkresult;

            }else{
                //codetime已经失效，返回失效标志
                $checkresult = array();
                $checkresult[0] = $result1[0];
                $checkresult[1] = $result1[1];
                $checkresult[2] = $result1[2];
                $checkresult[3] = FALSE;
                return $checkresult;
            }


        }
        
    }    

    /******************
     *库写入zipurl功能，url失效调用该方法
     *
     * @param $type char $openid 根据传入的openid,url,并自行取当前时间写入对应字段
     *
     * @return bool 返回up 成功、或失败
     *
     */
    public static function updateZipurl($openid,$url){
        $dsn = PDO_DSN;//使用config.php 配置  数据库连接准备 
        $user = PDO_USER;
        $pass = PDO_PASS;
        try{
            //用建立一个新PDO类的方法连接数据库
            $dbh = new \PDO($dsn,$user,$pass);// "\PDO" 名字空间中调用全局PDO命名
                //echo 'ok!数据库连接成功<br>';
        }catch(PDOException $e){
                //echo '数据库连接失败:'.$e->getMessage();
            exit;
        }

    //用$zipurl的值去更改数据库中对应字段的内容
    $zipurl = $url;
    $openid = $openid;
    $time = time();
    $query2 = "UPDATE qrcode SET zipurl = '{$zipurl}',codetime = {$time} WHERE open_ID = '{$openid}'";
    $affected = $dbh->exec($query2);//使用PDO类的exec()方法调用SQL语句（UPDATE、INSERT、DELETE修改数据
    if (!$Saffected) {
        # code...somgthing error
        return false;
    }else{
        return true;
    }
 }          

}