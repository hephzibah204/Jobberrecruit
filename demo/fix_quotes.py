import sys
path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\employers\post-job.php'
with open(path, 'r', encoding='utf-8') as f:
    c = f.read()
c = c.replace(r"\'", "'")
with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print("Quotes Fixed")
