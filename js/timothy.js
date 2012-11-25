/*
jQuery scripts for theme minyx-20-lite
Code by Timothy 
Version:0.1
Create:2010.02.04
Last Modify:2010.06.14

Modify History List:
Add 2010-6-14 Double click to scroll top


*/


jQuery(document).ready(function($){
	
	//Ctrl+Enter for fast submit
	$("#comment").keydown(
    	function(event){
        	if(event.ctrlKey && event.keyCode == 13)
        	{
            		$("#commentform").submit();
        	}
    	});

	
	$("#wrapper .post h2 a").click(function(){$(this).text("页面加载中……"); windows.location = $(this).attr("href");  });
		
	$('#container').dblclick(function(e){e.stopPropagation();}); 	
	    
	
	/* 用 jQuery 为每张图片链接自动加上 class="thickbox" */
$('#content p a').each(function(){ //根据主题内容区的 id 设置选择器
var a_href = $(this).attr('href').toLowerCase();
var file_type = a_href.substring(a_href.lastIndexOf('.'));
if (file_type == '.jpg' || file_type == '.png' || file_type == '.gif'){$(this).addClass('thickbox')};
});

});
