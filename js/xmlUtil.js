function newXmlDocument (rootTagName, namespaceURL) { 
 if (!rootTagName) rootTagName = ""; 
 if (!namespaceURL) namespaceURL = ""; 
  if (document.implementation && document.implementation.createDocument) { 
	    // This is the W3C standard way to do it 
	    return document.implementation.createDocument(namespaceURL, rootTagName, null); 
	  } 
	  else { // This is the IE way to do it 
	    // Create an empty document as an ActiveX object 
	    // If there is no root element, this is all we have to do 
	    var doc = new ActiveXObject("MSXML2.DOMDocument"); 
	    // If there is a root tag, initialize the document 
	    if (rootTagName) { 
	      // Look for a namespace prefix 
	      var prefix = ""; 
	      var tagname = rootTagName; 
	      var p = rootTagName.indexOf(':'); 
	      if (p != -1) { 
	        prefix = rootTagName.substring(0, p); 
	        tagname = rootTagName.substring(p+1); 
	      } 
	      // If we have a namespace, we must have a namespace prefix 
	      // If we don't have a namespace, we discard any prefix 
	      if (namespaceURL) { 
	        if (!prefix) prefix = "a0"; // What Firefox uses 
	      } 
	      else prefix = ""; 
	      // Create the root element (with optional namespace) as a 
	      // string of text 
	      var text = "<" + (prefix?(prefix+":"):"") +  tagname + 
	          (namespaceURL 
	           ?(" xmlns:" + prefix + '="' + namespaceURL +'"') 
	           :"") + 
	          "/>"; 
	      // And parse that text into the empty document 
	      doc.loadXML(text); 
	    } 
	    return doc; 
	  } 
	}; 
	
	/**
		Input:    {obj_node_Xml: node u object xml}
		Return: { str objeto}
	*/
	function log(obj_node_Xml){
		//console.info((new XMLSerializer()).serializeToString(obj_node_Xml));
	};
	
	
	function selectSingleNode(xmlDoc, elementPath){
        if(window.ActiveXObject) {
            return xmlDoc.selectSingleNode(elementPath);
        }   else {
           var xpe = new XPathEvaluator();
           var nsResolver = xpe.createNSResolver( xmlDoc.ownerDocument == null ? xmlDoc.documentElement : xmlDoc.ownerDocument.documentElement);
           var results = xpe.evaluate(elementPath,xmlDoc,nsResolver,XPathResult.FIRST_ORDERED_NODE_TYPE, null);
           return results.singleNodeValue; 
        }
    };
	
	function selectNodes(xmlDoc, elementPath){
	  var nodesResponse = [];
		
	   if (window.ActiveXObject){
			nodesResponse = xml.selectNodes(path);
		
		} else if (document.implementation && document.implementation.createDocument){
		   var xpe = new XPathEvaluator();
		   var nsResolver = xpe.createNSResolver( xmlDoc.ownerDocument == null ? xmlDoc.documentElement : xmlDoc.ownerDocument.documentElement);
		   var nodes = xpe.evaluate(elementPath,xmlDoc,null,XPathResult.ANY_TYPE, null);
		   
		   var result = nodes.iterateNext();
			while (result){
				nodesResponse.push(result);
				result =  nodes.iterateNext();
			 }
		}
		return nodesResponse;
	};

	/*XMLDocument.prototype.xml = function(objXml){
		var respuesta = "";
		if (window.ActiveXObject){
			respuesta = objXml.xml;
		} else {
			respuesta = (new XMLSerializer()).serializeToString(objXml);
	
			//console.info("respuesta:"+respuesta)
		}	
		return respuesta;
	};*/
	
	XMLDocument.prototype.__defineGetter__("xml", 
		function () { 
                return (new XMLSerializer()).serializeToString(this); 
        } 
	);
	
	Element.prototype.__defineGetter__("xml", 
		function () { 
                return (new XMLSerializer()).serializeToString(this); 
        } 
	);