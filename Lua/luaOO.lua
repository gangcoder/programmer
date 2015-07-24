
function People(name)
    local self = {}

    local function init()
        self.name = name
    end

    self.sayHi = function()
        print('Hell'..self.name)
    end

    init()
    return self
end

-- local p = People("ZhangSan")
-- p:sayHi()

function Man(name)
    local self = People(name)

    self.sayHello = function()
        print("Hi"..self.name)
    end
    return self
end

local m = Man('LiSi')
m:sayHi()
m:sayHello()


-----
function copy(dist, tab)
    for key, var in pairs(tab) do
        dist[key] = var
    end
end

People.new = function (name)
    local self = clone(People)
    self.name = name

    return self
end

Man = {}
Man.new = function(name)
    local self = People.new(name)
    copy(self, Man)
    return self
end

Man.sayHello = function()
    print('Man say Hello')
end

local m = Man.new('lisi')
m:sayHello()
