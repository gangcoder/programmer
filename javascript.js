//窗口加载完成条用函数
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

//在元素后面插入同级节点
function insertAfter(newElement, targetElement) {
	var parent = targetElement.parentNode;
	if (parent.lastChild == targetElement) {
		parent.appendChild(newElement);
	}else{
		parent.insertBefore(newElement, targetElement.nextSibling)
	}
}

// 检测XMLHttpRequest的创建
function getHttpObject () {
  if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
   return new XMLHttpRequest();
    }
  else
    {// code for IE6, IE5
   return new ActiveXObject("Microsoft.XMLHTTP");
    }
}

//返回异步加载文件文本
function getNewContent (fileName) {
  var request = getHttpObject();
  if (request) {
    request.open("GET",fileName , true);
    // 服务器响应时触发
    request.onreadystatechange = function(){  
      if (request.readyState == 4) {
        return request.responseText;
      };
    }
    request.send(null);
  }else{
    alert('Sorry, your browser does\'t support XMLHttpRequest');
  }
}

//显示缩写信息列表
function displayAbbreviations () {
  //兼容性检查
  if (!document.getElementsByTagName) return false;
  if (!document.createElement) return false;
  if (!document.createTextNode) return false;
// if (setAttribute) 
      // alert(typeof setAttribute);
  // return false;
    var abbrArr = document.getElementsByTagName('abbr');
    var defs = new Array();
    //获取缩写信息
    for (var i = 0; i < abbrArr.length; i++) {
      if (abbrArr[i].childNodes.length<1) continue;
      var value = abbrArr[i].getAttribute('title');
      // alert(typeof abbrArr[i].getAttribute);
      // var txtArr[i] = abbrArr[i].lastChild;
      // var txtArr = new Array();
      var key = abbrArr[i].lastChild.nodeValue;
      defs[key] = value;
      // alert(txtArr[i]);
    }
    //创建标记，将缩写信息加入DOM树中
    var dl = document.createElement('dl');
    for(key in defs){
      var keyNode = document.createTextNode(key);
      var dt = document.createElement('dt');
      dt.appendChild(keyNode);
      var keyValue = document.createTextNode(defs[key]);
      var dd = document.createElement('dd');
      dd.appendChild(keyValue);
      dl.appendChild(dt);
      dl.appendChild(dd);
     } 
      if (dl.childNodes.length <1) return false;
      // var dt = document.createElement('dt');
      // dl.appendChild(dt);
      // dt.appendChild(txtArr[i]);
    // };
    document.body.appendChild(dl);
}

//显示引用链接信息
function displayCitations () {
  if (!document.getElementsByTagName || !document.createElement || !document.createTextNode) return false;
  //取得引用标签
  var blockquote = document.getElementsByTagName('blockquote');
  //遍历引用
  for (var i = 0; i < blockquote.length; i++) {
    if (!blockquote[i].getAttribute('cite')) continue;
    //取得引用的链接属性
    var url = blockquote[i].getAttribute('cite');
    //取得引用块中最后一个元素
    var quoteChildren = blockquote[i].getElementsByTagName('*');
    if (quoteChildren.length <1 ) continue;
    var elem = quoteChildren[quoteChildren.length -1];
    //创建链接元素
    var link = document.createElement('a');
    link.setAttribute('href',url);
    var linkText = document.createTextNode(url);
    link.appendChild(linkText);
    //修饰链接元素
    var superscript = document.createElement('sup');
    superscript.appendChild(link);
    //插入DOM树
    elem.appendChild(superscript);
  }
}

//显示键绑定信息
function displayAccesskeys() {
  if (!document.getElementsByTagName || !document.createElement || !document.createTextNode) return false;
// get all the links in the document
  var links = document.getElementsByTagName("a");
// create an array to store the accesskeys
  var akeys = new Array();
// loop through the links
  for (var i=0; i<links.length; i++) {
    var current_link = links[i];
// if there is no accesskey attribute, continue the loop
    if (current_link.getAttribute("accesskey") == null) continue;
// get the value of the accesskey
    var key = current_link.getAttribute("accesskey");
// get the value of the link text
    var text = current_link.lastChild.nodeValue;
// add them to the array
    akeys[key] = text;
  }
// create the list
  var list = document.createElement("ul");
// loop through the accesskeys
  for (key in akeys) {
    var text = akeys[key];
//  create the string to put in the list item
    var str = key + " : "+text;
// create the list item
    var item = document.createElement("li");
    var item_text = document.createTextNode(str);
    item.appendChild(item_text);
// add the list item to the list
    list.appendChild(item);
  }
// create a headline
  var header = document.createElement("h3");
  var header_text = document.createTextNode("Accesskeys");
  header.appendChild(header_text);
// add the headline to the body
  document.body.appendChild(header);
// add the list to the body
  document.body.appendChild(list);
}

//设置表格隔行变色
function stripeTables (oddStyle) {
  if (!document.getElementsByTagName) return false;
  //获取所有表格
  var tables = document.getElementsByTagName('table');
  //遍历每个表格
  for (var i = 0; i < tables.length; i++) {
    var falg = false;  //设置变色的标签
    //获取表格中每行
    var rows = tables[i].getElementsByTagName('tr');
    //隔行设置颜色
    for (var j = 0; j < rows.length; j++) {
      if (falg == true) {
        // rows[j].className = oddStyle;
        addClass(rows[j], oddStyle);
        falg = false;
      }else{
        falg = true;
      }
    };
  };
}
addLoadEvent(function  () {
  stripeTables('odd');
});


//给元素添加类
function addClass (element,value){
  //如果该元素没有class，直接添加
  if(!element.className){
    element.className = value;
  }else{
    var newClassName = element.className;
    newClassName += " ";
    newClassName += value;
    element.className = newClassName;
  }
}