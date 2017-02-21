# 生成HDCMS更新包方法

## 生成差异文件(本地执行)
git diff v2.0.32 v2.0.33 --name-status > files.php

## 创建压缩包(远程服务器执行)
php hd cli:upgrade