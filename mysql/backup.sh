#!/bin/bash

mysqldump --defaults-extra-file=/home/config.cnf demo > /docker-entrypoint-initdb.d/dump.sql
