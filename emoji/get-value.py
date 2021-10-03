#!/usr/bin/env python3
"""
评论表情 Json 模式
"""
import os
import json

listdir = os.listdir()
is_win = os.sep == '\\'


def custom_sort(d: list[str]) -> list[str]:
    _data = {}
    for s in d:
        k = len(s)
        _d = _data.get(k)
        if _d is None:
            _d = [s]
            _data[k] = _d
        else:
            _d.append(s)
    keys = _data.keys()
    keys = sorted(keys)
    d = []
    for k in keys:
        d.extend(_data[k])
    return d


for emoji in listdir:
    if not os.path.isdir(emoji):
        continue
    print('Emoji: ' + emoji)
    data = []
    prefix = emoji + os.sep
    prefix_len = len(prefix)
    for root, dirs, files in os.walk(emoji):
        if root == emoji:
            root = None
        elif root.startswith(prefix):
            root = root[prefix_len:]
        for f in files:
            if root is not None:
                f: str = os.path.join(root, f)
                if is_win:
                    f = f.replace('\\', '/')
            data.append(f)
    data = custom_sort(data)
    print(json.dumps(data))
    print()
