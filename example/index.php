<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title></title>
<link>
</head>
<body>
<div id="box">index.html</div>

<hr>

<pre>
ルータスクリプトを指定しない場合

 - 存在しないディレクトリをすると
    - ディレクトリパスを遡って最初に見つかったディレクトリの index が表示される
    - 「最初に見つかったディレクトリ」の「index」であって「最初に見つかった index」ではない

ルータスクリプトを指定した場合

 - SCRIPT_FILENAME にはルータスクリプトを指定した場合と同じ方法で解決されたパスが入る
    - ただしファイル名に解決出来ない場合はルータスクリプトのファイル名が入る

 - SCRIPT_NAME は解決されたファイル名
    - PHP_SELF は SCRIPT_NAME に PATH_INFO を付与したもの
</pre>

</body>
</html>
