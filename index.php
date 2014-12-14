<?php
/**
 * 用户图形化分析类
 * @authors Andy Chen (bootoo@sina.cn)
 * @date    2014-12-14 21:00:18
 * @version $Id$
 */

class userClass {
    
      function __construct(){
        $this->fontfile = 'XHei.ttc';
      }
	
	/**
	 * 用户图形化分析方法
	 * @param int    $speck_num  画多少个点
	 * @param int    $line_num   画多少条线
	 * @param int    $font_num   填充文字数量
	 * @return img   $im  
	 */
	public function userAnalyse($speck_num,$line_num,$font_num){
		// 坐标宽高 coordinate
        	$coor_width   = 2000;   
        	$coor_height = 400;
		// 画布边距
        	$cre_left       = 150;
        	$cre_right     = 500;
        	$cre_top       = 350;
        	$cre_bottom = 150;
		// 图柱宽
        	$con_width = 30;
		// x轴和y轴 宽
        	$con_line_wh = 2;
		// x轴和y轴 延伸
        	$con_line_plus = 100;

		//画布
		$cre_wh = $coor_width + $cre_left + $cre_right;
		$cre_ht  = $coor_height + $cre_top + $cre_bottom;
		$im = imagecreatetruecolor($cre_wh, $cre_ht);    

		//字体颜色
		$font_color = imagecolorallocate($im, 24, 24, 24); 

		// 画背景
		$red     = '244';
		$green = '244';
		$blue   = '245';
		$back_color = imagecolorallocate($im, $red, $green, $blue); 
		imagefilledrectangle($im,0,0,$cre_wh,$cre_ht,$back_color);            

		// X轴
		$red    = 4;
		$green  = 32;
		$blue   = 41;
		$x1     = $cre_left;
		$y1     = $coor_height + $cre_top;
		$x2     = $coor_width + $cre_left + $con_line_plus;
		$y2     = $coor_height + $cre_top;
		$line_color = imagecolorallocate($im, $red, $green, $blue); //线条颜色
		imagesetthickness($im, $con_line_wh);
		imageline($im, $x1, $y1, $x2, $y2, $line_color);

		// X轴 - 数值柱形图
		$time_num = 24;
		$space       = $coor_width / $time_num;
		for ($i=1; $i <= $time_num; $i++) { 
			$x1     = $cre_left + $space * $i;
			$y1     = $coor_height + $cre_top - mt_rand(0,$coor_height);
			$x2     = $cre_left + $space * $i;
			$y2     = $coor_height + $cre_top - $con_line_wh;
	
			$coor_red     = mt_rand(0,255);
			$coor_green = mt_rand(0,255);
			$coor_blue   = mt_rand(0,255);
			$coor_line_color = imagecolorallocate($im, $coor_red, $coor_green, $coor_blue); //线条颜色

			imagesetthickness($im, $con_width);
			imageline($im, $x1, $y1, $x2, $y2, $coor_line_color);

			// X轴 - 时间刻度（小时）
			if ($i < 10) {
				$font = '0'.$i.':00';
			} else {
				$font = $i.':00';
			}
			imagefttext($im, 16, 30, $x2 - 35, $y2 + 50, $font_color, $this->fontfile, $font);
		}

		// Y轴
		$x1     = $cre_left;
		$y1     = $cre_top - $con_line_plus;
		$x2     = $cre_left;
		$y2     = $coor_height + $cre_top;
		imagesetthickness($im, $con_line_wh);
		imageline($im, $x1, $y1, $x2, $y2, $line_color);

		// Y轴 - 数值刻度
		$value_num = 5;
		$max_value = 4000;
		$val = $max_value / $value_num;
		$space = $coor_height / $value_num;
		for ($i=0; $i < $value_num; $i++) { 
			$x1     = $cre_left;
			$y1     = $cre_top + $space * $i;
			$x2     = $cre_left + 10;
			$y2     = $cre_top + $space * $i;
			imagesetthickness($im, $con_line_wh);
			imageline($im, $x1, $y1, $x2, $y2, $line_color);

			// 画虚线
			$x1 = $cre_left + 20;
			$x2 = $coor_width + $cre_left + 20;
			$w    = imagecolorallocate ($im, 255, 255, 255);
			$red = imagecolorallocate ($im, 0, 0, 0);
			/* 5 个红色像素，5 个白色像素 */
			$style  = array( $red, $red, $red, $red, $red, $w, $w, $w, $w, $w );
			imagesetstyle($im, $style);
			imagesetthickness($im, 1);
			imageline($im, $x1, $y1, $x2, $y2, IMG_COLOR_STYLED);

			// Y轴 - 数值刻度数值
			$font = $val * ($value_num - $i);
			imagefttext($im, 16, 0, $x1 - 75, $y1 + 5, $font_color, $this->fontfile, $font);
		}

		return $im;
	}
}

header("Cache-Control:max-age=1,s-maxage=1,no-cache,must-revalidate");
header("Content-type:image/png;charset=utf8");
$obj = new userClass;
$im = $obj->userAnalyse(1000,10,4);

// 生成图片
imagepng($im);
// 销毁图片
imagedestroy($im);

  


?>