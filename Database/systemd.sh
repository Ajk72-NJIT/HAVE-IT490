#!/bin/bash

sudo scp database.service /etc/systemd/system/

sudo systemctl daemon-reload

sudo systemctl enable database

sudo systemctl start database

sudo systemctl status database
