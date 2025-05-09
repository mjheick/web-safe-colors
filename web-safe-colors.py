import sys
import os.path
import math
from PIL import Image

# 216 web-safe colors from https://htmlcolorcodes.com/color-chart/web-safe-color-chart
colors = []
for r in range(0, 6):
    for g in range(0, 6):
        for b in range(0, 6):
            colors.append([int(r * 51), int(g * 51), int(b * 51)])

# parameters
if len(sys.argv) == 1:
    print("parameter 1 needs to be an image file")
    quit()
if len(sys.argv) == 2:
    output_filename = "output.png"
if len(sys.argv) > 2:
    output_filename = sys.argv[2]
input_filename = sys.argv[1]

if not os.path.isfile(input_filename):
    print(f"File {input_filename} does not exist")
    quit()

try:
    src = Image.open(input_filename)
    dst = Image.new("RGBA", src.size)
except:
    print(f"{input_filename} is not a readable image")
    quit()

for y in range(0, src.size[1] + 1):
    for x in range(0, src.size[0]):
        pxl = src.getpixel((x, y))
        if (len(pxl) == 3):
            red = pxl[0]
            grn = pxl[1]
            blu = pxl[2]
        else:
            print(f"do not support pixel format")
            quit()
        distance = 255
        new_pxl = (0, 0, 0)
        for clrs in colors:
            c_red = clrs[0]
            c_grn = clrs[1]
            c_blu = clrs[2]
            clr_distance = math.sqrt(
                math.pow(c_red - red, 2) + math.pow(c_grn - grn, 2) + math.pow(c_blu - blu, 2))
            if (clr_distance < distance):
                distance = clr_distance
                new_pxl = (c_red, c_grn, c_blu)
        dst.putpixel((x, y), new_pxl)

dst.save(output_filename)
