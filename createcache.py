import mysql.connector 
 
sqlconfig = ""
with open("config.php", "r", encoding="utf8") as confin:
    for line in confin:
        if "$sql" in line:
            sqlconfig = line[:-2].strip().split(",")

thisuser = sqlconfig[1].replace("'","").replace('"','').replace('(','').replace(')','').strip()
thispw = sqlconfig[2].replace("'","").replace('"','').replace('(','').replace(')','').strip()
thisdb = sqlconfig[0].split("dbname=")[1].replace("'","").replace('"','').strip()

mydb = mysql.connector.connect(
    host="localhost",
    user=thisuser,
    password=thispw
)
cs = mydb.cursor()
cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author,title,urn")
resstr=""
for x in cs:
    line = str(x[0])+"\t"+str(x[1])+"\t"+str(x[2])+"\t"+str(x[3])+"\t"+str(x[4])+"\t"+str(x[5])+"\n"
    while "\tNone\t" in line:
        line = line.replace("\tNone\t","\t\t")        
    while "\tNone\n" in line:
        line = line.replace("\tNone\n","\t\n")        
    resstr += line

    
with open("plain/editions.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip("\n"))

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author")
resstr=""
for x in cs:
    line = str(x[0])+"\t"+str(x[1])+"\t"+str(x[2])+"\t"+str(x[3])+"\t"+str(x[4])+"\t"+str(x[5])+"\n"
    while "\tNone\t" in line:
        line = line.replace("\tNone\t","\t\t")        
    while "\tNone\n" in line:
        line = line.replace("\tNone\n","\t\n")        
    resstr += line
with open("plain/editions_author.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip("\n"))
    
cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY year")
resstr=""
for x in cs:
    line = str(x[0])+"\t"+str(x[1])+"\t"+str(x[2])+"\t"+str(x[3])+"\t"+str(x[4])+"\t"+str(x[5])+"\n"
    while "\tNone\t" in line:
        line = line.replace("\tNone\t","\t\t")        
    while "\tNone\n" in line:
        line = line.replace("\tNone\n","\t\n")        
    resstr += line
with open("plain/editions_year.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip("\n"))

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author,year")
resstr=""
for x in cs:
    line = str(x[0])+"\t"+str(x[1])+"\t"+str(x[2])+"\t"+str(x[3])+"\t"+str(x[4])+"\t"+str(x[5])+"\n"
    while "\tNone\t" in line:
        line = line.replace("\tNone\t","\t\t")        
    while "\tNone\n" in line:
        line = line.replace("\tNone\n","\t\n")        
    resstr += line
with open("plain/editions_author,year.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip("\n"))

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author,title")
resstr=""
for x in cs:
    line = str(x[0])+"\t"+str(x[1])+"\t"+str(x[2])+"\t"+str(x[3])+"\t"+str(x[4])+"\t"+str(x[5])+"\n"
    while "\tNone\t" in line:
        line = line.replace("\tNone\t","\t\t")        
    while "\tNone\n" in line:
        line = line.replace("\tNone\n","\t\n")        
    resstr += line
with open("plain/editions_author,title.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip("\n"))
