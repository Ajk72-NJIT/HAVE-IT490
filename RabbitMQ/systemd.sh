#!/bin/bash

sudo scp rabbit.service /etc/systemd/system/

sudo systemctl daemon-reload

sudo systemctl enable rabbit

sudo systemctl start rabbit

sudo systemctl status rabbit
