#!/usr/bin/env python
import os
import sys
import subprocess

if(len(sys.argv) != 7):
    print ("Wrong number of arguments.")
    print r"""
    program = sys.argv[1]   (The question name, CRICKET for example)
    user = sys.argv[2]      (User's tech id)
    library = sys.argv[3]   (MPI or anything else. MPI matches OpenMP for some weird reason)
    timelimit = sys.argv[4] (Timelimit from DB, eg 00:05:00 is 5 minutes)
    id = sys.argv[5]        (Submission id from DB)
    contact = sys.argv[6]
    Also, the binary to be run is assumed to be at:
    (binaries/{user_id}_{program/question_name}_{submission_id_from_db})
    """
    exit()

program = sys.argv[1]
user = sys.argv[2]
library = sys.argv[3]
timelimit = sys.argv[4]
id = sys.argv[5]
contact = sys.argv[6]

print("Program is %s" % program)
print("User is %s" % user)
print("Library is %s" % library)
print("Time limit is %s" % timelimit)
print("Id is %s" % id)
print("Reach me at %s" % contact)
print("I am "),
sys.stdout.flush()
subprocess.call("whoami")

mpiString = r"""################################################
#!/bin/bash
#PBS -l nodes=1:ppn=16
#PBS -l walltime=%s
#PBS -o /home/external/iitk/ankmahato/out_$PBS_JOBID
#PBS -e /home/external/iitk/ankmahato/err_$PBS_JOBID
## Comma separated list of email address and mobile numbers
#PBS -m abe
#PBS -M saksham0808@gmail.com,%s
export I_MPI_JOB_CONTEXT=$PBS_JOBID
export OMP_NUM_THREADS=16
echo %s
echo %s
echo %s
echo PBS JOB id is $PBS_JOBID
echo PBS_NODEFILE is $PBS_NODEFILE
echo PBS_QUEUE is $PBS_QUEUE
NPROCS=`wc -l < $PBS_NODEFILE`
echo NPROCS is $NPROCS
cd $PBS_O_WORKDIR
ulimit -s unlimited
time mpirun -np $NPROCS $HOME/Judge_2016/binaries/%s_%s_%s < $HOME/Judge_2016/inputs/%s.in > $HOME/Judge_2016/runtime_outputs/%s_%s.out
################################################
""" % (
    timelimit,
    contact,
    program,
    id,
    user,
    user,
    program,
    id,
    program,
    user,
    id
)

openMPString = r"""################################################
#!/bin/bash
#PBS -l nodes=1:ppn=16
#PBS -l walltime=%s
#PBS -o /home/external/iitk/ankmahato/out_$PBS_JOBID
#PBS -e /home/external/iitk/ankmahato/err_$PBS_JOBID
## Comma separated list of email address and mobile numbers
#PBS -m abe
#PBS -M saksham0808@gmail.com,%s
export I_MPI_JOB_CONTEXT=$PBS_JOBID
export OMP_NUM_THREADS=16
echo %s
echo %s
echo %s
echo PBS JOB id is $PBS_JOBID
echo PBS_NODEFILE is $PBS_NODEFILE
echo PBS_QUEUE is $PBS_QUEUE
NPROCS=`wc -l < $PBS_NODEFILE`
echo NPROCS is $NPROCS
cd $PBS_O_WORKDIR
ulimit -s unlimited
time mpirun -np $NPROCS $HOME/Judge_2016/binaries/%s_%s_%s < $HOME/Judge_2016/inputs/%s.in > $HOME/Judge_2016/runtime_outputs/%s_%s.out
################################################
""" % (
    timelimit,
    contact,
    program,
    id,
    user,
    user,
    program,
    id,
    program,
    user,
    id
)

script_name = "/home/external/iitk/ankmahato/Judge_2016/submission_scripts/job_script_%s.sh" % id
f = open(script_name, 'w')

# The IF-ELSE are opposite. Thanks for inverting everything, old managers ;)
if(library == "MPI"):
    f.write(mpiString)
else:
    f.write(openMPString)
    
f.close()

print("Job file written as "),
sys.stdout.flush()
subprocess.call("whoami")

# Submit job.
submission_file = "/home/external/iitk/ankmahato/Judge_2016/submission_outputs/" + user + "_" + program + "_" + id + ".submit"
submit_out = open(submission_file, 'w')

# This is how a job is submitted. By calling qsub with the script file as argument.
submission = subprocess.Popen(
    ["/usr/local/PBS/bin/qsub", script_name], stdout=submit_out)
submission.wait()
submit_out.close()

print("Job submission tried.")
submit_out = open(submission_file, 'r')
print("Submission output:")
str = submit_out.read()
print(str)
submit_out.close()

sys.exit(0)
# Also run out_watcher to watch for the output and update the db when needed.
