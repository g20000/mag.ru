$(document).ready(function(){

	SyntaxHighlighter.defaults['auto-links'] = true;

	SyntaxHighlighter.defaults['class-name'] = '';
	SyntaxHighlighter.defaults['collapse'] = false;
	SyntaxHighlighter.defaults['first-line'] = 1;
	SyntaxHighlighter.defaults['gutter'] = true;

	SyntaxHighlighter.defaults['highlight'] = null;
	SyntaxHighlighter.defaults['html-script'] = false;
	SyntaxHighlighter.defaults['smart-tabs'] = true;
	SyntaxHighlighter.defaults['tab-size'] = 4;
	SyntaxHighlighter.defaults['toolbar'] = false;


	SyntaxHighlighter.autoloader(
		'applescript			/design/syntaxhighlighter/scripts/shBrushAppleScript.js',
		'actionscript3 as3		/design/syntaxhighlighter/scripts/shBrushAS3.js',
		'bash shell				/design/syntaxhighlighter/scripts/shBrushBash.js',
		'coldfusion cf			/design/syntaxhighlighter/scripts/shBrushColdFusion.js',
		'cpp c					/design/syntaxhighlighter/scripts/shBrushCpp.js',
		'c# c-sharp csharp		/design/syntaxhighlighter/scripts/shBrushCSharp.js',
		'css					/design/syntaxhighlighter/scripts/shBrushCss.js',
		'delphi pascal			/design/syntaxhighlighter/scripts/shBrushDelphi.js',
		'diff patch pas			/design/syntaxhighlighter/scripts/shBrushDiff.js',
		'erl erlang				/design/syntaxhighlighter/scripts/shBrushErlang.js',
		'groovy					/design/syntaxhighlighter/scripts/shBrushGroovy.js',
		'java					/design/syntaxhighlighter/scripts/shBrushJava.js',
		'jfx javafx				/design/syntaxhighlighter/scripts/shBrushJavaFX.js',
		'js jscript javascript	/design/syntaxhighlighter/scripts/shBrushJScript.js',
		'perl pl				/design/syntaxhighlighter/scripts/shBrushPerl.js',
		'php					/design/syntaxhighlighter/scripts/shBrushPhp.js',
		'text plain				/design/syntaxhighlighter/scripts/shBrushPlain.js',
		'py python				/design/syntaxhighlighter/scripts/shBrushPython.js',
		'ruby rails ror rb		/design/syntaxhighlighter/scripts/shBrushRuby.js',
		'sass scss				/design/syntaxhighlighter/scripts/shBrushSass.js',
		'scala					/design/syntaxhighlighter/scripts/shBrushScala.js',
		'sql					/design/syntaxhighlighter/scripts/shBrushSql.js',
		'vb vbnet				/design/syntaxhighlighter/scripts/shBrushVb.js',
		'xml xhtml xslt html	/design/syntaxhighlighter/scripts/shBrushXml.js'
	);
	SyntaxHighlighter.all();
});