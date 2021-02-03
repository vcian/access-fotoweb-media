<?php
include_once 'fotoware-auth.php';
if(isset($_SESSION['fw_url'])){
	$_SESSION['fw_url'] = filter_var($_SESSION['fw_url'], FILTER_SANITIZE_URL);
    $fotoweb_url = $_SESSION['fw_url'];
}
if(isset($_SESSION['access_token'])){
	$_SESSION['access_token'] = filter_var($_SESSION['access_token'], FILTER_SANITIZE_STRING);
    $access_token = $_SESSION['access_token'];
}
if(isset($_SESSION['client_id'])){
	$_SESSION['client_id'] = filter_var($_SESSION['client_id'], FILTER_SANITIZE_STRING);
    $client_id = $_SESSION['client_id'];
}
if(isset($_SESSION['redirect_uri'])){
	$_SESSION['redirect_uri'] = filter_var($_SESSION['redirect_uri'], FILTER_SANITIZE_URL);
    $redirect_uri = $_SESSION['redirect_uri'];
}
if(isset($_SESSION['state'])){
	$_SESSION['state'] = filter_var($_SESSION['state'], FILTER_SANITIZE_STRING);
    $state = $_SESSION['state'];
} 

if(isset($_SESSION['access_token'])){
 echo '<iframe src="'.$fotoweb_url.'/fotoweb/widgets/selection?access_token='.$access_token.'" width="100%" height="100%"  frameBorder="0" id="iframe"></iframe>';
}
else{
	echo 'Something went wrong! Please <a href="'.$fotoweb_url.'/fotoweb/oauth2/authorize?response_type=code&client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&state='.$state.'"> log in </a> again!';
}
?>
<script>
const fw_url = '<?php echo $_SESSION['fw_url'];?>';
document.title = "Add Media from FotoWare";
document.documentElement.style.overflow = 'hidden';  // firefox, chrome
document.getElementsByTagName("body")[0].style.margin = "0";
document.getElementsByTagName("body")[0].style.padding = "0";

function listener (event) {
    if (event.data === 'authenticated') {
		const iframes = document.getElementById('iframe');
		const FW_iframe = '';
		for (var i in iframes) {
			if (iframes[i].src.match(fw_url)) {
				iframes[i].src = iframes[i].src;
				break;
			}
		}
	} else if (event.data.event === 'assetSelected') {
		handleSelected(getFrameURL(event.data, fw_url));
	} else if (event.data.event === 'assetExported') {
		handleExported(event.data);
	}else if (event.data.event === 'selectionWidgetCancel') {
        window.close();
    }
}
function handleSelected(data) {
	window.document.getElementById('iframe').src = data;
}

function handleExported (data) {
	var exported_url = '<img class="alignnone size-medium wp-image-14" src="' + data.export.export.image.normal +
		'" alt=""'+
		'" width="' + data.export.export.size.w +
		'" height="' + data.export.export.size.h +
        '" />';
window.localStorage.setItem('exported_url',exported_url);
	window.close();
}

function getFrameURL (data, url) {
    const token = '<?php echo $_SESSION['access_token']?>';
	const frameURL = url + '/fotoweb/widgets/publish?access_token=' + token + '&i=' + encodeURIComponent(data.asset.href);
	return frameURL;
}

if (window.addEventListener) {
	window.addEventListener('message', listener, false);
}
</script> 