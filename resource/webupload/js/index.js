/**
 * Created by DM on 2017/8/21.
 */
var userInfo = {userId:"kazaff", md5:""};   //用户会话信息
var chunkSize = 5000 * 1024;        //分块大小
var uniqueFileName = null;          //文件唯一标识符
var md5Mark = null;

var backEndUrl = '/public/fileupload/serverPHP/fileUpload_Prove.php';
WebUploader.Uploader.register({
    "before-send-file": "beforeSendFile"
    , "before-send": "beforeSend"
    , "after-send-file": "afterSendFile"
}, {
    beforeSendFile: function(file){
        //秒传验证
        var task = new $.Deferred();
        var start = new Date().getTime();
        (new WebUploader.Uploader()).md5File(file, 0, 10*1024*1024).progress(function(percentage){
            console.log(percentage);
        }).then(function(val){
            console.log("总耗时: "+((new Date().getTime()) - start)/1000);

            md5Mark = val;
            userInfo.md5 = val;

            $.ajax({
                type: "POST"
                , url: backEndUrl
                , data: {
                    status: "md5Check"
                    , md5: val
                }
                , cache: false
                , timeout: 1000 //todo 超时的话，只能认为该文件不曾上传过
                , dataType: "json"
            }).then(function(data, textStatus, jqXHR){
                if(data.ifExist){   //若存在，这返回失败给WebUploader，表明该文件不需要上传
                    task.reject();
                    uploader.skipFile(file);
                    file.path = data.path;
                    UploadComlate(file);
                }else{
                    task.resolve();
                    //拿到上传文件的唯一名称，用于断点续传
                    uniqueFileName = md5(''+userInfo.userId+file.name+file.type+file.lastModifiedDate+file.size);
                }
            }, function(jqXHR, textStatus, errorThrown){    //任何形式的验证失败，都触发重新上传
                task.resolve();
                //拿到上传文件的唯一名称，用于断点续传
                uniqueFileName = md5(''+userInfo.userId+file.name+file.type+file.lastModifiedDate+file.size);
            });
        });
        return $.when(task);
    }
    , beforeSend: function(block){
        //分片验证是否已传过，用于断点续传
        var task = new $.Deferred();
        $.ajax({
            type: "POST"
            , url: backEndUrl
            , data: {
                status: "chunkCheck"
                , name: uniqueFileName
                , chunkIndex: block.chunk
                , size: block.end - block.start
            }
            , cache: false
            , timeout: 1000 //todo 超时的话，只能认为该分片未上传过
            , dataType: "json"
        }).then(function(data, textStatus, jqXHR){
            if(data.ifExist){   //若存在，返回失败给WebUploader，表明该分块不需要上传
                task.reject();
            }else{
                task.resolve();
            }
        }, function(jqXHR, textStatus, errorThrown){    //任何形式的验证失败，都触发重新上传
            task.resolve();
        });

        return $.when(task);
    }
    , afterSendFile: function(file){
        var chunksTotal = 0;
        if((chunksTotal = Math.ceil(file.size/chunkSize)) > 1){
            //合并请求
            var task = new $.Deferred();
            $.ajax({
                type: "POST"
                , url: backEndUrl
                , data: {
                    status: "chunksMerge"
                    , name: uniqueFileName
                    , chunks: chunksTotal
                    , ext: file.ext
                    , md5: md5Mark
                }
                , cache: false
                , dataType: "json"
            }).then(function(data, textStatus, jqXHR){
                //todo 检查响应是否正常
                task.resolve();
                file.path = data.path;
                UploadComlate(file);

            }, function(jqXHR, textStatus, errorThrown){
                task.reject();
            });
            return $.when(task);
        }else{
            UploadComlate(file);
        }
    }
});

var uploader = WebUploader.create({
    swf: "Uploader.swf"
    , server: backEndUrl
    , pick: "#picker"
    , resize: false
    , dnd: "#theList"
    , disableGlobalDnd: true
    , thumb: {
        width: 100
        , height: 100
        , quality: 70
        , allowMagnify: true
        , crop: true
        //, type: "image/jpeg"
    }
//              , compress: {
//                  quality: 90
//                  , allowMagnify: false
//                  , crop: false
//                  , preserveHeaders: true
//                  , noCompressIfLarger: true
//                  ,compressSize: 100000
//              }
    , compress: false
    , prepareNextFile: true
    , chunked: true
    , chunkSize: chunkSize
    , threads: true
    , formData: function(){return $.extend(true, {}, userInfo);}
    , fileNumLimit: 5
    , fileSingleSizeLimit: 1000 * 1024 * 1024
    , duplicate: true
});

uploader.on("fileQueued", function(file){
    $("#theList").append('<li id="'+file.id+'">'+
        '<span class="image_name_value">'+file.name+'</span><span class="itemDel">删除</span>' +
        '<div class="percentage"></div>' +
        '</li>');

});

$("#itemUpload").on("click", function(){
    if($("#theList").children().length>0){
        uploader.upload();
        //"上传"-->"暂停"
        $(this).hide();
        $("#itemStop").show();
    }else{
        alert('未选择上传文件');
    }

});

$("#itemStop").on("click", function(){
    uploader.stop(true);
    //"暂停"-->"上传"
    $(this).hide();
    $("#itemUpload").show();
});

//todo 如果要删除的文件正在上传（包括暂停），则需要发送给后端一个请求用来清除服务器端的缓存文件
$("#theList").on("click", ".itemDel", function(){
	if($(this).parent().attr("id")!=undefined){
		uploader.removeFile($(this).parent().attr("id"));//从上传文件列表中删除
	}
    $(this).parent().remove();//从上传列表dom中删除
});

uploader.on("uploadProgress", function(file, percentage){
    $("#" + file.id + " .percentage").text(percentage * 100 + "%");
});

// uploader.on( 'uploadSuccess', function(file,response) {
//     console.log(response);
//     $("#" + file.id + " .percentage").text("上传完毕");
//     // $( '#'+file.id ).find('p.state').text('已上传');
// });

uploader.on( 'uploadError', function( file ) {
    $( '#'+file.id ).find('p.state').text('上传出错');
});

uploader.on( 'uploadComplete', function( file ) {
    $( '#'+file.id ).find('.progress').fadeOut();
});

uploader.on('uploadSuccess',function(file,response){
     console.log(file);
     console.log(response);

});
function UploadComlate(file){
    $("#" + file.id + " .percentage").text("上传完毕");
    $(".itemStop").hide();
    $(".itemUpload").hide();
    $(".itemDel").hide();

}


