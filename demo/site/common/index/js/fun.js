var like_act = function(id,obj){
	
	var like_url = web_host+"/index.php/act/like/method/ajax";
	$.ajax({
		   type: "POST",
		   url: like_url,
		   data: "id="+id,
		   async:false,
		   success: function(msg){
		   
		     var info = eval('(' + msg + ')');
		     if(info.json.state=="1"){
		    	 $(obj).attr("class","icon liked");
		    	 $(obj).html(info.json.data+"赞");
		    	
		     }else{
		    	 alert(info.json.msg);
		     }
		   }
		
	});
}
var like_act2 = function(id,obj){
	var like_url = web_host+"/index.php/act/like/method/ajax";
	$.ajax({
		   type: "POST",
		   url: like_url,
		   data: "id="+id,
		   async:false,
		   success: function(msg){
		   
		     var info = eval('(' + msg + ')');
		     if(info.json.state=="1"){
				//alert("11");
		    	$(obj).html("<i class=\"common-sprite icon-like ie6fixpic\"></i>已赞");
		    	
		     }else{
		    	 alert(info.json.msg);
		     }
		   }
		
	});
}

$(document).ready(function(){
	
	$(".kuang").find("textarea[name='content']").bind("focus click",function(){
		if($.trim($(this).val())=="发表评论")
		{
			$(this).val("");
		}
	});
	$(".kuang").find("textarea[name='content']").bind("blur",function(){
		if($.trim($(this).val())=="")
		{
			$(this).val("发表评论");
		}
	});
	
	
	$(".replycomment").bind("click",function(){
		if($(this).parent().parent().find(".reply_box").css("display")=="none")
			$(this).parent().parent().find(".reply_box").show();
		else
			$(this).parent().parent().find(".reply_box").hide();	
	});
	
	
	$(".comment_form").bind("submit",function(){
		//alert("22");
		var btn = $(this).find(".send_btn");
		var form = $(this);
		if($.trim($(this).find("textarea[name='content']").val())==""||$.trim($(this).find("textarea[name='content']").val())=="发表评论"||$.trim($(this).find("textarea[name='content']").val())==$(this).find("textarea[name='content']").attr("rel"))
		{
			$(this).find("textarea[name='content']").focus();
			return false;
		}
				
		$(btn).find("input").val("发送中");
		//return false;
		
	}); //end comment_form_onsubmit
	
	$(".send_btn>input[name='comment_pid']").bind("click",function(){	
		alert("11");
		alert($(this).parent().parent().find("textarea[name='content']").val());
		if($.trim($(this).parent().parent().find("textarea[name='content']").val())==""||$.trim($(this).parent().parent().find("textarea[name='content']").val())==$(this).parent().parent().find("textarea[name='content']").attr("rel"))
		{
			$(this).parent().parent().find("textarea[name='content']").focus();
			return false;
		}
		$(this).find("input").html()!="发送中"
		//return false;
	});
	
});