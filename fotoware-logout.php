<?php
session_start();
session_destroy();
echo 'You have been logged out of FotoWare <a href="javascript:history.go(-1)" title="Return to previous page">&laquo; Go back</a>';
?>
<script>
window.localStorage.setItem('isLoggedIn', 'false');
</script>