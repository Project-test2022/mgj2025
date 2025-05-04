<?php

namespace App\Services;

use Exception;
use finfo;

final readonly class Utility
{
    /**
     * @throws Exception
     */
    public static function getPngCompressedImage(string $imgUrl): string
    {
        $binary = file_get_contents($imgUrl);
        $image = imagecreatefromstring($binary);
        if ($image === false) {
            throw new Exception('Failed to create image from string');
        }

        // 透過保持の設定
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // 出力をバッファリング
        ob_start();

        // 0 (非圧縮) ～ 9 (最大圧縮)
        // レベルが高いほどサイズが小さくなるが、時間がかかる
        $compressionLevel = 6;

        // PNG画像を圧縮してバッファに出力
        imagepng($image, null, $compressionLevel);

        // バッファの内容を取得
        $compBinary = ob_get_clean();

        // バッファを閉じる
        imagedestroy($image);

        // 画像のバイナリデータを返す
        return $compBinary;
    }

    public static function getImageResponse(string $imgBinary)
    {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($imgBinary);
        return response($imgBinary)->header('Content-Type', $mime);
    }

}
