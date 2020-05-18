<style>
#sqlbuddy-wrapper {
  position: absolute;
  left: 250px;
  top: 0px;
  margin-left: 250px;
  width: calc(100% - 250px);
  margin: 0px auto;
  height: 100%;
  border: none;
}
#sqlbuddy {
  width: 100%;
  height: 100%;
  border: none;
}
</style>
<div id="sqlbuddy-wrapper">
  <iframe id="sqlbuddy" src="<?php echo document::href_link(WS_DIR_ADMIN . 'sqlbuddy.app/sqlbuddy/'); ?>"></iframe>
</div>
<script>
var buffer = 20; //scroll bar buffer
var iframe = document.getElementById('ifm');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    var height = document.documentElement.clientHeight;
    height -= pageY(document.getElementById('ifm'))+ buffer ;
    height = (height < 0) ? 0 : height;
    document.getElementById('ifm').style.height = height + 'px';
}

// .onload doesn't work with IE8 and older.
if (iframe.attachEvent) {
    iframe.attachEvent("onload", resizeIframe);
} else {
    iframe.onload=resizeIframe;
}

window.onresize = resizeIframe;
</script>