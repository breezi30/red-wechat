
<?php


            
            include 'lanewechat.php';


            //注意必须3个0级菜单
            $menuList = array(

                array('id'=>'1', 'pid'=>'0', 'name'=>'分享推广', 'type'=>'', 'code'=>''),
                array('id'=>'2', 'pid'=>'1', 'name'=>'分享二维码', 'type'=>'click', 'code'=>'key_1'),
                array('id'=>'3', 'pid'=>'1', 'name'=>'分享好文章', 'type'=>'click', 'code'=>'key_2'),
                array('id'=>'4', 'pid'=>'0', 'name'=>'我的统计', 'type'=>'view', 'code'=>'http://www.giant-land.com/zhuye.html'),
                
            );

           
           $result1 =  \LaneWeChat\Core\Menu::setMenu($menuList);
           var_dump($result1);
           //$result2 = \LaneWeChat\Core\Menu::getMenu();
           
//         $result = \LaneWeChat\Core\Menu::delMenu();

