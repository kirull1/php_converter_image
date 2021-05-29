<?php

    class image{

        private static $lets = ['/', '*', '%', '#', '&', '@', '\\', '|'];

        public function __construct(){
            $this->checkdir('img');
            $this->checkdir('img/video');
            $this->checkdir('img/convert');

            $this->count = count(array_diff(scandir("img/video"), [".", ".."]));
        }

        public static function convert($way, $save = null, $type = 0){
            list($width, $height) = getimagesize($way);
            $gd = imagecreatetruecolor($width, $height);
            $im = imagecreatefromjpeg($way);

            $res = '';
        
            for ($i=0; $i < $width; $i++) { 
                for ($j=0; $j < $height; $j++) { 
                    $rgb = imagecolorat($im, $i, $j);
                    list($r, $g, $b) = [($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, $rgb & 0xFF];
                    $res .= self::generate_img($gd, $i, $j, $r, $g, $b, $type);
                }
                $res .= "\n";
            }
            if($type == 0) self::save_img($gd, $save); else return $res;
        }

        public function clear() {
            if (file_exists('img/video/') && file_exists('img/convert/')) {
                foreach (glob('img/video/*') as $file) unlink($file);
                foreach (glob('img/convert/*') as $file) unlink($file);
            }
        }

        public static function rotate($way, $deg, $flip = false){
            $im = imagecreatefromjpeg($way);
            $flip !== true ?: imageflip($im, IMG_FLIP_HORIZONTAL); 
            $im = imagerotate($im, $deg, 0);
            imagejpeg($im, $way);
        }

        public static function squeeze($file, $get, $save, $width, $height, $quality = -1){
            list($width_old, $height_old) = @getimagesize(rtrim($get, '/')."/$file");
            $image_background = @imagecreatetruecolor($width, $height);
            $image = @imagecreatefromjpeg(rtrim($get, '/')."/$file");
            @imageinterlace($image_background, 1);
            @imagecopyresampled($image_background, $image, 0, 0, 0, 0, $width, $height, $width_old, $height_old);
            @imagejpeg($image_background, rtrim($save, '/')."/$file", $quality);
            @imagedestroy($image_background);
            @imagedestroy($image);
        } 

        private function checkdir($way){
            if (!file_exists($way)) return mkdir($way);
        }

        private static function generate_img($gd, $x, $y, $r, $g, $b, $type){
            list($r, $g, $b) = round(($r + $g + $b) / 3) <= 128 ? [0, 0, 0] : [255, 255, 255]; 
            if($type == 0) imagesetpixel($gd, $x, $y, imagecolorallocate($gd, $r, $g, $b));
            else return $r != 255 ? self::$lets[mt_rand(0, count(self::$lets)-1)] : ' ';
        }

        private static function save_img($gd, $save){
            imagejpeg($gd, $save);
            imagedestroy($gd);
        }

    }

    class video{

        public function __construct($way){
            $this->way = $way;
        }

        public function get_img($file, $save, $fps = 24){
            exec($this->way.' -i '.$file.' -r '.$fps.' "'.$save.'"');
        }

        public function get_video($way, $save, $fps){
            exec($this->way.' -r '.$fps.' -y -i '.$way.' '. $save);
        }

    }
