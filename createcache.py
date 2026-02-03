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
    line = ""
    for item in x:
        if item == None:
            item = ""
        line += "\t"+str(item)
    resstr += line.strip("\t")+"\n"

with open("plain/editions.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip())

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author")
resstr=""
for x in cs:
    line = ""
    for item in x:
        if item == None:
            item = ""
        line += "\t"+str(item)
    resstr += line.strip("\t")+"\n"
with open("plain/editions_author.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip())
    
cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY year")
resstr=""
for x in cs:
    line = ""
    for item in x:
        if item == None:
            item = ""
        line += "\t"+str(item)
    resstr += line.strip("\t")+"\n"
with open("plain/editions_year.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip())

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author,year")
resstr=""
for x in cs:
    line = ""
    for item in x:
        if item == None:
            item = ""
        line += "\t"+str(item)
    resstr += line.strip("\t")+"\n"
with open("plain/editions_author,year.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip())

cs.execute("SELECT urn,title,year,author,cast(restricted as CHAR(1)),lang FROM "+thisdb+".workdata ORDER BY author,title")
resstr=""
for x in cs:
    line = ""
    for item in x:
        if item == None:
            item = ""
        line += "\t"+str(item)
    resstr += line.strip("\t")+"\n"
with open("plain/editions_author,title.cache", "w", encoding="utf8") as outf:
    outf.write(resstr.strip())