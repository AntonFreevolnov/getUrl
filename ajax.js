function setUrl () {
	$pageUrl = document.getElementById("page_url").value;
	if($pageUrl == "") { alert("Input is empty!!!"); return; }
	if($pageUrl.length < 4) { alert("Слишком короткий адрес!!!"); return; }
	$params = "pageUrl="+ $pageUrl;
	var xmlHttp = createXmlHttpRequestObject();
	function createXmlHttpRequestObject() {
		var xmlHttp;
		try {
			xmlHttp = new XMLHttpRequest();
		}
		catch(e) {
			var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
			"MSXML2.XMLHTTP.5.0",
			"MSXML2.XMLHTTP.4.0",
			"MSXML2.XMLHTTP.3.0",
			"MSXML2.XMLHTTP",
			"Microsoft.XMLHTTP");
			for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) {
				try {
					xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
				}
				catch (e) {}
			}
		}
		if (!xmlHttp) alert("Ошибка создания объекта  XMLHttpRequest.");
		else
		return xmlHttp;
	}
		{
			if (xmlHttp) {
				try {
				xmlHttp.open("post", "routing.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = function () {
					if (xmlHttp.readyState == 4) {
						if (xmlHttp.status == 200) {
							try {
								var xmlResponse = xmlHttp.responseXML;
								if (!xmlResponse || !xmlResponse.documentElement) 
									throw("Неверная структура документа XML:\n" + xmlHttp.responseText);
								var rootNodeName = xmlResponse.documentElement.nodeName;
								if (rootNodeName == "parsererror") throw("Invalid XML structure");
								xmlRoot = xmlResponse.documentElement;
								myNewUrl = '<a id="mylink" href="'+xmlRoot.firstChild.data +'">' + location + xmlRoot.firstChild.data + '</a>';
								myDiv = document.getElementById("myform");
								newDiv = document.createElement("div");
								myDiv.parentNode.replaceChild(newDiv, myDiv);
								newDiv.innerHTML = myNewUrl;
			
							}
							catch(e) {
								alert("Ошибка чтения ответа: " + e.toString());
							}
						}
						else {
							alert("Возникли проблемы во время получения данных:\n" +
							xmlHttp.statusText);
						}
					}
				}
				xmlHttp.send($params);
			}
			catch (e) {
				alert("Невозможно соединиться с сервером:\n" + e.toString());
			}
		}
	}
}

window.onload = function () {
	document.getElementById("page_url").value = '';
	document.getElementById("myform").onsubmit = function() {
	setUrl();
	return false;
	}
}