<?php
class utility
{

	static public function pushArrayToArray(&$stack,$arrayPush){
		if(!is_array($stack)){
			$stack = array();
		}
		foreach ($arrayPush as $item){
			array_push($stack, $item);
		}
	}
	
	static  public function deleteFile( $url ){
		if ( file_exists($url) ){
			unlink($url);
		}
	}
	
    /**
     *
     * @param $str string
     * @return string
     */
    static public function stripUnicode($str)
    {
        if (! $str)
            return false;
        $unicode = array(
            'a' => 'Â|Á|Ả|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ|Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ'
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    static public function convertoLink($str, $lenght = null)
    {
        if (! $str)
            return false;
        if ($lenght != null)
            return utility::stripUnicode(str_replace(' ', '-', substr($str, 0, $lenght)));
        else
            return utility::stripUnicode(str_replace(' ', '-', substr($str, 0, strlen($str))));
    }

    /**
     *
     * @param $nhomtin entity\NhomTin
     *
     * @param $loaitin entity\LoaiTin
     * @param $tin entity\Tin
     */
    static public function showlink($nhomtin, $loaitin = null, $tin = null)
    {



        /* @var $tin entity\Tin  */
        $url = 'tin-tuc/';

        $url .= utility::convertoLink($nhomtin->getTen_nhomtin()) . '-' . $nhomtin->getId_nhomtin() . '/';

        if ($loaitin != null) {
            $url .= utility::convertoLink($loaitin->getTen_loaitin()) . '-' . $loaitin->getId_loaitin() . '/';
        }

        if ($tin != null) {
            $url .= utility::convertoLink($tin->getTieude()) . '-' . $tin->getId_tin() . '/';
        }

        //loai bo tat ca cac ki tu dat biet
        $url =preg_replace('/[^A-Za-z0-9-\/]/', '', $url);

        return '/'.Name_Site.'/'.$url;
    }

    /**
     *
     * @param $limit int
     */
    static public function gioiHanChuoi($s, $limit)
    {
        if ($limit == null || strlen($limit) == 0)
            return '';

        if (strlen($s) > $limit) {
            $offset = ($limit - 3) - strlen($s);
            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
        }
        return $s;
    }

    static public function product_price($priceFloat) {
    	$symbol = ' VND';
    	$symbol_thousand = '.';
    	$decimal_place = 0;
    	$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
    	return $price.$symbol;
    }
}
?>