import MySQLdb
import sys
import subprocess
import shlex
import time
import datetime

db = MySQLdb.connect("localhost", "root", "abc", "hpc")
db.autocommit(True)

cursor = db.cursor()
upcurs = db.cursor()
qcurso = db.cursor()
contcursor = db.cursor()

get_uncompiled = "SELECT * FROM `queue` WHERE `status` = '-1'"

while(1):
    try:
        cursor.execute(get_uncompiled)
        results = cursor.fetchall()
      #  print("Got " + str(len(results)) + " results")
        sys.stdout.flush()
        for row in results:
            print("*****STARTING WITH PROGRAM*****")
            print(row)
            id = row[0]
            user = row[1]
            library = row[2]
            problem = row[3]
            timestamp = row[4]
            status = row[5]

            qcurso.execute("SELECT * FROM `problem` WHERE `problemid` = '" + problem + "'")
            newres = qcurso.fetchone()
            timelimit = newres[2]
            print ("Found problem.")
    
            try:
                print("User is %s" % user)
                qu = "SELECT contact FROM user WHERE `techid1` = %d" % int(user)
                print("Query is %s" % qu)
                contcursor.execute(qu)
                use = contcursor.fetchone()
                mobile = use[0]
                print (str(mobile) + "***")
            except:
                print("Error:: ", sys.exc_info())

            args = shlex.split("python /home/external/iitk/ankmahato/Judge_2016/compiler.py " + str(problem) + " " + str(user) + " " + str(library) + " " + str(timestamp) + " " + str(id) + " " + str(timelimit) + " " + str(mobile))
            cp = subprocess.Popen(args)
            cp.wait()
            if(cp.returncode == 103):
                update_compilation_error = "UPDATE `queue` SET `status` = '0' WHERE `id`='%s' and `techid1`='%s'" % (id, user)
                upcurs.execute(update_compilation_error)
                db.commit()
            else:
                update_compilation_done = "UPDATE `queue` SET `status` = '4' WHERE `id`='%s' and `techid1`='%s'" % (id, user)
                upcurs.execute(update_compilation_done)
                db.commit()
                print ("Calling job submitter.")
                args2 = shlex.split("python /home/external/iitk/ankmahato/Judge_2016/job_submitter.py " + str(problem) + " " + str(user) + " " + str(library) + " " + str(timelimit) + " " + str(id) + " " + str(mobile))
                p = subprocess.Popen(args2)
                p.wait()

    except:
        print("Got error: ", sys.exc_info())

    time.sleep(20)
