<?php
/*
 * PHP QR Code encoder
 *
 */

define('QR_IMAGE', true);

class QRimage {

    public static $BGColor = array(255,255,255);
    public static $FGColor = array(0,0,0);
    public static $qrlogo = "";
    public static $addText = "";
    public static $addTextPosition = "";
    public static $labelImage = "";

        //----------------------------------------------------------------------
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE) 
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            Header("Content-type: image/png");
            ImagePng($image);
        } else {
            if($saveandprint===TRUE){
                ImagePng($image, $filename);
                header("Content-type: image/png");
                ImagePng($image);
            }else{
                ImagePng($image, $filename);
            }
        }
        ImageDestroy($image);

        if(QRImage::$qrlogo != ''){

            $logo = imagecreatefrompng(QRImage::$qrlogo);
            $QR = imagecreatefrompng( $filename );
            $sw = intval( imagesx( $QR ) );
            $sh = intval( imagesy( $QR ) );
            $lw = intval( imagesx( $logo ) );
            $lh = intval( imagesy( $logo ) );

            // Create a new image onto which we will copy images & assign transparency 
            $target = imagecreatetruecolor( $sw, $sh );
            imagesavealpha( $target , true );

            // common divisor for overlay image size calculations 
            $divisor = 3;

            // image size calculations 
            $clw = $sw / $divisor;      #   calculated width
            $scale = $lw / $clw;        #   calculated ratio
            $clh = $lh / $scale;        #   calculated height

            // allocate a transparent colour for the new image 
            $transparent = imagecolorallocatealpha( $target, 0, 0, 0, 127 );
            imagefill( $target,0, 0, $transparent );

            // copy the QR-Code to the new image 
            imagecopy( $target, $QR, 0, 0, 0, 0, $sw, $sh );

            // Determine position of overlay image using divisor 
            $px=$sw/$divisor;
            $py=$sh/$divisor;

            // add the overlay 
            imagecopyresampled( $target, $logo, $px, $py, 0, 0, $clw, $clh, $lw, $lh );

            // output or save image 
            imagepng( $target, $filename);

            // clean up 
            imagedestroy( $target );
            imagedestroy( $QR );
            imagedestroy( $logo );
        }
        if(QRImage::$labelImage != '')
        {
            $logoLabel = imagecreatefrompng($filename);
            $QRLabel = imagecreatefrompng(QRImage::$labelImage);
            $sw = intval( imagesx( $QRLabel ) );
            $sh = intval( imagesy( $QRLabel ) );
            $lw = intval( imagesx( $logoLabel ) );
            $lh = intval( imagesy( $logoLabel ) );

            // Create a new image onto which we will copy images & assign transparency 
            $targetLabel = imagecreatetruecolor( $sw, $sh );
            imagesavealpha( $targetLabel , true );

            // common divisor for overlay image size calculations 
            $divisor = 1.9;

            // image size calculations 
            $clw = $sw / $divisor;      #   calculated width
            $scale = $lw / $clw;        #   calculated ratio
            $clh = $lh / $scale;        #   calculated height

            // allocate a transparent colour for the new image 
            $transparent = imagecolorallocatealpha( $targetLabel, 0, 0, 0, 127 );
            imagefill( $targetLabel,0, 0, $transparent );

            // copy the QR-Code to the new image 
            imagecopy( $targetLabel, $QRLabel, 0, 0, 0, 0, $sw, $sh );

            // Determine position of overlay image using divisor 
            $px=$sw/$divisor;
            $py=$sh/$divisor;

            // add the overlay 
            imagecopyresampled( $targetLabel, $logoLabel, $px-130, $py-60, 0, 0, $clw, $clh, $lw, $lh );

            // output or save image 
            imagepng( $targetLabel, $filename);

            // clean up 
            imagedestroy( $targetLabel );
            imagedestroy( $QRLabel );
            imagedestroy( $logoLabel );
        }
        if(QRImage::$addText != '')
        {
            define('DOCUMENT_PATH', $_SERVER['DOCUMENT_ROOT'].'/'.$_SERVER['REQUEST_URI']);
            $font = DOCUMENT_PATH."/assets/fonts/arialbd.ttf";
            // echo $font = "/fonts/arialbd.ttf";
            $font_size = 12;
            $QRtext = QRImage::$addText;

            // FETCH IMAGE & WRITE TEXT
            $textTarget = imagecreatefrompng($filename);
            $txt_color = imagecolorallocate($textTarget, 0, 0, 0);
            $bg_color = imagecolorallocate($textTarget, 255, 255, 255);

            // THE IMAGE SIZE
            $width = imagesx($textTarget);
            $height = imagesy($textTarget);

            // THE TEXT SIZE
            $text_size = imagettfbbox($font_size, 0, $font, $QRtext);
            $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
            $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

            // CENTERING THE TEXT BLOCK
            $centerX = CEIL(($width - $text_width) / 2);
            if($centerX<0)
            {
                $centerX = 0;
            }
            else
            {
                $centerX = $centerX;
            }
            $centerY = CEIL(($height - $text_height) / 2);
            if($centerY<0)
            {
                $centerY = 0;
            }
            else
            {
                $centerY = $centerY;
            }
            $text_width = $text_size[4] - $text_size[6] + $centerX;
            $text_height = $text_size[3] - $text_size[5] + $centerY;

            if(QRImage::$qrlogo != '')
            {
                if(QRImage::$addTextPosition!='' && QRImage::$addTextPosition=="TOP")
                {
                    // Add text background
                    imagefilledrectangle($textTarget, $centerX, $centerY-84, $text_width, $centerY-60, $bg_color);

                    // Add text TOP
                    imagettftext($textTarget, $font_size, 0, $centerX, $centerY-65, $txt_color, $font, $QRtext);
                }
                elseif(QRImage::$addTextPosition!='' && QRImage::$addTextPosition=="BOTTOM")
                {
                    // Add text background
                    imagefilledrectangle($textTarget, $centerX, $centerY+47, $text_width, $centerY+70, $bg_color);

                    // Add text BOTTOM
                    imagettftext($textTarget, $font_size, 0, $centerX, $centerY+65, $txt_color, $font, $QRtext);
                }
                else
                {
                    // Add text background
                    imagefilledrectangle($textTarget, $centerX, $centerY-24, $text_width, $centerY+10, $bg_color);

                    // Add text CENTER
                    imagettftext($textTarget, $font_size, 0, $centerX, $centerY, $txt_color, $font, $QRtext);
                }
            }
            else
            {
                // Add text background
                imagefilledrectangle($textTarget, $centerX, $centerY-24, $text_width, $centerY+10, $bg_color);

                // Add text CENTER
                imagettftext($textTarget, $font_size, 0, $centerX, $centerY, $txt_color, $font, $QRtext);
            }

            imagepng($textTarget, $filename);
            imagedestroy($textTarget);
        }
    }
    
        //----------------------------------------------------------------------
    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85) 
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            Header("Content-type: image/jpeg");
            ImageJpeg($image, null, $q);
        } else {
            ImageJpeg($image, $filename, $q);            
        }

        ImageDestroy($image);
    }
    
        //----------------------------------------------------------------------
    private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4) 
    {
        $h = count($frame);
        $w = strlen($frame[0]);

        $imgW = $w + 2*$outerFrame;
        $imgH = $h + 2*$outerFrame;

        $base_image =ImageCreate($imgW, $imgH);

        $col[0] = ImageColorAllocate($base_image,QRImage::$BGColor[0],QRImage::$BGColor[1],QRImage::$BGColor[2]);
        $col[1] = ImageColorAllocate($base_image,QRImage::$FGColor[0],QRImage::$FGColor[1],QRImage::$FGColor[2]);

        imagefill($base_image, 0, 0, $col[0]);

        for($y=0; $y<$h; $y++) {
            for($x=0; $x<$w; $x++) {
                if ($frame[$y][$x] == '1') {
                    ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                }
            }
        }

        $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
        ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);


        ImageDestroy($base_image);

        return $target_image;
    }
}