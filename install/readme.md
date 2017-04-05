# 生成HDCMS更新包方法

## 生成差异文件(本地执行 废弃!)
git diff v2.0.49 v2.0.50 --name-status > files.php

## 创建压缩包
php hd cli:upgrade
