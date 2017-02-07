# 生成HDCMS更新包

## 生成差异文件(本地执行)
git diff v2.0.2 v2.0.6 --name-status > upgrade/files.php

## 创建压缩包(远程服务器执行)
php hd cli:upgrade


#完整包数据库
##先备份所有表结构
##需要执行INSERT动作的表
hd_menu
hd_migrations
hd_modules
hd_modules_bindings
hd_profile_fields
hd_template
hd_user_group