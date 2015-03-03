<?php
/*********************
 * User: Vitaly
 * Date: 22.05.12
 *********************/
class ImageService
{

    // Выполняет изменение размера картинки $file_input и сохранение ее в файл $file_output

    static function resize($file_input, $file_output, $w_o) {
        list($w_i, $h_i, $type) = getimagesize($file_input);
        $img = imagecreatefromjpeg($file_input);
        $h_o = $w_o/($w_i/$h_i);
        $w_o = $h_o/($h_i/$w_i);
        $img_o = imagecreatetruecolor($w_o, $h_o);
        imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
        return imagejpeg($img_o,$file_output);
    }

    // Выполняет обрезку картинки

    static function crop($file_input, $file_output, $crop = 'square')
    {
        list($w_i, $h_i, $type) = getimagesize($file_input);
        $img = imagecreatefromjpeg($file_input);
        if ($crop == 'square') {
            $min = $w_i;
            if ($w_i > $h_i) $min = $h_i;
            $w_o = $h_o = $min;
        } else {
            list($x_o, $y_o, $w_o, $h_o) = $crop;
            if ($w_o < 0) $w_o += $w_i;
            $w_o -= $x_o;
            if ($h_o < 0) $h_o += $h_i;
            $h_o -= $y_o;
        }
        $img_o = imagecreatetruecolor($w_o, $h_o);
        imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
        return imagejpeg($img_o, $file_output, 100);
    }
}
