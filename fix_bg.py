import numpy as np
from PIL import Image

def make_transparent_better(input_path, output_path):
    img = Image.open(input_path).convert('RGBA')
    data = np.array(img)
    
    h, w = data.shape[:2]
    
    # Calculate color distance from pure white
    r, g, b = data[:,:,0].astype(float), data[:,:,1].astype(float), data[:,:,2].astype(float)
    dist_from_white = np.sqrt((255-r)**2 + (255-g)**2 + (255-b)**2)
    
    # Identify non-white pixels (the logo itself)
    non_white = dist_from_white > 30
    coords = np.argwhere(non_white)
    
    if len(coords) > 0:
        y_min, x_min = coords.min(axis=0)
        y_max, x_max = coords.max(axis=0)
        
        # Calculate the center and radius of the logo based on its bounding box
        cy, cx = (y_min + y_max) / 2.0, (x_min + x_max) / 2.0
        logo_radius = max(y_max - y_min, x_max - x_min) / 2.0
        
        # Create a distance matrix from the center of the logo
        y, x = np.ogrid[:h, :w]
        dist_from_center = np.sqrt((x - cx)**2 + (y - cy)**2)
        
        # Make pixels transparent based on how close they are to white
        alpha_background = np.clip(dist_from_white * 3, 0, 255).astype(np.uint8)
        
        # Keep EVERYTHING inside the logo circle 100% opaque
        # (logo_radius - 2) ensures we don't accidentally capture the white edge border
        inside_logo = dist_from_center < (logo_radius - 2)
        
        final_alpha = np.where(inside_logo, 255, alpha_background)
        data[:,:,3] = final_alpha
    
    out_img = Image.fromarray(data)
    out_img.save(output_path, 'PNG')
    print("Fixed logo transparency successfully.")

make_transparent_better('public/images/logo.jpg', 'public/images/logo.png')
