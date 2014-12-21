#!/bin/bash
#Purpose = Backup of ABCD Database
#Created on 7-09-2014
#Author = Daniel Deogratus
#Title = System Librarian - Mzumbe University
#email = ddmwashiuya@mzumbe.ac.tz
#Version 1.0
#START
 
#TIME=`date +"%b-%d-%y"`             
TIME=`date +"%a-%Y"`
FILENAME="backup-$TIME.tar.gz"      
SRCDIR="/var/opt/ABCD/bases/"       
DESDIR="/var/backups/abcd"            
tar -cpzf $DESDIR/$FILENAME $SRCDIR

#copying to ARIS server using key
sudo scp /var/backups/abcd/backup-$TIME.tar.gz user@my.ip.no.ip:/my/backupbolder/abcd
 
#END
