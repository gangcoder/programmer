## Simple HTML DOM

[Simple HTML DOM](http://simplehtmldom.sourceforge.net/)

### Simple HTML DOM功能

- 验证/解析HTML文档
- 类似jQuery的元素选择器
- 添加、删除、修改文档树
- 消耗内存

### 加载HTML文件

- url
- string
- file
        
        $html = new simple_html_dom();
        //文件或者url载入html
        $html->load_file(文件/URL路径)；
        //字符串载入html
        $html->load(‘字符串’)；

