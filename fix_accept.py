import glob
for file in glob.glob(r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\news\*.blade.php'):
    with open(file, 'r', encoding='utf-8') as f:
        c = f.read()
    c = c.replace('accept="image/*"', 'accept="image/*,video/*"')
    with open(file, 'w', encoding='utf-8') as f:
        f.write(c)