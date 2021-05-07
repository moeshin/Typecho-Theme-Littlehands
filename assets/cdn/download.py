#!/usr/bin/env python
# coding=utf-8

import urllib.request
import os

cdn = 'https://cdnjs.loli.net/ajax/libs/'

urls = open('urls.txt', 'r')
for line in urls:
    path = line.strip()
    dir = os.path.dirname(path)
    if not os.path.exists(dir):
        os.makedirs(dir)
    url = cdn + path
    print(url + ' => ' + path)
    urllib.request.urlretrieve(url, path)
urls.close()