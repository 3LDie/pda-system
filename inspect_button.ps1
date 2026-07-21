Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("C:\Users\Kai\.gemini\antigravity\brain\ea14f50d-1e38-4e07-bb76-a22510345210\media__1784645485145.png")
$bmp = New-Object System.Drawing.Bitmap($img)

# Crop the region of the "Register New Dentist" button
# The image is 1024x419. The button is near the top right.
# Let's inspect x from 830 to 970, y from 40 to 65.
# Let's count unique colors in this bounding box.
$colors = @{}
for ($x = 830; $x -lt 970; $x++) {
    for ($y = 40; $y -lt 65; $y++) {
        $pixel = $bmp.GetPixel($x, $y)
        $hex = "#{0:X2}{1:X2}{2:X2}" -f $pixel.R, $pixel.G, $pixel.B
        $colors[$hex]++
    }
}

$colors.GetEnumerator() | Sort-Object Value -Descending | Select-Object -First 10
$img.Dispose()
$bmp.Dispose()
