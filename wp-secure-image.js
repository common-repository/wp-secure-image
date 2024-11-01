<!-- hide JavaScript from non-JavaScript browsers

	//  WP Secure Image 
	//  Copyright (c) ArtistScope 1998-2020. All Rights Reserved.
	//  www.artistscope.com
	//
	//  Supported platforms: Windows, Mac, Linux
	//
	// Special JS version for Wordpress

// Debugging outputs the generated html into a textbox instead of rendering
//	option has been moved to wp-secure-image.php

// REDIRECTS

var JavaCheck = navigator.javaEnabled();	
var versions = deployJava.getJREs();
var m_szLocation = document.location.href.replace(/&/g,'%26');	
var m_szDownloadNo = wpsiw_plugin_url + "download_no.html";
var m_szDownloadJava = wpsiw_plugin_url + "download_java.html?ref=" + m_szLocation + "&err=nojava";
var m_szDownloadJavaScript = wpsiw_plugin_url + "download_javascript.html?ref=" + m_szLocation;

// JAVA CHECK

if ((!(JavaCheck)) || (versions == null))
	{
	window.location=unescape(m_szDownloadJava);
	document.MM_returnValue=false;
	}


//===========================
//   DO NOT EDIT BELOW 
//===========================

var m_szAgent = navigator.userAgent.toLowerCase();
var m_szPlatform = navigator.platform.toLowerCase();
var m_bMozilla = false;
var m_bMicrosoft = false;
var m_szPlugin = "";

var m_szWindows = (m_szAgent.indexOf("windows")!=-1);
var m_szMacintosh = (m_szAgent.indexOf("macintosh")!=-1);
var m_szLinux = ((m_szPlatform.indexOf("x11")!=-1) && (m_szPlatform.indexOf("linux")!=-1));
var m_szAndroid = ((m_szAgent.indexOf("android")!=-1) && (m_szAgent.indexOf("mobile")!=-1));
var m_szIos = ((m_szAgent.indexOf("iphone")!=-1) || (m_szAgent.indexOf("ipad")!=-1));

var m_szAbrowse = (m_szAgent.indexOf("abrowse")!=-1);
var m_szAcoo = (m_szAgent.indexOf("acoo")!=-1);		//IE
var m_szAmiga = (m_szAgent.indexOf("amiga")!=-1);
var m_szAol = (m_szAgent.indexOf("america online")!=-1);		//IE
var m_szArora = (m_szAgent.indexOf("arora")!=-1);
var m_szArtisBrowser = ((m_szAgent.indexOf('artisreader')!=-1) || (m_szAgent.indexOf('artisbrowser')!=-1));	
var m_szAvant = (m_szAgent.indexOf("avant")!=-1);
var m_szBeonex = (m_szAgent.indexOf("beonex")!=-1);		//mozilla
var m_szBonecho = (m_szAgent.indexOf("bonecho")!=-1);	//firefox
var m_szBrowzar = (m_szAgent.indexOf("browzar")!=-1);	//IE
var m_szCamino = (m_szAgent.indexOf("camino")!=-1);
var m_szCharon = (m_szAgent.indexOf("charon")!=-1);
var m_szCheshire = (m_szAgent.indexOf("cheshire")!=-1);
var m_szChimera = (m_szAgent.indexOf("chimera")!=-1);
var m_szChrome = ((m_szAgent.indexOf("chrome")!=-1) && (m_szAgent.indexOf("safari/5")!=-1) && (m_szAgent.indexOf("vivaldi") > -1));
var m_szComodo = (m_szAgent.indexOf("comodo")!=-1);
var m_szCrazy = (m_szAgent.indexOf("crazy")!=-1);		//IE
var m_szDeepnet = (m_szAgent.indexOf("deepnet")!=-1);	//IE
var m_szEpiphany = (m_szAgent.indexOf("epiphany")!=-1);
var m_szEdge = (m_szAgent.indexOf('edg')!=-1);	
var m_szExplorer = ((m_szAgent.indexOf('msie')!=-1) || (m_szAgent.indexOf('trident')!=-1));	
var m_szFirefox = (m_szAgent.indexOf("firefox")!=-1);
var m_szKmeleon = (m_szAgent.indexOf("k-meleon")!=-1);
var m_szKonqueror = (m_szAgent.indexOf("konqueror")!=-1);
var m_szMaxthon = (m_szAgent.indexOf("maxthon")!=-1);
var m_szMidori = (m_szAgent.indexOf("midori")!=-1);
var m_szNetscape = ((m_szAgent.indexOf("netscape/")!=-1) || (m_szAgent.indexOf("navigator/")!=-1));
var m_szOmniweb = (m_szAgent.indexOf("omniweb")!=-1);
var m_szOpera = ((m_szAgent.indexOf("opera")!=-1) || (m_szAgent.indexOf("opr")!=-1));
var m_szPalemoon = (m_szAgent.indexOf("palemoon")!=-1);
var m_szRockmelt = (m_szAgent.indexOf("rockmelt")!=-1);
var m_szSafari = ((m_szAgent.indexOf("safari/6")!=-1) && (m_szAgent.indexOf("applewebkit")!=-1) && (m_szAgent.indexOf("chrome") > -1));
var m_szSeamonkey = (m_szAgent.indexOf("seamonkey")!=-1);
var m_szSleipnir = (m_szAgent.indexOf("sleipnir")!=-1);
var m_szVivaldi = (m_szAgent.indexOf('vivaldi')!=-1);
var m_szYandex = (m_szAgent.indexOf('yabrowser')!=-1);


var m_bMozilla = ((m_szArtisBrowser) || (m_szChrome) || (m_szEdge) || (m_szFirefox) || (m_szOpera) || (m_szPalemoon) || (m_szSafari) || (m_szYandex));
var m_bMicrosoft = ((m_szAcoo) || (m_szAol) || (m_szBrowzar) || (m_szCrazy) || (m_szDeepnet) || (m_szExplorer)); 

function testCSS(prop) {
    return prop in document.documentElement.style;
}
	
if (m_szWindows)
	{
	m_szPlugin = "JAVA";
	}
else if (m_szMacintosh)
	{
	m_szPlugin = "JAVA";
	}
else if (m_szLinux)
	{
	m_szPlugin = "JAVA";
	}

else
	{
	window.location=unescape(m_szDownloadNo);
	document.MM_returnValue=false;
	}

function bool2String(bValue)
{
    if (bValue == true) {return "1";}
    else {return "0";}
}

function paramValue(szValue, szDefault)
{
    if (szValue.toString().length > 0) {return szValue;}
    else {return szDefault;}
}

function expandNumber(nValue, nLength)
{
    var szValue = nValue.toString();
    while(szValue.length < nLength)
        szValue = "0" + szValue;
    return szValue;
}


// The secure-image-insert functions

function insertSecureImage(szImageName)
{
    // Extract the image width and height from the image name (example name: zulu580_0580_0386_S.class)

    var nIndex = szImageName.lastIndexOf('_S.');
    if (nIndex == -1)
    {
        // Strange filename that doesn't conform to the secure image standard. Can't render it.
        return;
    }

    var szWidth = szImageName.substring(nIndex - 9, nIndex - 5);
    var szHeight = szImageName.substring(nIndex - 4, nIndex);

    var nWidth = szWidth * 1;
    var nHeight = szHeight * 1;


    // Expand width and height to allow for border

    var nBorder = m_szDefaultBorder * 1;
    nWidth = nWidth + (nBorder * 2);
    nHeight = nHeight + (nBorder * 2);

    insertSecureImageClass(nWidth, nHeight, "", "", nBorder, "", "", "", [szImageName]);
}

function insertSecureImageClass(nWidth, nHeight,
    szTextColor,
    szBorderColor,
    nBorder,
    szLoading,
    szLink,
    szTargetFrame,
    arFrames)

{
    if (m_bpDebugging == true)
        { 
        document.writeln("<textarea rows='27' cols='80'>"); 
        }       
    if (m_szPlugin == "JAVA")
    {

	document.writeln("<app" + "let codebase='" + wpsiw_plugin_url + "' code='com.artistscope.ArtistScopeViewer.class' archive='ArtistScopeViewer501.jar' id='Artistscope' width='" + nWidth + "' height='" + nHeight + "'>");
 
    document.writeln("<param name='Style' value='ImageLink' />");
    document.writeln("<param name='TextColor' value='" + paramValue(szTextColor, m_szDefaultTextColor) + "' />");
    document.writeln("<param name='BorderColor' value='" + paramValue(szBorderColor, m_szDefaultBorderColor) + "' />");
    document.writeln("<param name='Border' value='" + paramValue(nBorder, m_szDefaultBorder) + "' />");
    document.writeln("<param name='Loading' value='" + paramValue(szLoading, m_szDefaultLoading) + "' />");
    document.writeln("<param name='Label' value='' />");
    document.writeln("<param name='Link' value='" + paramValue(szLink, m_szDefaultLink) + "' />");
    document.writeln("<param name='TargetFrame' value='" + paramValue(szTargetFrame, m_szDefaultTargetFrame) + "' />");
    document.writeln("<param name='Message' value='' />");   
    document.writeln("<param name='FrameDelay' value='2000' />");
    document.writeln("<param name='FrameCount' value='1' />");
    document.writeln("<param name='Frame000' value='" + wpsiw_upload_url + m_szClassName + "' />");

    document.writeln("<param name='permissions' value='sandbox' />");
    document.writeln("</app" + "let />"); 

    if (m_bpDebugging == true)
        { document.writeln("</textarea />"); }
    }
}
// -->
