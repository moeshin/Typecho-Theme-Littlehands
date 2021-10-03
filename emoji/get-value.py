#!/usr/bin/env python3
"""
评论表情 Json 模式
"""
import os
import json

listdir = os.listdir()
is_win = os.sep == '\\'

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
    data.sort()
    print(json.dumps(data))
    print()
