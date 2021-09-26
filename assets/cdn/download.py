#!/usr/bin/env python3
# coding=utf-8

import urllib.request
import os

cdn = 'https://cdn.staticfile.org/'

with open('urls.txt', 'r') as urls:
    for line in urls:
        path = line.strip()
        url = cdn + path
        path = 'local/' + path
        dir = os.path.dirname(path)
        if not os.path.exists(dir):
            os.makedirs(dir)
        print(url + ' => ' + path)
        urllib.request.urlretrieve(url, path)
