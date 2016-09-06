<?php
/*
 *  数据库测试
 */
    include 'lanewechat.php';//后台总入口，其include了包含数据库配置的config.php文件

    header("Content-type: text/html; charset=UTF8");//确保网页输出中文不是乱码

    $link = mysql_connect(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASS);
    if (!$link) {
    	die('连接mysql数据库服务器失败了:'.mysql_error());
    }

    //echo '成功连接mysql数据库服务器<br>';
    //echo mysql_get_client_info(),'<br>';
    //echo mysql_get_host_info(),"<br>";
    //echo mysql_get_server_info(),"<br>";
    //echo mysql_client_encoding(),"<br>";
    //echo mysql_stat(),"<br>";

    mysql_select_db(MYSQL_DBNAME,$link) or die('不能连接到具体数据库db！'.mysql_errno());
    echo '成功连接mysql数据库<br>';

    //执行sql语句查询，返回结果集$result
    $result = mysql_query("SELECT open_ID,zipurl,codetime,users,dataNO,backup FROM qrcode");
    echo '执行sql语句查询成功<br>';

    if (!$result) {
       echo "DB Error, could not list tables<br>";
       echo 'MySQL Error: ' . mysql_error().'<br>';
        exit;
    }
    echo '<table align="center" width="80%" border="1">'; //以HTML表格输出结果
    echo '<caption><h1>数据库测试表1</h1></caption>';//输出表名
    echo '<th>open_ID</th><th>zipurl</th><th>codetime</th><th>users</th><th>dataNO</th><th>backup</th>';
   while ($row = mysql_fetch_row($result)) {
    	echo "<tr>";
    	foreach($row as $data){
    		echo '<td>'.$data.'</td>';
    	}
       echo '</tr>';
    }
    echo '</table><br>';

    mysql_free_result($result);//释放查询结果


    // Create table in my database
    //$open_ID = 'ovZLqwm8q7D54N5Ce54-1snC41OI'

    	
	//$sql1 = "create table openid (open_ID varchar(255),datatime int);";//create table 一定要小写
	//$table = mysql_query($sql1); 
    //if (!$table) {
   //    echo "Table Error, could not creat tables<br>";
   //    echo 'MySQL Error: ' . mysql_error().'<br>';
   //     exit;
    //}
   // echo 'Table1 created<br>';
    
    $openid = 'ovZLqwm8q7D54N5Ce54-1snC41OI';
    $sql1 = "SELECT qrcode.dataNO FROM qrcode WHERE qrcode.open_ID = '{$openid}'"; //根据openid来查他在qrcode表中的dataNO。注意此处'{$opend}'传变量的复杂表达式方法。
    $dataNO = mysql_query($sql1);
    if (!dataNO) {
       echo "dataNO Error, could not select dataNO<br>";
       echo 'MySQL Error: ' . mysql_error().'<br>';
       exit;
    }
    echo 'dataNO sql is successed!<br>';
    $dataNOrow = mysql_fetch_row($dataNO);//将查询结果以普通索引数组的方式存入$dataNOrow
    var_dump($dataNOrow);
    echo '<br>';

    $dataNOchr = 'no'.$dataNOrow[0];//在字符串前增加一个随意选择的字母no前缀满足阿里云mysql表名规则
    var_dump($dataNOchr);
    echo '<br>';
    
    mysql_free_result($dataNO);//释放查询结果

    // Create table in my database 使用传参数$dataNOchr的方式创建新表，表名就是$dataNOchr中的字符串。
         
    $sql2 = "create table $dataNOchr (open_ID varchar(255),datatime int);";//create table 一定要小写
    $table = mysql_query($sql2); 
    if (!$table) {
       echo "Table Error, could not creat tables<br>";
       echo 'MySQL Error: ' . mysql_error().'<br>';
        exit;
    }
    echo 'Table noXXX created!<br>';

    mysql_free_result($table);//释放查询结果



    mysql_close($link);//关闭数据库服务器连接

















