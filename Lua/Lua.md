# Lua

## 部署工具 LuaRocks

### 安装

```
wget http://luarocks.org/releases/luarocks-2.0.13.tar.gz
cd luarocks-2.0.13
./configure --prefix=/usr/local/luarocks/ --rocks-tree=/usr/local --sysconfdir=/usr/local/etc/luarocks
make
make install
```
