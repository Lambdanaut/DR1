<form method="post" action="<?php echo $_GET['post_target_url']; ?>" id="postform">
<?php 
    unset($_GET['post_target_url']);
    foreach($_GET as $key => $value) {
        echo "<input type='text' name='".$key."' value='".htmlentities($value, ENT_QUOTES)."'>";
    }
?>
</form>
<script language='javascript'>
    document.getElementById('postform').submit();
</script>
