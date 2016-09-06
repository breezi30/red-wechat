<?php
/**
 * 企业付款接口-demo
 * ====================================================
 * 注意：最小金额是100分，即1元
 *
 *
 *
*/
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
    header("Content-type: text/html; charset=UTF8");//确保网页输出中文不是乱码
	//输入需支付的用户Openid号
	if (!isset($_POST["openid"]) || !isset($_POST["amount"]) || !isset($_POST["desc"]) )
	{
		$openid = "ovZLqwm8q7D54N5Ce54-1snC41OI";//默认发给我
		$amount = 100;
		$desc = "财神到！";
	}else{
	    $openid = $_POST["openid"];
	    $amount = $_POST["amount"];
	    $desc = $_POST["desc"];
		
		//使用企业付款
		$mchpay = new WxMchPay_pub();
		//设置必填参数
		//mch_appid已填,商户无需重复填写
		//mch_id已填,商户无需重复填写
		//noncestr已填,商户无需重复填写
		//sign已填,商户无需重复填写
		$mchpay->setParameter("openid","$openid");//收款的用户openid
		$mchpay->setParameter("amount","$amount");//付款金额
		//$desc = $mchpay->characet($desc);//将汉字转化为UF8-1编码
		$mchpay->setParameter("desc","$desc");//付款信息描述
		
		//非必填参数，商户可根据实际情况选填
        // $mchPay->setParameter('re_user_name', 'Max wen');// 收款用户姓名
        // $mchPay->setParameter('device_info', 'dev_server'); // 设备信息
		

		
		
       
		//调用结果
		$mchpayResult = $mchpay->getResult();
		$xml = $mchpay->parameters;//获得xml参数值
		var_dump($xml);
		echo "<br>";

		//商户根据实际情况设置相应的处理流程,此处仅作举例
		if ($mchpayResult["return_code"] == "FAIL") {
			echo "通信出错：".$mchpayResult['return_msg']."<br>";
		}
		else{
			echo "业务结果：".$mchpayResult['result_code']."<br>";
			echo "错误代码：".$mchpayResult['err_code']."<br>";
			echo "错误代码描述：".$mchpayResult['err_code_des']."<br>";
			echo "公众账号ID：".$mchpayResult['mch_appid']."<br>";
			echo "商户号：".$mchpayResult['mchid']."<br>";
			//echo "设备号：".$mchpayResult['device_info']."<br>";
			echo "微信订单号：".$mchpayResult['payment_no']."<br>";
			echo "商户订单号：".$mchpayResult['partner_trade_no']."<br>";
			echo "微信支付成功时间：".$mchpayResult['payment_time']."<br>";
		}
	}
	
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>微信安全支付-企业付款接口</title>
</head>
<body>
	</br></br></br></br>
	<div align="center">
		<form  action="./wxmchpay.php" method="post">
			<p>企业付款：</p>
			<p>收款用户Openid: <input type="text" name="openid" value=<?php echo $openid; ?> ></p>
			<p>金额(分): <input type="int" name="amount" value=<?php echo $amount; ?> ></p>
			<p>企业付款信息描述: <input type="text" name="desc" value=<?php echo $desc; ?> ></p>
		    <button type="submit" >提交</button>
		</form>
		
		</br>
		

	</div>
</body>
</html>

