#!/bin/bash

sudo scp dmz.service /etc/systemd/system/

sudo systemctl daemon-reload

sudo systemctl enable dmz

sudo systemctl start dmz

sudo systemctl status dmz
