/**
 * Created by DM on 2017/8/21.
 */
var userInfo = {userId:"kazaff", md5:""};   //用户会话信息
var chunkSize = 5000 * 1024;        //分块大小
//var backEndUrl = '../serverPHP/fileUpload.php';
var backEndUrl1 = '/public/fileupload/serverPHP/fileUpload_chat_file.php';

var uploader2 = WebUploader.create({
    swf: "../Uploader.swf"
    , server: backEndUrl1
    , pick: "#addphoto"
    , resize: false
    , disableGlobalDnd: true
    , thumb: {
        width: 100
        , height: 100
        , quality: 70
        , allowMagnify: true
        , crop: true
        , type: "image/jpeg"
    }
	, compress: {
		quality: 90
		, allowMagnify: false
		, crop: false
		, preserveHeaders: true
		, noCompressIfLarger: true
		,compressSize: 100000
	}
    , compress: false
    , prepareNextFile: true
    , chunked: true
    , chunkSize: chunkSize
    , threads: true
    , formData: function(){return $.extend(true, {}, userInfo);}
    , fileNumLimit: 1
    , fileSingleSizeLimit: 1000 * 1024 * 1024
    , duplicate: true
});

uploader2.on("fileQueued", function(file){
    uploader2.upload();
});

uploader2.on("uploadProgress", function(file, percentage){
    
});

uploader2.on( 'uploadError', function( file ) {
	alert('上传出错');
});

uploader2.on( 'uploadComplete', function( file ) {

});
uploader2.on('uploadSuccess',function(file,response){
	$("#cover_photo").val(response.uploadpath+response.path);
	console.log(file);
    console.log(response);
});


