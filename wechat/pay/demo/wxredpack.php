<?php
/**
 * 企业发红包接口-demo
 * ====================================================
 * 注意：最小金额是100分，即1元
 *
 *
 *
*/
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
    header("Content-type: text/html; charset=UTF8");//确保网页输出中文不是乱码
	//输入需支付的用户Openid号
	if (!isset($_POST["re_openid"]) || !isset($_POST["total_amount"]) || !isset($_POST["wishing"]) )
	{//设置默认值
		$re_openid = "ovZLqwm8q7D54N5Ce54-1snC41OI";//默认发给我
		$total_amount = 100;//单位分，最小1元最大200元
		$wishing = "祝您拆红包拆到手酸！";
		$act_name = "财神发红包活动";
		$remark = "这是红包备注信息。";
		
	}else{//从页面表单传入参数
	    $re_openid = $_POST["re_openid"];
	    $total_amount = $_POST["total_amount"];
	    $wishing = $_POST["wishing"];
	    $act_name = "财神发红包活动";
		$remark = "这是红包备注信息。";
	

		
		//使用企业红包类实例
		$redpack = new Wxredpack_pub();
		//设置必填参数
		$redpack->setParameter("re_openid","$re_openid");//收红包的用户openid
		$redpack->setParameter("total_amount","$total_amount");//红包金额
		$redpack->setParameter("wishing","$wishing");//红包祝福语
		$redpack->setParameter("act_name","$act_name");//红包活动名
		$redpack->setParameter("remark","$remark");//红包备注
		
		
       
		//调用结果
		$redpackResult = $redpack->getResult();
		$xml = $redpack->parameters;//获得xml参数值
		var_dump($xml);
		echo "<br>";

		//商户根据实际情况设置相应的处理流程,此处仅作举例
		if ($redpackResult["return_code"] == "FAIL") {
			echo "通信出错：".$redpackResult['return_msg']."<br>";
		}
		else{
			echo "业务结果：".$redpackResult['result_code']."<br>";
			echo "错误代码：".$redpackResult['err_code']."<br>";
			echo "错误代码描述：".$redpackResult['err_code_des']."<br>";
			echo "公众账号ID：".$redpackResult['wxappid']."<br>";
			echo "商户号：".$redpackResult['mch_id']."<br>";
			echo "金额：".$redpackResult['total_amount']."<br>";
			echo "商户订单号：".$redpackResult['mch_billno']."<br>";
			echo "微信单号：".$redpackResult['send_listid']."<br>";
			echo "发放成功时间：".$redpackResult['send_time']."<br>";
		}
	
	}
?>


<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>微信红包</title>
	<link rel="stylesheet" href="/prom/dist/style/weui.css"/>
    <link rel="stylesheet" href="/prom/dist/example/example.css"/>
</head>
<body ontouchstart>
	<div align="center">
		<form  action="./wxredpack.php" method="post">
			<div class="weui_cells_title">企业发微信红包</div>
			 <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">用户</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input required class="weui_input" type="text" placeholder="请输入收红包用户Openid" name="re_openid" >
                    </div>
                </div>
              <div class="weui_cell">			
                	<div class="weui_cell_hd"><label class="weui_label">金额</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input required class="weui_input" type="text"  placeholder="请输入红包金额(单位分，最少100起):" name="total_amount" >
                    </div>
                </div>
              <div class="weui_cell">			
                	<div class="weui_cell_hd"><label class="weui_label">祝福</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input required class="weui_input" type="text" placeholder="请输入红包祝福语" name="wishing" >
                    </div>
                </div>
            <!--实现weui的按钮-->
		    <div class="weui_btn_area">
                <input type="button" class="weui_btn weui_btn_primary" href="javascript:" id="showTooltips" value="确定">
            </div>
		    <!--<button type="submit" >提交</button>-->
		</form>
	</div>
</body>
</html>

			