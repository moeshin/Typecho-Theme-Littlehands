#!/usr/bin/env python3
# coding=utf-8
import re
import urllib.request
import urllib.error
import os

CDN_URL = 'https://cdn.staticfile.org/'
RE_SOURCE = re.compile(r'(?:/\*# sourceMappingURL=(.+?) \*/|//# sourceMappingURL=(.+?))$')


def download(name):
    url = CDN_URL + name
    path = 'local/' + name
    d = os.path.dirname(path)
    if not os.path.exists(d):
        os.makedirs(d)
    print(url + ' => ' + path)
    urllib.request.urlretrieve(url, path)
    return path


def main():
    with open('urls.txt', encoding='utf-8') as urls:
        for line in urls:
            name = line.strip()
            path = download(name)
            if path.endswith('.js') or path.endswith('.css'):
                with open(path, encoding='utf-8') as f:
                    search = RE_SOURCE.search(f.read())
                    if search is None:
                        continue
                    source = search.group(1)
                    if source is None:
                        source = search.group(2)
                    # print(source)
                    name = os.path.dirname(name) + '/' + source
                    print(name)
                    download(name)


if __name__ == '__main__':
    main()
