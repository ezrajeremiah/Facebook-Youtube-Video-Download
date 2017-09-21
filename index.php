<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>DownloadClub Video Downloader</title>
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style type="text/css">
  .centered {
      text-align: center;
      width: 90%;
      margin: 0 auto;
    }
  #vid_url{
    word-break: break-all;
    }</style>

</head>

<div class="container">
  <div class="row clearfix">
    <div class="col-md-12 column">
      <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#facebook-video-downloader">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">DownloadClub</a>
        </div>
        <div class="collapse navbar-collapse" id="facebook-video-downloader">
          <ul class="nav navbar-nav">
            <li class="active"><a href="./index.php">Video Downloader</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          </ul>
        </div>
      </nav>
      <div class="well">
        <div class="centered">
            <h2>Download Facebook/Youtube Videos</h2>

              <div class="input-group col-mg-12">
                  <input id="url" type="text" name="url" class="form-control" placeholder="Facebook/Youtube Video URL" id="url">
                  <span class="input-group-btn"><a class="btn btn-primary" onclick="getDownloadLink();" id="download">Download!</a></span>
              </div>
			  
			
			<div class="row" style="text-align:left; margin-top:20px;">
              <div class="col-md-6" >
				<p>Valid inputs for Youtube Videos are:</p>
					<ul>
						<li>youtube.com/watch?v=...</li>
						<li>youtu.be/...</li>
						<li>youtube.com/embed/...</li>
						<li>youtube-nocookie.com/embed/...</li>
						<li>youtube.com/watch?feature=player_embedded&amp;v=...</li>
					</ul>
              </div>
              <div class="col-md-6">
                <p>Valid inputs for Facebook Videos are:</p>
					<ul>
						<li>facebook.com/UserName/videos/...</li>
						<li>m.facebook.com/story.php...</li>
						<li>m.facebook.com/sharer.php...</li>	
					</ul>
               </div>
            </div>
        </div>
      </div>
      <div class="well" id="result" style="display:none;">
          <div id="bar"><p class="text-center"><img src="img/ajax.gif"></p></div>
          <div id="downloadUrl" style="display:none;">
            <div class="row">
              <div class="col-md-4"><p class="text-center"><b>Video Picture</b></p><p class="text-center" id="img"></p></div>
              <div class="col-md-4">
                  <p class="text-center"><b>Information</b></p>
                  <div class="col-sm-2">Title:</div>
                  <div class="col-sm-10" id="title"></div>
                  <div class="col-sm-2">Source:</div>
                  <div class="col-sm-10" id="src"></div>
              </div>
              <div class="col-md-4">
                <p class="text-center"><b>Download Link</b></p>
                <p class="text-center" id="sd"></p>
                <p class="text-center" id="hd"></p>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery.min.js" ></script>
<script type="text/javascript" src="js/bootstrap.min.js" ></script>
<script type="text/javascript">

$( document ).ready(function() {
    var url = document.getElementById("url");
	url.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        getDownloadLink();
    }
});
});
function gup( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}
function saveContent(fileContents, fileName)
{
    var link = document.createElement('a');
    link.download = fileName;
    link.href = 'data:,' + fileContents;
    link.click();
}

function parseQuery(qstr) {
    var query = {};
    var a = (qstr[0] === '?' ? qstr.substr(1) : qstr).split('&');
    for (var i = 0; i < a.length; i++) {
        var b = a[i].split('=');
        query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
    }
    return query;
}

function getDownloadLink(){
var vid_url = $("#url").val();
$("#download").html("Grabbing Link ...");
$("#download").attr("disabled","disabled");
$("#result").css("display","block");
$("#downloadUrl").css("display","none");
$("#bar").css("display","block");
$("#hd").html('');
$("#sd").html('');
if(vid_url.includes("facebook"))
{
	
	var sid = ''; var uid = '';
	if(vid_url.includes("story.php"))
	{
		var querydata = parseQuery(vid_url.split('.php?')[1]);
		sid = querydata['story_fbid'];
		uid = querydata['id'];
		vid_url = 'https://www.facebook.com/'+ uid +'/videos/' + sid + '/';
	}
	else if(vid_url.includes("sharer.php"))
	{
		sid = decodeURIComponent(vid_url).split('&sid=')[1].split('&')[0];
		uid = decodeURIComponent(vid_url).split('page_id.')[1].split('&')[0];
		vid_url = 'https://www.facebook.com/'+ uid +'/videos/' + sid + '/';
	}
	  
	$.ajax({
    type:"POST",
    dataType:'json',
    url:'main.php',
    data:{url:vid_url},
		success:function(data){
			console.log(data);
			$("#bar").css("display","none");
			$("#downloadUrl").css("display","block");
			if(data.type=="success") {
			var img_link = vid_url.split("/")[5];
			$("#title").html(data.title);
			$("#img").html('<img class="img-thumbnail" src="https://graph.facebook.com/'+img_link+'/picture">');
			$("#src").html('<a id="vid_url" href="'+vid_url+'">'+vid_url+'</a>');
			var savename = data.title.split('"').join(' ');
			var encodedStringSD = btoa(data.sd_download_url);
			$("#sd").html('<a href="download-file.php?mime=video/mp4&title='+savename+'&url='+ encodedStringSD +'" download="sd.mp4"><b>MP4 SD</b></a>');
			//$("#sd").html('<a href="'+data.sd_download_url+'" download="sd.mp4"><b>MP4 SD</b></a>');
			
			if(data.hd_download_url){
			var encodedString = btoa(data.hd_download_url);
			$("#hd").html('<a href="download-file.php?mime=video/mp4&title='+savename+'&url='+ encodedString +'" download="hd.mp4"><b>MP4 HD</b></a>');
			//$("#hd").html('<a href="'+data.hd_download_url+'" download="hd.mp4"><b>MP4 HD</b></a>');
			}
		  }
		  if(data.type=="failure"){
			$("#downloadUrl").html('<h3>'+data.message+'</h3>');
		  }

		  $("#download").html("Download!");
		  $("#download").removeAttr("disabled");
		},
	error: function(XMLHttpRequest, textStatus, errorThrown) { 
                   // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
					$("#downloadUrl").html('<h3>Error retrieving the download link for the url. Please try again later</h3>');
                } 
	})
  }
  else if(vid_url.includes("youtu"))
  {

	$.ajax({
    type:"POST",
    dataType:'json',
    url:'main-yt.php',
    data:{url:vid_url},
    success:function(data){
      console.log(data);
      $("#bar").css("display","none");
      $("#downloadUrl").css("display","block");
      if(data.type=="success") {
		
        var img_link = $("#url").val().split("/")[5];
        $("#title").html(data.title);
        $("#img").html('<img class="img-thumbnail" src="'+ data.thumbnail +'">');
        $("#src").html('<a id="vid_url" href="'+$("#url").val()+'">'+$("#url").val()+'</a>');
		
		var videolist = JSON.parse(data.videolist);
		var videodata = '';
		Object.keys(videolist).forEach(function(key) {
		var filetype = key.split(' ')[1];
		var savename = data.title.split('"').join(' ');
			$("#sd").html('<a href="download-file.php?mime=video/mp4&title='+savename+'&url='+ encodedString +'" download="hd.mp4"><b>MP4 HD</b></a>');
			var encodedString = btoa(videolist[key]);
				videodata = videodata + '<a href="download-file.php?mime=video/'+filetype+'&title='+savename+'&url='+ encodedString +'"><b>' + key + '</b></a><br/>';
				//videodata = videodata + '<a href="'+videolist[key]+'" download="'+ savename+'"><b>' + key + '</b></a><br/>';
		})
        $("#sd").html(videodata);

      }

      if(data.type=="failure"){
        $("#downloadUrl").html('<h3>'+data.message+'</h3>');
      }

      $("#download").html("Download!");
      $("#download").removeAttr("disabled");
    },
	error: function(XMLHttpRequest, textStatus, errorThrown) { 
                   // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
					$("#downloadUrl").html('<h3>Error retrieving the download link for the url. Please try again later</h3>');
                } 
  })
  }
}

</script>
</body>
</html>