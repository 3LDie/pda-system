Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("C:\Users\Kai\.gemini\antigravity\brain\ea14f50d-1e38-4e07-bb76-a22510345210\media__1784645502128.png")
$bmp = New-Object System.Drawing.Bitmap($img)
$colors = @{}
for ($x = 0; $x -lt $bmp.Width; $x++) {
    for ($y = 0; $y -lt $bmp.Height; $y++) {
        $pixel = $bmp.GetPixel($x, $y)
        $hex = "#{0:X2}{1:X2}{2:X2}" -f $pixel.R, $pixel.G, $pixel.B
        $colors[$hex] = $true
    }
}
$colors.Keys
$img.Dispose()
$bmp.Dispose()
