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

 - SCRIPT_FILENAME
    - PHPによって解決されたローカルファイルのパス
    - ただしファイル名に解決できなかった場合はルータスクリプトのファイル名が入る
 - SCRIPT_NAME
    - 解決されたファイル名
    - ファイル名に解決できなかった場合は URL のパス部分
 - PHP_SELF
    - SCRIPT_NAME に PATH_INFO を付与したもの
</pre>

</body>
</html>
