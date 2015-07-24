require('luacurl')

-- print(curl.escape("南京"));

c = curl.new()
c:setopt(curl.OPT_URL, "http://localhost/feeyobin/tmp.php")
c:perform()