import numpy as np
from PIL import Image

def make_transparent(input_path, output_path):
    img = Image.open(input_path).convert('RGBA')
    data = np.array(img)
    
    r, g, b, a = data[:,:,0].astype(float), data[:,:,1].astype(float), data[:,:,2].astype(float), data[:,:,3].astype(float)
    
    # Calculate distance from white (255, 255, 255)
    dist = np.sqrt((255-r)**2 + (255-g)**2 + (255-b)**2)
    
    # We want white (dist=0) to be fully transparent (alpha=0)
    # And non-white (dist > threshold) to be opaque (alpha=255)
    # A threshold of 50 means colors fairly close to white will start becoming transparent
    alpha = np.clip(dist * 3, 0, 255).astype(np.uint8)
    
    data[:,:,3] = alpha
    
    # To remove the white halo effect on semi-transparent pixels,
    # we can change the RGB of the very bright pixels to a dark green/blue
    # But just setting the alpha channel usually does the trick for a quick fix.
    
    out_img = Image.fromarray(data)
    out_img.save(output_path, 'PNG')

make_transparent('public/images/logo.jpg', 'public/images/logo.png')
print('Transparent logo saved as logo.png')
