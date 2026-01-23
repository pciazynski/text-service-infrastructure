import os
unwantedlist = '"%" "urndata" "urndatarestr" require(" $_GET[" "/" ":" "." "@" "" "-" "#" "urn:cts" $sql->query'.split(" ")  

def QAcheck(fn):
    global unwanted
    with open(fn, 'r', encoding='utf8') as inf:
        for line in inf:
            for unwanted in unwantedlist:
                if unwanted.replace('#',' ') in line:
                    print(fn+" "+unwanted)
    
    
for file in os.listdir('./'):
    if file.endswith('.php') and not 'config' in file:
        QAcheck(file)
    if os.path.isdir(file):
        for file2 in os.listdir(file):
            if file2.endswith('.php'):
                QAcheck(file+'/'+file2)