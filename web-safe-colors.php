<?php

/* 216 web-safe colors from https://htmlcolorcodes.com/color-chart/web-safe-color-chart/ */
$colors = [];
for ($r = 0; $r < 6; $r++) {
	for ($g = 0; $g < 6; $g++) {
		for ($b = 0; $b < 6; $b++) {
			$colors[] = [($r * 51), ($g * 51), ($b * 51)];
		}
	}
}

/* parameters */
$input = $argv[1] ?? null;
$output = $argv[2] ?? 'output.png';
if (is_null($input)) { die('parameter 1 needs to be an image file' ."\n"); }
if (!file_exists($input)) { die('file ' . $input . ' not found' . "\n"); }

$i = imagecreatefromstring(file_get_contents($input));
$o = imagecreatetruecolor(imagesx($i), imagesy($i));

for ($y = 0; $y < imagesy($i); $y++) {
	for ($x = 0; $x < imagesx($i); $x++) {
		$clr = imagecolorat($i, $x, $y);
		$red = ($clr >> 16) & 0xFF;
		$grn = ($clr >> 8) & 0xFF;
		$blu = $clr & 0xFF;
		$distance = 256;
		$new_clr = 0;
		foreach ($colors as $clr_rgb) {
			$clr_distance = sqrt(pow($clr_rgb[0] - $red, 2) + pow($clr_rgb[1] - $grn, 2) + pow($clr_rgb[2] - $blu, 2));
			if ($clr_distance < $distance) {
				$distance = $clr_distance;
				$new_clr = ($clr_rgb[0] << 16) + ($clr_rgb[1] << 8) + $clr_rgb[2];
			}
		}
		imagesetpixel($o, $x, $y, $new_clr);		
	}
}

imagedestroy($i);
imagepng($o, $output);
