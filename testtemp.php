<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/faq.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<!-- InstanceBeginEditable name="doctitle" -->
    <title>How to Set a DIV Height to 100% Using CSS</title>
    <!-- InstanceEndEditable -->
    	<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="/favicon.png" />
<style>
/* html,body,h1,h2,h3,h4,h5,h6,p,blockquote,pre,img{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline} */
html,body{min-height:100%}
/* p{margin:0 0 12px}
ol,ul{margin:0 0 12px}
a,a:active,a:visited{outline:none;color:#1db79f;text-decoration:none}
a img{border:none;outline:none}
p code,table td:last-child code,.content ul li code,code.mark{padding:2px 4px;color:#333;background-color:#f1f1f1;border-radius:4px}
a code{color:inherit;background:none;padding:0}
table{border-collapse:collapse;border-spacing:0}
table td{vertical-align:top}
body{min-width:1300px;color:#414141;background:#fafafa;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Helvetica Neue",sans-serif;font-size:17px;line-height:1.7}
input,select,textarea,button,div,span,a{box-sizing:border-box}
h1,h2,h3,h4{color:#262626;margin:20px 0 10px;line-height:1.5;font-weight:600}
h1{font-size:34px;margin-top:17px}
h2{font-size:28px}
h3{font-size:22px}
.space{margin-bottom:25px!important}
.break{margin-bottom:15px!important}
.text-center{text-align:center!important}
.scroll{height:195px;overflow-y:scroll}
.scroll.large{height:245px}
.scroll.xlarge{height:290px}
.scroll.xxlarge{height:340px}
sup{font-size:13px}
h1 sup{background:#ec0000;border-radius:3px;padding:5px 10px;font-size:15px;color:#fff;position:relative;top:5px}
.leaderboard{padding-top:14px;position:relative;height:105px;overflow:hidden}
.intro-image img{display:block;padding:10px 0 25px;max-width:100%}
*/
.clearfix::after{content:".";display:block;height:0;clear:both;visibility:hidden}
/*
code,.code,.syntax,.green-box,.sky-box,.gray-box,.pink-box,.red-box,.at-rule,.codebox pre,.console-output,.command{font-size:16px;font-family:Consolas,Monaco,Courier,monospace}
.console-output{margin:15px 0}
.command{background:#f2f2f2;padding:16px 18px;margin:15px 0 25px;border-radius:3px}
hr{border:none;border-bottom:1px solid #e7e9ed;height:1px;margin:30px 0}
.summary,.topic{border:1px solid #eaeaea;border-width:1px 0;margin:20px 0;padding:10px 0;color:#8e9aa6;line-height:1.5}
h2.section-title span{display:inline-block;border-bottom:4px solid #c9d7e0;padding:0 50px 12px 0}
*/
.wrapper{width:1300px;padding:0 15px;margin:0 auto}
.header{background:#23384e;padding:16px 0} 
/* input.search{background:#fff;border:0 none;color:#807E7E;float:left;height:38px;line-height:26px;font-size:14px;margin:0 0 0 12px;outline:medium none;padding:6px 14px;width:500px;border-radius:2px;box-shadow:0 0 1px rgba(0,0,0,0.6);font-family:inherit}
.search-btn{color:rgba(0,0,0,.6);background:#ebebeb;border:none;outline:none;cursor:pointer;float:left;height:38px;line-height:46px;width:44px;display:block;margin-left:-2px;border-radius:0 2px 2px 0;box-shadow:0 0 1px rgba(0,0,0,0.7)}
.logo{width:304px}
.logo img{height:44px;margin:-3px 0;display:block}
.site-search{float:left;margin-left:100px}
.menu{background-color:#f5f5f5;box-shadow:0 1px 1px rgba(0,0,0,.15);position:relative;z-index:9}
.menu a{color:#666;display:inline-block;padding:0 10px;text-decoration:none;font-size:14px;font-weight:600;height:48px;line-height:48px}
.menu a:first-child{margin-left:-10px}
.menu a.tool-link{float:right;display:block;border-radius:30px;line-height:28px;position:relative;height:auto;top:9px;padding:0 12px;color:#1ebba3;border:1px double #1ebba3} */
/* .fl,.logo{float:left} */
.leftcolumn{width:240px;float:left;font-size:16px;color:#4f4f4f; background-color: green;}
.centercolumn{width:850px;float:left;}
.rightcolumn{width:180px;float:left}
.content{background:#fff;padding:15px 40px 20px;border:1px solid #dedede;border-top:none;border-radius:1px;background-color: yellow;}
.sidebar{width:160px;float:left;padding-top:28px;margin-left:20px;position:relative}
.leftcolumn .segment{margin:16px 0 12px;position:relative;font-size:18px;font-weight:600;line-height:normal}
.leftcolumn a{color:#4f4f4f;font-size:16px;line-height:26px;display:block;border-bottom:1px solid transparent}
.leftcolumn ul{list-style:none;padding:0;margin:0}
.segment,.chapters,.chapters a{float:left;clear:both}
/* h1 code,h2 code,h3 code{font:inherit}
.color-box{margin:15px 0;padding-left:20px}
.note-box,.warning-box,.tip-box{padding:8px 8px 3px 26px}
.info-tab{float:left;margin-left:-23px}
.content ul li{margin-top:7px}
.extra{padding-top:5px}
.green-box,.sky-box,.gray-box,.red-box,.pink-box{color:#000;margin-top:15px;padding:10px;background-color:#f6f8fa;border:1px solid #d7e2ec}
.example{background:#f4f5f6;padding:3px;margin:15px 0}
.codebox{background:#fff;border:1px solid #ddd}
.codebox-title{height:41px;padding-left:12px;border-bottom:1px solid #ddd;background:#f5f5f5}
.codebox-title h4{margin:0;font-size:18px;line-height:40px;float:left;font-weight:600}
a.try-btn,a.download-btn{width:140px;height:40px;color:#333;font-size:15px;line-height:41px;font-weight:600;text-align:center;text-decoration:none;float:right;display:block;border-left:1px solid #ddd;background:rgba(27,31,35,0.08);box-sizing:border-box;font-family:Arial,sans-serif}
a.try-btn span{font-size:18px;line-height:normal}
.hide,.code-style,.box-size,.bottom-link,.footer,.code-style,.snippets,.report-error,.badge,.social,.ad-label,.mobile-only,ul.tree-menu li ul{display:none}
.skyscraper{width:160px;height:600px;overflow:hidden;margin-bottom:20px;background:#ebecf0}
.bottom-ad{margin-top:46px;padding:24px;position:relative;background:url(/lib/images/smooth-line.png) no-repeat center #f9f9f9}
.rectangle-left,.rectangle-right{min-width:336px;min-height:280px;overflow:hidden}
.fr,a.previous-page,a.next-page,.rectangle-right,.topic-nav{float:right}
a.previous-page,a.next-page{width:32px;height:32px;line-height:30px}
.shadow{background:#F7F8F9;padding:3px;margin:10px 0}
.syntax{color:#2f4959;padding:13px 18px;background:#F9F9FA;border:1px solid #ddd;font-size:15px}
code[class*="language-"],pre[class*="language-"]{color:#000;background:none;font-family:Consolas,Monaco,'Andale Mono','Ubuntu Mono',monospace;text-align:left;white-space:pre;word-break:normal;word-wrap:normal;line-height:1.5;tab-size:4;hyphens:none}
pre[class*="language-"]{position:relative;margin:.5em 0;overflow:visible;padding:0}
pre[class*="language-"]>code{position:relative;border-left:10px solid #358ccb;box-shadow:-1px 0 0 0 #358ccb,0 0 0 1px #dfdfdf;background-color:#fdfdfd;background-image:linear-gradient(transparent 50%,rgba(69,142,209,0.04) 50%);background-size:3em 3em;background-origin:content-box;background-attachment:local}
code[class*="language"]{max-height:inherit;height:inherit;padding:0 1em;display:block;overflow:auto}
:not(pre) > code[class*="language-"]{position:relative;padding:.2em;border-radius:.3em;color:#c92c2c;border:1px solid rgba(0,0,0,0.1);display:inline;white-space:normal}
pre[class*="language-"].line-numbers{padding-left:0}
pre[class*="language-"].line-numbers code{padding-left:3.8em}
pre[class*="language-"].line-numbers .line-numbers-rows{left:0}
pre[class*="language-"][data-line]{padding-top:0;padding-bottom:0;padding-left:0}
pre[data-line] code{position:relative;padding-left:4em}
pre .line-highlight{margin-top:0}
pre.line-numbers{position:relative;padding-left:3.8em;counter-reset:linenumber;white-space:pre-wrap!important}
pre.line-numbers > code{position:relative;white-space:inherit}
.line-numbers-rows,.codebox pre.inactive{display:none}
.codebox pre.syntax-highlighter{margin:0;padding:0;overflow:auto}
pre.line-numbers .line-numbers-rows{border-right:3px solid #6CE26C}
.codebox pre.syntax-highlighter > code{box-shadow:none!important;padding-left:3.8em;background-image:linear-gradient(transparent 50%,#F8F8F8 50%);font-family:Consolas,Monaco,'Andale Mono','Ubuntu Mono',monospace!important;font-size:16px;line-height:1.5;overflow-wrap:break-word}
.codebox pre.syntax-highlighter,.codebox pre.syntax-highlighter code{border:none;width:100%;box-sizing:border-box}
pre.line-numbers code,pre.line-numbers .line-numbers-rows{padding-top:2px;padding-bottom:2px}
.preview-box{padding:15px;text-align:center;cursor:pointer;overflow:hidden;background:#FFF;border:1px solid #e6e6e6}
.preview-box a,.preview-box img{display:block;margin:0 auto}
.download-box{text-align:center;padding:20px 0;margin:20px 0 10px}
.output-box{border-color:#d4d4d4;border-style:solid;border-width:1px 0;padding:5px 15px;overflow:hidden;background:#fff;margin:10px 0}
.demo-box{margin-top:15px}
.subhead{border-bottom:3px solid #DCE3EB;margin-bottom:15px;padding-bottom:10px}
table.data,table.description{width:100%;font-size:92%}
table.data th{color:#000;padding:8px 7px;text-align:left;font-size:15px;background:#F8F8F8}
table.data td{color:#484848;padding:5px 7px;background:#fff}
table.data th,table.data td{vertical-align:top;border:1px solid #DCE3EB}
table.data tr.section th,table.data td.section{font-size:15px;background:#f0f4f7}
table.description th{width:150px}
table.no-wrap tr td:first-child{white-space:nowrap}
.topic-nav{padding-right:5px;color:#d0d0d0}
.topic-nav a{padding:0 15px;margin:0 0 0 5px;position:relative;display:inline-block}
.topic-nav a::after{font-size:24px;position:absolute;line-height:22px}
.topic-nav a:first-child{margin:0 5px 0 0}
.topic-nav a:first-child::after{content:'\00AB';left:-5px}
.topic-nav a:last-child::after{content:'\00BB';right:-5px} */
@media screen and (max-width: 1280px) {
body{min-width:1260px}
.wrapper{width:1260px}
.leftcolumn{width:230px}
.centercolumn{width:820px}
.preview-box img{max-width:100%;height:auto}
}
@media screen and (max-width: 800px) {
body{min-width:100%;max-width:100%;padding-top:46px}
.wrapper{width:100%;padding:0}
/* .header{height:46px;padding:5px 0;position:fixed;top:0;left:0;right:0;width:100%;z-index:99} */
/* .logo{width:auto;display:block;padding:6px 0 0 60px;position:absolute;left:0;z-index:100} */
/* .logo img{height:30px} */
/* .menu{width:100%;padding-left:12px;padding-right:12px;overflow-x:auto;white-space:nowrap} */
.centercolumn{width:100%;float:none}
.content{padding:10px;border-width:0 0 1px 0}
.content img{max-width:100%;height:auto}
/* .skyscraper{display:inline-block} */
/* .shadow,.example,.console-output,.content pre{max-width:100%;overflow-x:auto}
.codebox-title{position:relative}
.codebox.multi-style-mode pre{padding-top:7px;margin-top:36px;border-top:1px solid #ddd}
.bottom-ad{height:auto;background:none;padding:30px 0 0;margin:40px 0 0;text-align:center;position:relative}
.rectangle-left,.rectangle-right{float:none;display:inline-block;margin:10px auto;background:#EDEEF2}
.leftcolumn,.footer,.social,.site-search,.code-style,.menu a.tool-link,.backdrop{display:none}
.summary,.topic{padding:5px 0;margin:10px 0}
.leftcolumn,.centercolumn,.rightcolumn,.sidebar{float:none}
.header,.menu,.centercolumn,.footer,.appeal-text{width:100%}
a.try-btn,a.download-btn{width:130px}
.native-unit{margin-bottom:30px}
.rightcolumn,.sidebar{margin: 25px auto 0}
.overview{padding-right:0}
.scroll-pane{overflow-x:auto}
table.data{min-width:480px}
table.data pre{display:inline;white-space:normal}
table tr th,table tr td{width:auto!important}
.preview-box{padding:6px}
.leaderboard{margin:20px 0}
h1{font-size:30px}
h2{font-size:24px}
h3{font-size:20px}
.codebox pre.syntax-highlighter{overflow-x:auto}
.codebox pre.syntax-highlighter > code{min-width:614px;height:auto;overflow-x:hidden} */
}
@media screen and (min-width: 801px) {
.site-search,.leftcolumn,.social{display:block!important}
/* .backdrop{display:none!important} */
/* .hide-scroll{overflow-x:hidden!important} */
}
</style>
    
</head>
<body>

    <div class="wrapper clearfix">
        <div class="leftcolumn" id="myNav">
		
        <div class="segment"><span>WEB</span> TUTORIALS</div>
<div class="chapters">
    <a href="/html-tutorial/">HTML Tutorial</a>    
    <a href="/css-tutorial/">CSS Tutorial</a>
	<a href="/javascript-tutorial/">JavaScript Tutorial</a>
	<a href="/jquery-tutorial/">jQuery Tutorial</a>
    <a href="/twitter-bootstrap-tutorial/">Bootstrap Tutorial</a>
	<a href="/php-tutorial/">PHP Tutorial</a>
	<a href="/sql-tutorial/">SQL Tutorial</a>
</div>
<div class="segment"><span>PRACTICE</span>&thinsp;EXAMPLES</div>
<div class="chapters">
    <a href="/html-examples.php">HTML Examples</a>
    <a href="/css-examples.php">CSS Examples</a>
	<a href="/javascript-examples.php">JavaScript Examples</a>
	<a href="/jquery-examples.php">jQuery Examples</a>
    <a href="/twitter-bootstrap-examples.php">Bootstrap Examples</a>
	<a href="/php-examples.php">PHP Examples</a>
</div>
<div class="segment"><span>HTML</span> REFERENCES</div>
<div class="chapters">
    <a href="/html-reference/html5-tags.php">HTML Tags/Elements</a>
	<a href="/html-reference/html5-global-attributes.php">HTML Global Attributes</a>
    <a href="/html-reference/html5-event-attributes.php">HTML Event Attributes</a>             
    <a href="/html-reference/html-color-picker.php">HTML Color Picker</a>
    <a href="/html-reference/html-language-codes.php">HTML Language Codes</a>
    <a href="/html-reference/html-character-entities.php">HTML Character Entities</a>
    <a href="/html-reference/http-status-codes.php">HTTP Status Codes</a>
</div>
<div class="segment"><span>CSS</span> REFERENCES</div>
<div class="chapters">
    <a href="/css-reference/css-at-rules.php">CSS At-rules</a>
    <a href="/css-reference/css3-properties.php">CSS Properties</a>
	<a href="/css-reference/css-animatable-properties.php">CSS Animatable Properties</a>
    <a href="/css-reference/css-color-values.php">CSS Color Values</a>
    <a href="/css-reference/css-color-names.php">CSS Color Names</a>
    <a href="/css-reference/css-web-safe-fonts.php">CSS Web Safe Fonts</a>
    <a href="/css-reference/css-aural-properties.php">CSS Aural Properties</a>
</div>
<div class="segment"><span>PHP</span> REFERENCES</div>
<div class="chapters">
	<a href="/php-reference/php-array-functions.php">PHP Array Functions</a>
	<a href="/php-reference/php-string-functions.php">PHP String Functions</a>
    <a href="/php-reference/php-file-system-functions.php">PHP File System Functions</a>
    <a href="/php-reference/php-date-and-time-functions.php">PHP Date/Time Functions</a>
    <a href="/php-reference/php-calendar-functions.php">PHP Calendar Functions</a>
    <a href="/php-reference/php-mysqli-functions.php">PHP MySQLi Functions</a>
    <a href="/php-reference/php-filters.php">PHP Filters</a>
    <a href="/php-reference/php-error-levels.php">PHP Error Levels</a>
</div>        
                </div>
        <div class="centercolumn">
            <!--Text Content-->
            <div class="content">
                   <!-- InstanceBeginEditable name="main_content" -->
                <h1>How to set the height of a div to 100% using CSS</h1>
                <p class="topic">Topic: <a href="../faq.php#html-css">HTML&thinsp;/&thinsp;CSS</a><span class="topic-nav"><a href="how-to-align-text-vertically-center-in-a-div-using-css.php">Prev</a>|<a href="how-to-align-a-div-horizontally-center-using-css.php">Next</a></span></p>
                <h2>Answer: Set the 100% height for parents too</h2>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>If you will try the set the height of a div container to 100% of the browser window using the style rule <code>height: 100%;</code> it doesn't work, because the percentage (%) is a relative unit so the resulting height depends on the height of parent element's height.</p>
                <p>For instance, if you consider the following example, the <code>.container</code> div has two parent elements: the <a href="../html-reference/html-body-tag.php"><code>&lt;body&gt;</code></a> and the <a href="../html-reference/html-html-tag.php"><code>&lt;html&gt;</code></a> element. And we all know that the default value of the <a href="../css-reference/css-height-property.php"><code>height</code></a> property is <code>auto</code>, so if we also set the height of <code>&lt;body&gt;</code> and <code>&lt;html&gt;</code> elements to 100%, the resulting height of the container div becomes equal the 100% height of the browser window.</p>
                <!--Code box-->
                <div class="example">
                    <div class="codebox">
                        <div class="codebox-title"><h4>Example</h4><a href="../codelab.php?topic=faq&amp;file=set-div-height-to-100-percent" target="_blank" class="try-btn" title="Try this code using online Editor">Try this code <span>&raquo;</span></a></div>
                        <pre class="syntax-highlighter line-numbers"><code class="language-markup">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
&lt;meta charset="utf-8"&gt;
&lt;title&gt;Set DIV Height to 100% Using CSS&lt;/title&gt;
&lt;style&gt;
    html, body {
        height: 100%;
        margin: 0px;
    }
    .container {
        height: 100%;
        background: #f0e68c;
    }
&lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;The height of this DIV element is equal to the 100% height of its parent element's height.&lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                    </div>
                </div>
                <!--End:Code box-->
				<hr />
				<h2>Related FAQ</h2>
                <p>Here are some more FAQ related to this topic:</p>
                <ul class="faq-list">					
                    <li><a href="how-to-create-two-divs-with-same-height-side-by-side-in-css.php">How to create two DIV elements with same height side by side in CSS</a></li>
                    <li><a href="how-to-make-a-div-not-larger-than-its-contents-using-css.php">how to make a DIV not larger than its contents CSS</a></li>
                    <li><a href="how-to-align-text-vertically-center-in-a-div-using-css.php">How to align text vertically center in a DIV element using CSS</a></li>
                </ul>
                <!--Bottom Navigation-->
                <div class="bottom-link clearfix">
                    <a href="how-to-align-text-vertically-center-in-a-div-using-css.php" class="previous-page-bottom">Previous Page</a>
                    <a href="how-to-align-a-div-horizontally-center-using-css.php" class="next-page-bottom">Next Page</a>
                </div>
                <!--End:Bottom Navigation-->
                <!-- InstanceEndEditable -->
                <div class="bottom-ad clearfix">

     <div class="ad-label">Advertisements</div>


</div>            </div>
            <!--End:Text Content-->
            <!--Feedback Form-->
			<div class="snippets">
	<a href="/snippets/gallery.php" target="_blank"><img src="/lib/images/bootstrap-code-snippets.png" alt="Bootstrap UI Design Templates" /></a>
</div>            <!--End:Feedback Form-->
        </div>        

    </div>

	<!--Footer-->
<div class="footer">
    <div class="appeal">
        <div class="wrapper">
            <p>Is this website helpful to you? Please give us a
            <a href="/like.php" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=250,width=600,top=150, left='+((screen.width/2)-300));return false;">like</a>,
            or share your <a href="/contact-us.php" target="_blank">feedback</a><em> to help us improve</em>.
            Connect with us on <a href="https://www.facebook.com/tutorialrepublic" target="_blank">Facebook</a> and <a href="https://twitter.com/tutrepublic" target="_blank">Twitter</a> for the latest updates.</p>               
        </div>
    </div>
    <div class="footer-inner">
        <div class="wrapper clearfix">                
            <div class="link-box">
                <h4>About Us</h4>
                <div class="clearfix">
                    <a href="/about-us.php">Our Story</a>                    
                    <a href="/terms-of-use.php">Terms of Use</a>
                    <a href="/privacy-policy.php">Privacy Policy</a>                    
                </div>
            </div>
            <div class="link-box">
                <h4>Contact</h4>
                <div class="clearfix">
                    <a href="/contact-us.php">Contact Us</a>                    
                    <a href="/contact-us.php" target="_blank">Report Error</a>
                    <a href="/advertise-with-us.php">Advertise</a>                    
                </div>
            </div>
            <div class="tool-box">
                <h4>Interactive Tools</h4>
                <div class="tools-list clearfix" id="tools">
					<a href="/bootstrap-icons-classes.php" target="_blank">Bootstrap Icon Search Utility</a>
                    <a href="/html-formatter.php" target="_blank">HTML Formatter</a>
                    <a href="/faq/what-is-the-maximum-length-of-title-and-meta-description-tag.php">Title &amp; Meta Length Calculator</a>
                    <a href="/html-reference/html-color-picker.php">HTML Color Picker</a>
                    <a href="/twitter-bootstrap-button-generator.php" target="_blank">Bootstrap Button Generator</a>
                    <a href="/codelab.php?topic=sql&amp;file=select-all" target="_blank">SQL Playground</a>
                    <a href="/font-awesome-icons-classes.php" target="_blank">Font Awesome Icon Finder</a>
                    <a href="/codelab.php?topic=html&amp;file=hello-world" target="_blank">HTML Editor</a>
                </div>
            </div>
            <div class="footer-logo">
                <p><img src="/lib/images/logo.svg" alt="TutorialRepublic" /><p>
					<div>
						<a href="https://www.buymeacoffee.com/tutrepublic" class="bmc-btn" rel="nofollow" target="_blank">
    						<img src="/lib/images/bmc-btn.png" alt="BMC" />
						</a>
					</div>
            </div>
        </div>
    </div>           
</div>
<!--End:Footer-->
    

</body>
</html>