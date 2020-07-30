1. 需要swoole扩展
2. php run.php运行
3. 错误信息在php_error.log中查看
4. think-orm
5. JWT
6. 路由
7. 数据库配置 application/Base.php中
8. JWT token有效期以及key在Tool/Tool.php中查看
9. 自动加载类文件，不过要注意文件夹名称和命名空间大小写一致
10. Noodlehaus\Config处理conf/config.json配置文件。实际代码不要包含config.json防止泄露重要信息
11. 引入composer
#### 增加新的application/中的类的步骤
1. 比如在application中创建类Server.php
2. 增加Tool/Route.php访问路由
