##  哈希分布

Memcache 数据分布：根据 key的hash值%服务器数取余数的方法来决定当前这个key的内容发往哪一个服务器的

散列值转为数值

```
function mHashs($key)
{
    $md5 = substr(md5($key), 0, 8);
    $seed = 31;
    $hash = 0;

    for ($i=0; $i <8 ; $i++) { 
        $hash = $hash*$seed + ord($md5[$i]);
    }
    return $hash & 0x7FFFFFFF;  // 只取前32位
}
```

## 一致性哈希算法 Consistent hashing

哈希分布存在服务器节点不能变动问题

